<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\models\crud\WidgetTemplate;
use yii\helpers\Url;

/**
 * Class WidgetTemplateController
 * @package hrzg\widget\controllers\crud
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetTemplateController extends \hrzg\widget\controllers\crud\base\WidgetTemplateController
{
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
        $newWidgetTemplate = new WidgetTemplate();
        if ($newWidgetTemplate->load(\Yii::$app->request->post())) {
            if ($newWidgetTemplate->save()) {
                $successMsg = \Yii::t('widgets', 'Widget template successfully copied');
                \Yii::$app->session->setFlash('success', $successMsg);
                \Yii::info($successMsg, __METHOD__);
                return $this->redirect(['view', 'id' => $newWidgetTemplate->id]);
            } else {
                $errorMsg = \Yii::t('widgets', 'Copy widget template failed');
                \Yii::$app->session->setFlash('error', $errorMsg . ' | ' . implode('<br>', $newWidgetTemplate->getFirstErrors()));
                \Yii::error($errorMsg, __METHOD__);
                \Yii::error($newWidgetTemplate->getErrors(), __METHOD__);
                return $this->redirect(Url::previous());
            }
        }

        // find widget to copy
        $widgetTemplate = $this->findModel($id);

        // apply widgets attributes to new widget
        $newWidgetTemplate->attributes = $widgetTemplate->attributes;

        // clear attributes
        $newWidgetTemplate->id = null;
        $newWidgetTemplate->created_at = null;
        $newWidgetTemplate->updated_at = null;

        // render copy form
        return $this->render('copy', ['model' => $newWidgetTemplate]);
    }
}
