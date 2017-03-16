<?php
/**
 * /app/src/../runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22.
 */
use hrzg\widget\models\crud\WidgetTemplate;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var hrzg\widget\models\crud\search\WidgetContent $searchModel
 */
$this->title = $searchModel->getAliasModel(true);
$this->params['breadcrumbs'][] = $this->title;


/**
 * create action column template depending acces rights
 */
$actionColumnTemplates = [];

if (\Yii::$app->user->can('widgets_crud_widget_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
}

if (\Yii::$app->user->can('widgets_crud_widget_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
}

if (\Yii::$app->user->can('widgets_crud_widget_delete', ['route' => true])) {
    $actionColumnTemplates[] = '{delete}';
}
if (isset($actionColumnTemplates)) {
    $actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
    Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>

<div class="giiant-crud widget-index">

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
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => $actionColumnTemplateString,
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            /** @var hrzg\widget\models\crud\WidgetContent $model */
                            if (!$model->hasPermission('access_update')) {
                                return false;
                            } else {
                                $title = Yii::t('yii', 'Update');
                                $options = [
                                    'title'      => $title,
                                    'aria-label' => $title,
                                    'data-pjax'  => '0',
                                ];
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                                return Html::a($icon, $url, $options);
                            }
                        },
                        'delete' => function ($url, $model, $key) {
                            /** @var hrzg\widget\models\crud\WidgetContent $model */
                            if (!$model->hasPermission('access_delete')) {
                                return false;
                            } else {
                                $title = Yii::t('yii', 'Delete');
                                $options = [
                                    'title'      => $title,
                                    'aria-label' => $title,
                                    'data-pjax'  => '0',
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post',
                                ];
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                                return Html::a($icon, $url, $options);
                            }
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id.'/'.$action : $action;

                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap'],
                ],
                'id',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model) {
                        return $model::optsStatus()[$model->status];
                    }
                ],
                [
                    'attribute' => 'template.name',
                    'header' => 'Template',
                    'contentOptions' => ['nowrap' => 'nowrap'],
                    'filter'=> ArrayHelper::map(WidgetTemplate::find()->asArray()->all(), 'name', 'name'),
                ],
                'route',
                'request_param',
                'container_id',
                /*'domain_id',*/
                /*'rank',*/
                [
                    'attribute' => 'default_properties_json',
                    'format' => 'raw',
                    'value' => function ($model) { return \devgroup\jsoneditor\Jsoneditor::widget([
                        'name' => '_display',
                        'value' => $model->default_properties_json,
                        'options' => [
                            'style' => [
                                'width' => '600px',
                                ]
                        ],
                        'editorOptions' => [
                            'mode' => 'view',

                            'modes' => [
                                'view',
                                'code'
                            ]
                        ]
                    ]);},
                ],
                'access_domain',
                /*'access_owner',*/
                'access_read',
                'access_update',
                'access_delete',

            ],
        ]); ?>
    </div>


    <?php \yii\widgets\Pjax::end() ?>
    <?php \insolita\wgadminlte\Box::end() ?>

</div>

