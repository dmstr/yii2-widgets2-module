<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var hrzg\widget\models\crud\WidgetPage $model
*/

$this->title = Yii::t('widgets', 'Widget Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('widgets', 'Widget Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud widget-page-create">

    <h1>
        <?= Yii::t('widgets', 'Widget Page') ?>
        <small>
                        <?= Html::encode($model->id) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            Yii::t('widgets', 'Cancel'),
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
