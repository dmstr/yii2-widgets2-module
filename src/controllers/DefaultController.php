<?php

namespace hrzg\widget\controllers;

use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\ReOrderWidgets;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class DefaultController
 *
 * @package hrzg\widget\controllers
 */
class DefaultController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                're-order' => ['POST']
            ]
        ];
        return $behaviors;
    }

    /**
     * @param $action
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id === 're-order') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new WidgetTemplate();
        $dataProvider = $searchModel->search([]);

        return $this->render('index', ['templatesDataProvider' => $dataProvider]);
    }


    /**
     * @return bool
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function actionReOrder()
    {


        $model = new ReOrderWidgets();

        if ($model->load(json_decode(Yii::$app->request->rawBody, true)) && $model->reorder()) {
            return true;
        }
        throw new HttpException(500, json_encode($model->errors));
    }
}
