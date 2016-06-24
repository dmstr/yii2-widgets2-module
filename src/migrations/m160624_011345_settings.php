<?php

use yii\db\Migration;

class m160624_011345_settings extends Migration
{
    public function up()
    {
        Yii::$app->settings->set('availablePhpClasses', '{"hrzg\\\widget\\\widgets\\\TwigTemplate": "Twig layout"}', 'widgets', 'object');

        return true;
    }

    public function down()
    {
        Yii::$app->settings->delete('availablePhpClasses', 'widgets');

        return true;
    }

}
