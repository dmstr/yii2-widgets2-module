<?php

namespace hrzg\widget\models\crud\query;

/**
 * This is the ActiveQuery class for [[\hrzg\widget\models\crud\WidgetPageTranslation]].
 *
 * @see \hrzg\widget\models\crud\WidgetPageTranslation
 */
class WidgetPageTranslationQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \hrzg\widget\models\crud\WidgetPageTranslation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \hrzg\widget\models\crud\WidgetPageTranslation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
