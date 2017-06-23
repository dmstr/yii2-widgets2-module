<?php

use yii\db\Migration;

class m160401_152910_add_widget_auth_item extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {

            if (!$auth->getPermission('widgets_crud_widget')) {
                $permission = $auth->createPermission('widgets_crud_widget');
                $permission->description = 'Widgets CRUD Content';
                $auth->add($permission);
            }

            if (!$auth->getPermission('widgets_crud_widget-template')) {
                $permission = $auth->createPermission('widgets_crud_widget-template');
                $permission->description = 'Widgets CRUD Template';
                $auth->add($permission);
            }

            if (!$auth->getPermission('widgets')) {
                $permission = $auth->createPermission('widgets');
                $permission->description = 'Widgets Module';
                $auth->add($permission);
            }
        }
    }

    public function down()
    {
        return false;
    }
}
