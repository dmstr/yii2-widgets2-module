<?php

use yii\db\Migration;

/**
 * Class m180313_072331_add_translation_table
 */
class m180313_072331_add_translation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%hrzg_widget_content_translation}}', [
            'id' => $this->primaryKey(),
            'widget_content_id' => $this->integer()->notNull(),
            'language' => $this->char(7)->notNull(),
            'default_properties_json' => 'TEXT NULL DEFAULT NULL',
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey(
            'fk_widget_widget_translation_id',
            '{{%hrzg_widget_content_translation}}',
            'widget_content_id',
            '{{%hrzg_widget_content}}',
            'id',
            'RESTRICT',
            'RESTRICT');


        // select all contents to insert them into the translation table
        $query = new \yii\db\Query();
        $contents = $query->select([
            'id',
            'access_domain',
            'default_properties_json'
        ])->from('{{%hrzg_widget_content}}')->all();

        foreach ($contents as $content) {
            $this->insert('{{%hrzg_widget_content_translation}}', [
                'widget_content_id' => $content['id'],
                'language' => $content['access_domain'],
                'default_properties_json' => $content['default_properties_json']
            ]);
        }

        $this->dropColumn('{{%hrzg_widget_content}}', 'default_properties_json');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('{{%hrzg_widget_content}}', 'default_properties_json',
            'TEXT NULL DEFAULT NULL AFTER widget_template_id');

        // select all content translations to insert them back into the content table
        $query = new \yii\db\Query();
        $contents = $query->select([
            'widget_content_id',
            'language',
            'default_properties_json'
        ])->from('{{%hrzg_widget_content_translation}}')->all();

        foreach ($contents as $content) {
            $this->update('{{%hrzg_widget_content}}', [
                'access_domain' => $content['language'],
                'default_properties_json' => $content['default_properties_json']
            ],'id = ' . $content['widget_content_id']);
        }

        $this->dropForeignKey('fk_widget_widget_translation_id', '{{%hrzg_widget_content_translation}}');
        $this->dropTable('{{%hrzg_widget_content_translation}}');
    }

}
