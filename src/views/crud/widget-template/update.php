<?php
/**
 * /app/src/../runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetTemplate $model
 */
$this->title = $model->getAliasModel().$model->name.', '.Yii::t('app', 'Edit');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud widget-template-update">

    <?php \insolita\wgadminlte\Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-eye-open"></span> '.Yii::t('app', 'View'),
            ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

    <?php \insolita\wgadminlte\Box::end() ?>

</div>
