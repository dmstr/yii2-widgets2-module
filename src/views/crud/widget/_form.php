<?php

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \hrzg\widget\models\crud\WidgetContent
 * @var $form \yii\widgets\ActiveForm
 */

/** @var array $userAuthItems */
$userAuthItems = $model::getUsersAuthItems();
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

    <?php
    $language = Yii::$app->language;
    $js = <<<JS
var lastTemplateId = '{$model->widget_template_id}';
var widgets = {
	'updateTemplate': function(elem){
        $.pjax.defaults.timeout = 5000;
        console.log('template: update',$(elem).val());
		if (!lastTemplateId || confirm('Reset values and update template?')) {
		    console.log('template: reload');
		    lastTemplateId = $(elem).val(); 
			url = '/{$language}/widgets/crud/widget/create?Widget[widget_template_id]='+$('#widgetcontent-widget_template_id').val();
			//alert(url);
			
			
			$.pjax.reload({
			    url: url, 
			    container: '#pjax-widget-form'
			});
			
		} else {
		    $(elem).val(lastTemplateId);
		    console.log('template: last');
            editor.trigger('ready');
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

            <div class="panel panel-info">
                <div class="panel-heading">
                    <?php echo $form->field($model, 'status')->checkbox($model::optsStatus()) ?>
                </div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id' => 'pjax-widget-form']) ?>
            <?php echo $form->field($model, 'default_properties_json')->label(false)
                ->widget(\beowulfenator\JsonEditor\JsonEditorWidget::className(), [
                    'id' => 'editor',
                    'schema' => $schema,
                    'enableSelectize' => true,
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
        <div class="col-sm-3">

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMeta" aria-expanded="true" aria-controls="collapseOne">
                                <?= \Yii::t('widgets', 'Meta Data')?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseMeta" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <?= $form->field($model, 'widget_template_id')->widget(
                                Select2::classname(),
                                [
                                    'data' => $model::optsWidgetTemplateId(),
                                    'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                    'pluginOptions' => ['allowClear' => true],
                                    'pluginEvents' => [
                                        'change' => 'function() {widgets.updateTemplate(this)}'
                                    ],
                                ]
                            );
                            ?>
                            <?php echo $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
                            <?php echo $form->field($model, 'request_param')->textInput(['maxlength' => true]) ?>
                            <?php echo $form->field($model, 'container_id')->textInput(['maxlength' => true]) ?>
                            <?php echo $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>
                            <?php echo $form->field($model, 'domain_id')->textInput(
                                [
                                    'disabled' => (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->isAdmin) ? false : true,
                                    'maxlength' => true
                                ]
                            ); ?>
                            <?php echo $form->field($model, 'copied_from')->hiddenInput()->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccess" aria-expanded="false" aria-controls="collapseTwo">
                                <?= \Yii::t('widgets', 'Access')?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseAccess" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <?= $form->field($model, 'access_domain')->widget(
                                Select2::classname(),
                                [
                                    'data' => $model::optsAccessDomain(),
                                    'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                    'pluginOptions' => ['allowClear' => true],
                                ]
                            );
                            ?>
                            <?= $form->field($model, 'access_read')->widget(
                                Select2::classname(),
                                [
                                    'data' => $userAuthItems,
                                    'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                    'pluginOptions' => ['allowClear' => true],
                                ]
                            );
                            ?>
                            <?php if ($model->hasPermission('access_update') || $model->isNewRecord) : ?>
                                <?= $form->field($model, 'access_update')->widget(
                                    Select2::classname(),
                                    [
                                        'data' => $userAuthItems,
                                        'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]
                                );
                                ?>
                            <?php endif; ?>

                            <?php if ($model->hasPermission('access_delete') || $model->isNewRecord) : ?>
                                <?= $form->field($model, 'access_delete')->widget(
                                    Select2::classname(),
                                    [
                                        'data' => $userAuthItems,
                                        'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]
                                );
                                ?>
                            <?php endif; ?>
                            <?php echo $form->field($model, 'access_owner')->hiddenInput()->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>

    <?php echo $this->blocks['main'] ?>
    <hr/>
    <?php echo Html::submitButton(
        '<span class="glyphicon glyphicon-check"></span> '.
        ($model->isNewRecord ? Yii::t('widgets', 'Create') : Yii::t('widgets', 'Save')),
        [
            'id' => 'save-'.$model->formName(),
            'class' => 'btn btn-success',
        ]
    );
    ?>
    <?php ActiveForm::end(); ?>
</div>


<?php
// TODO: this is just a positioning workaround
$js = file_get_contents(Yii::getAlias('@hrzg/widget/assets/web/widgets-init.js'));
$this->registerJs($js);
