<?php

use yii\db\Migration;

class m170320_095050_update_template_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hrzg_widget_template}}', 'created_at', 'DATETIME NULL');
        $this->addColumn('{{%hrzg_widget_template}}', 'updated_at', 'DATETIME NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%hrzg_widget_template}}', 'created_at');
        $this->dropColumn('{{%hrzg_widget_template}}', 'updated_at');
    }
}
