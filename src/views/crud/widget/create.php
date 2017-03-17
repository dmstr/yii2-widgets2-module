<?php
use insolita\wgadminlte\Box;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 */
$this->title = Yii::t('widgets', 'Create');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud widget-create">

    <?php Box::begin() ?>
    <h1>
        <?php echo $model->getAliasModel() ?>
        <small><?php echo $model->name_id ?></small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a(\Yii::t('widgets', 'Cancel'), Url::previous(),['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php echo $this->render('_form', ['model' => $model]); ?>
    <?php Box::end() ?>
</div>
