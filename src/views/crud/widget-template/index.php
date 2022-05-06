<?php
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
            'id'                 => 'widget-template-main',
            'enableReplaceState' => false,
            'linkSelector'       => '#pjax-main ul.pagination a, th a',
        ]
    ) ?>
    <h1>
        <?= $searchModel->getAliasModel(true) ?>
        <small>List</small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> '.\Yii::t('widgets', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>
        <?php if (Yii::$app->getUser()->can('widgets_crud_widget-template_import')): ?>
        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-import"></span> '.\Yii::t('widgets', 'Import'), ['import'],
                ['class' => 'btn btn-info']) ?>
        </div>
        <?php endif ?>
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
                    'template' => '{view} {update} {export} {delete}',
                    'buttons' => [
                            'export' => function ($url) {
                                return Html::a('<span class="glyphicon glyphicon-export"></span>', $url,['data-pjax' => 0]);
                            }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id.'/'.$action : $action;

                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap'],
                    'visibleButtons' => [
                            'export' => Yii::$app->getUser()->can('widgets_crud_widget-template_export')
                    ]
                ],
                'name',
                'php_class',
            ],
        ]); ?>
    </div>
    <?php Pjax::end() ?>
    <?php Box::end() ?>
</div>
