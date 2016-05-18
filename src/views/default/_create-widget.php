<?php

namespace _;

use yii\helpers\Html;

?>

<div class="col-sm-3">
    <?= Html::a(
        $model->name,
        ['/widgets/crud/widget/create', 'widget_template_id' => $model->id],
        ['class' => 'btn btn-default btn-block']) ?>
</div>
