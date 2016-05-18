<?php
/**
 * /app/src/../runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use dmstr\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-template-form">

    <?php $form = ActiveForm::begin([
            'id' => 'WidgetTemplate',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-10',
                    'error' => '',
                    'hint' => 'hidden',
                ]
            ]
        ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>

            <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'php_class')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'json_schema')->widget(\trntv\aceeditor\AceEditor::className()) ?>
            <?php echo $form->field($model, 'twig_template')->widget(\trntv\aceeditor\AceEditor::className()) ?>

        </p>
        <?php $this->endBlock(); ?>

        <?php echo
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => $model->getAliasModel(),
                        'content' => $this->blocks['main'],
                        'active' => true,
                    ],
                ]
            ]
        );
        ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> '.
            ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
            [
                'id' => 'save-'.$model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
