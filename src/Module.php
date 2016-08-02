<?php

namespace hrzg\widget;

use dmstr\web\traits\AccessBehaviorTrait;

class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;
    
    public $controllerNamespace = 'hrzg\widget\controllers';

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Widget module', 'url' => ['/widgets']];

        return true;
    }
}
