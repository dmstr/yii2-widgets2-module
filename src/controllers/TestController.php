<?php

namespace hrzg\widget\controllers;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index.twig');
    }

    public function actionWithParam()
    {
        return $this->render('index.twig');
    }
}
