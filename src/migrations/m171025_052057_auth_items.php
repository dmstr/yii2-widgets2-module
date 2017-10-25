<?php

use yii\db\Migration;

class m171025_052057_auth_items extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets-cell-edit');
            $permission->description = 'Widgets frontend buttons';
            $auth->add($permission);
        }
    }

    public function safeDown()
    {
        echo "m171025_052057_auth_items cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171025_052057_auth_items cannot be reverted.\n";

        return false;
    }
    */
}
