<?php
/**
 * /app/src/../runtime/giiant/4b7e79a8340461fe629a6ac612644d03.
 */
namespace _;

use franciscomaya\sceditor\SCEditorAsset;
use insolita\wgadminlte\Box;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetContent $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="widget-form">


    <?php $form = ActiveForm::begin([
            'id' => 'Widget',
            'layout' => 'default',
            'enableClientValidation' => false,
            'errorSummaryCssClass' => 'error-summary alert alert-error',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-10',
                    'error' => '',
                    'hint' => 'hidden',
                ],
            ],
        ]
    );

    ?>

    <?php $js = <<<JS
var lastTemplateId = '{$model->widget_template_id}';
var widgets = {
	'updateTemplate': function(elem){
        $.pjax.defaults.timeout = 5000;
        console.log($(elem).val());
		if (!lastTemplateId || confirm('Reset values and update template?')) {
		    lastTemplateId = $(elem).val(); 
			url = '/widgets/crud/widget/create?Widget[widget_template_id]='+$('#widgetcontent-widget_template_id').val();
			//alert(url);
			$.pjax.reload({url: url, container: '#pjax-widget-form'});
		} else {
		    $(elem).val(lastTemplateId);
		}
		return false;
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

    <div class="row">
        <div class="col-sm-9">
            <?php Box::begin() ?>
            <?php echo $form->field($model, 'widget_template_id')->dropDownList($model::optsWidgetTemplateId(),
                [
                    'onchange' => 'widgets.updateTemplate(this)',
                ]
            ) ?>

            <div style="overflow: auto">

                <?php
                # TODO: workaround for editor registration
                \franciscomaya\sceditor\SCEditorAsset::register($this)
                ?>
                <?php \yii\widgets\Pjax::begin(['id' => 'pjax-widget-form']) ?>
                <?php echo $form->field($model, 'default_properties_json')
                    ->widget(\beowulfenator\JsonEditor\JsonEditorWidget::className(), [
                        'schema' => $schema,
                        'clientOptions' => [
                            'theme' => 'bootstrap3',
                            'disable_collapse' => true,
                            #'disable_edit_json' => true,
                            'disable_properties' => true,
                            #'no_additional_properties' => true,
                        ],
                    ]); ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div>

            <?php Box::end() ?>
        </div>
        <div class="col-sm-3">
            <?php Box::begin() ?>

            <?php echo $form->field($model, 'status')->checkbox() ?>
            <?php echo $form->field($model, 'name_id')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'request_param')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'container_id')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>

            <?php Box::end() ?>

            <?php Box::begin([
                'title' => 'Access',
                'collapse' => true,
                'collapseDefault' => true,
            ]) ?>
            <?php echo $form->field($model, 'access_domain')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'access_owner')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'access_read')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'access_update')->textInput(['maxlength' => true]) ?>
            <?php echo $form->field($model, 'access_delete')->textInput(['maxlength' => true]) ?>
            <?php Box::end() ?>
        </div>
    </div>


    </p>
    <?php $this->endBlock(); ?>

    <?php echo $this->blocks['main'] ?>
    <hr/>

    <?php echo $form->errorSummary($model); ?>

    <?php echo Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> '.
        ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
        [
            'id' => 'save-'.$model->formName(),
            'class' => 'btn btn-success',
        ]
    );
    ?>

    <?php ActiveForm::end(); ?>


</div>
