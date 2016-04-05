<?php

return [
    'aliases'       => [
        '@hrzg/widget' => '/app/vendor/hrzg/yii2-widgets2-module/src'
    ],
    'params' => [
      'yii.migrations' => [
          '@vendor/hrzg/yii2-widgets2-module/src/migrations'
      ]
    ],
    'controllerMap' => [
        'widget-cruds' => [
            'class'                    => 'schmunk42\giiant\commands\BatchController',
            'overwrite'                => true,
            'modelNamespace'           => 'hrzg\\widget\\models\\crud',
            'modelQueryNamespace'      => 'hrzg\\widget\\models\\crud\\query',
            'modelGenerateQuery'       => true,
            'crudTidyOutput'           => true,
            'crudAccessFilter'         => true,
            'crudControllerNamespace'  => 'hrzg\\widget\\controllers\\crud',
            'crudSearchModelNamespace' => 'hrzg\\widget\\models\\crud\\search',
            'crudViewPath'             => '@hrzg/widget/views/crud',
            'crudPathPrefix'           => '/crud/',
            'crudProviders'            => [
                'schmunk42\\giiant\\generators\\crud\\providers\\optsProvider',
            ],
            'tablePrefix'              => 'app_hrzg_',
            'tables'                   => [
                'app_hrzg_widget',
                'app_hrzg_widget_template',
            ]
        ]
    ],
    'modules'       => [
        'widget' => [
            'class' => 'hrzg\widget\Module',
            'layout' => '@admin-views/layouts/box',
        ]
    ]
];