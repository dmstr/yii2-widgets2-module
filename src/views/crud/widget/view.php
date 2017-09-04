<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContent $model
 */
use devgroup\jsoneditor\Jsoneditor;
use dmstr\bootstrap\Tabs;
use Highlight\Highlighter;
use hrzg\widget\Module;
use hrzg\widget\widgets\CellPreview;
use insolita\wgadminlte\Box;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->getAliasModel().$model->id;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('widgets', 'View');
?>

<?php $this->beginBlock('crud-navigation') ?>
<div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
        <?php if (\Yii::$app->user->can('widgets_crud_widget_create', ['route' => true])) :?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . \Yii::t('widgets', 'New'),
                ['create'],
                ['class' => 'btn btn-success']
            ) ?>

            <?php if (\Yii::$app->user->can('widgets_crud_widget_copy', ['route' => true])) : ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-copy"></span> ' . \Yii::t('widgets', 'Copy'),
                    ['copy', 'id' => $model->id],
                    ['class' => 'btn btn-default']
                ) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($model->hasPermission('access_update') && \Yii::$app->user->can('widgets_crud_widget_update', ['route' => true])) : ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-pencil"></span> ' . \Yii::t('widgets', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']
            ) ?>
        <?php endif; ?>

        <?php if ($model->hasPermission('access_delete') && \Yii::$app->user->can('widgets_crud_widget_delete', ['route' => true])) : ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-trash"></span> ' . \Yii::t('widgets', 'Delete'),
                ['delete', 'id' => $model->id],
                [
                    'class'        => 'btn btn-danger',
                    'data-confirm' => '' . \Yii::t('widgets', 'Are you sure to delete this item?') . '',
                    'data-method'  => 'post',
                ]
            ); ?>
        <?php endif; ?>

    </div>
    <div class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> '.\Yii::t('widgets', 'Full list'), ['index'],
            ['class' => 'btn btn-default']) ?>
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

    <?= DetailView::widget([
        'options' => ['class' => 'table table-striped detail-view'],
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model::optsStatus()[$model->status]
            ],
            [
                'attribute' => 'widget_template_id',
                'format' => 'raw',
                'value' => (\Yii::$app->user->can(Module::TEMPLATE_ACCESS_PERMISSION))
                    ? Html::a($model->template->name, ['crud/widget-template/view', 'id'=>$model->widget_template_id])
                    .' '.Html::a(FA::icon(FA::_EDIT), ['crud/widget-template/update', 'id'=>$model->widget_template_id])
                    : $model->template->name
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

    <?= $this->blocks['crud-navigation'] ?>

    <?php Box::end() ?>
</div>
