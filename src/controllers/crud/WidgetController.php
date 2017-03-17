<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use yii\helpers\Url;

/**
 * This is the class for controller "WidgetController".
 */
class WidgetController extends \hrzg\widget\controllers\crud\base\WidgetController
{

    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);
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
        if ($newWidget->load(\Yii::$app->request->post()) && $newWidget->save()) {
            return $this->redirect(['view', 'id' => $newWidget->id]);
        }

        // find widget to copy
        $widget = $this->findModel($id);

        // apply widgets attributes to new widget
        $newWidget->attributes = $widget->attributes;

        // clear attributes
        $newWidget->id = null;
        $newWidget->created_at = null;
        $newWidget->updated_at = null;

        // set new auto generated defaults
        $newWidget->domain_id = uniqid();
        $newWidget->rank = 'a-'.dechex(date('U'));

        // render copy form
        return $this->render('copy', ['model' => $newWidget]);
    }
}
