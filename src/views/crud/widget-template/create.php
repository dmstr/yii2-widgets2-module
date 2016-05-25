<?php
/**
 * /app/src/../runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetTemplate $model
 */
$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud widget-template-create">

    <?php \insolita\wgadminlte\Box::begin() ?>

    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a(
                Yii::t('app', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    
    <hr>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

    <?php \insolita\wgadminlte\Box::end() ?>

</div>
