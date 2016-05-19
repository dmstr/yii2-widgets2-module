<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;


use hrzg\widget\models\crud\WidgetContent;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\Json;

class WidgetContainer extends Widget
{
    public function init(){
        \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
    }

    public function run()
    {
        return $this->renderWidgets();
    }

    private function queryWidgets()
    {
        \Yii::trace(\Yii::$app->requestedRoute, __METHOD__);
        $models = WidgetContent::find()
            ->where(
                [
                    'container_id' => $this->id,
                    'route' => \Yii::$app->requestedRoute,
                    'request_param' => \Yii::$app->request->get('id'),
                    'access_domain' => \Yii::$app->language
                ])
            ->all();
        return $models;
    }

    private function renderWidgets()
    {
        $html = '';
        foreach ($this->queryWidgets() AS $widget) {
            $properties = Json::decode($widget->default_properties_json);
            $class = \Yii::createObject($widget->template->php_class);
            $class->setView($widget->getViewFile());

            if($properties) {
                $class->setProperties($properties);
            }

            $html .= $class->run();
        }
        return $html;
    }

    private function createWidget()
    {

    }

    public function getMenuItems(){

        return [
            [
                'label' => 'Create '.$this->id.' widget',
                'url' => [
                    '/widgets/crud/widget/create',
                    'WidgetContent' => [
                        'route' => \Yii::$app->requestedRoute,
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get('id'),
                        'access_domain' => \Yii::$app->language
                    ]
                ]
            ],
            [
                'label' => 'Show '.$this->id.' widgets',
                'url' => [
                    '/widgets/crud/widget/index',
                    'WidgetContent' => [
                        'route' => \Yii::$app->requestedRoute,
                        'container_id' => $this->id,
                        'request_param' => \Yii::$app->request->get('id'),
                        'access_domain' => \Yii::$app->language
                    ]
                ]
            ]
        ];
    }
}