<?php
namespace hrzg\widget\controllers\crud\api;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetTemplateController
 * @package hrzg\widget\controllers\crud\api
 */
class WidgetTemplateController extends \yii\rest\ActiveController
{
    public $modelClass = 'hrzg\widget\models\crud\WidgetTemplate';
}
