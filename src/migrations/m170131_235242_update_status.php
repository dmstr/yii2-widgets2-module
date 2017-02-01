<?php

use yii\db\Migration;

class m170131_235242_update_status extends Migration
{
    public function up()
    {
        $this->update('{{%hrzg_widget_content}}', ['status'=>1]);
    }

    public function down()
    {
        echo "m170131_235242_update_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
