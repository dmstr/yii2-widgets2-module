<?php
/**
 * @var $this \yii\web\View
 * @var $model \hrzg\widget\models\crud\WidgetContent
 * @var $form \yii\widgets\ActiveForm
 * @var array $userAuthItems
 */

use hrzg\widget\Module;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use zhuravljov\yii\widgets\DateTimePicker;

$userAuthItems = $model::getUsersAuthItems();
?>

<div class="widget-form">
    <?php
    $language = Yii::$app->language;
    $module = $this->context->module->id;
    $js = <<<JS
var lastTemplateId = '{$model->widget_template_id}';
var widgets = {
	'updateTemplate': function(elem){
        $.pjax.defaults.timeout = 5000;
        console.log('template: update',$(elem).val());
		if (!lastTemplateId || confirm('Reset values and update template?')) {
		    console.log('template: reload');
		    lastTemplateId = $(elem).val(); 
			url = '/{$language}/{$module}/crud/widget/create?Widget[widget_template_id]='+$('#widgetcontent-widget_template_id').val();
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


    <?php $this->beginBlock('meta-data') ?>
    <?= $form->field($model, 'widget_template_id')->widget(
        Select2::classname(),
        [
            'data' => $model::optsWidgetTemplateId(),
            'disabled' => !$model->isNewRecord && !Yii::$app->user->can('Admin'),
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
            'disabled' => (!\Yii::$app->user->isGuest && \Yii::$app->user->can(Module::DOMAIN_ID_ACCESS_PERMISSION)) ? false : true,
            'maxlength' => true
        ]
    ); ?>
    <?= $form->field($model, 'copied_from')->hiddenInput()->label(false); ?>
    <?php $this->endBlock() ?>


    <?php $this->beginBlock('access') ?>
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
    <?php $this->endBlock() ?>






    <?php $this->beginBlock('main'); ?>
    <p>

    <div class="row">
        <div class="col-md-9">
            <?= $form->errorSummary($model) ?>
            <div class="panel panel-<?= $model->status ? 'success' : 'warning' ?>">
                <div class="panel-heading">
                    <?= $form->field($model, 'status')->dropDownList($model::optsStatus()) ?>
                </div>
                <?php if(\Yii::$app->controller->module->dateBasedAccessControl) { ?>

                    <?php
                    // sets $startday with the current date by timezone.
                    // the timezone can be configured in the widgets module.
                    // the default timezone is "UTC"
                    $timezone = Module::getInstance()->timezone;
                    $dateByTimeZone = new \DateTime(null, new \DateTimeZone($timezone));
                    // add 1 extra minutes. ex: cannot set 09:10 when 09:10.
                    $dateByTimeZone->add(new DateInterval('PT1M'));
                    $startDate = $dateByTimeZone->format('Y-m-d H:i');
                    $langCode = explode("-",$language)[0];
                    ?>

                <div class="panel-heading">
                    <div class="row">
                        <div class="form-group col-xs-12 col-md-6">
                            <?= $form->field($model, 'publish_at')->widget(DateTimePicker::class, [
                                'options' => [
                                    'class' => 'form-control col-md-6',
                                    'autocomplete' => 'off',

                                ],
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'autoclose' => true,
                                    'todayHighlight' => true,
                                    'minView' => (\Yii::$app->controller->module->datepickerMinutes) ? 0 : 1,
                                    'startDate' => $startDate,
                                    'language' => $langCode,
                                ],
                                'clientEvents' => [],
                            ])->textInput() ?>
                        </div>
                        <div class="form-group col-xs-12 col-md-6">
                            <?= $form->field($model, 'expire_at')->widget(DateTimePicker::class, [
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'autoclose' => true,
                                    'todayHighlight' => true,
                                    'minView' => (\Yii::$app->controller->module->datepickerMinutes) ? 0 : 1,
                                    'startDate' => $startDate,
                                    'language' => $langCode,
                                ],
                                'clientEvents' => [],
                            ])->textInput() ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
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


        <div class="col-md-3">
            <?= Collapse::widget([
                'items' => [
                    // equivalent to the above
                    [
                        'label' => \Yii::t('widgets', 'Meta Data'),
                        'content' => $this->blocks['meta-data'],
                        // open content by default, if it is a new record
                        'contentOptions' => ['class' => ($model->isNewRecord ? 'in':'')],
                    ],
                    // another group item
                    [
                        'label' => \Yii::t('widgets', 'Access'),
                        'content' => $this->blocks['access'],

                    ],


            ]]); ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?= $this->blocks['main'] ?>
</div>

<?php
// TODO: this is just a positioning workaround
$js = file_get_contents(Yii::getAlias('@hrzg/widget/assets/web/widgets-init.js'));
$this->registerJs($js);
