<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var hrzg\widget\models\crud\search\WidgetPage $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="widget-page-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'view') ?>

		<?= $form->field($model, 'access_owner') ?>

		<?= $form->field($model, 'access_domain') ?>

		<?= $form->field($model, 'access_read') ?>

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
