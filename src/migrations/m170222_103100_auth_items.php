<?php

use yii\db\Migration;

class m170222_103100_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_copy');
            $permission->description = 'Widget Copy';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170222_103100_auth_items cannot be reverted.\n";

        return false;
    }
}
