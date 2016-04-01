<?php

namespace hrzg\widget\crud\models\query;

/**
 * This is the ActiveQuery class for [[\hrzg\widget\crud\models\WidgetTemplate]].
 *
 * @see \hrzg\widget\crud\models\WidgetTemplate
 */
class WidgetTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \hrzg\widget\crud\models\WidgetTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \hrzg\widget\crud\models\WidgetTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
