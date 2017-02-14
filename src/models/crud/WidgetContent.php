<?php

namespace hrzg\widget\models\crud;

use dmstr\db\traits\ActiveRecordAccessTrait;
use hrzg\widget\models\crud\base\Widget as BaseWidget;
use hrzg\widget\widgets\Cell;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_hrzg_widget".
 */
class WidgetContent extends BaseWidget
{
    use ActiveRecordAccessTrait;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::className(),
                    'value' => date('Y-m-d h:i:s'),
                    ],
                'bedezign\yii2\audit\AuditTrailBehavior'
            ]
        );
    }

    /**
     * Enable access_domain access checks in ActiveRecordAccessTrait
     * @return array with access field names
     */
    public static function accessColumnAttributes()
    {
        return [
            'owner'  => false,
            'read'   => false,
            'update' => false,
            'delete' => false,
            'domain' => 'access_domain',
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['name_id', 'match', 'pattern' => '/^[a-z0-9-]*$/i'],
                ['rank', 'default', 'value' => 'a-'.dechex(date('U'))],
            ]
        );
    }

    /**
     * Global route needs empty request param
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        if ($this->route === Cell::GLOBAL_ROUTE) {
            $this->request_param = Cell::EMPTY_REQUEST_PARAM;
        }
        return true;
    }

    /**
     * @return array
     */
    public static function optsWidgetTemplateId()
    {
        return ArrayHelper::merge(
            ['' => 'none'],
            \yii\helpers\ArrayHelper::map(WidgetTemplate::find()->orderBy('name')->all(), 'id', 'name')
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
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
