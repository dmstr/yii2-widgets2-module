<?php

use yii\db\Migration;

class m160401_152910_add_widgets2_auth_item extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission1 = $auth->createPermission('widgets2_crud_widget');
            $permission1->description = 'Widgets2 CRUD';
            $auth->add($permission1);

            $permission2 = $auth->createPermission('widgets2_widget');
            $permission2->description = 'Widgets2 CRUD (JSON Data)';
            $auth->add($permission2);

            $widgets2 = $auth->createPermission('widgets2');
            $widgets2->description = 'Widgets2 Module';
            $auth->add($widgets2);
            $auth->addChild($widgets2, $permission1);
            $auth->addChild($widgets2, $permission2);
        }
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $auth->remove($auth->getPermission('widgets2_widget'));
            $auth->remove($auth->getPermission('widgets2_widget'));
            $auth->remove($auth->getChildren('widgets2'));
        } else {
            throw new \yii\base\Exception('Application authManager must be an instance of \yii\rbac\DbManager');
        }

    }

}
