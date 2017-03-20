<?php
namespace hrzg\widget\controllers\crud\api;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud\api
 */
class WidgetController extends \yii\rest\ActiveController
{
    public $modelClass = 'hrzg\widget\models\crud\Widget';
}
