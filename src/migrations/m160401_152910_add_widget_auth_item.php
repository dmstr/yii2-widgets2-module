<?php

use yii\db\Migration;

class m160401_152910_add_widget_auth_item extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $permission1 = $auth->createPermission('widget_crud_widget');
            $permission1->description = 'widget CRUD';
            $auth->add($permission1);

            $permission2 = $auth->createPermission('widget_widget');
            $permission2->description = 'widget CRUD (JSON Data)';
            $auth->add($permission2);

            $widget = $auth->createPermission('widget');
            $widget->description = 'widget Module';
            $auth->add($widget);
            $auth->addChild($widget, $permission1);
            $auth->addChild($widget, $permission2);
        }
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        if ($auth instanceof \yii\rbac\DbManager) {
            $auth->remove($auth->getPermission('widget_widget'));
            $auth->remove($auth->getPermission('widget_widget'));
            $auth->remove($auth->getChildren('widget'));
        } else {
            throw new \yii\base\Exception('Application authManager must be an instance of \yii\rbac\DbManager');
        }
    }
}
