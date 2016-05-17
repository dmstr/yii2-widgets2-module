<?php

namespace _;

use insolita\wgadminlte\Box;
use yii\helpers\Html;

?>

<div class="col-sm-3">
    <?= Html::a(
        $model->name,
        ['/widgets/crud/widget/create', 'templateId' => $model->id],
        ['class' => 'btn btn-default btn-block']) ?>
</div>
