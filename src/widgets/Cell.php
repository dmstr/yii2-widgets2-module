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

    /**
     * if set this route will be used as default for new widgets and appended in the
     * list of routes in the query constraint
     *
     * if not set, the module/controller/action route from current context
     * is used as default route
     *
     * @var
     */
    public $route;

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
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function run()
    {
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
                'label' => ' ' . $this->id . ' <span class="label label-info">' . \Yii::t('widgets', 'Cell') . '</span>',
                'items' => [[
                    'label' => FA::icon(FA::_PLUS),
                    'url' => [
                        '/' . $this->moduleName . '/crud/widget/create',
                        'WidgetContent' => [
                            'route' => $this->getDefaultRoute(),
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
                                'route' => $this->getDefaultRoute(),
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
                    'route' => array_filter([$this->route, $this->getActionRoute(), $this->getControllerRoute(), $this->getModuleRoute(), self::GLOBAL_ROUTE]),
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

    protected function getDefaultRoute()
    {
        return $this->route ? $this->route : $this->getActionRoute();
    }

    /**
     * @return string
     */
    private function getActionRoute()
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
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @return string
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
                // $properties can only be type array, boolean, integer or float (!=0) or a string
                // Cast $properties boolean, integer, float or string to an array
                if (!is_array($properties)) {
                    // If there is only one property, make it accessible as `value`
                    $properties = ['value' => $properties];
                }
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
                $html .= Html::beginTag('div', ['class' => [$visibility, 'hrzg-widget-content-frontend']]);
                $html .= $class->run();
                $html .= Html::endTag('div');
            }
            $html .= Html::endTag('div');
        }
        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @throws \Exception
     * @return string
     */
    private function generateCellControls()
    {
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-container-controls pos-' . $this->positionContainerControls]);
        $items = [
            ['label' => $this->id]
        ];

        $templates = WidgetTemplate::find()
            ->where(['php_class' => TwigTemplate::class])
            ->andWhere(['hide_in_list_selection' => WidgetTemplate::IS_VISIBLE_IN_LIST])
            ->orderBy('name')
            ->all();
        foreach ($templates as $template) {
            $items[] = [
                'label' => $template->name,
                'url' => [
                    '/' . $this->moduleName . '/crud/widget/create',
                    'WidgetContent' => [
                        'route' => $this->getDefaultRoute(),
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
            'options' => ['class' => 'btn btn-widget-control btn-primary'],
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
     * @throws \Exception
     * @return string
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
    button.find("i").toggleClass("fa-eye fa-eye-slash");
    button.parent().next('.hrzg-widget-content-frontend').toggleClass('hrzg-widget-widget-invisible-frontend');
  }
  button.button('reset');
}
JS

            ,
            'errorExpression' => <<<JS
function(xhr) {
  if (xhr.status === 404) {
    button.addClass("btn-danger").html("Error");
    console.error(xhr.responseJSON)
  }
  button.button('reset');
}
JS
            ,
            'method' => 'put',
            'url' => ['/' . $this->moduleName . '/crud/api/widget/update', 'id' => $widget->id],
            'params' => ['status' => $newStatus],
            'options' => [
                'class' => 'btn  btn-widget-control btn-' . (($widget->status && $published) ? 'success' : 'warning'),
                'aria-label' => \Yii::t('widgets', 'Toggle visibility status'),
                'data' => [
                    'button' => 'loading',
                    'loading-text' => FA::icon(FA::_SPINNER, ['class' => 'fa-spin']),
                    'html' => true
                ]
            ],
        ]);


        $translation_count = (int)$widget->getTranslations()->count();
        $widget_translation = $widget->getTranslation();
        // there exists one translation for the current language and there are more than one translation
        if ($widget_translation->id && $translation_count > 1) {
            $html .= Html::a(
                FA::icon(FA::_REMOVE),
                ['/' . $this->moduleName . '/crud/widget-translation/delete', 'id' => $widget->getTranslation()->id],
                [
                    'class' => 'btn  btn-widget-control btn-danger',
                    'aria-label' => \Yii::t('widgets', 'Delete translation'),
                    'data' => [
                        'method' => 'delete',
                        'confirm' => \Yii::t('widgets', 'Are you sure to delete this translation?'),
                        'pjax' => '0',
                        'params' => [
                            'returnUrl' => Url::to('')
                        ]
                    ]
                ]
            );
        } else {
            // is able to delete due to permissions and there is just one translation left
            if ($widget->hasPermission('access_delete') && $translation_count <= 1 && $widget_translation->id) {
                $html .= Html::a(
                    FA::icon(FA::_TRASH),
                    ['/' . $this->moduleName . '/crud/widget/delete', 'id' => $widget->id],
                    [
                        'class' => 'btn  btn-widget-control btn-danger',
                        'aria-label' => \Yii::t('widgets', 'Delete Widget'),
                        'data' => [
                            'method' => 'delete',
                            'confirm' => \Yii::t('widgets', 'Are you sure to delete this translation?'),
                            'pjax' => '0',
                            'params' => [
                                'returnUrl' => Url::to('')
                            ]
                        ]
                    ]
                );
            }
        }

        ##$published = $this->checkPublicationStatus($widget);
        $html .= Html::a(
            FA::icon(FA::_PENCIL) . '',
            ['/' . $this->moduleName . '/crud/widget/update', 'id' => $widget->id],
            [
                'class' => 'btn  btn-widget-control btn-primary',
                'aria-label' => \Yii::t('widgets', 'Update Widget'),
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
                'class' => 'btn  btn-widget-control btn-' . $color,
                'aria-label' => \Yii::t('widgets', 'View Widget'),
                'target' => \Yii::$app->params['backend.iframe.name'] ?? '_self'
            ]
        );

        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @param $widget
     *
     * @throws \Exception
     * @return boolean
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
                    new \DateTime('now', new \DateTimeZone($this->timezone)) >= new \DateTime($widget->publish_at, new \DateTimeZone($this->timezone))
                )
                &&
                (
                    !$widget->expire_at
                    ||
                    new \DateTime('now', new \DateTimeZone($this->timezone)) <= new \DateTime($widget->expire_at, new \DateTimeZone($this->timezone))
                );
        }

        return $published;
    }
}
