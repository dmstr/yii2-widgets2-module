<?php

use yii\db\Migration;

class m170317_124118_update_widget_content_add_copied_from extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hrzg_widget_content}}', 'copied_from', $this->integer()->null()->after('access_delete'));
    }

    public function down()
    {
        $this->dropColumn('{{%hrzg_widget_content}}', 'copied_from');
    }
}
