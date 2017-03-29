<?php
/**
 * @var $this \yii\web\View
 * @var $model \hrzg\widget\models\crud\WidgetContent
 * @var $form \yii\widgets\ActiveForm
 * @var array $userAuthItems
 */
use hrzg\widget\Module;
use kartik\select2\Select2;

$userAuthItems = $model::getUsersAuthItems();
?>

<div class="widget-form">
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

    <div class="row">
        <div class="col-sm-9">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <?= $form->field($model, 'status')->checkbox($model::optsStatus()) ?>
                </div>
            </div>
            <?php \yii\widgets\Pjax::begin(['id' => 'pjax-widget-form']) ?>
            <?= $form->field($model, 'default_properties_json')->label(false)
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
                            <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'request_param')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'container_id')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'domain_id')->textInput(
                                [
                                    'disabled' => (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->isAdmin) ? false : true,
                                    'maxlength' => true
                                ]
                            ); ?>
                            <?= $form->field($model, 'copied_from')->hiddenInput()->label(false); ?>
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
                            <?php if (\Yii::$app->user->can(Module::COPY_ACCESS_PERMISSION, ['route' => true])) : ?>
                                <?= $form->field($model, 'access_domain')->widget(
                                    Select2::classname(),
                                    [
                                        'data' => $model::optsAccessDomain(),
                                        'options' => ['placeholder' => Yii::t('pages', 'Select ...')],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]
                                );
                                ?>
                            <?php endif; ?>
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
                            <?= $form->field($model, 'access_owner')->hiddenInput()->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?= $this->blocks['main'] ?>
</div>

<?php
// TODO: this is just a positioning workaround
$js = file_get_contents(Yii::getAlias('@hrzg/widget/assets/web/widgets-init.js'));
$this->registerJs($js);
