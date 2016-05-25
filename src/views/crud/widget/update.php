<?php

namespace _;

/**
 * /app/src/../runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;
use Yii;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetContent $model
 */
$this->title = $model->getAliasModel().$model->id.', '.Yii::t('app', 'Edit');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud widget-update">

    <?php \insolita\wgadminlte\Box::begin() ?>
    
    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name_id ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-eye-open"></span> '.Yii::t('app', 'View'),
            ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php \insolita\wgadminlte\Box::end() ?>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>


</div>
