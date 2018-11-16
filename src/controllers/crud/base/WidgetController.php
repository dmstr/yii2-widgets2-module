<?php

namespace hrzg\widget\controllers\crud\base;

use dmstr\bootstrap\Tabs;
use hrzg\widget\models\crud\search\WidgetContent as WidgetSearch;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetTemplate;
use yii\base\UserException;
use yii\helpers\Json;
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
     * @param WidgetContent $model
     *
     * @return array|mixed
     * @throws HttpException
     */
    public function getJsonSchema(WidgetContent $model)
    {
        $schema = [];

        // get json schema
        switch (true) {
            case !empty($model->widget_template_id):

                $template = WidgetTemplate::findOne($model->widget_template_id);
                if(empty($template)) {
                    throw new HttpException(404, \Yii::t('widgets', 'Template not found'));
                }

                break;
            case \Yii::$app->request->get('Widget'):
                $template = WidgetTemplate::findOne(\Yii::$app->request->get('Widget')['widget_template_id']);
                if(empty($template)) {
                    throw new HttpException(404, \Yii::t('widgets', 'Template not found'));
                }
                break;
            default:
                $templateId = null;
        }

        if (!empty($template)) {
            $schema = Json::decode($template->json_schema);
        }

        return $schema;
    }

    /**
     * Lists all Widget models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $searchModel = new WidgetSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

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
        Url::remember();

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

                if (isset($_POST['apply'])) {
                    return $this->redirect(['update', 'id' => $model->id]);
                } else {
                    return $this->redirect(['view', 'id'=>$model->id]);
                }
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
            \Yii::error($msg, __METHOD__);
        }

        return $this->render('create', ['model' => $model, 'schema' => $this->getJsonSchema($model)]);
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

        // remember old model
        $oldAccessDomain = $model->oldAttributes['access_domain'];

        if ($model->load($_POST) && $model->save()) {

            // detect cross side domain update
            if (\Yii::$app->getModule('pages') !== null && $oldAccessDomain !== $model->access_domain) {

                $targetPage = (new \dmstr\modules\pages\models\Tree())->sibling(
                    $model->access_domain,
                    $model->request_param,
                    $model->route
                );

                if ($targetPage !== null) {
                    $model->request_param = (string)$targetPage->id;
                    $model->save();
                    $newPageSuccessMsg = \Yii::t(
                        'widgets',
                        'Placed widget on page "{PAGE} #{ID}" in language "{LANGUAGE}"',
                        ['ID' => $targetPage->id, 'PAGE' => $targetPage->name, 'LANGUAGE' => $targetPage->access_domain]
                    );
                    \Yii::$app->session->setFlash('success', $newPageSuccessMsg);
                } else {
                    $newPageInfoMsg = \Yii::t(
                        'widgets',
                        'No sibling page found for page "#{PAGE_ID}" in language "{LANGUAGE}"',
                        ['PAGE_ID' => $model->request_param, 'LANGUAGE' => $model->access_domain]
                    );
                    \Yii::$app->session->setFlash('info', $newPageInfoMsg);
                }
            }

            if (isset($_POST['apply'])) {
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                return $this->redirect(['view', 'id'=>$model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'schema' => $this->getJsonSchema($model),
        ]);
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
            if (!$model->delete()) {
                throw new UserException(\Yii::t('widgets', 'Can not delete Widget.'));
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
        }

        return $this->redirect(['index']);
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
