<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 * @var array $schema
 */
use insolita\wgadminlte\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = $model->getAliasModel() . $model->id . ', ' . Yii::t('widgets', 'Copy');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = \Yii::t('widgets', 'Copy');
?>
<div class="giiant-crud widget-copy">
    <?php $form = ActiveForm::begin(
        [
            'id'                     => 'widget-copy',
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
        <small><?= Html::encode($model->name_id) ?></small>
    </h1>

    <div class="clearfix crud-navigation">
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            \Yii::t('widgets', 'Create'),
            [
                'id'    => 'copy-' . $model->formName(),
                'class' => 'btn btn-success',
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
