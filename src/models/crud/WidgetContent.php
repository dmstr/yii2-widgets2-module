<?php

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\Widget as BaseWidget;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_hrzg_widget".
 */
class WidgetContent extends BaseWidget
{
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['name_id', 'match', 'pattern' => '/^[a-z0-9-]*$/i']
            ]
        );
    }

    public static function optsWidgetTemplateId()
    {
        return ArrayHelper::merge(
            ['' => 'none'],
            \yii\helpers\ArrayHelper::map(WidgetTemplate::find()->orderBy('name')->all(), 'id', 'name')
        );
    }

    public function getTemplate()
    {
        return $this->hasOne(WidgetTemplate::className(), ['id' => 'widget_template_id']);
    }

    /**
     * @return string
     */
    public function getViewFile()
    {
        $file = '/'.\Yii::getAlias('@runtime').'/'.md5($this->template->twig_template).'.twig';
        file_put_contents($file, $this->template->twig_template);

        return $file;
    }
}
