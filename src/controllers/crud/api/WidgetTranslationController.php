<?php
namespace hrzg\widget\controllers\crud\api;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetTranslationController
 * @package hrzg\widget\controllers\crud\api
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class WidgetTranslationController extends \yii\rest\ActiveController
{
    public $modelClass = 'hrzg\widget\models\crud\WidgetContentTranslation';
}
