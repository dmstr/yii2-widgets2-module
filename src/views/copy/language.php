<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @var $copyForm CopyForm
 */

use hrzg\widget\models\forms\CopyForm;
use hrzg\widget\Module;
use insolita\wgadminlte\Box;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = \Yii::t('widgets', 'Copy Widgets');

/**
 * Attribute names
 */
$attributeSourceLanguage = 'sourceLanguage';
$attributeDestinationLanguage = 'destinationLanguage';
?>
<?php
Box::begin(
    [
        'title'    => Yii::t('widgets', 'General'),
        'collapse' => false
    ]
);
$form = ActiveForm::begin();
?>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-lg-3">
            <?= $form->field($copyForm, $attributeSourceLanguage)->widget(
                Select2::classname(),
                [
                    'name'          => Html::getInputName($copyForm, $attributeSourceLanguage),
                    'model'         => $copyForm,
                    'attribute'     => $attributeSourceLanguage,
                    'addon'         => [
                        'prepend' => [
                            'content' => FA::i('flag'),
                        ],
                    ],
                    'data'          => $copyForm::availableLanguages(),
                    'options'       => [
                        'placeholder' => Yii::t('widgets', 'Select source language'),
                        'multiple'    => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]
            )->label(false); ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-lg-3">
            <?= $form->field($copyForm, $attributeDestinationLanguage)->widget(
                Select2::classname(),
                [
                    'name'          => Html::getInputName($copyForm, $attributeDestinationLanguage),
                    'model'         => $copyForm,
                    'attribute'     => $attributeDestinationLanguage,
                    'addon'         => [
                        'prepend' => [
                            'content' => FA::i('flag'),
                        ],
                    ],
                    'data'          => $copyForm::availableLanguages(),
                    'options'       => [
                        'placeholder' => Yii::t('widgets', 'Select target language'),
                        'multiple'    => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]
            )->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-lg-3">
            <?= Html::submitButton(FA::i('copy').' '.\Yii::t('widgets', 'Start copy'), ['class' => 'btn btn-info'])?>
            <?php if (\Yii::$app->user->can(Module::WIDGETS_ACCESS_PERMISSION)) : ?>
                <?= Html::a('Widget Manager',['/widgets'],['class' => 'btn btn-default'])?>
            <?php endif; ?>
        </div>
    </div>
<?php ActiveForm::end();
Box::end();
