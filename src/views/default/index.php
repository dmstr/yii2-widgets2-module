<?php
/**
 * @var \yii\web\View $this
 */

use hrzg\widget\models\crud\search\WidgetTemplate;
use hrzg\widget\models\crud\WidgetContent;
use hrzg\widget\Module;
use insolita\wgadminlte\Box;
use insolita\wgadminlte\InfoBox;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$this->title = \Yii::t('widgets', 'Widget Manager');
$moduleId = $this->context->module->id;
?>
<?php #Box::begin(['title' => \Yii::t('widgets', 'General')]) ?>
<div class="row">
    <?php if (\Yii::$app->user->can('widgets_crud_widget_create', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'New Widget Content'),
                    'boxBg' => InfoBox::TYPE_AQUA,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget/create']);
            ?>
        </div>
    <?php endif; ?>
    <?php if (\Yii::$app->user->can('widgets_crud_widget_index', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-8">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'Widget Contents'),
                    'number' => WidgetContent::find()->count(),
                    'boxBg' => InfoBox::TYPE_AQUA,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget/index']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can('widgets_crud_widget_translation_create', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'New Widget Translation'),
                    'boxBg' => InfoBox::TYPE_NAVY,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget-translation/create']);
            ?>
        </div>
    <?php endif; ?>
    <?php if (\Yii::$app->user->can('widgets_crud_widget_translation_index', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-8">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'Widget Translations'),
                    'number' => \hrzg\widget\models\crud\WidgetContentTranslation::find()->where(['language' => Yii::$app->language])->count(),
                    'boxBg' => InfoBox::TYPE_NAVY,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget-translation/index']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (\Yii::$app->user->can(Module::TEMPLATE_ACCESS_PERMISSION, ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'New Widget Template'),
                    'boxBg' => InfoBox::TYPE_PURPLE,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget-template/create']);
            ?>
        </div>
        <div class="col-xs-12 col-sm-8">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text' => \Yii::t('widgets', 'Widget Templates'),
                    'number' => \hrzg\widget\models\crud\WidgetTemplate::find()->count(),
                    'boxBg' => InfoBox::TYPE_PURPLE,
                    'icon' => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                ]
            );
            echo Html::a($infoBoxHtml, ['/' . $moduleId . '/crud/widget-template/index']);
            ?>
        </div>
    <?php endif; ?>

</div>
<?php #Box::end() ?>


<?php Box::begin(['title' => \Yii::t('widgets', 'Extras'), 'type' => Box::TYPE_PRIMARY]) ?>
<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3">
        <?php
        // check settings component and module existence
        if (\Yii::$app->has('settings') && \Yii::$app->hasModule('settings')) {

            // check module permissions
            $settingPermission = false;
            if (\Yii::$app->getModule('settings')->accessRoles === null) {
                $settingPermission = true;
            } else {
                foreach (\Yii::$app->getModule('settings')->accessRoles as $role) {
                    $settingPermission = \Yii::$app->user->can($role);
                }
            }
            if ($settingPermission) {
                ?>

                <?= Html::a(
                    FA::icon(FA::_COGS) . ' ' . \Yii::t('widgets', 'Settings'),
                    ['/settings', 'SettingSearch' => ['section' => 'widgets']],
                    ['class' => 'btn btn-app']);
                ?>
                <?php
            }
        } ?>
        <?php
        if (Yii::$app->getUser()->can('widgets_crud_widget-template_import')) {
            echo Html::a(FA::icon(FA::_UPLOAD) . ' ' . \Yii::t('widgets', 'Import'),
                ['crud/widget-template/import'],
                ['class' => 'btn btn-app']);
        }
        ?>
    </div>
</div>
<?php Box::end() ?>


<?php Box::begin(['title' => \Yii::t('widgets', 'Documentation'), 'type' => Box::TYPE_INFO]) ?>
<?= Html::a(
    'Online documentation',
    'https://github.com/dmstr/yii2-widgets2-module',
    ['class' => 'btn btn-info', 'target' => '_blank']
) ?>
<?php Box::end() ?>

<?php if (\Yii::$app->user->can(Module::TEST_ACCESS_PERMISSION, ['route' => true])) : ?>
    <?php Box::begin(['title' => \Yii::t('widgets', 'Playground'), 'type' => Box::TYPE_WARNING]) ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <?= Html::a('Test Index', ['/' . $moduleId . '/test/index'], ['target' => '_blank', 'class' => 'btn btn-default']);
            ?>

            <?= Html::a('Test With Param (page-1)', ['/' . $moduleId . '/test/with-param', 'pageId' => 'page-1'], ['target' => '_blank', 'class' => 'btn btn-default']);
            ?>

            <?= Html::a('Test With Param (page-2)', ['/' . $moduleId . '/test/with-param', 'pageId' => 'page-2'], ['target' => '_blank', 'class' => 'btn btn-default']);
            ?>
        </div>
    </div>
    <?php Box::end(); ?>
<?php endif; ?>


