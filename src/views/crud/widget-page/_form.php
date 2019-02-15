<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

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

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>
            

<!-- attribute view -->
			<?= $form->field($model, 'view'); ?>

<!-- attribute access_owner -->
			<?= $form->field($model, 'access_owner'); ?>

<!-- attribute access_domain -->
			<?= $form->field($model, 'access_domain'); ?>

<!-- attribute access_read -->
			<?= $form->field($model, 'access_read'); ?>

<!-- attribute access_update -->
			<?= $form->field($model, 'access_update'); ?>

<!-- attribute access_delete -->
			<?= $form->field($model, 'access_delete'); ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                    'encodeLabels' => false,
                    'items' => [ 
                        [
    'label'   => Yii::t('widgets', 'WidgetPage'),
    'content' => $this->blocks['main'],
    'active'  => true,
],
                    ]
                 ]
    );
    ?>
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

</div>

