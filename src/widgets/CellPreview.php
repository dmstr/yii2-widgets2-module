<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;


use hrzg\widget\models\crud\WidgetContent;

class CellPreview extends Cell
{
    public $widget_id;
    public $showWidgetControls = false;
    public $showContainerControls = false;

    protected function queryWidgets() {
        return WidgetContent::findAll(['id'=>$this->widget_id]);
    }
}