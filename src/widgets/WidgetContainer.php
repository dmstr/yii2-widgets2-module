<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;


use yii\base\Widget;

class WidgetContainer extends Widget
{
    public function run(){
        \Yii::$app->params['backend.menuItems'][] = [
            'label' => 'Edit widget',
            'url' => ['/widgets/crud/widget/create']
        ];
        echo "Widget";
    }
}