<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace hrzg\widget\controllers\crud;

use hrzg\widget\models\crud\search\WidgetContent;

/**
 * This is the class for controller "WidgetController".
 */
class WidgetController extends \hrzg\widget\controllers\crud\base\WidgetController
{
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
}
