<?php

use yii\db\Migration;

class m160401_152059_add_widget_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%hrzg_widget_template}}', [
            'id' => 'pk',
            'name' => 'VARCHAR(255) NOT NULL',
            'json_schema' => 'TEXT NOT NULL',
            'twig_template' => 'TEXT NULL'
        ]);

        $this->createTable('{{%hrzg_widget}}', [
            'id' => 'pk',
            'status' => 'VARCHAR(32) NOT NULL',
            'widget_template_id' => 'INT(11) NOT NULL',
            'default_properties_json' => 'TEXT NULL DEFAULT NULL',
            'name_id' => 'VARCHAR(64) NULL DEFAULT NULL',
            'container_id' => 'VARCHAR(128) NOT NULL',
            'rank' => 'VARCHAR(11) NOT NULL DEFAULT "0"',
            'route' => 'VARCHAR(128) NOT NULL',
            'request_param' => 'VARCHAR(255) NULL',
            'access_owner' => 'VARCHAR(11) NULL DEFAULT NULL',
            'access_domain' => 'VARCHAR(8) NULL DEFAULT NULL',
            'access_read' => 'VARCHAR(255) NULL DEFAULT NULL',
            'access_update' => 'VARCHAR(255) NULL DEFAULT NULL',
            'access_delete' => 'VARCHAR(255) NULL DEFAULT NULL',
            'created_at' => 'DATETIME NULL DEFAULT NULL',
            'updated_at' => 'DATETIME NULL DEFAULT NULL',
        ]);

        $this->addForeignKey(
            'fk_widget_widget_template_id',
            '{{%hrzg_widget}}',
            'widget_template_id',
            '{{%hrzg_widget_template}}',
            'id',
            'RESTRICT',
            'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%hrzg_widget}}');
        $this->dropTable('{{%hrzg_widget_template}}');
    }

}
