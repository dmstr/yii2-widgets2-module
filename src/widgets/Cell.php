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

use dmstr\web\traits\AccessBehaviorTrait;
use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetTemplate;
use hrzg\widget\Module;
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class Cell extends Widget
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
    public $positionContainerControls = 'bottom-right';

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
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function run()
    {
        Url::remember('', $this->getRoute());
        return $this->renderWidgets();
    }

    public function getMenuItems()
    {
        // todo, register FA-asset from asset bundle
        AssetBundle::register($this->view);
        return [
            [
                'label' => ' <b>' . $this->id . '</b> <span class="label label-info">widget</span>',
                'items' => [[
                    'label' => FA::icon(FA::_PLUS),
                    'url' => [
                        '/' . $this->moduleName . '/crud/widget/create',
                        'WidgetContent' => [
                            'route' => $this->getRoute(),
                            'container_id' => $this->id,
                            'request_param' => \Yii::$app->request->get($this->requestParam),
                            'access_domain' => \Yii::$app->language,
                        ],
                    ],
                    'linkOptions' => [
                        'target' => (isset(\Yii::$app->params['backend.iframe.name']))
                            ? \Yii::$app->params['backend.iframe.name']
                            : '_self',
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
                            'target' => (isset(\Yii::$app->params['backend.iframe.name']))
                                ? \Yii::$app->params['backend.iframe.name']
                                : '_self',
                        ],

                    ]],
            ],
        ];
    }

    protected function queryWidgets()
    {
        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $query = WidgetContent::find()
            ->orderBy('rank ASC')
            ->andFilterWhere(
                [
                    'request_param' => [\Yii::$app->request->get($this->requestParam), self::EMPTY_REQUEST_PARAM],
                ]
            )
            ->andWhere(
                [
                    'container_id' => $this->id,
                    'route' => [$this->getRoute(), $this->getControllerRoute(), $this->getModuleRoute(), self::GLOBAL_ROUTE],
                    '{{%hrzg_widget_content}}.access_domain' => [mb_strtolower(\Yii::$app->language), '*'],
                ]);
        if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true])) {
            // editors see all widgets, also untranslated ones
        } else {
            $query->joinWith('translations');
            $query->andWhere(['language'=>\Yii::$app->language]);
        }
        $models = $query->all();
        return $models;
    }

    /**
     * @return string
     */
    private function getRoute()
    {
        #return '/' . \Yii::$app->controller->getRoute();
        return \Yii::$app->controller->module->id . '/' . \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;
    }

    /**
     * @return string
     */
    private function getControllerRoute()
    {
        return \Yii::$app->controller->module->id . '/' . \Yii::$app->controller->id . '/';
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
            $html .= $this->generateContainerControls();
        }

        foreach ($this->queryWidgets() as $widget) {
            $properties = Json::decode($widget->default_properties_json);
            $class = \Yii::createObject($widget->template->php_class);
            $class->setView($widget->getViewFile());

            if ($properties) {
                $class->setProperties($properties);
            }
            $visbility = $widget->isVisibleFrontend()?'':'hrzg-widget-widget-invisible-frontend';
            $html .= Html::beginTag('div',
                [
                    'id' => 'widget-' . ($widget->name_id ?: $widget->id),
                    'class' => 'hrzg-widget-widget ',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'title' => \Yii::$app->user->can($this->rbacEditRole, ['route' => true]) ?
                        ' #' . $widget->id . ' ' . $widget->template->name . ' §' . $widget->rank . '' :
                        null,
                ]);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) && $this->showWidgetControls) {
                $html .= $this->generateWidgetControls($widget);
            }
            $published = $this->checkPublicationStatus($widget);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) || ($widget->status == 1 && $published == true)) {
                $html .= Html::beginTag('div',['class' => $visbility,]);
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
     */
    private function generateContainerControls()
    {
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-container-controls pos-' . $this->positionContainerControls]);
        $items = [
            ['label' => $this->id]
        ];

        $availableFrontendPhpClasses = array_unique(ArrayHelper::merge(
            [TwigTemplate::class],
            explode("\n", \Yii::$app->settings->get('availableFrontendPhpClasses', 'widgets'))
        ));

        foreach (WidgetTemplate::find()->where(['php_class' => $availableFrontendPhpClasses])->orderBy('name')->all() as $template) {
            $items[] = [
                'label' => $template->name,
                'url' => [
                    '/' . $this->moduleName . '/crud/widget/create',
                    'WidgetContent' => [
                        'route' => $this->getRoute(),
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get($this->requestParam),
                        'access_domain' => \Yii::$app->language,
                        'widget_template_id' => $template->id,
                    ],
                ],
                'linkOptions' => [
                    'target' => (isset(\Yii::$app->params['backend.iframe.name']))
                        ? \Yii::$app->params['backend.iframe.name']
                        : '_self'
                ]
            ];
        }

        $html .= ButtonDropdown::widget([
            'label' => FA::icon(FA::_PLUS_SQUARE) . ' ' . $this->id,
            'encodeLabel' => false,
            'options' => ['class' => 'btn btn-xs btn-primary'],
            'dropdown' => [
                'options' => [
                    'class' => 'dropdown-menu-right'
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
     */
    private function generateWidgetControls($widget)
    {
        $html = Html::beginTag(
            'div',
            [
                'class' => 'hrzg-widget-widget-controls btn-group pos-' . $this->positionWidgetControls, 'role' => 'group',
            ]);

        $html .= '<span class="pull-left label '.
            ($widget->access_domain == '*' ? 'label-info' : 'label-default').'">'.
            FA::icon(FA::_GLOBE).' '.$widget->access_domain.
            ($widget->isFallback ? 'FALLBACK' : '').
            '</span>';

        $html .= Html::a(
            FA::icon(FA::_TRASH_O),
            ['/' . $this->moduleName . '/crud/widget/delete', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-danger',
                'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this item?') . '',
            ]
        );
        $html .= Html::a(
            FA::icon(FA::_EYE),
            ['/' . $this->moduleName . '/crud/widget/view', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-default',
                'target' => (isset(\Yii::$app->params['backend.iframe.name']))
                    ? \Yii::$app->params['backend.iframe.name']
                    : '_self'
            ]
        );
        $published = $this->checkPublicationStatus($widget);
        $html .= Html::a(
            FA::icon(FA::_PENCIL) . '',
            ['/' . $this->moduleName . '/crud/widget/update', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-' . (($widget->status && $published) ? 'success' : 'warning'),
                'target' => (isset(\Yii::$app->params['backend.iframe.name']))
                    ? \Yii::$app->params['backend.iframe.name']
                    : '_self'
            ]
        );


        $html .= Html::endTag('div');
        return $html;
    }

    /**
     * @param $widget
     *
     * @return boolean
     */
    private function checkPublicationStatus($widget)
    {
        $published = false;
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
