<?php
/**
 * /app/src/../runtime/giiant/fccccf4deb34aed738291a9c38e87215.
 */
use yii\helpers\Html;

/*
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetContent $model
 */
$this->title = Yii::t('widgets', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud widget-create">

    <?php \insolita\wgadminlte\Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name_id ?>        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a(
                Yii::t('widgets', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']) ?>
        </div>
    </div>


    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

    <?php \insolita\wgadminlte\Box::end() ?>

</div>
