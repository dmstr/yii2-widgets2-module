<?php

use hrzg\widget\models\crud\WidgetPage;
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
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('widgets', 'Copy'),
                ['create', 'id' => $model->id, 'WidgetPage' => $copyParams],
                ['class' => 'btn btn-success']) ?>

            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('widgets', 'New'),
                ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
                . Yii::t('widgets', 'Full list'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('hrzg\widget\models\crud\WidgetPage'); ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'class' => DataColumn::class,
                'attribute' => 'status',
                'value' => function ($model) {
                    return Html::tag('span', WidgetPage::optsStatus()[$model->status], ['class' => 'label label-' . ($model->status === WidgetPage::STATUS_ACTIVE ? 'success' : 'warning')]);
                },
                'format' => 'raw'
            ],
            'view',
            [
                'class' => DataColumn::class,
                'attribute' => 'keywords',
                'value' => function ($model) {
                    $html_elements = [];
                    foreach (explode(',', $model->keywords) as $keyword) {
                        $html_elements[] = Html::tag('span', trim($keyword), ['class' => 'label label-primary']);
                    }

                    return implode(' ', $html_elements);
                },
                'format' => 'raw'
            ],
            [
                'class' => DataColumn::class,
                'attribute' => 'description',
                'value' => function ($model) {
                    $description = $model->description;
                    $max_length = 30;
                    if (strlen($description) < $max_length) {
                        return $description;
                    }
                    return rtrim(substr($description, 0, $max_length)) . '...';
                },
            ],
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


    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . Html::encode($model->id) . '</b>',
                    'content' => $this->blocks['hrzg\widget\models\crud\WidgetPage'],
                    'active' => true,
                ]
            ]
        ]
    );
    ?>
</div>
