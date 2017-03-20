<?php
use insolita\wgadminlte\Box;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 */
$this->title = $model->getAliasModel().$model->id.', '.Yii::t('widgets', 'Copy');
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('widgets', 'Copy');
?>
<div class="giiant-crud widget-copy">

    <?php Box::begin() ?>
    <h1>
        <?php echo $model->getAliasModel() ?>
        <small>
            <?php echo $model->name ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> '.Yii::t('widgets', 'Cancel'),
            ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php echo $this->render('_form', ['model' => $model]); ?>
    <?php Box::end() ?>
</div>
