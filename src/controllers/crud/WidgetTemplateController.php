<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetTemplate;
use yii\helpers\Url;

/**
 * Class WidgetTemplateController
 * @package hrzg\widget\controllers\crud
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetTemplateController extends \hrzg\widget\controllers\crud\base\WidgetTemplateController
{
    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);
        return parent::beforeAction($action);
    }
}
