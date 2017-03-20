<?php

namespace hrzg\widget\controllers;

use hrzg\widget\models\crud\search\WidgetTemplate;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package hrzg\widget\controllers
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new WidgetTemplate();
        $dataProvider = $searchModel->search([]);

        return $this->render('index', ['templatesDataProvider' => $dataProvider]);
    }
}
