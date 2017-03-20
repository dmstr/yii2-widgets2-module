<?php
/**
 * /app/src/../runtime/giiant/eeda5c365686c9888dbc13dbc58f89a1.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\search\WidgetTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'json_schema') ?>

    <?= $form->field($model, 'twig_template') ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('widgets', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('widgets', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
