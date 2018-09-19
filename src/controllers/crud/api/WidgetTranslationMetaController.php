<?php
namespace hrzg\widget\controllers\crud\api;

use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetContentTranslationMeta;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud\api
 */
class WidgetTranslationMetaController extends \yii\rest\ActiveController
{
    public $modelClass = WidgetContentTranslationMeta::class;
}
