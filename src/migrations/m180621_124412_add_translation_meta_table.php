<?php

use yii\db\Migration;

/**
 * Class m180621_124412_add_translation_meta_table
 */
class m180621_124412_add_translation_meta_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%hrzg_widget_content_translation_meta}}', [
            'id' => $this->primaryKey(),
            'widget_content_id' => $this->integer()->notNull(),
            'language' => $this->char(7)->notNull(),
            'status' => 'VARCHAR(32) NOT NULL',
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey(
            'fk_widget_widget_translation_meta_id',
            '{{%hrzg_widget_content_translation_meta}}',
            'widget_content_id',
            '{{%hrzg_widget_content}}',
            'id',
            'CASCADE',
            'CASCADE');


        // select all contents to insert them into the translation table
        $query = new \yii\db\Query();
        $contents = $query->select([
            'id',
            'status',
        ])->from('{{%hrzg_widget_content}}')->all();

        foreach ($contents as $content) {
            $this->insert('{{%hrzg_widget_content_translation_meta}}', [
                'widget_content_id' => $content['id'],
                'language' => Yii::$app->language,
                'status' => $content['status'],
            ]);
        }

        $this->dropColumn('{{%hrzg_widget_content}}', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('{{%hrzg_widget_content}}', 'status',
            'VARCHAR(32) NOT NULL AFTER id');

        // select all content translations to insert them back into the content table
        $query = new \yii\db\Query();
        $contents = $query->select([
            'widget_content_id',
            'language',
            'status'
        ])->from('{{%hrzg_widget_content_translation_meta}}')->all();

        foreach ($contents as $content) {
            $this->update('{{%hrzg_widget_content}}', [
                'status' => $content['status']
            ],['id' => $content['widget_content_id']]);
        }

        $this->dropForeignKey('fk_widget_widget_translation_meta_id', '{{%hrzg_widget_content_translation_meta}}');
        $this->dropTable('{{%hrzg_widget_content_translation_meta}}');
    }

}
