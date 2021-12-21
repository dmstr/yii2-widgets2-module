<?php
/**
 * @var \yii\web\View $this
 * @var \hrzg\widget\models\crud\WidgetContentTranslation $model
 */

use devgroup\jsoneditor\Jsoneditor;
use Highlight\Highlighter;
use hrzg\widget\Module;
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
    <div class='pull-right'>
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
        <small><?= $model->id ?></small>
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
                'attribute' => 'widget_content_id',
                'format' => 'raw',
                'value' => (
                \Yii::$app->user->can(Module::CONTENT_ACCESS_PERMISSION))
                    ?
                    Html::a(
                        FA::icon(FA::_EDIT),
                        ['crud/widget/update', 'id' => $model->widget_content_id],
                        ['class' => 'btn btn-primary btn-sm'])
                    .' '.
                    Html::a(
                        $model->getWidgetContent()->one()->id,
                        ['crud/widget/view', 'id' => $model->widget_content_id])
                    :
                    $model->getWidgetContent()->one()->id,
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
            'access_owner',
            'access_domain',
            'access_read',
            'access_update',
            'access_delete',
            'created_at',
            'updated_at',
        ],
    ]); ?>

    <?= $this->blocks['crud-navigation'] ?>

    <?php Box::end() ?>
</div>
