<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\helpers\WidgetTemplateExport;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class WidgetTemplateController
 *
 * @package hrzg\widget\controllers\crud
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetTemplateController extends \hrzg\widget\controllers\crud\base\WidgetTemplateController
{
    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);
        return parent::beforeAction($action);
    }

    /**
     * Export given widget template
     *
     * @param string $id
     * @return void
     * @throws \yii\base\ErrorException
     * @throws \yii\base\ExitException
     * @throws \yii\web\HttpException
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);

        $export = new WidgetTemplateExport([
            'widgetTemplate' => $model
        ]);

        if ($export->generateTar() === false) {
            throw new HttpException(500, \Yii::t('widgets', 'Error while exporting widget template'));
        }

        if (\Yii::$app->getResponse()->sendFile($export->getTarFilePath()) instanceof Response) {
            unlink($export->getTarFilePath());
        } else {
            throw new HttpException(500, \Yii::t('widgets', 'Error while downloading widget template'));
        }
        \Yii::$app->end();
    }
}
