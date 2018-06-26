<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 */

use devgroup\jsoneditor\Jsoneditor;
use dmstr\bootstrap\Tabs;
use Highlight\Highlighter;
use hrzg\widget\Module;
use insolita\wgadminlte\Box;
use rmrevin\yii\fontawesome\FA;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = $model->getAliasModel() . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('widgets', 'View');

// enable bootstrap tooltips
$this->registerJs(<<<JS
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
JS
);

?>

<?php $this->beginBlock('crud-navigation') ?>
<div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-right'>
        <?php #TODO: need to check correct request param, currently not stored in database ?>
        <?= Html::a('View in Frontend', ['/'.$model->route, 'pageId'=>$model->request_param], ['class'=>'btn btn-default']) ?>

        <?php if (\Yii::$app->user->can('widgets_crud_widget_create', ['route' => true])) : ?>
            <?php if (\Yii::$app->user->can('widgets_crud_widget_copy', ['route' => true])) : ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-copy"></span> ' . \Yii::t('widgets', 'Copy'),
                    ['copy', 'id' => $model->id],
                    ['class' => 'btn btn-primary']
                ) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($model->hasPermission('access_delete') && \Yii::$app->user->can('widgets_crud_widget_delete', ['route' => true])) : ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-trash"></span> ' . \Yii::t('widgets', 'Delete'),
                ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this item?') . '',
                    'data-method' => 'post',
                ]
            ); ?>
        <?php endif; ?>

    </div>
    <div class="pull-left">
        <?php if ($model->hasPermission('access_update') && \Yii::$app->user->can('widgets_crud_widget_update', ['route' => true])) : ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-pencil"></span> ' . \Yii::t('widgets', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        <?php endif; ?>
    </div>
</div>
<?php $this->endBlock() ?>

<div class="giiant-crud widget-view">

    <?php Box::begin() ?>
    <h1>
        <?= $model->getAliasModel() ?>
        <small><?= $model->name_id ?></small>
    </h1>

    <?= $this->blocks['crud-navigation'] ?>

    <?php
    $hl = new Highlighter();
    $r = $hl->highlight("json", $model->default_properties_json, JSON_PRETTY_PRINT);
    ?>

    <?php $this->beginBlock('widget-properties') ?>
    <?= DetailView::widget([
        'options' => ['class' => 'table table-striped detail-view'],
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => (isset($model->status) ? Html::encode($model::optsStatus()[$model->status]) : 'n/a')
                    . ($model->getBehavior('translation_meta')->isFallbackTranslation ?
                        ' <span class="label label-warning" title="' . \Yii::t('widgets', 'Uses the same value as the fallback language. Edit and save to override the default.') . '" data-toggle="tooltip" data-placement="top">fallback</span>'
                        : ''),
            ],
            [
                'attribute' => 'widget_template_id',
                'format' => 'raw',
                'value' => (
                \Yii::$app->user->can(Module::TEMPLATE_ACCESS_PERMISSION))
                    ?
                    Html::a(
                        FA::icon(FA::_EDIT),
                        ['crud/widget-template/update', 'id' => $model->widget_template_id],
                        ['class' => 'btn btn-primary btn-sm'])
                    . ' ' .
                    Html::a(
                        $model->template->name,
                        ['crud/widget-template/view', 'id' => $model->widget_template_id])
                    :
                    $model->template->name,
            ],
            [
                'attribute' => 'default_properties_json',
                'format' => 'raw',
                'value' => Jsoneditor::widget([
                    'name' => '_display',
                    'value' => $model->default_properties_json,
                    'editorOptions' => [
                        'mode' => 'view',
                        'modes' => [
                            'view',
                            'code'
                        ]
                    ]
                ]),
            ],
            'domain_id',
            'name_id',
            'container_id',
            'rank',
            'route',
            'request_param',
            'access_owner',
            'access_domain',
            'access_read',
            'access_update',
            'access_delete',
            'copied_from',
            'created_at',
            'updated_at',
        ],
    ]); ?>
    <?php $this->endBlock() ?>

    <?php $this->beginBlock('translations') ?>
    <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' => new ActiveDataProvider(['query' => $model->getTranslations()]),
        'pager' => [
            'class' => yii\widgets\LinkPager::class,
            'firstPageLabel' => Yii::t('widgets', 'First'),
            'lastPageLabel' => Yii::t('widgets', 'Last'),
        ],
        'tableOptions' => ['class' => 'table table-striped table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                    $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '-translation/' . $action : $action;

                    return Url::toRoute($params);
                },
                'contentOptions' => ['nowrap' => 'nowrap'],
            ],
            'id',
            [
                'attribute' => 'default_properties_json',
                'format' => 'raw',
                'value' => function ($model) {
                    return \devgroup\jsoneditor\Jsoneditor::widget([
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
                    ]);
                },
            ],
            'access_domain',
            'access_read',
            'access_update',
            'access_delete',

        ],
    ]) ?>
    <?php $this->endBlock() ?>

    <?php
    echo Tabs::widget([
        'encodeLabels' => false,
        'items' => [
            [
                'label' => '#' . $model->id,
                'content' => $this->blocks['widget-properties'],
                'active' => true
            ],
            [
                'label' => Yii::t('widgets', 'Translations') . ' <span class="badge badge-default">' . count($model->getTranslations()->asArray()->all()) . '</span>',
                'content' => $this->blocks['translations'],
                'active' => false
            ]
        ]
    ])
    ?>

    <?= $this->blocks['crud-navigation'] ?>

    <?php Box::end() ?>
</div>
