<?php

namespace hrzg\widget\controllers\crud\base;

use dmstr\bootstrap\Tabs;
use hrzg\widget\models\crud\search\WidgetContent as WidgetSearch;
use hrzg\widget\models\crud\WidgetContent;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud\base
 */
class WidgetController extends Controller
{
    /**
     * Lists all Widget models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WidgetSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Widget model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();

        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Widget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WidgetContent();

        try {
            if ($model->load($_POST) && $model->save()) {
                if (Url::previous($model->route)) {
                    return $this->redirect(Url::previous($model->route));
                } else {
                    return $this->redirect(['view', 'id'=>$model->id]);
                }
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
     * Updates an existing Widget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save() && Url::previous($model->route)) {
            return $this->redirect(Url::previous($model->route));
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Widget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            $redirectUrl = $model->route;
            $model->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);

            return $this->redirect(Url::previous());
        }

        if (Url::previous($redirectUrl)) {
            return $this->redirect(Url::previous($redirectUrl));
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Widget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws HttpException if the model cannot be found
     *
     * @param int $id
     *
     * @return WidgetContent the loaded model
     */
    protected function findModel($id)
    {
        if (($model = WidgetContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
