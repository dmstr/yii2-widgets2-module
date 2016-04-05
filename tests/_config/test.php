<?php

namespace app\config;

use hrzg\widget\models\crud\WidgetTemplate;
use schmunk42\giiant\generators\crud\callbacks\base\Callback;
use schmunk42\giiant\generators\crud\callbacks\yii\Db;
use schmunk42\giiant\generators\crud\callbacks\yii\Html;

$aceEditorField = function ($attribute, $model, $generator) {
    return "\$form->field(\$model, '{$attribute}')->widget(\\trntv\\aceeditor\\AceEditor::className())";
};

$jsonEditorField = function ($attribute, $model, $generator) {
    return <<<CODE
\$form->field(\$model, '{$attribute}')->widget(beowulfenator\\JsonEditor\\JsonEditorWidget::className(), [
    'schema' => \yii\helpers\Json::decode(hrzg\widget\models\crud\WidgetTemplate::find(['id'=>1])->one()->json_schema),
    'clientOptions' => [
        #'theme' => 'bootstrap3',
        'disable_collapse' => true,
        'disable_edit_json' => true,
        'disable_properties' => true,
        'no_additional_properties' => true,
    ],
]);
CODE;
};

\Yii::$container->set(
    'schmunk42\giiant\generators\crud\providers\CallbackProvider',
    [
        'columnFormats' => [
            // hide system fields, but not ID in table
            'created_at$|updated_at$' => Callback::false(),
            // hide all TEXT or TINYTEXT columns
            '.*' => Db::falseIfText(),
        ],
        'activeFields' => [
            // hide system fields in form
            'id$' => Db::falseIfAutoIncrement(),
            'id$|created_at$|updated_at$' => Callback::false(),
            'schema$' => $aceEditorField,
            'json' => $jsonEditorField,
        ],
        'attributeFormats' => [
            // render HTML output
            '_html$' => Html::attribute(),
        ],
    ]
);

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