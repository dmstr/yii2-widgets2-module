<?php

use yii\db\Migration;

class m170317_101811_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_crud_widget-template_index');
            $permission->description = 'Widgets CRUD Template Index';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget-template_create');
            $permission->description = 'Widgets CRUD Template Create';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget-template_view');
            $permission->description = 'Widgets CRUD Template View';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget-template_update');
            $permission->description = 'Widgets CRUD Template Update';
            $auth->add($permission);
            $permission = $auth->createPermission('widgets_crud_widget-template_delete');
            $permission->description = 'Widgets CRUD Template Delete';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170317_101811_auth_items cannot be reverted.\n";

        return false;
    }
}
