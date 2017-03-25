<?php
use yii\db\Migration;

class m170316_160125_set_default_read_update_delete_access extends Migration
{
    public function up()
    {
        // set read access for previously unused columns
        $this->update('{{%hrzg_widget_content}}', ['access_read' => '*'], ['access_read' => null]);
        $this->update('{{%hrzg_widget_content}}', ['access_update' => '*'], ['access_update' => null]);
        $this->update('{{%hrzg_widget_content}}', ['access_delete' => '*'], ['access_delete' => null]);
        $this->update('{{%hrzg_widget_content}}', ['access_read' => '*'], ['access_read' => '']);
        $this->update('{{%hrzg_widget_content}}', ['access_update' => '*'], ['access_update' => '']);
        $this->update('{{%hrzg_widget_content}}', ['access_delete' => '*'], ['access_delete' => '']);
    }

    public function down()
    {
        $this->update('{{%hrzg_widget_content}}', ['access_read' => null]);
        $this->update('{{%hrzg_widget_content}}', ['access_update' => null]);
        $this->update('{{%hrzg_widget_content}}', ['access_delete' => null]);
    }
}
