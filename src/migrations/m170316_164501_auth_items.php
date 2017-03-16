<?php

use yii\db\Migration;

class m170316_164501_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_crud_widget_index');
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_view');
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_update');
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_delete');
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170316_164501_auth_items cannot be reverted.\n";

        return false;
    }
}
