<?php

use dmstr\widgets\AccessInput;
use kartik\select2\Select2;
use pudinglabs\tagsinput\TagsinputWidget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetPage $model
 * @var yii\widgets\ActiveForm $form
 */

$this->registerCss('.bootstrap-tagsinput {width: 100%;}');
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

    <?= $form->field($model, 'view')->widget(Select2::class, [
        'data' => $model::optsView(),
        'theme' => Select2::THEME_BOOTSTRAP,
    ]); ?>

    <?= $form->field($model, 'title'); ?>

    <?= $form->field($model, 'description')->textarea(); ?>

    <?= $form->field($model, 'keywords')->widget(TagsinputWidget::class, ['clientOptions' => ['trimValue' => true, 'allowDuplicates' => false,'tagClass' => 'label label-primary']]); ?>

    <?= $form->field($model, 'status')->radioList($model::optsStatus()); ?>

    <?= AccessInput::widget(['form' => $form, 'model' => $model]) ?>

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

