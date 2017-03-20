<?php

namespace hrzg\widget\controllers\crud\base;

use dmstr\bootstrap\Tabs;
use hrzg\widget\models\crud\search\WidgetTemplate as WidgetTemplateSearch;
use hrzg\widget\models\crud\WidgetTemplate;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class WidgetTemplateController
 * @package hrzg\widget\controllers\crud\base
 */
class WidgetTemplateController extends Controller
{
    /**
     * Lists all WidgetTemplate models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WidgetTemplateSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single WidgetTemplate model.
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
     * Creates a new WidgetTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WidgetTemplate();

        try {
            if ($model->load($_POST) && $model->save()) {
                if (isset($_POST['apply'])) {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
                return $this->redirect('index');
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
     * Updates an existing WidgetTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($_POST) && $model->save()) {
            if (isset($_POST['apply'])) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id'=>$model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WidgetTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);

            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the WidgetTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws HttpException if the model cannot be found
     *
     * @param int $id
     *
     * @return WidgetTemplate the loaded model
     */
    protected function findModel($id)
    {
        if (($model = WidgetTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
