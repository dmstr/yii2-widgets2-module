<?php
/**
 * /app/src/../runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22.
 */
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/*
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var hrzg\widget\models\crud\search\WidgetContent $searchModel
 */
$this->title = $searchModel->getAliasModel(true);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud widget-index">

    <?php //             echo $this->render('_search', ['model' =>$searchModel]);
    ?>
    <?php \insolita\wgadminlte\Box::begin() ?>

    <?php \yii\widgets\Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => ['pjax:success' => 'function(){alert("yo")}'],
    ]) ?>

    <h1>
        <?php echo $searchModel->getAliasModel(true) ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('widgets', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>


    </div>

    <hr>

    <div class="table-responsive">
        <?php echo GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => Yii::t('widgets', 'First'),
                'lastPageLabel' => Yii::t('widgets', 'Last'),
            ],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id.'/'.$action : $action;

                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap'],
                ],
                [
                    'attribute' => 'template.name',
                    'header' => 'Template',
                    'contentOptions' => ['nowrap' => 'nowrap'],
                ],
                'access_domain',
                'route',
                'request_param',
                'container_id',
                'name_id',
                'rank',
                'status',
                /*'access_owner',*/
                /*'access_read'*/
                /*'access_update'*/
                /*'access_delete'*/

            ],
        ]); ?>
    </div>


    <?php \yii\widgets\Pjax::end() ?>
    <?php \insolita\wgadminlte\Box::end() ?>

</div>

