<?php

use yii\db\Migration;

class m160401_153933_add_widget_editor_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%hrzg_widget_template}}', [
            'id' => 'pk',
            'name' => 'VARCHAR(255) NOT NULL',
            'json_schema' => 'TEXT NOT NULL',
            'editor_settings' => 'TEXT NULL',
            'form' => 'LONGTEXT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%hrzg_widget_template}}');
    }

}
