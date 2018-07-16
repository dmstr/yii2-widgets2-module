<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use yii\helpers\Url;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetController extends \hrzg\widget\controllers\crud\base\WidgetController
{

    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);
        // if set use CKEditor configurations from settings module else use default configuration.
        $json = \Yii::$app->settings->get('ckeditor.config', 'widgets');
        $ckeditorConfiguration = isset($json->scalar) ? $json->scalar : "{}";
        $script = "window.CKCONFIG = {$ckeditorConfiguration};";
        \Yii::$app->view->registerJs($script, \yii\web\View::POS_HEAD);
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
                return $this->redirect(Url::previous());
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Copy a single widget
     *
     * @param $id
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCopy($id)
    {
        Url::remember();

        // save new widget
        $newWidget = new WidgetContent();
        if ($newWidget->load(\Yii::$app->request->post())) {
            if ($newWidget->save()) {
                $successMsg = \Yii::t('widgets', 'Widget successfully copied from #{ID}', ['ID' => $newWidget->copied_from]);
                \Yii::$app->session->setFlash('success', $successMsg);
                \Yii::info($successMsg, __METHOD__);
                \Yii::$app->language = $newWidget->access_domain;
                return $this->redirect(['view', 'id' => $newWidget->id]);
            } else {
                $errorMsg = \Yii::t('widgets', 'Copy widget from from #{ID}', ['ID' => $newWidget->copied_from]);
                \Yii::$app->session->setFlash('error', $errorMsg . ' | ' . implode('<br>', $newWidget->getFirstErrors()));
                \Yii::error($errorMsg, __METHOD__);
                \Yii::error($newWidget->getErrors(), __METHOD__);
                return $this->redirect(Url::previous());
            }
        }

        // find widget to copy
        $widget = $this->findModel($id);

        // apply widgets attributes to new widget
        $newWidget->attributes = $widget->attributes;
        $newWidget->copied_from = $widget->id;
        $newWidget->access_owner = \Yii::$app->user->id;

        // clear attributes
        $newWidget->id = null;
        $newWidget->created_at = null;
        $newWidget->updated_at = null;

        // set new auto generated defaults
        $newWidget->domain_id = uniqid();
        $newWidget->rank = 'a-' . dechex(date('U'));

        // render copy form
        return $this->render('copy', ['model' => $newWidget, 'schema' => $this->getJsonSchema($newWidget)]);
    }
}
