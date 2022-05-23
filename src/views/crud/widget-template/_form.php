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

        <?php $this->beginBlock('json_schema') ?>
        <?= $form->field($model, 'json_schema')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'json', 'containerOptions' => ['style' => 'height: 70vh;']]
            ) ?>
        <?php $this->endBlock() ?>

        <?php $this->beginBlock('twig_template') ?>
        <?= $form->field($model, 'twig_template')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'twig', 'containerOptions' => ['style' => 'height: 70vh;']]
            ) ?>
        <?php $this->endBlock() ?>

        <?= \dmstr\bootstrap\Tabs::widget([
                                            'items' => [
                                                [
                                                    'label' => 'Schema',
                                                    'content' => $this->blocks['json_schema']
                                                ],
                                                [
                                                    'label' => 'Template',
                                                    'content' => $this->blocks['twig_template']
                                                ]
                                            ]
                                        ]) ?>
        <?php $this->beginBlock('json_schema') ?>
        <?= $form->field($model, 'json_schema')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'json', 'containerOptions' => ['style' => 'height: 800px;']]
            ) ?>
        <?php $this->endBlock() ?>
        <?php $this->beginBlock('json_schema') ?>

        <?= $form->field($model, 'twig_template')
            ->widget(
                \trntv\aceeditor\AceEditor::className(),
                ['mode' => 'twig', 'containerOptions' => ['style' => 'height: 800px;']]
            ) ?>
        <?php $this->endBlock() ?>

        <?php echo $form->field($model,'hide_in_list_selection')->checkbox() ?>
    </p>
    <?php $this->endBlock(); ?>
    <?= $this->blocks['main'] ?>
</div>
