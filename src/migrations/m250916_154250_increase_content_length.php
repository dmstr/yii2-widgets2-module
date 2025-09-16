<?php

use yii\db\Migration;

class m250916_154250_increase_content_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('{{%hrzg_widget_template}}', 'json_schema', $this->longText()->notNull());
        $this->alterColumn('{{%hrzg_widget_template}}', 'twig_template', $this->longText()->null());

        $this->alterColumn('{{%hrzg_widget_content_translation}}', 'default_properties_json', $this->longText()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->alterColumn('{{%hrzg_widget_content_translation}}', 'default_properties_json', $this->text()->null());

        $this->alterColumn('{{%hrzg_widget_template}}', 'twig_template', $this->text()->null());
        $this->alterColumn('{{%hrzg_widget_template}}', 'json_schema', $this->text()->notNull());
    }

    protected function longText()
    {
        switch ($this->getDb()->getDriverName()) {
            case 'mysql':
                // MySQL supports LONGTEXT (4GB max)
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT');
            case 'pgsql':
                // PostgreSQL TEXT type already supports unlimited length
                return $this->text();
            case 'mssql':
            case 'dblib':
                // SQL Server uses VARCHAR(MAX)
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('VARCHAR(MAX)');
            default:
                // Fallback to regular text
                return $this->text();
        }
    }
}
