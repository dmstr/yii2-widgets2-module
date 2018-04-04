<?php

namespace hrzg\widget;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'hrzg\widget\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function beforeAction($action)
    {
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Widget module', 'url' => ['/widgets']];

        return parent::beforeAction($action);
    }
}
