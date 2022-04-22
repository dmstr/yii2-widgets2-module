<?php

namespace hrzg\widget\models\crud;

use bedezign\yii2\audit\AuditTrailBehavior;
use hrzg\widget\models\crud\base\WidgetTemplate as BaseWidgetTemplate;
use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * Class WidgetTemplate
 *
 * @package hrzg\widget\models\crud
 */
class WidgetTemplate extends BaseWidgetTemplate
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()'),
        ];
        $behaviors['audit'] = [
            'class' => AuditTrailBehavior::class
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'name',
            'unique'
        ];
        $rules[] = [
            'json_schema',
            function ($attribute) {
                try {
                    Json::decode($this->$attribute);
                } catch (InvalidArgumentException $e) {
                    $this->addError($attribute, \Yii::t('widgets', 'Invalid JSON: {exceptionMessage}',
                        ['exceptionMessage' => $e->getMessage()]));
                }
            },
        ];
        return $rules;
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
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert) {
        $data = json_decode($this->json_schema);
        $this->json_schema = json_encode($data, JSON_PRETTY_PRINT);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
    }
}
