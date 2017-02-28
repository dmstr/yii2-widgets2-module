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
     * Virtual attribute generated from "domain_id"_"access_domain".
     *
     * @var string
     */
    public $name_id;

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
                'audit' => [
                    'class' => 'bedezign\yii2\audit\AuditTrailBehavior'
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->setNameId($this->domain_id.'_'.$this->access_domain);
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
     * Get virtual name_id.
     *
     * @return string
     */
    public function getNameId()
    {
        return $this->name_id;
    }

    /**
     * Generate and Set virtual attribute name_id.
     *
     * @param mixed $name_id
     */
    public function setNameId($name_id)
    {
        $this->name_id = $name_id;
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
                ['rank', 'default', 'value' => 'a-'.dechex(date('U'))],
                [
                    'domain_id',
                    'default',
                    'value' => function () {
                        return uniqid();
                    }
                ],
                [
                    'access_domain',
                    'default',
                    'value' => function () {
                        return mb_strtolower(\Yii::$app->language);
                    }
                ],
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

        // ensure lowercase language id
        $this->access_domain = mb_strtolower($this->access_domain);

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
