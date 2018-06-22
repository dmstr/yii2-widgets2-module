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

<?php $this->beginBlock('crud-navigation') ?>
<div class="clearfix crud-navigation">

    <div class="btn-group" role="group">
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
        '<span class="glyphicon glyphicon-floppy-disk"></span> ' .
        \Yii::t('widgets', 'Apply'),
        [
            'id'    => 'apply-' . $model->formName(),
            'name' => 'apply',
            'class' => 'btn btn-success',
        ]
    );
    ?>
    </div>

    <div class="pull-right">
        <?= Html::a(
            '<span class="glyphicon glyphicon-eye-open"></span> ' . \Yii::t('widgets', 'View'),
            ['view', 'id' => $model->id],
            ['class' => 'btn btn-default']
        );
        ?>
    </div>
</div>
<?php $this->endBlock() ?>

<?php if ($model->getBehavior('translatable')->isFallbackTranslation) {
    echo ' <div class="alert alert-info">' . \Yii::t('widgets', 'The currently displayed values are taken from the fallback language. If you change translated values a new translation will be stored for this widget. Changing the status does not affect the translation.') . '</div>';
}
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

    <?= $this->blocks['crud-navigation'] ?>
    <?= $this->render('_form', ['model' => $model, 'form' => $form, 'schema' => $schema]); ?>
    <?= $this->blocks['crud-navigation'] ?>

    <?php Box::end() ?>
    <?php ActiveForm::end(); ?>
</div>
