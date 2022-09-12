<?php

use yii\db\Migration;

/**
 * Class m220523_110031_add_hide_template_flag
 */
class m220523_110031_add_hide_template_flag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hrzg_widget_template}}', 'hide_in_list_selection',
            $this->boolean()->notNull()->defaultValue(false)->after('twig_template'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hrzg_widget_template}}', 'hide_in_list_selection');
    }
}
