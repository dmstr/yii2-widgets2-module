<?php

use dmstr\widgets\AccessInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetPage $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-page-form">

    <?php $form = ActiveForm::begin([
            'id' => 'WidgetPage',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    #'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    ?>




    <?= $form->field($model, 'view'); ?>

    <?= $form->field($model, 'title'); ?>

    <?= $form->field($model, 'description')->textarea(); ?>

    <?= $form->field($model, 'keywords'); ?>

    <?= $form->field($model, 'status')->radioList($model::optsStatus()); ?>

    <?php
    $model->access_owner = $model->isNewRecord ? Yii::$app->user->id : $model->access_owner;
    ?>
    <?= $form->field($model, 'access_owner')->textInput(['readonly' => true]); ?>

    <?= $form->field($model, 'access_domain')->widget(Select2::class, [
        'data' => $model::optsAccessDomain(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

    <?= $form->field($model, 'access_read')->widget(Select2::class, [
        'data' => $model::optsAccessPrivileges(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

    <?= $form->field($model, 'access_update')->widget(Select2::class, [
        'data' => $model::optsAccessPrivileges(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

    <?= $form->field($model, 'access_delete')->widget(Select2::class, [
        'data' => $model::optsAccessPrivileges(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

    <hr/>

    <?php echo $form->errorSummary($model); ?>

    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> ' .
        ($model->isNewRecord ? Yii::t('widgets', 'Create') : Yii::t('widgets', 'Save')),
        [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
        ]
    );
    ?>

    <?php ActiveForm::end(); ?>


</div>

