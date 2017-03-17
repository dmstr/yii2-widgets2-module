<?php

use devgroup\jsoneditor\Jsoneditor;
use dmstr\bootstrap\Tabs;
use Highlight\Highlighter;
use hrzg\widget\Module;
use hrzg\widget\widgets\CellPreview;
use insolita\wgadminlte\Box;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 */

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

    <?php Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name_id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>

            <?php echo Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . \Yii::t('widgets', 'New'),
                ['create'],
                ['class' => 'btn btn-success']
            ) ?>

            <?php if ($model->hasPermission('access_update') && \Yii::$app->user->can('widgets_crud_widget_update', ['route' => true])) : ?>
                <?php echo Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span> ' . \Yii::t('widgets', 'Edit'),
                    ['update', 'id' => $model->id],
                    ['class' => 'btn btn-info']
                ) ?>
            <?php endif; ?>

            <?php if (\Yii::$app->user->can('widgets_crud_widget_copy', ['route' => true])) : ?>
                <?php echo Html::a(
                    '<span class="glyphicon glyphicon-copy"></span> ' . \Yii::t('widgets', 'Copy'),
                    ['copy', 'id' => $model->id],
                    ['class' => 'btn btn-warning']
                ) ?>
            <?php endif; ?>

            <?php if ($model->hasPermission('access_delete') && \Yii::$app->user->can('widgets_crud_widget_delete', ['route' => true])) : ?>
                <?php echo Html::a(
                    '<span class="glyphicon glyphicon-trash"></span> ' . \Yii::t('widgets', 'Delete'),
                    ['delete', 'id' => $model->id],
                    [
                        'class'        => 'btn btn-danger',
                        'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this item?') . '',
                        'data-method'  => 'post',
                    ]
                ); ?>
            <?php endif; ?>

        </div>
        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '.\Yii::t('widgets', 'Full list'), ['index'],
                ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <h2><?=\Yii::t('widgets', 'Preview') ?></h2>
    <?= CellPreview::widget(['widget_id'=>$model->id]) ?>
    <hr />

    <h2>Data</h2>
    <?php $this->beginBlock('hrzg\widget\models\crud\Widget'); ?>

    <?php
    $hl = new Highlighter();
    $r = $hl->highlight("json", $model->default_properties_json, JSON_PRETTY_PRINT);
    ?>

    <?php echo DetailView::widget([
        'options' => ['class' => 'table table-striped detail-view'],
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model::optsStatus()[$model->status]
            ],
            [
                'attribute' => 'widget_template_id',
                'format' => 'raw',
                'value' => (\Yii::$app->user->can(Module::TEMPLATE_ACCESS_PERMISSION))
                    ? Html::a($model->template->name, ['crud/widget-template/view', 'id'=>$model->widget_template_id])
                    .' '.Html::a(FA::icon(FA::_EDIT), ['crud/widget-template/update', 'id'=>$model->widget_template_id])
                    : $model->template->name
            ],
            [
                'attribute' => 'default_properties_json',
                'format' => 'raw',
                'value' => Jsoneditor::widget([
                    'name' => '_display',
                    'value' => $model->default_properties_json,
                    'editorOptions' => [
                        'mode' => 'view',
                        'modes' => [
                            'view',
                            'code'
                        ]
                    ]
                ]),
            ],
            'domain_id',
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
            'copied_from',
            'created_at',
            'updated_at',
        ],
    ]); ?>

    <hr/>
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
    <?php Box::end() ?>
</div>
