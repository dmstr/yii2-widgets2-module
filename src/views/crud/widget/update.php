<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 * @var array $schema
 */
use insolita\wgadminlte\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = $model->getAliasModel() . $model->id . ', ' . \Yii::t('widgets', 'Edit');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('widgets', 'Edit');
?>
<div class="giiant-crud widget-update">
    <?php $form = ActiveForm::begin(
        [
            'id'                     => 'widget-update',
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

    <div class="clearfix crud-navigation">
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
            \Yii::t('widgets', 'Update'),
            [
                'id'    => 'update-' . $model->formName(),
                'class' => 'btn btn-success',
            ]
        );
        ?>
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-refresh"></span> ' .
            \Yii::t('widgets', 'Apply'),
            [
                'name' => 'apply',
                'class' => 'btn btn-warning',
            ]
        );
        ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-file"></span> ' . \Yii::t('widgets', 'View'),
            ['view', 'id' => $model->id],
            ['class' => 'btn btn-default']
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
    <?= $this->render('_form', ['model' => $model, 'form' => $form, 'schema' => $schema]); ?>
    <?php Box::end() ?>
    <?php ActiveForm::end(); ?>
</div>
