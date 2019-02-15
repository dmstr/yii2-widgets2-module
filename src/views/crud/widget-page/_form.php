<?php

use dmstr\widgets\AccessInput;
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

        <?= $form->field($model, 'access_owner'); ?>

        <?= $form->field($model, 'access_domain'); ?>

        <?= $form->field($model, 'access_read'); ?>

        <?= $form->field($model, 'access_update'); ?>

        <?= $form->field($model, 'access_delete'); ?>

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

