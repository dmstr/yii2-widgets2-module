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
        parent::beforeAction($action);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Widget', 'url' => ['/widgets']];
        return true;
    }
}
