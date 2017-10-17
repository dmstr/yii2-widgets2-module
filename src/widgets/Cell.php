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

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetTemplate;
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
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

    public $rbacEditRole= 'widgets-cell-edit';

    public $moduleName = 'widgets';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (\Yii::$app->user->can('widgets_crud_widget', ['route' => true])) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
            WidgetAsset::register(\Yii::$app->view);
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
                'label' => ' <b>'.$this->id.'</b> <span class="label label-info">widget</span>',
                'url' => null,
                'items' => [
                    [
                        'label' => FA::icon(FA::_PLUS_SQUARE).' Add',
                        'url' => [
                            '/'.$this->moduleName.'/crud/widget/create',
                            'WidgetContent' => [
                                'route' => $this->getRoute(),
                                'container_id' => $this->id,
                                'request_param' => \Yii::$app->request->get($this->requestParam),
                                'access_domain' => \Yii::$app->language,
                            ],
                        ],
                        'linkOptions' => [
                            'target'=> (isset(\Yii::$app->params['backend.iframe.name']))
                                ? \Yii::$app->params['backend.iframe.name']
                                : '_self'
                        ]
                    ],
                    [
                        'label' => FA::icon(FA::_LIST).' List',
                        'url' => [
                            '/'.$this->moduleName.'/crud/widget/index',
                            'WidgetContent' => [
                                'route' => $this->getRoute(),
                                'container_id' => $this->id,
                                'request_param' => \Yii::$app->request->get('id'),
                                'access_domain' => \Yii::$app->language,
                            ],
                        ],
                        'linkOptions' => [
                            'target'=> (isset(\Yii::$app->params['backend.iframe.name']))
                                ? \Yii::$app->params['backend.iframe.name']
                                : '_self'
                        ]

                    ],
                ],

            ],
        ];
    }

    protected function queryWidgets()
    {
        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $models = WidgetContent::find()
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
                    'access_domain' => mb_strtolower(\Yii::$app->language),
                ])
            ->all();

        return $models;
    }

    /**
     * @return string
     */
    private function getRoute()
    {
        #return '/' . \Yii::$app->controller->getRoute();
        return \Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/'.\Yii::$app->controller->action->id;
    }

    /**
     * @return string
     */
    private function getControllerRoute()
    {
        return \Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/';
    }

    /**
     * @return string
     */
    private function getModuleRoute()
    {
        return \Yii::$app->controller->module->id.'/';
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
                'id' => 'cell-'.$this->id,
                'class' => self::CSS_PREFIX.'-'.$this->id.' '.self::CSS_PREFIX.'-widget-container',
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
            $html .= Html::beginTag('div',
                ['id' => 'widget-'.($widget->name_id ?: $widget->id), 'class' => 'hrzg-widget-widget']);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) && $this->showWidgetControls) {
                $html .= $this->generateWidgetControls($widget);
            }
            $published = $this->checkPublicationStatus($widget);
            if (\Yii::$app->user->can($this->rbacEditRole, ['route' => true]) || ($widget->status == 1 && $published == true)) {
                $html .= $class->run();
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
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-container-controls pos-'.$this->positionContainerControls]);
        $items = [
            ['label'=>$this->id]
        ];

        foreach (WidgetTemplate::find()->orderBy('name')->all() as $template) {
            $items[] = [
                'label' => $template->name,
                'url' => [
                    '/'.$this->moduleName.'/crud/widget/create',
                    'WidgetContent' => [
                        'route' => $this->getRoute(),
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get($this->requestParam),
                        'access_domain' => \Yii::$app->language,
                        'widget_template_id' => $template->id,
                    ],
                ],
                'linkOptions' => [
                    'target'=> (isset(\Yii::$app->params['backend.iframe.name']))
                        ? \Yii::$app->params['backend.iframe.name']
                        : '_self'
                ]
            ];
        }

        $html .= ButtonDropdown::widget([
            'label' => FA::icon(FA::_PLUS_SQUARE).' '.$this->id,
            'encodeLabel' => false,
            'options' => ['class' => 'btn btn-xs btn-success'],
            'dropdown' =>  [
                'options' => [
                    'class'=>'dropdown-menu-right'
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
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-widget-controls btn-group pos-'.$this->positionWidgetControls, 'role' => 'group']);
        $html .= Html::a(
            FA::icon(FA::_FILE_O),
            ['/'.$this->moduleName.'/crud/widget/view', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-default',
                'target'=> (isset(\Yii::$app->params['backend.iframe.name']))
                    ? \Yii::$app->params['backend.iframe.name']
                    : '_self'
            ]
        );
        $published = $this->checkPublicationStatus($widget);
        $html .= Html::a(
            FA::icon(FA::_PENCIL).' #'.$widget->id.' '.$widget->template->name.' <span class="label label-default">'.$widget->rank.'</span>',
            ['/'.$this->moduleName.'/crud/widget/update', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-'.(($widget->status && $published) ? 'primary' : 'default'),
                'target'=> (isset(\Yii::$app->params['backend.iframe.name']))
                    ? \Yii::$app->params['backend.iframe.name']
                    : '_self'
            ]
        );
        $html .= Html::a(
            FA::icon(FA::_TRASH_O),
            ['/'.$this->moduleName.'/crud/widget/delete', 'id' => $widget->id],
            [
                'class' => 'btn btn-xs btn-danger',
                'data-confirm' => ''.\Yii::t('widgets', 'Are you sure to delete this item?').'',
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
    private function checkPublicationStatus($widget) {
        $published = false;
        if(!\Yii::$app->getModule($this->moduleName)->dateBasedAccessControl) {
            $published = true;
        } else {
            $published =
                (
                    !$widget->publish_at
                    ||
                    new \DateTime() >= new \DateTime($widget->publish_at.' UTC')
                )
                &&
                (
                    !$widget->expire_at
                    ||
                    new \DateTime() <= new \DateTime($widget->expire_at.' UTC')
                );
        }

        return $published;
    }
}
