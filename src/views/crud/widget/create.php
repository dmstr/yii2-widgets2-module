<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 * @var array $schema
 */
use insolita\wgadminlte\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = Yii::t('widgets', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud widget-create">
    <?php $form = ActiveForm::begin(
        [
            'id'                     => 'widget-create',
            'layout'                 => 'default',
            'enableClientValidation' => false,
            'errorSummaryCssClass'   => 'error-summary alert alert-error',
            'fieldConfig'            => [
                'horizontalCssClasses' => [
                    'label'   => 'col-sm-2',
                    'wrapper' => 'col-sm-10',
                    'error'   => '',
                    'hint'    => 'hidden',
                ],
            ],
        ]
    );
    ?>
    <?php Box::begin() ?>
    <h1>
        <?= $model->getAliasModel() ?>
        <small><?= $model->name_id ?></small>
    </h1>
    <div class="clearfix crud-navigation sticky-controls">
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            \Yii::t('widgets', 'Create'),
            [
                'id'    => 'create-' . $model->formName(),
                'class' => 'btn btn-success',
            ]
        );
        ?>
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-refresh"></span> ' .
            \Yii::t('widgets', 'Apply'),
            [
                'id'    => 'apply-' . $model->formName(),
                'name' => 'apply',
                'class' => 'btn btn-warning',
            ]
        );
        ?>
        <div class="pull-right">
            <?= Html::a(
                '<span class="glyphicon glyphicon-file"></span> ' . \Yii::t('widgets', 'Cancel'),
                ['/widgets/crud/widget/index'],
                ['class' => 'btn btn-default']
            ) ?>
        </div>
    </div>
    <hr/>
    <?= $this->render('_form', ['model' => $model, 'form' => $form, 'schema' => $schema]); ?>
    <?php Box::end() ?>
    <?php ActiveForm::end(); ?>
</div>
