<?php

namespace _;

use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\Module;
use insolita\wgadminlte\Box;
use insolita\wgadminlte\SmallBox;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$this->title = \Yii::t('widgets', 'Widget Manager');

/*
 * @var yii\web\View $this
 */
?>
<?php Box::begin(['title' => \Yii::t('widgets', 'General')]) ?>
<?= Html::a('Online documentation', 'https://github.com/dmstr/yii2-widgets2-module', ['class' => 'btn btn-info', 'target' => '_blank']) ?>
<?php Box::end() ?>

<?php Box::begin(['title' => \Yii::t('widgets', 'Crud')]) ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-1">
            <?= SmallBox::widget(
                [
                    'head'        => FA::i('plus'),
                    'footer'      => \Yii::t('widgets', 'Add'),
                    'footer_link' => ['crud/widget/create'],
                ]
            ) ?>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-3">
            <?= SmallBox::widget(
                [
                    'head'        => WidgetContent::find()->count(),
                    'footer'      => 'Widget Contents',
                    'footer_link' => ['crud/widget'],
                ]
            ) ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-1">
            <?= SmallBox::widget(
                [
                    'head'        => FA::i('plus'),
                    'type'        => SmallBox::TYPE_PURPLE,
                    'footer'      => \Yii::t('widgets', 'Add'),
                    'footer_link' => ['crud/widget-template/create'],
                ]
            ) ?>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-3">
            <?= SmallBox::widget(
                [
                    'head'        => WidgetTemplate::find()->count(),
                    'type'        => SmallBox::TYPE_PURPLE,
                    'footer'      => 'Widget Templates',
                    'footer_link' => ['crud/widget-template'],
                ]
            ) ?>
        </div>
        <?php if (\Yii::$app->user->can(Module::COPY_ACCESS_PERMISSION)) : ?>
            <div class="col-xs-12 col-md-3 col-md-offset-1">
                <?= SmallBox::widget(
                    [
                        'head'        => FA::i('copy'),
                        'type'        => SmallBox::TYPE_AQUA,
                        'footer'      => 'Widget Copy',
                        'footer_link' => ['/widgets/copy'],
                    ]
                ) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php Box::end() ?>

<?php Box::begin(['title' => \Yii::t('widgets', 'Playground')]) ?>
<?= Html::a('Test page index', ['test/index'], ['class' => 'btn btn-default']) ?>
<?= Html::a(
    'Test page-1 (with parameter)',
    ['test/with-param', 'pageId' => 'page-1'],
    ['class' => 'btn btn-default']
) ?>
<?= Html::a(
    'Test page-2 (with parameter)',
    ['test/with-param', 'pageId' => 'page-2'],
    ['class' => 'btn btn-default']
) ?>
<?php Box::end(); ?>

<?php Box::begin(['title' => \Yii::t('widgets', 'Settings')]) ?>
<p>
    <?= \Yii::t('widgets', 'Section')?>: <code>widgets</code><br>
    <?= \Yii::t('widgets', 'Key')?>: <code>availablePhpClasses</code><br>
    <?= \Yii::t('widgets', 'Type')?>: <code>JSON</code>
</p>
<p><?= \Yii::t('widgets', 'Example')?>: <code>{"hrzg\\widget\\widgets\\TwigTemplate": "Twig layout"}</code></p>
<?= Html::a('Open settings', ['/settings'], ['class' => 'btn btn-default']) ?>
<?php Box::end();
