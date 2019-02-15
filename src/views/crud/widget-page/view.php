<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var hrzg\widget\models\crud\WidgetPage $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('widgets', 'Widget Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('widgets', 'Widget Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('widgets', 'View');
?>
<div class="giiant-crud widget-page-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('widgets', 'Widget Page') ?>
        <small>
            <?= Html::encode($model->id) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('widgets', 'Edit'),
            [ 'update', 'id' => $model->id],
            ['class' => 'btn btn-info']) ?>

            <?= Html::a(
            '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('widgets', 'Copy'),
            ['create', 'id' => $model->id, 'WidgetPage'=>$copyParams],
            ['class' => 'btn btn-success']) ?>

            <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('widgets', 'New'),
            ['create'],
            ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
            . Yii::t('widgets', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('hrzg\widget\models\crud\WidgetPage'); ?>

    
    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
            'view',
        'access_owner',
        'access_domain',
        'access_read',
        'access_update',
        'access_delete',
    ],
    ]); ?>

    
    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('widgets', 'Delete'), ['delete', 'id' => $model->id],
    [
    'class' => 'btn btn-danger',
    'data-confirm' => '' . Yii::t('widgets', 'Are you sure to delete this item?') . '',
    'data-method' => 'post',
    ]); ?>
    <?php $this->endBlock(); ?>


    
<?php $this->beginBlock('HrzgWidgetPageMetas'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('widgets', 'List All') . ' Hrzg Widget Page Metas',
            ['/crud/widget-page/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('widgets', 'New') . ' Hrzg Widget Page Meta',
            ['/crud/widget-page/create', 'WidgetPage' => ['widget_page_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-HrzgWidgetPageMetas', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-HrzgWidgetPageMetas ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getHrzgWidgetPageMetas(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-hrzgwidgetpagemetas',
        ]
    ]),
    'pager'        => [
        'class'          => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('widgets', 'First'),
        'lastPageLabel'  => Yii::t('widgets', 'Last')
    ],
    'columns' => [
 [
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = '/crud/widget-page' . '/' . $action;
        $params['WidgetPage'] = ['widget_page_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/crud/widget-page'
],
'id',
'view',
'access_owner',
'access_domain',
'access_read',
'access_update',
'access_delete',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('HrzgWidgetPageTranslations'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('widgets', 'List All') . ' Hrzg Widget Page Translations',
            ['/crud/widget-page-translation/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('widgets', 'New') . ' Hrzg Widget Page Translation',
            ['/crud/widget-page-translation/create', 'WidgetPageTranslation' => ['widget_page_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-HrzgWidgetPageTranslations', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-HrzgWidgetPageTranslations ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getHrzgWidgetPageTranslations(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-hrzgwidgetpagetranslations',
        ]
    ]),
    'pager'        => [
        'class'          => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('widgets', 'First'),
        'lastPageLabel'  => Yii::t('widgets', 'Last')
    ],
    'columns' => [
 [
    'class'      => 'yii\grid\ActionColumn',
    'template'   => '{view} {update}',
    'contentOptions' => ['nowrap'=>'nowrap'],
    'urlCreator' => function ($action, $model, $key, $index) {
        // using the column name as key, not mapping to 'id' like the standard generator
        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        $params[0] = '/crud/widget-page-translation' . '/' . $action;
        $params['WidgetPageTranslation'] = ['widget_page_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => '/crud/widget-page-translation'
],
'id',
'language',
'title',
'description',
'keywords',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?= Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [
 [
    'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    'content' => $this->blocks['hrzg\widget\models\crud\WidgetPage'],
    'active'  => true,
],
[
    'content' => $this->blocks['HrzgWidgetPageMetas'],
    'label'   => '<small>Hrzg Widget Page Metas <span class="badge badge-default">'. $model->getHrzgWidgetPageMetas()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['HrzgWidgetPageTranslations'],
    'label'   => '<small>Hrzg Widget Page Translations <span class="badge badge-default">'. $model->getHrzgWidgetPageTranslations()->count() . '</span></small>',
    'active'  => false,
],
 ]
                 ]
    );
    ?>
</div>
