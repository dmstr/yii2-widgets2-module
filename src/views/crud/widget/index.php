<?php
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var hrzg\widget\models\crud\search\WidgetContent $searchModel
 */
use hrzg\widget\models\crud\WidgetTemplate;
use insolita\wgadminlte\Box;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = $searchModel->getAliasModel(true);
$this->params['breadcrumbs'][] = $this->title;

/**
 * create action column template depending acces rights
 */
$actionColumnTemplates = [];

if (\Yii::$app->user->can('widgets_crud_widget_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
}

if (\Yii::$app->user->can('widgets_crud_widget_copy', ['route' => true])) {
    $actionColumnTemplates[] = '{copy}';
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
    $actionColumnTemplateString = "{view} {copy} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>

<div class="giiant-crud widget-index">

    <?php Box::begin() ?>

    <?php Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
    ]) ?>

    <h1>
        <?= $searchModel->getAliasModel(true) ?>
        <small>
            List
        </small>
    </h1>

    <?php if (\Yii::$app->user->can('widgets_crud_widget_create', ['route' => true])) :?>
        <div class="clearfix crud-navigation">
            <div class="pull-left">
                    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('widgets', 'New'), ['create'],
                        ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <hr />
    <?php endif; ?>

    <div class="table-responsive">
        <?= GridView::widget([
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
                            $title = Yii::t('widgets', 'Update');
                            $disabled = null;
                            $disabledClass = null;
                            if ( ! ($model->hasPermission('access_update') && \Yii::$app->user->can('widgets_crud_widget_update', ['route' => true]))) {
                                $title = Yii::t('widgets', 'Update denied');
                                $disabled = 'disabled';
                                $disabledClass = 'btn-default';
                                $url = null;
                            }
                            $options = [
                                'class'      => $disabledClass,
                                'title'      => $title,
                                'aria-label' => $title,
                                'disabled'   => $disabled,
                                'data-pjax'  => '0',
                            ];
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                            return Html::a($icon, $url, $options);
                        },
                        'copy' => function ($url, $model, $key) {
                            /** @var hrzg\widget\models\crud\WidgetContent $model */
                            $title = Yii::t('widgets', 'Copy');
                            $disabled = null;
                            $disabledClass = null;
                            if ( ! \Yii::$app->user->can('widgets_crud_widget_copy', ['route' => true])) {
                                $title = Yii::t('widgets', 'Copy denied');
                                $disabled = 'disabled';
                                $disabledClass = 'btn-default';
                                $url = null;
                            }
                            $options = [
                                'class'      => $disabledClass,
                                'title'      => $title,
                                'aria-label' => $title,
                                'disabled'   => $disabled,
                                'data-pjax'  => '0',
                            ];
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-copy"]);
                            return Html::a($icon, $url, $options);
                        },
                        'delete' => function ($url, $model, $key) {
                            /** @var hrzg\widget\models\crud\WidgetContent $model */
                            $title = Yii::t('widgets', 'Delete');
                            $disabled = null;
                            $disabledClass = null;
                            $dataConfirm = Yii::t('widgets', 'Are you sure you want to delete this item?');
                            $dataMethod = 'post';
                            if ( ! ($model->hasPermission('access_delete') && \Yii::$app->user->can('widgets_crud_widget_delete', ['route' => true]))) {
                                $title = Yii::t('widgets', 'Delete denied');
                                $disabled = 'disabled';
                                $disabledClass = 'btn-default';
                                $url = null;
                                $dataConfirm = null;
                                $dataMethod = null;
                            }
                            $options = [
                                'class'      => $disabledClass,
                                'title'        => $title,
                                'aria-label'   => $title,
                                'disabled'     => $disabled,
                                'data-pjax'    => '0',
                                'data-confirm' => $dataConfirm,
                                'data-method'  => $dataMethod,
                            ];
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                            return Html::a($icon, $url, $options);
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
                    'contentOptions' => ['nowrap' => 'nowrap'],
                    'filter'=> \hrzg\widget\models\crud\WidgetContent::optsStatus(),
                    'value' => function($model) {
                        return $model::optsStatus()[$model->status];
                    },
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'style' => 'width:80px;'
                    ]
                ],
                [
                    'attribute' => 'template.name',
                    'header' => 'Template',
                    'contentOptions' => ['nowrap' => 'nowrap'],
                    'filter'=> ArrayHelper::map(WidgetTemplate::find()->asArray()->all(), 'name', 'name'),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'style' => 'width:180px;'
                    ]
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
    <?php Pjax::end() ?>
    <?php Box::end() ?>

</div>

