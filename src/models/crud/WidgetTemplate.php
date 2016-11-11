<?php

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\WidgetTemplate as BaseWidgetTemplate;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "app_hrzg_widget_template".
 */
class WidgetTemplate extends BaseWidgetTemplate
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'bedezign\yii2\audit\AuditTrailBehavior'
            ]
        );
    }

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
     * @param string $json_schema
     */
    public function beforeSave($insert) {
        $data = json_decode($this->json_schema);
        $this->json_schema = json_encode($data, JSON_PRETTY_PRINT);
        return parent::beforeSave($insert);
    }
}
