<?php

return [
    'aliases'       => [
        '@hrzg/widget' => 'app/vendor/hrzg/yii2-widget-module/src'
    ],
    'params' => [
      'yii.migrations' => [
          '@vendor/hrzg/yii2-widget-module/src/migrations'
      ]
    ],
    'controllerMap' => [
        'widget-cruds' => [
            'class'                    => 'schmunk42\giiant\commands\BatchController',
            'overwrite'                => true,
            'modelNamespace'           => 'hrzg\\widget\\crud\\models',
            'modelQueryNamespace'      => 'hrzg\\widget\\crud\\models\\query',
            'modelGenerateQuery'       => true,
            'crudTidyOutput'           => true,
            'crudAccessFilter'         => true,
            'crudControllerNamespace'  => 'hrzg\\widget\\crud\\controllers',
            'crudSearchModelNamespace' => 'hrzg\\widget\\crud\\models\\search',
            'crudViewPath'             => 'hrzg/widget/modules/crud/views',
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
        ]
    ]
];