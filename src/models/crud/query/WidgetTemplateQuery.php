<?php

namespace hrzg\widget\models\crud\query;

/**
 * This is the ActiveQuery class for [[\hrzg\widget\models\crud\WidgetTemplate]].
 *
 * @see \hrzg\widget\models\crud\WidgetTemplate
 */
class WidgetTemplateQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     *
     * @return \hrzg\widget\models\crud\WidgetTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     *
     * @return \hrzg\widget\models\crud\WidgetTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
