<?php

namespace _;

use insolita\wgadminlte\Box;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Inflector;
use yii\widgets\ListView;

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

<?php Box::begin(['title' => 'Create new widgets']) ?>
<div class="row">
        <?= ListView::widget([
            'dataProvider' => $templatesDataProvider,
            'itemView' => '_create-widget'
        ]) ?>
</div>
<?php Box::end() ?>

