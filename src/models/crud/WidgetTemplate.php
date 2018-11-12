<?php

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\WidgetTemplate as BaseWidgetTemplate;
use Yii;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class WidgetTemplate
 * @package hrzg\widget\models\crud
 */
class WidgetTemplate extends BaseWidgetTemplate
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
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
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    ['name'],
                    'unique'
                ],
                [
                    'json_schema',
                    function ($attribute, $params) {
                        try {
                            Json::decode($this->$attribute);
                        } catch (InvalidParamException $e) {
                            $this->addError($attribute, 'Invalid JSON: '.$e->getMessage());
                        }
                    },
                ],

            ]
        );
    }

    /**
     * @return array|mixed
     */
    public function optPhpClass()
    {
        $json = Yii::$app->settings->get('availablePhpClasses', 'widgets', []);
        if (!isset($json->scalar)) {
            return [];
        }

        return Json::decode($json->scalar);
    }

    /**
     * Format json_schema before saving to database
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert) {
        $data = json_decode($this->json_schema);
        $this->json_schema = json_encode($data, JSON_PRETTY_PRINT);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
    }

    public function afterDelete()
    {
        parent::afterDelete();
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
    }
}
