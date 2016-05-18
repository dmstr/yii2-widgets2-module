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
 * @var hrzg\widget\models\crud\WidgetContent $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-form">

    <?php \yii\widgets\Pjax::begin(['id' => 'pjax-widget-form']) ?>

    <?php $form = ActiveForm::begin([
            'id' => 'Widget',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    );

    ?>

    <?php $js = <<<JS
var widgets = {
	'updateTemplate': function(elem){
		if (confirm('Reset values and update template?')) {
			url = '/widgets/crud/widget/create?Widget[widget_template_id]='+$('#widget-widget_template_id').val();
			$.pjax({url: url, container: '#pjax-widget-form'});
		}
	}
}
JS;
    ?>
    <?php $this->registerJs($js, \yii\web\View::POS_HEAD) ?>



    <?php $this->beginBlock('main'); ?>

    <p>
        <?php
        # TODO: This is just a hack, move to controller...
        if ($model->widget_template_id) {
            $id = $model->widget_template_id;
            $json = \hrzg\widget\models\crud\WidgetTemplate::findOne(['id' => $id])->json_schema;
            $schema = \yii\helpers\Json::decode($json);
        } else {
            if (isset($_GET['Widget']['widget_template_id'])) {
                $id = $_GET['Widget']['widget_template_id'];
                $json = \hrzg\widget\models\crud\WidgetTemplate::findOne(['id' => $id])->json_schema;
                $schema = \yii\helpers\Json::decode($json);
            } else {
                $schema = [];
            }
        }
        ?>
        <?php echo $form->field($model, 'status')->checkbox() ?>

        <?php echo $form->field($model, 'widget_template_id')->dropDownList($model::optsWidgetTemplateId(),
            [
                'onchange' => 'widgets.updateTemplate()'
            ]
        ) ?>

        <?php echo $form->field($model, 'default_properties_json')
            ->widget(\beowulfenator\JsonEditor\JsonEditorWidget::className(), [
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

    <?php \yii\widgets\Pjax::end() ?>

</div>
