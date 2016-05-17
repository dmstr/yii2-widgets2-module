<?php

namespace hrzg\widget\models\crud;

use Yii;
use \hrzg\widget\models\crud\base\Widget as BaseWidget;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_hrzg_widget".
 */
class Widget extends BaseWidget
{
    public static function optsWidgetTemplateId()
    {
        return ArrayHelper::merge(
            ['' => 'none'],
            \yii\helpers\ArrayHelper::map(WidgetTemplate::find()->all(), 'id', 'name')
        );
    }
}
