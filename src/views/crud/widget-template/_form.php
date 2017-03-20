<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetTemplate $model
 * @var \yii\widgets\ActiveForm $form
 */

?>
<div class="widget-template-form">
    <?php $this->beginBlock('main'); ?>
    <p>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'php_class')->dropDownList($model->optPhpClass()) ?>
        <?= $form->field($model, 'json_schema')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'json', 'containerOptions' => ['style' => 'height: 800px;']]
            ) ?>
        <?= $form->field($model, 'twig_template')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'twig', 'containerOptions' => ['style' => 'height: 800px;']]
            ) ?>

    </p>
    <?php $this->endBlock(); ?>
    <?= $this->blocks['main'] ?>
</div>
