<?php

use yii\db\Migration;

class m170320_110929_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_default_index');
            $permission->description = 'Widgets Manager';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170320_110929_auth_items cannot be reverted.\n";

        return false;
    }
}
