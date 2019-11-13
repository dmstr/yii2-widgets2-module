<?php
/**
 * /app/src/../runtime/giiant/eeda5c365686c9888dbc13dbc58f89a1.
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\search\WidgetContent $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'language') ?>

    <?= $form->field($model, 'widget_content_id') ?>

    <?= $form->field($model, 'default_properties_json') ?>

    <?php // echo $form->field($model, 'name_id') ?>

    <?php // echo $form->field($model, 'container_id') ?>

    <?php // echo $form->field($model, 'rank') ?>

    <?php // echo $form->field($model, 'route') ?>

    <?php // echo $form->field($model, 'request_param') ?>

    <?php // echo $form->field($model, 'access_owner') ?>

    <?php // echo $form->field($model, 'access_domain') ?>

    <?php // echo $form->field($model, 'access_read') ?>

    <?php // echo $form->field($model, 'access_update') ?>

    <?php // echo $form->field($model, 'access_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('widgets', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('widgets', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
