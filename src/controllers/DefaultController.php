<?php

namespace hrzg\widget\controllers;

use dmstr\modules\pages\traits\RequestParamActionTrait;
use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\crud\WidgetPage;
use hrzg\widget\Module;
use hrzg\widget\widgets\EditPageControls;
use Yii;
use yii\helpers\ArrayHelper;
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

    use RequestParamActionTrait;

    /**
     * @return mixed
     */
    protected function pageActionParamPage_id() {
        return ArrayHelper::map(WidgetPage::find()->all(),'id','title');
    }


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

    /**
     * @return string
     */
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

        if ($widget_page === null || $widget_page->is_visible === false) {
            throw new NotFoundHttpException(Yii::t('widgets', 'Page not found.'));
        }

        if ($widget_page->is_accessible === false) {
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
