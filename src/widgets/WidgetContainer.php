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

use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\assets\WidgetAsset;
use rmrevin\yii\fontawesome\AssetBundle;
use rmrevin\yii\fontawesome\component\Icon;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class WidgetContainer extends Widget
{
    public function init()
    {
        \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        if (\Yii::$app->user->can('widgets')) {
            WidgetAsset::register(\Yii::$app->view);
        }
    }

    public function run()
    {
        return $this->renderWidgets();
    }

    private function queryWidgets()
    {
        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $models = WidgetContent::find()
            ->orderBy('rank')
            ->where(
                [
                    'container_id' => $this->id,
                    'route' => \Yii::$app->requestedRoute,
                    'request_param' => \Yii::$app->request->get('id'),
                    'access_domain' => \Yii::$app->language,
                ])
            ->all();

        return $models;
    }

    private function renderWidgets()
    {
        $html = Html::beginTag('div',['class'=>'hrzg-widget-widget-container']);

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
            $html .= Html::beginTag('div',['class'=>'hrzg-widget-widget']);
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

    private function generateContainerControls(){
        $html = Html::beginTag('div',['class'=>'hrzg-widget-container-controls pull-right']);
        $html .= Html::a('Add', ['/widgets/crud/widget/create',
            'WidgetContent' => [
                'route' => \Yii::$app->requestedRoute,
                'container_id' => $this->id,
                'request_param' => \Yii::$app->request->get('id'),
                'access_domain' => \Yii::$app->language,
            ]], ['class'=>'btn btn-default']);
        $html .= Html::endTag('div');
        return $html;
    }

    private function generateWidgetControls($widget){
        $html = Html::beginTag('div',['class'=>'hrzg-widget-widget-controls']);
        $html .= Html::a('Edit', ['/widgets/crud/widget/update', 'id'=>$widget->id], ['class'=>'btn btn-default']);
        $html .= Html::endTag('div');
        return $html;
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
                        'route' => \Yii::$app->requestedRoute,
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
                        'route' => \Yii::$app->requestedRoute,
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get('id'),
                        'access_domain' => \Yii::$app->language,
                    ],
                ],
            ],
        ];
    }
}
