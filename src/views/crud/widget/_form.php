<?php
/**
 * /app/src/../runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\Widget $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-form">

    <?php $form = ActiveForm::begin([
		'id' => 'Widget',
		'layout' => 'horizontal',
		'enableClientValidation' => true,
		'errorSummaryCssClass' => 'error-summary alert alert-error'
	]
);
?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>
			<?php
			$json = \hrzg\widget\models\crud\WidgetTemplate::findOne(['id'=>1])->json_schema;
			$schema = \yii\helpers\Json::decode($json);
			?>
			<?php echo $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'default_properties_json')->widget(\beowulfenator\JsonEditor\JsonEditorWidget::className(), [
				'schema' => $schema,
				'clientOptions' => [
					'theme' => 'bootstrap3',
					'disable_collapse' => true,
					'disable_edit_json' => true,
					'disable_properties' => true,
					'no_additional_properties' => true,
				],
			]); ?>
			<?php echo $form->field($model, 'name_id')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'container_id')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'request_param')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'access_owner')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'access_domain')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'access_read')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'access_update')->textInput(['maxlength' => true]) ?>
			<?php echo $form->field($model, 'access_delete')->textInput(['maxlength' => true]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
Tabs::widget(
	[
		'encodeLabels' => false,
		'items' => [ [
				'label'   => $model->getAliasModel(),
				'content' => $this->blocks['main'],
				'active'  => true,
			], ]
	]
);
?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
	'<span class="glyphicon glyphicon-check"></span> ' .
	($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
	[
		'id' => 'save-' . $model->formName(),
		'class' => 'btn btn-success'
	]
);
?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
