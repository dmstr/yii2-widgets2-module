<?php
/**
 * /app/src/../runtime/giiant/d4b4964a63cc95065fa0ae19074007ee
 *
 * @package default
 */


use dmstr\bootstrap\Tabs;
use dmstr\helpers\Html;
use yii\widgets\DetailView;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetTemplate $model
 */
$copyParams = $model->attributes;

$this->title = $model->getAliasModel().$model->name;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="giiant-crud widget-template-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Edit'),
                ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?php echo Html::a('<span class="glyphicon glyphicon-copy"></span> '.Yii::t('app', 'Copy'),
                ['create', 'id' => $model->id, 'WidgetTemplate            ' => $copyParams],
                ['class' => 'btn btn-success']) ?>
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>
        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '.Yii::t('app', 'Full list'), ['index'],
                ['class' => 'btn btn-default']) ?>
        </div>

    </div>


    <?php $this->beginBlock('hrzg\widget\models\crud\WidgetTemplate'); ?>


    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'php_class',
            'json_schema:ntext',
            'twig_template:ntext',
        ],
    ]); ?>


    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => ''.Yii::t('app', 'Are you sure to delete this item?').'',
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
                    'content' => $this->blocks['hrzg\widget\models\crud\WidgetTemplate'],
                    'active' => true,
                ],
            ]
        ]
    );
    ?>
</div>
