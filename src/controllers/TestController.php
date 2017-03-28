<?php

namespace hrzg\widget\controllers;

/**
 * Class TestController
 * @package hrzg\widget\controllers
 */
class TestController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        // set layout
        $this->layout = $this->module->playgroundLayout;

        return $this->render('index.twig');
    }

    /**
     * @return string
     */
    public function actionWithParam()
    {
        // set layout
        $this->layout = $this->module->playgroundLayout;

        return $this->render('index.twig');
    }
}
