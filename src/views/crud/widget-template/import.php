<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 */

use insolita\wgadminlte\Box;

Box::begin()
?>
<h1>
    <?php echo Yii::t('widgets', 'WidgetTemplates'); ?>
    <small><?php echo $this->title; ?></small>
</h1>
<?php
Box::end();
