<?php
/**
 * @var $this \yii\web\View
 * @var $model \hrzg\widget\models\crud\WidgetContentTranslation
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



    <?php $this->beginBlock('meta-data') ?>

    <?= $form->field($model, 'language')->widget(
        Select2::class,
        [
            'data' => $model::optsAccessDomain(),
            'options' => ['placeholder' => Yii::t('widgets', 'Select ...')],
            'pluginOptions' => ['allowClear' => true],
        ]
    );
    ?>
    
    <?php $this->endBlock() ?>


    <?php $this->beginBlock('access') ?>
    <?php if (\Yii::$app->user->can(Module::COPY_ACCESS_PERMISSION, ['route' => true])) : ?>
        <?= $form->field($model, 'access_domain')->widget(
            Select2::class,
            [
                'data' => $model::optsAccessDomain(),
                'options' => ['placeholder' => Yii::t('widgets', 'Select ...')],
                'pluginOptions' => ['allowClear' => true],
            ]
        );
        ?>
    <?php endif; ?>
    <?= $form->field($model, 'access_read')->widget(
        Select2::class,
        [
            'data' => $userAuthItems,
            'options' => ['placeholder' => Yii::t('widgets', 'Select ...')],
            'pluginOptions' => ['allowClear' => true],
        ]
    );
    ?>
    <?php if ($model->hasPermission('access_update') || $model->isNewRecord) : ?>
        <?= $form->field($model, 'access_update')->widget(
            Select2::class,
            [
                'data' => $userAuthItems,
                'options' => ['placeholder' => Yii::t('widgets', 'Select ...')],
                'pluginOptions' => ['allowClear' => true],
            ]
        );
        ?>
    <?php endif; ?>
    <?php if ($model->hasPermission('access_delete') || $model->isNewRecord) : ?>
        <?= $form->field($model, 'access_delete')->widget(
            Select2::class,
            [
                'data' => $userAuthItems,
                'options' => ['placeholder' => Yii::t('widgets', 'Select ...')],
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


            <?php \yii\widgets\Pjax::begin(['id' => 'pjax-widget-form']) ?>
            <?= $form->field($model, 'default_properties_json')->label(false)
                ->widget(\dmstr\jsoneditor\JsonEditorWidget::class, [
                    'id' => 'editor',
                    'schema' => $schema,
                    'clientOptions' => [
                        'theme' => 'bootstrap3',
                        'disable_collapse' => true,
                        'disable_properties' => true,
                        'keep_oneof_values' => false
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