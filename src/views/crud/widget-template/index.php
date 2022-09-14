<?php

use hrzg\widget\models\crud\WidgetTemplate;
use insolita\wgadminlte\Box;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \hrzg\widget\models\crud\search\WidgetTemplate $searchModel
 */
$this->title = $searchModel->getAliasModel(true);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud widget-template-index">
    <?php Box::begin() ?>
    <?php Pjax::begin(
        [
            'id' => 'widget-template-main',
            'enableReplaceState' => false,
            'linkSelector' => '#pjax-main ul.pagination a, th a',
        ]
    ) ?>
    <h1>
        <?= $searchModel->getAliasModel(true) ?>
        <small>List</small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . \Yii::t('widgets', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <hr/>
    <div class="table-responsive">
        <?= GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => \Yii::t('widgets', 'First'),
                'lastPageLabel' => \Yii::t('widgets', 'Last'),
            ],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'columns' => [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;

                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap'],
                ],
                'name',
                [
                    'attribute' => 'php_class',
                    'filter' => $searchModel->optPhpClass()
                ],
                [
                    'attribute' => 'hide_in_list_selection',
                    'filter' => [
                        WidgetTemplate::IS_VISIBLE_IN_LIST => Yii::t('widgets', 'Visible'),
                        WidgetTemplate::IS_HIDDEN_IN_LIST => Yii::t('widgets', 'Hidden'),
                    ],
                    'value' => function ($model) {
                        return (bool)$model->hide_in_list_selection === WidgetTemplate::IS_VISIBLE_IN_LIST ? Yii::t('widgets', 'Visible') : Yii::t('widgets', 'Hidden');
                    }
                ]
            ],
        ]); ?>
    </div>
    <?php Pjax::end() ?>
    <?php Box::end() ?>
</div>
