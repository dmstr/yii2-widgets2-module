<?php

namespace _;

use rmrevin\yii\fontawesome\FA;
use yii\helpers\Inflector;

/*
 * @var yii\web\View $this
 */
?>

<?= \insolita\wgadminlte\SmallBox::widget(
    [
        'head' => 'Co',
        'footer' => 'Widget Contents',
        'footer_link' => ['crud/widget']
    ]
) ?>

<?= \insolita\wgadminlte\SmallBox::widget(
    [
        'head' => 'Wi',
        'footer' => 'Templates',
        'footer_link' => ['crud/widget-template']
    ]
) ?>
