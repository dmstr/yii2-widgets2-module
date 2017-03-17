<?php
namespace hrzg\widget\controllers\crud\api;

/*
 * This is the class for REST controller "WidgetTemplateController".
 */
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class WidgetTemplateController extends \yii\rest\ActiveController
{
    public $modelClass = 'hrzg\widget\models\crud\WidgetTemplate';

    /**
     * {@inheritdoc}
     *
     * @return unknown
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,

                            /*
                             *
                             */
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->can($this->module->id.'_'.$this->id.'_'.$action->id,
                                    ['route' => true]);
                            },
                        ],
                    ],
                ],
            ]
        );
    }
}
