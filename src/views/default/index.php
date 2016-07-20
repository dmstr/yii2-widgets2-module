<?php

namespace _;

use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\crud\WidgetContent;
use insolita\wgadminlte\SmallBox;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 */
?>

<div class="row">
    <div class="col-sm-6">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => WidgetContent::find()->count(),
                'footer' => 'Widget Contents',
                'footer_link' => ['crud/widget'],
            ]
        ) ?>
    </div>
    <div class="col-sm-4">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => WidgetTemplate::find()->count(),
                'type' => SmallBox::TYPE_PURPLE,
                'footer' => 'Templates',
                'footer_link' => ['crud/widget-template'],
            ]
        ) ?>
    </div>
    <div class="col-sm-2">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => '+',
                'type' => SmallBox::TYPE_GREEN,
                'footer' => 'New template',
                'footer_link' => ['crud/widget-template/create'],
            ]
        ) ?>
    </div>
</div>

<?php \insolita\wgadminlte\Box::begin() ?>
<p>
    <code>widgets</code>
    <code>availablePhpClasses</code>
    JSON
</p>
<h4>Example</h4>
<p>
    <code>{"hrzg\\widget\\widgets\\TwigTemplate": "Twig layout"}</code></p>

<?= Html::a('Open settings', ['/settings'], ['class' => 'btn btn-default']) ?>

<?= Html::a('Online documentation', 'https://git.hrzg.de/hrzg/yii2-widgets2-module', ['class' => 'btn btn-info']) ?>

<?php \insolita\wgadminlte\Box::end() ?>


<?php \insolita\wgadminlte\Box::begin() ?>

<?= Html::a('Test page index', ['test/index'], ['class' => 'btn btn-default']) ?>

<?= Html::a('Test page-1 (with parameter)', ['test/with-param', 'pageId' => 'page-1'], ['class' => 'btn btn-default']) ?>

<?= Html::a('Test page-2 (with parameter)', ['test/with-param', 'pageId' => 'page-2'], ['class' => 'btn btn-default']) ?>

<?php \insolita\wgadminlte\Box::end() ?>
