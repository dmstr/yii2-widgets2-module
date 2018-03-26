<?php

use yii\db\Migration;

/**
 * Class m180313_072331_add_translation_table
 */
class m180326_092332_update_foreign_key_from_translation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->dropForeignKey(
            'fk_widget_widget_translation_id',
            '{{%hrzg_widget_content_translation}}');

        $this->addForeignKey(
            'fk_widget_widget_translation_id',
            '{{%hrzg_widget_content_translation}}',
            'widget_content_id',
            '{{%hrzg_widget_content}}',
            'id');


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_widget_widget_translation_id',
            '{{%hrzg_widget_content_translation}}');

        $this->addForeignKey(
            'fk_widget_widget_translation_id',
            '{{%hrzg_widget_content_translation}}',
            'widget_content_id',
            '{{%hrzg_widget_content}}',
            'id','CASCADE','CASCADE');
    }

}
