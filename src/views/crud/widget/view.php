<?php

namespace _;

/**
 * /app/src/../runtime/giiant/d4b4964a63cc95065fa0ae19074007ee.
 */
use dmstr\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetContent $model
 */
$copyParams = $model->attributes;

$this->title = $model->getAliasModel().$model->id;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('widgets', 'View');
?>
<div class="giiant-crud widget-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <?php \insolita\wgadminlte\Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name_id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a('<span class="glyphicon glyphicon-pencil"></span> '.\Yii::t('widgets', 'Edit'),
                ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?php echo Html::a('<span class="glyphicon glyphicon-copy"></span> '.\Yii::t('widgets', 'Copy'),
                ['create', 'id' => $model->id, 'Widget' => $copyParams], ['class' => 'btn btn-success']) ?>
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> '.\Yii::t('widgets', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>
        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '.\Yii::t('widgets', 'Full list'), ['index'],
                ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('hrzg\widget\models\crud\Widget'); ?>


    <?php echo DetailView::widget([
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            [
                'attribute' => 'widget_template_id',
                'format' => 'raw',
                'value' => \yii\helpers\Html::a($model->template->name, ['crud/widget-template/view', 'id'=>$model->widget_template_id])
            ],
            [
                'attribute' => 'default_properties_json',
                'format' => 'raw',
                'value' => '<pre>'.Json::encode($model->default_properties_json, JSON_PRETTY_PRINT).'</pre>',
            ],
            'name_id',
            'container_id',
            'rank',
            'route',
            'request_param',
            'access_owner',
            'access_domain',
            'access_read',
            'access_update',
            'access_delete',
            'created_at',
            'updated_at',
        ],
    ]); ?>


    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> '.\Yii::t('widgets', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => ''.\Yii::t('widgets', 'Are you sure to delete this item?').'',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>



    <?php echo Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># '.$model->id.'</b>',
                    'content' => $this->blocks['hrzg\widget\models\crud\Widget'],
                    'active' => true,
                ],
            ],
        ]
    );
    ?>

    <?php \insolita\wgadminlte\Box::end() ?>
</div>
