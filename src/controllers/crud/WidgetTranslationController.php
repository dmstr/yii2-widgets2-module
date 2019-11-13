<?php

namespace hrzg\widget\controllers\crud;

use hrzg\widget\assets\WidgetAsset;
use hrzg\widget\models\crud\WidgetContent;
use yii\helpers\Url;

/**
 * Class WidgetTranslationController
 * @package hrzg\widget\controllers\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class WidgetTranslationController extends \hrzg\widget\controllers\crud\base\WidgetTranslationController
{

    public function beforeAction($action)
    {
        WidgetAsset::register($this->view);
        return parent::beforeAction($action);
    }

}
