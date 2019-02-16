<?php

namespace hrzg\widget\controllers;

use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\crud\WidgetPage;
use hrzg\widget\Module;
use hrzg\widget\widgets\EditPageControls;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class DefaultController
 * @package hrzg\widget\controllers
 *
 * @property Module $module
 * @property string $layout
 */
class DefaultController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed
     * @throws \Exception
     */
    public function afterAction($action, $result)
    {
        if ($action->id === 'page') {
            $result .= EditPageControls::widget(['edit_page_url' => ['/' . $this->module->id . '/crud/widget-page/update','id' => $action->controller->actionParams['page_id']]]);
        }
        return parent::afterAction($action, $result);
    }

    public function actionIndex()
    {
        $searchModel = new WidgetTemplate();
        $dataProvider = $searchModel->search([]);

        return $this->render('index', ['templatesDataProvider' => $dataProvider]);
    }


    /**
     * @param $page_id
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionPage($page_id)
    {
        $widget_page = WidgetPage::find()->andWhere([WidgetPage::tableName() . '.id' => $page_id])->one();

        if ($widget_page === null || !$widget_page->is_visible) {
            throw new NotFoundHttpException(Yii::t('widgets', 'Page not found.'));
        }

        if (!$widget_page->is_accessible) {
            if (Yii::$app->user->isGuest) {
                return $this->redirect(\Yii::$app->user->loginUrl);
            }
            throw new ForbiddenHttpException(Yii::t('widgets', 'You are not allowed to access this page.'));
        }

        $this->layout = $this->module->widget_page_layout;

        $this->view->title = $widget_page->title;
        $this->view->registerMetaTag(['name' => 'description', 'content' => $widget_page->description]);
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => $widget_page->keywords]);

        return $this->render($widget_page->view, ['widget_page' => $widget_page]);
    }
}
