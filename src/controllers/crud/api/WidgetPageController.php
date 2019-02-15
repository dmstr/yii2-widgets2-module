<?php

namespace hrzg\widget\controllers\crud\api;

/**
* This is the class for REST controller "WidgetPageController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class WidgetPageController extends \yii\rest\ActiveController
{
public $modelClass = 'hrzg\widget\models\crud\WidgetPage';
}
