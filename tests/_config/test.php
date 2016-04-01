<?php

return [
    'aliases' => [
        '@hrzg/widgets2' => '/app/vendor/hrzg/yii2-widgets2-module/src'
    ],
    'controllerMap' => [
        'widget-cruds' => [
            'class' => 'schmunk42\giiant\commands\BatchController',
            'overwrite' => true,
            'modelNamespace' => 'app\\modules\\crud\\models',
            'modelQueryNamespace' => 'app\\modules\\crud\\models\\query',
            'crudTidyOutput' => true,
            'crudAccessFilter' => true,
            'crudControllerNamespace' => 'app\\modules\\crud\\controllers',
            'crudSearchModelNamespace' => 'app\\modules\\crud\\models\\search',
            'crudViewPath' => '@app/modules/crud/views',
            'crudPathPrefix' => '/crud/',
            'crudProviders' => [
                'schmunk42\\giiant\\generators\\crud\\providers\\optsProvider',
            ],
            'tablePrefix' => 'app_',
            /*'tables' => [
                'app_profile',
            ]*/
        ]
    ]
];