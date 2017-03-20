<?php

use yii\db\Migration;

class m170320_133511_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_test');
            $permission->description = 'Widgets TEST Playground';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170320_133511_auth_items cannot be reverted.\n";

        return false;
    }
}
