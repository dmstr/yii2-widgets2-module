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
<?php Box::begin(['title' => \Yii::t('widgets', 'General')]) ?>
<div class="row">
        <?php if (\Yii::$app->user->can('widgets_crud_widget_create', ['route' => true])) : ?>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                                    <h3 style="white-space: normal;">' . \Yii::t('widgets', 'Widget Content') . '</h3>
                                    ' . \Yii::t('widgets', 'New') . '
                                    </div>',
                        'boxBg' => InfoBox::TYPE_AQUA,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget/create']);
                ?>
            </div>
        <?php endif; ?>
    <?php if (\Yii::$app->user->can('widgets_crud_widget_index', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-8 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                            <h3 style="white-space: normal;">' . \Yii::t('widgets', 'Widget Contents') . '</h3>
                            '.WidgetContent::find()->count().'</div>',
                        'boxBg' => InfoBox::TYPE_AQUA,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget/index']);
                ?>
            </div>
    <?php endif; ?>
    <?php if (\Yii::$app->user->can(Module::TEMPLATE_ACCESS_PERMISSION, ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                                    <h3 style="white-space: normal;">' . \Yii::t('widgets', 'Widget Template') . '</h3>
                                    ' . \Yii::t('widgets', 'New') . '
                                    </div>',
                        'boxBg' => InfoBox::TYPE_PURPLE,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget-template/create']);
                ?>
            </div>
        <div class="col-xs-12 col-sm-8 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                            <h3 style="white-space: normal;">' .  \Yii::t('widgets', 'Widget Templates') . '</h3>
                            '.WidgetTemplate::find()->count().'</div>',
                        'boxBg' => InfoBox::TYPE_PURPLE,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget-template/index']);
                ?>
            </div>
    <?php endif; ?>
    <?php if (\Yii::$app->user->can('widgets_crud_widget_translation_create', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                                    <h3 style="white-space: normal;">' . \Yii::t('widgets', 'Widget Translation') . '</h3>
                                    ' . \Yii::t('widgets', 'New') . '
                                    </div>',
                        'boxBg' => InfoBox::TYPE_NAVY,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-plus'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget-translation/create']);
                ?>
            </div>
    <?php endif; ?>
    <?php if (\Yii::$app->user->can('widgets_crud_widget_translation_index', ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-8 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center">
                            <h3 style="white-space: normal;">' . \Yii::t('widgets', 'Widget Translations') . '</h3>
                            '.\hrzg\widget\models\crud\WidgetContentTranslation ::find()->where(['language' => Yii::$app->language])->count().'</div>',
                        'boxBg' => InfoBox::TYPE_NAVY,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-list'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/crud/widget-translation/index']);
                ?>
            </div>
    <?php endif; ?>
    </div>
<?php Box::end() ?>

<?php if (\Yii::$app->user->can(Module::TEST_ACCESS_PERMISSION, ['route' => true])) : ?>
    <?php Box::begin(['title' => \Yii::t('widgets', 'Playground')]) ?>
    <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center"><h3 style="white-space: normal;">'
                            . \Yii::t('widgets', 'Test page 1') . '</h3>Index</div>',
                        'boxBg' => InfoBox::TYPE_LBLUE,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-cubes'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/test/index'], ['target' => '_blank']);
                ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center"><h3 style="white-space: normal;">'
                            . \Yii::t('widgets', 'Test page 2') . '</h3>params</div>',
                        'boxBg' => InfoBox::TYPE_LBLUE,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-cubes'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/test/with-param', 'pageId' => 'page-1'], ['target' => '_blank']);
                ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center"><h3 style="white-space: normal;">'
                            . \Yii::t('widgets', 'Test page 3') . '</h3>params</div>',
                        'boxBg' => InfoBox::TYPE_LBLUE,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-cubes'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/'.$moduleId.'/test/with-param', 'pageId' => 'page-2'], ['target' => '_blank']);
                ?>
            </div>
        </div>
    <?php Box::end(); ?>
<?php endif; ?>

<?php Box::begin(['title' => \Yii::t('widgets', 'Extras')]) ?>
<div class="row">
    <?php if (\Yii::$app->user->can(Module::COPY_ACCESS_PERMISSION, ['route' => true])) : ?>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <?php $infoBoxHtml = InfoBox::widget(
                [
                    'text'  => '<div class="text-center"><h3 style="white-space: normal;">'
                        . \Yii::t('widgets', 'Copy') . '</h3>Widgets</div>',
                    'boxBg' => InfoBox::TYPE_GREEN,
                    'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-copy'
                ]
            );
            echo Html::a($infoBoxHtml, ['/'.$moduleId.'/copy/language']);
            ?>
        </div>
    <?php endif; ?>
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
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $infoBoxHtml = InfoBox::widget(
                    [
                        'text'  => '<div class="text-center"><h3 style="white-space: normal;">'
                            . \Yii::t('widgets', 'Settings') . '</h3>Module</div>',
                        'boxBg' => InfoBox::TYPE_GRAY,
                        'icon'  => FA::$cssPrefix . ' ' . FA::$cssPrefix . '-cogs'
                    ]
                );
                echo Html::a($infoBoxHtml, ['/settings', 'SettingSearch' => ['section' => 'widgets']]);
                ?>
            </div>
            <?php
        }
    } ?>
</div>
<?php Box::end() ?>

<?php Box::begin(['title' => \Yii::t('widgets', 'Documentation')]) ?>
<?= Html::a(
    'Online documentation',
    'https://github.com/dmstr/yii2-widgets2-module',
    ['class' => 'btn btn-info', 'target' => '_blank']
) ?>
<?php Box::end() ?>
