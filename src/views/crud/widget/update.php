<?php

namespace _;

/*
 * /app/src/../runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */

use Yii;
use yii\helpers\Html;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetContent $model
 */
$this->title = $model->getAliasModel().$model->id.', '.Yii::t('widgets', 'Edit');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('widgets', 'Edit');
?>
<div class="giiant-crud widget-update">

    <?php \insolita\wgadminlte\Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name_id ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> '.Yii::t('widgets', 'View'),
            ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

    <?php \insolita\wgadminlte\Box::end() ?>

</div>
