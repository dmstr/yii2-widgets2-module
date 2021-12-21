<?php
namespace hrzg\widget\controllers\crud\api;

use hrzg\widget\models\crud\WidgetContent;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud\api
 */
class WidgetController extends \yii\rest\ActiveController
{
    public $modelClass = WidgetContent::class;
}
