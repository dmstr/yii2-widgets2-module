<?php

use yii\db\Migration;

class m170222_115710_add_unique_key_widget_content_table extends Migration
{
    public function up()
    {
        // add unique name ids if name_id is null, unsure lowercase domain_id`s
        $query = new \yii\db\Query();
        $query->select(['id', 'access_domain']);
        $query->from('{{%hrzg_widget_content}}');

        foreach ($query->all() as $widgetContent) {
            $this->update(
                '{{%hrzg_widget_content}}',
                ['name_id' => uniqid(), 'access_domain' => mb_strtolower($widgetContent['access_domain'])],
                ['id' => $widgetContent['id'], 'name_id' => '']
            );
        }

        // now rename `name_id` to `domain_id`
        $this->renameColumn('{{%hrzg_widget_content}}', 'name_id', 'domain_id');

        // add missing unique keys
        $this->createIndex('domain_id_access_domain_unique', '{{%hrzg_widget_content}}', ['domain_id', 'access_domain'], true);
    }

    public function down()
    {
        return false;
    }
}
