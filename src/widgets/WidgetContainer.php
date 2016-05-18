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
use yii\base\Widget;
use yii\helpers\Json;

class WidgetContainer extends Widget
{
    public function run()
    {
        \Yii::$app->params['backend.menuItems'][] = [
            'label' => 'Edit widget',
            'url' => ['/widgets/crud/widget/create']
        ];
        #return "Widget";

        return $this->renderWidgets();
    }

    private function queryWidgets()
    {
        $models = WidgetContent::find()->all();
        return $models;
    }

    private function renderWidgets()
    {
        $html = '';
        foreach ($this->queryWidgets() AS $widget) {
            $properties = Json::decode($widget->default_properties_json);
            $class = \Yii::createObject($widget->template->php_class);
            $class->setView($widget->getViewFile());
            $class->setProperties($properties);
            $html .= $class->run();
            #var_dump($widget->template->php_class);
            #$html .= $widget->template->php_class;
        }
        return $html;
    }

    private function createWidget()
    {

    }
}