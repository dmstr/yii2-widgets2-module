<?php

namespace _;

use insolita\wgadminlte\Box;
use yii\widgets\ListView;

/*
 * @var yii\web\View $this
 */
?>

<div class="row">
    <div class="col-sm-6">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => 'Co',
                'footer' => 'Widget Contents',
                'footer_link' => ['crud/widget']
            ]
        ) ?>
    </div>
    <div class="col-sm-6">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => 'Wi',
                'footer' => 'Templates',
                'footer_link' => ['crud/widget-template']
            ]
        ) ?>
    </div>
</div>
