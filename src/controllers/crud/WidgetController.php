<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetController extends \hrzg\widget\controllers\crud\base\WidgetController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => [
                    'DELETE',
                    'POST'
                ]
            ]
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);

        // if set use CKEditor configurations from settings module else use default configuration.
        $json = \Yii::$app->settings->get('ckeditor.config', 'widgets');
        $ckeditorConfiguration = isset($json->scalar) ? $json->scalar : "{}";
        $CKConfigScript = "window.CKCONFIG = {$ckeditorConfiguration};";
        \Yii::$app->view->registerJs($CKConfigScript, \yii\web\View::POS_HEAD);

        // if set use FILEFLYCONFIG configurations from settings module else use default configuration.
        $json = \Yii::$app->settings->get('filefly.config', 'widgets');
        $fileflyConfiguration = isset($json->scalar) ? $json->scalar : "{}";
        $fileflyConfigScript = "window.FILEFLYCONFIG = {$fileflyConfiguration};";
        \Yii::$app->view->registerJs($fileflyConfigScript, \yii\web\View::POS_HEAD);
        return parent::beforeAction($action);
    }

    /**
     * Creates a new Widget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreateFromTemplate($templateId = null)
    {
        $model = new WidgetContent();

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['view','id' => $model->id]);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', ['model' => $model]);
    }
}
