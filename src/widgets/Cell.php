<?php
/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;

use dmstr\ajaxbutton\AjaxButton;
use dmstr\modules\backend\interfaces\ContextMenuItemsInterface;
use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetTemplate;
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\base\Event;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\caching\TagDependency;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class Cell
 *
 * @package hrzg\widget\widgets
 *
 * @property string $moduleRoute
 * @property string $route
 * @property array $menuItems
 * @property string $controllerRoute
 */
class Cell extends Widget implements ContextMenuItemsInterface
{
    /**
     * Global route
     */
    const GLOBAL_ROUTE = '*';

    /**
     * Empty request param
     */
    const EMPTY_REQUEST_PARAM = '';

    /**
     * Class prefix
     */
    const CSS_PREFIX = 'hrzg-widget';

    /**
     * @var string
     */
    public $requestParam = 'pageId';


    public $showWidgetControls = true;
    public $showContainerControls = true;

    public $positionWidgetControls = 'top-right';
    public $positionContainerControls = 'bottom-left';

    public $rbacEditRole = 'widgets-cell-edit';

    /**
     *
     * You can overwrite module Name for all Cell Objects without module context if module is not configured as "widget"
     * Yii::$container->set(
     *     \hrzg\widget\widgets\Cell::className(),
     *     [
     *        'moduleName' => 'widgets',
     *     ]
     * );
     *
     * @var string
     */
    public $moduleName = 'widgets';

