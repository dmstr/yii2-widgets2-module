<?php

use yii\db\Migration;

class m170317_101222_auth_items extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission = $auth->createPermission('widgets_crud_widget_copy');
            $permission->description = 'Widgets CRUD Content Copy';
            $auth->add($permission);
        }
    }

    public function down()
    {
        echo "m170317_101222_auth_items cannot be reverted.\n";

        return false;
    }
}
