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
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class WidgetContainer extends Widget
{
    const CSS_PREFIX = 'hrzg-widget';

    public function init()
    {
        \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        if (\Yii::$app->user->can('widgets')) {
            WidgetAsset::register(\Yii::$app->view);
        }
    }

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
                'label' => FA::icon(FA::_PLUS_SQUARE).' <b>'.$this->id.'</b> <span class="label label-info">widget</span>',
                'url' => [
                    '/widgets/crud/widget/create',
                    'WidgetContent' => [
                        'route' => $this->getRoute(),
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get('id'),
                        'access_domain' => \Yii::$app->language,
                    ],
                ],
            ],
            [
                'label' => FA::icon(FA::_EDIT).' <b>'.$this->id.'</b> <span class="label label-info">widget</span>',
                'url' => [
                    '/widgets/crud/widget/index',
                    'WidgetContent' => [
                        'route' => $this->getRoute(),
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get('id'),
                        'access_domain' => \Yii::$app->language,
                    ],
                ],
            ],
        ];
    }

    private function queryWidgets()
    {
        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $models = WidgetContent::find()
            ->orderBy('rank ASC')
            ->andFilterWhere(
                [
                    'request_param' => \Yii::$app->request->get('id'),
                ]
            )
            ->andWhere(
                [
                    'container_id' => $this->id,
                    'route' => $this->getRoute(),
                    'access_domain' => \Yii::$app->language,
                ])
            ->all();

        return $models;
    }

    private function getRoute()
    {
        return \Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/'.\Yii::$app->controller->action->id;
    }

    private function renderWidgets()
    {
        $html = Html::beginTag(
            'div',
            ['class' => self::CSS_PREFIX.'-'.$this->id.' '.self::CSS_PREFIX.'-widget-container']
        );

        if (\Yii::$app->user->can('widgets')) {
            $html .= $this->generateContainerControls();
        }

        foreach ($this->queryWidgets() as $widget) {
            $properties = Json::decode($widget->default_properties_json);
            $class = \Yii::createObject($widget->template->php_class);
            $class->setView($widget->getViewFile());

            if ($properties) {
                $class->setProperties($properties);
            }
            $html .= Html::beginTag('div', ['class' => 'hrzg-widget-widget']);
            if (\Yii::$app->user->can('widgets')) {
                $html .= $this->generateWidgetControls($widget);
            }
            $html .= $class->run();
            $html .= Html::endTag('div');
        }
        $html .= Html::endTag('div');
        return $html;
    }

    private function createWidget()
    {
    }

    private function generateContainerControls()
    {
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-container-controls pull-right']);
        $html .= Html::a(
            FA::icon(FA::_PLUS_SQUARE).' '.$this->id,
            [
                '/widgets/crud/widget/create',
                'WidgetContent' => [
                    'route' => $this->getRoute(),
                    'container_id' => $this->id,
                    'request_param' => \Yii::$app->request->get('id'),
                    'access_domain' => \Yii::$app->language,
                ]
            ],
            ['class' => 'btn btn-success']);
        $html .= Html::endTag('div');
        return $html;
    }

    private function generateWidgetControls($widget)
    {
        $html = Html::beginTag('div', ['class' => 'hrzg-widget-widget-controls btn-group', 'role' => 'group']);
        $html .= Html::a(
            FA::icon(FA::_PENCIL).' #'.$widget->id.' '.$widget->template->name.' <span class="label label-default">'.$widget->rank.'</span>',
            ['/widgets/crud/widget/update', 'id' => $widget->id],
            ['class' => 'btn btn-xs btn-primary']
        );
        $html .= Html::a(
            FA::icon(FA::_EYE),
            ['/widgets/crud/widget/view', 'id' => $widget->id],
            ['class' => 'btn btn-xs btn-default']
        );
        $html .= Html::endTag('div');
        return $html;
    }
}