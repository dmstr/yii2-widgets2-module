<?php

use yii\db\Migration;

class m170316_164501_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_crud_widget_index');
            $permission->description = 'Widgets CRUD Content Index';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_create');
            $permission->description = 'Widgets CRUD Content Create';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_view');
            $permission->description = 'Widgets CRUD Content View';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_update');
            $permission->description = 'Widgets CRUD Content Update';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget_delete');
            $permission->description = 'Widgets CRUD Content Delete';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170316_164501_auth_items cannot be reverted.\n";

        return false;
    }
}
