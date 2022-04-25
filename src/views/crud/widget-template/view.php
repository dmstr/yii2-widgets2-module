<?php
/**
 * @var yii\web\View $this
 * @var hrzg\widget\models\crud\WidgetTemplate $model
 */
use dmstr\bootstrap\Tabs;
use insolita\wgadminlte\Box;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title                   = $model->getAliasModel() . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('widgets', 'View');
?>

<?php $this->beginBlock('crud-navigation') ?>
<div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
        <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('widgets', 'New'),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('widgets', 'Edit'),
            ['update', 'id' => $model->id],
            ['class' => 'btn btn-info']
        ) ?>
        <?php
        if (Yii::$app->getUser()->can('widgets_crud_widget-template_export')) {
            echo Html::a(
                '<span class="glyphicon glyphicon-download"></span> ' . Yii::t('widgets', 'Export'),
                ['export', 'id' => $model->id],
                ['class' => 'btn btn-warning']
            );
        }
        ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('widgets', 'Delete'),
            ['delete', 'id' => $model->id],
            [
                'class'        => 'btn btn-danger',
                'data-confirm' => '' . Yii::t('widgets', 'Are you sure to delete this item?') . '',
                'data-method'  => 'post',
            ]
        ); ?>
    </div>
    <div class="pull-right">
        <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('widgets', 'Full list'),
            ['index'],
            ['class' => 'btn btn-default']
        ) ?>
    </div>
</div>
<?php $this->endBlock() ?>

<div class="giiant-crud widget-template-view">

    <?php Box::begin() ?>

    <h1>
        <?= $model->getAliasModel() ?>
        <small><?= $model->name ?></small>
    </h1>



    <?= $this->blocks['crud-navigation'] ?>
    <hr />
    <?php $this->beginBlock('hrzg\widget\models\crud\WidgetTemplate'); ?>
    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                'id',
                'name',
                'php_class',
                [
                    'attribute' => 'json_schema',
                    'format'    => 'html',
                    'value'     => '<pre class="pre-x-scrollable">' . Html::encode($model->json_schema) . '</pre>'
                ],
                [
                    'attribute' => 'twig_template',
                    'format'    => 'html',
                    'value'     => '<pre>' . Html::encode($model->twig_template) . '</pre>'
                ],
                'created_at',
                'updated_at',
            ],
        ]
    ); ?>
    <?= $this->blocks['crud-navigation'] ?>
    <?php $this->endBlock(); ?>
    <?= Tabs::widget(
        [
            'id'           => 'relation-tabs',
            'encodeLabels' => false,
            'items'        => [
                [
                    'label'   => '<b class=""># ' . $model->id . '</b>',
                    'content' => $this->blocks['hrzg\widget\models\crud\WidgetTemplate'],
                    'active'  => true,
                ],
            ],
        ]
    );
    ?>
    <?php Box::end() ?>
</div>
