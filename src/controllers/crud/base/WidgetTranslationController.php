<?php

namespace hrzg\widget\controllers\crud\base;

use dmstr\bootstrap\Tabs;
use hrzg\widget\models\crud\search\WidgetContentTranslation as WidgetTranslationSearch;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\models\crud\WidgetContentTranslation;
use hrzg\widget\models\crud\WidgetTemplate;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class WidgetController
 * @package hrzg\widget\controllers\crud\base
 */
class WidgetTranslationController extends Controller
{
    /**
     * @param WidgetContentTranslation $model
     *
     * @return array|mixed
     * @throws HttpException
     */
    public function getJsonSchema(WidgetContentTranslation $model)
    {
        $schema = [];


        /**@var $model WidgetContent */
        $model = $model->getWidgetContent()->one();


        // get json schema
        switch (true) {
            case !empty($model->widget_template_id):

                $template = WidgetTemplate::findOne($model->widget_template_id);
                if (empty($template)) {
                    throw new HttpException(404, \Yii::t('widgets', 'Template not found'));
                }

                break;
            case \Yii::$app->request->get('Widget'):
                $template = WidgetTemplate::findOne(\Yii::$app->request->get('Widget')['widget_template_id']);
                if (empty($template)) {
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
        $searchModel = new WidgetTranslationSearch();
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
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

                $widgetContent = $model->getWidgetContent()->one();

                $widgetContent->access_domain = $model->access_domain;

                if ($widgetContent !== null) {
                    $targetPage = (new \dmstr\modules\pages\models\Tree())->sibling(
                        $widgetContent->access_domain,
                        $widgetContent->request_param,
                        $widgetContent->route
                    );

                    if ($targetPage !== null) {
                        $widgetContent->request_param = (string)$targetPage->id;
                        $widgetContent->save();
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
                            ['PAGE_ID' => $widgetContent->request_param, 'LANGUAGE' => $widgetContent->access_domain]
                        );
                        \Yii::$app->session->setFlash('info', $newPageInfoMsg);
                    }
                } else {
                    $contentNotFoundMsg = \Yii::t(
                        'widgets',
                        'Related content not dound'
                    );
                    \Yii::$app->session->setFlash('danger', $contentNotFoundMsg);
                }

            }

            if (isset($_POST['apply'])) {
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
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
            $model->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);

        }

        return $this->redirect(Url::previous());

    }

    /**
     * Finds the Widget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws HttpException if the model cannot be found
     *
     * @param int $id
     *
     * @return WidgetContentTranslation the loaded model
     */
    protected function findModel($id)
    {
        if (($model = WidgetContentTranslation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