    /**
     * Timezone that will be used to correct dates.
     *
     * @var string
     */
    public $timezone;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (\Yii::$app->user->can('widgets_crud_widget', ['route' => true])) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
            WidgetAsset::register(\Yii::$app->view);
        }

        if ($this->timezone === null) {
            $this->timezone = \Yii::$app->getModule($this->moduleName)->timezone;
        }

        parent::init();
    }

    /**
     * @inheritdoc
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        Url::remember('', $this->getRoute());
        return $this->renderWidgets();
    }

    /**
     * @return array
     */
    public function getMenuItems()
    {
        // todo, register FA-asset from asset bundle
        AssetBundle::register($this->view);

        $linkTarget = \Yii::$app->params['backend.iframe.name'] ?? '_self';
        return [
            [
                'label' => ' ' . $this->id . ' <span class="label label-info">Cell</span>',
                'items' => [[
                    'label' => FA::icon(FA::_PLUS),
                    'url' => [
                        '/' . $this->moduleName . '/crud/widget/create',
                        'WidgetContent' => [
                            'route' => $this->getRoute(),
                            'container_id' => $this->id,
                            'request_param' => \Yii::$app->request->get($this->requestParam),
                        ],
                    ],
                    'linkOptions' => [
                        'target' => $linkTarget,
                    ],
                ],
                    [
                        'label' => FA::icon(FA::_LIST),
                        'url' => [
                            '/' . $this->moduleName . '/crud/widget/index',
                            'WidgetContent' => [
                                'route' => $this->getRoute(),
                                'container_id' => $this->id,
                                'request_param' => \Yii::$app->request->get('id'),
                                'access_domain' => \Yii::$app->language,
                            ],
                        ],
                        'linkOptions' => [
                            'target' => $linkTarget
                        ]
                    ]]
            ]
        ];
    }

    /**
     * @return array|WidgetContent[]|mixed|\yii\db\ActiveRecord[]
     */
    protected function queryWidgets()
    {
        $cache = \Yii::$app->cache;
        $cacheKey = Json::encode([self::class, \Yii::$app->language, \Yii::$app->requestedRoute, \Yii::$app->request->get($this->requestParam), $this->id]);

        $data = $cache->get($cacheKey);

        if ($data !== false && \Yii::$app->user->isGuest) {
            return $data;
        }

        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $query = WidgetContent::find()
            ->orderBy('rank ASC')
            ->joinWith('template')
            ->andFilterWhere(
                [
                    'request_param' => [\Yii::$app->request->get($this->requestParam), self::EMPTY_REQUEST_PARAM],
                ]
            )
            ->andWhere(
                [
                    'container_id' => $this->id,
                    'route' => [$this->getRoute(), $this->getControllerRoute(), $this->getModuleRoute(), self::GLOBAL_ROUTE],
                    '{{%hrzg_widget_content}}.access_domain' => [mb_strtolower(\Yii::$app->language), WidgetContent::$_all],
                ]);
        if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true])) {
            // editors see all widgets, also untranslated ones
        } else {
            $query->joinWith('translations');
        }
        $data = $query->all();

        if (\Yii::$app->user->isGuest) {
            $cacheDependency = new TagDependency(['tags' => 'widgets']);
            $cache->set($cacheKey, $data, 3600, $cacheDependency);
        }

        return $data;
    }

    /**
     * @return string
     */
    private function getRoute()
    {
        #return '/' . \Yii::$app->controller->getRoute();
        return $this->getControllerRoute() . \Yii::$app->controller->action->id;
    }

    /**
     * @return string
     */
    private function getControllerRoute()
    {
        return $this->getModuleRoute() . \Yii::$app->controller->id . '/';
    }

    /**
     * @return string
     */
    private function getModuleRoute()
    {
        return \Yii::$app->controller->module->id . '/';
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    private function renderWidgets()
    {
        $html = Html::beginTag(
            'div',
            [
                'id' => 'cell-' . $this->id,
                'class' => self::CSS_PREFIX . '-' . $this->id . ' ' . self::CSS_PREFIX . '-widget-container',
            ]
        );

        if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) && $this->showContainerControls) {
            $html .= $this->generateCellControls();
        }

        foreach ($this->queryWidgets() as $widget) {
            $properties = Json::decode($widget->default_properties_json);
            $class = \Yii::createObject($widget->template->php_class);
            $class->setView($widget->getViewFile());

            if ($properties) {
                $class->setProperties($properties);
            }
            $visibility = $widget->isVisibleFrontend() ? '' : 'hrzg-widget-widget-invisible-frontend';
            $html .= Html::beginTag('div',
                [
                    'id' => 'widget-' . $widget->domain_id,
                    'class' => 'hrzg-widget-widget ',
                ]);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) && $this->showWidgetControls) {
                $html .= $this->generateWidgetControls($widget);
            }
            $published = $this->checkPublicationStatus($widget);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) || ($widget->status == 1 && $published == true)) {
                $html .= Html::beginTag('div', ['class' => $visibility,]);
                $html .= $class->run();
                $html .= Html::endTag('div');
            }
            $html .= Html::endTag('div');
        }
        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateCellControls()
    {
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-container-controls pos-' . $this->positionContainerControls]);
        $items = [
            ['label' => $this->id]
        ];

        foreach (WidgetTemplate::twigTemplatelist() as $template) {
            $items[] = [
                'label' => $template->name,
                'url' => [
                    '/' . $this->moduleName . '/crud/widget/create',
                    'WidgetContent' => [
                        'route' => $this->getRoute(),
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get($this->requestParam),
                        'widget_template_id' => $template->id,
                    ],
                ],
                'linkOptions' => [
                    'target' => \Yii::$app->params['backend.iframe.name'] ?? '_self'
                ]
            ];
        }

        $dropdownClass = $this->positionContainerControls === 'bottom-left' ? 'dropdown-menu-left' : 'dropdown-menu-right';
        $html .= ButtonDropdown::widget([
            'label' => FA::icon(FA::_PLUS_SQUARE) . ' ' . $this->id,
            'encodeLabel' => false,
            'options' => ['class' => 'btn btn-primary'],
            'dropdown' => [
                'options' => [
                    'class' => $dropdownClass
                ],
                'items' => $items,
            ],
        ]);

        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @param $widget
     *
     * @return string
     * @throws \Exception
     */
    private function generateWidgetControls(WidgetContent $widget)
    {


        $icon = $widget->access_domain === WidgetContent::$_all ? FA::_GLOBE : FA::_FLAG_O;
        $color = $widget->getBehavior('translatable')->isFallbackTranslation ? 'info' : 'default';
        $published = $this->checkPublicationStatus($widget);
        $newStatus = (int)!$widget->status;

        $html = '';
        $html .= Html::beginTag('div', ['class' => 'hrzg-widget-widget-info']);
        $html .= ' <span class="label label-info">' . $widget->rank . '</span>';
        $html .= ' <span class="hidden-xs label label-default">#' . $widget->id . ' ' . $widget->template->name . '</span> ';
        $html .= Html::endTag('div');


        $html .= Html::beginTag(
            'div',
            [
                'class' => 'hrzg-widget-widget-controls btn-group pos-' . $this->positionWidgetControls, 'role' => 'group',
            ]);

        $html .= AjaxButton::widget([
            'content' => FA::icon((($widget->status && $published) ? FA::_EYE : FA::_EYE_SLASH)),
            'successExpression' => <<<JS
function(resp,status,xhr) {
  if (xhr.status === 200) {
    var params = button.data("ajax-button-params");
    params.status = !params.status | 0;
    button.attr("data-ajax-button-params",JSON.stringify(params));
    button.toggleClass("btn-success btn-warning");
    button.find("i").toggleClass("fa-eye fa-eye-slash")
  }
}
JS

            ,
            'errorExpression' => <<<JS
function(xhr) {
  if (xhr.status === 404) {
    button.addClass("btn-danger").html("Error");
    console.error(xhr.responseJSON)
  }
}
JS
            ,
            'method' => 'put',
            'url' => ['/' . $this->moduleName . '/crud/api/widget/update', 'id' => $widget->id],
            'params' => ['status' => $newStatus],
            'options' => [
                'class' => 'btn  btn-' . (($widget->status && $published) ? 'success' : 'warning'),
            ]
        ]);


        $translation_count = (int)$widget->getTranslations()->count();
        $widget_translation = $widget->getTranslation();
        // there exists one translation for the current language and there are more than one translation
        if ($widget_translation->id && $translation_count > 1) {
            $html .= Html::a(
                FA::icon(FA::_REMOVE),
                ['/' . $this->moduleName . '/crud/widget-translation/delete', 'id' => $widget->getTranslation()->id],
                [
                    'class' => 'btn  btn-danger',
                    'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this translation?') . '',
                ]
            );
        } else {
            // is able to delete due to permissions and there is just one translation left
            if ($widget->hasPermission('access_delete') && $translation_count <= 1 && $widget_translation->id) {
                $html .= Html::a(
                    FA::icon(FA::_TRASH),
                    ['/' . $this->moduleName . '/crud/widget/delete', 'id' => $widget->id],
                    [
                        'class' => 'btn  btn-danger',
                        'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this base-item?') . '',
                    ]
                );
            }
        }

        ##$published = $this->checkPublicationStatus($widget);
        $html .= Html::a(
            FA::icon(FA::_PENCIL) . '',
            ['/' . $this->moduleName . '/crud/widget/update', 'id' => $widget->id],
            [
                'class' => 'btn  btn-primary',
                'target' => \Yii::$app->params['backend.iframe.name'] ?? '_self'
            ]
        );

