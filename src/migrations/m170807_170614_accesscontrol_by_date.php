<?php

use yii\db\Migration;

class m170807_170614_accesscontrol_by_date extends Migration
{
    public function up()
    {
        // TODO: date access control fields..
        $this->addColumn('{{%hrzg_widget_content}}', 'publish_at', $this->dateTime()->null()->after('access_delete'));
        $this->addColumn('{{%hrzg_widget_content}}', 'expire_at',  $this->dateTime()->null()->after('publish_at'));
    }

    public function down()
    {
        $this->dropColumn('{{%hrzg_widget_content}}', 'publish_at');
        $this->dropColumn('{{%hrzg_widget_content}}', 'expire_at');
    }
}
