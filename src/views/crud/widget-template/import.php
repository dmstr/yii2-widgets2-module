<?php
/**
 * --- FROM CONTEXT ---
 *
 * @var yii\web\View $this
 * @var \hrzg\widget\models\WidgetTemplateImport $model
 */

use insolita\wgadminlte\Box;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Box::begin()
?>
<h1>
    <?php echo Yii::t('widgets', 'WidgetTemplates'); ?>
    <small><?php echo $this->title; ?></small>
</h1>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'tarFiles[]')->fileInput(['multiple' => true, 'accept' => 'application/x-tar','class' => 'form-control']);
            ?>
            <div class="form-group">
                <?php echo Html::submitButton(Yii::t('widgets','Upload'),['class' => 'btn btn-primary']) ?>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
<?php
Box::end();