//        $html .= Html::a(
//            FA::icon((($widget->status && $published) ? FA::_EYE : FA::_EYE_SLASH)) . '',
//            ['/' . $this->moduleName . '/crud/api/widget/update', 'id' => $widget->id],
//            [
//                'data-method' => 'put',
//                'data-params' => ['status' => $newStatus],
//                'class' => 'btn  btn-' . (($widget->status && $published) ? 'default' : 'warning'),
//            ]
//        );


        $html .= Html::a(
            FA::icon($icon),
            ['/' . $this->moduleName . '/crud/widget/view', 'id' => $widget->id],
            [
                'class' => 'btn  btn-' . $color,
                'target' => \Yii::$app->params['backend.iframe.name'] ?? '_self'
            ]
        );

        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @param $widget
     *
     * @return boolean
     * @throws \Exception
     */
    private function checkPublicationStatus($widget)
    {
        if (!\Yii::$app->getModule($this->moduleName)->dateBasedAccessControl) {
            $published = true;
        } else {
            $published =
                (
                    !$widget->publish_at
                    ||
                    new \DateTime(null, new \DateTimeZone($this->timezone)) >= new \DateTime($widget->publish_at, new \DateTimeZone($this->timezone))
                )
                &&
                (
                    !$widget->expire_at
                    ||
                    new \DateTime(null, new \DateTimeZone($this->timezone)) <= new \DateTime($widget->expire_at, new \DateTimeZone($this->timezone))
                );
        }

        return $published;
    }
}
