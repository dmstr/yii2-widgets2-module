<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var hrzg\widget\models\crud\WidgetPage $model
*/

$this->title = Yii::t('widgets', 'Widget Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('widgets', 'Widget Page'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('widgets', 'Edit');
?>
<div class="giiant-crud widget-page-update">

    <h1>
        <?= Yii::t('widgets', 'Widget Page') ?>
        <small>
                        <?= Html::encode($model->id) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('widgets', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
