<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$common = [
    'id'          => 'test',
    'vendorPath'  => '@app/../vendor',
    'runtimePath' => '@app/../runtime',
    'aliases'     => [
        'dmstr/modules/widgets' => '@vendor/dmstr/yii2-widgets2-module',
        'tests'                 => '@vendor/dmstr/yii2-widgets2-module/tests',
    ],
    'components'  => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'db'          => [
            'class'             => 'yii\db\Connection',
            'dsn'               => getenv('DATABASE_DSN'),
            'username'          => getenv('DATABASE_USER'),
            'password'          => getenv('DATABASE_PASSWORD'),
            'charset'           => 'utf8',
            'tablePrefix'       => getenv('DATABASE_TABLE_PREFIX'),
            'enableSchemaCache' => YII_ENV_PROD ? true : false,
        ],
        'i18n'        => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'settings'    => [
            'class' => '\pheme\settings\components\Settings'
        ],
        'urlManager'  => [
            'class'                        => 'codemix\localeurls\UrlManager',
            'enablePrettyUrl'              => true,
            'enableDefaultLanguageUrlCode' => true,
            'showScriptName'               => getenv('YII_ENV_TEST') ? true : false,
            'scriptUrl'                    => '',
            'baseUrl'                      => '/',
            'rules'                        => [
                'site/login' => 'user/security/login'
            ],
            'languages'                    => ['de', 'en', 'ru'],
        ],
        'user'        => [
            'class'         => '\dmstr\web\User',
            'identityClass' => 'dektrium\user\models\User',
            'rootUsers'     => ['admin']
        ],
        'view' => [
            'renderers' => [
                'twig' => [
                    'class'      => 'yii\twig\ViewRenderer',
                    'cachePath'  => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options'    => [
                        'auto_reload' => true,
                    ],
                    'globals'    => [
                        'html'   => '\yii\helpers\Html',
                    ],
                    'uses'       => ['yii\bootstrap'],
                ],
            ],
        ]
    ],
    'modules'     => [
        'audit'            => [
            'class'         => 'bedezign\yii2\audit\Audit',
            'layout'        => '@app/views/layouts/main',
            'panels'        => [
                'audit/request',
                'audit/mail',
                'audit/trail',
                'audit/javascript',
                'audit/error',
            ],
            'ignoreActions' => ['*'],
            'maxAge'        => 7,
        ],
        'settings'    => [
            'class' => '\pheme\settings\Module'
        ],
        'user'        => [
            'class' => '\dektrium\user\Module'
        ],
        'widgets'          => [
            'class'  => '\hrzg\widget\Module',
            'layout' => '@app/views/layouts/main',
        ]
    ],
    'params'      => [
        'yii.migrations' => [
            '@vendor/dektrium/yii2-user/migrations',
            '@vendor/yiisoft/yii2/rbac/migrations',
            '@vendor/bedezign/yii2-audit/src/migrations',
            '@vendor/pheme/yii2-settings/migrations',
            '@vendor/dmstr/yii2-prototype-module/src/migrations',
            '@vendor/dmstr/yii2-widgets2-module/src/migrations',
            '@vendor/dmstr/yii2-widgets2-module/tests/migrations',
        ]
    ]
];

$web = [
    'components' => [],
    'modules'    => []
];

$console = [
    'components'    => [
        'urlManager' => [
            'scriptUrl' => '/',
        ],
    ],
    'controllerMap' => [
        'db'      => '\dmstr\console\controllers\MysqlController',
        'migrate' => '\dmstr\console\controllers\MigrateController'
    ]
];

return \yii\helpers\ArrayHelper::merge($common, (PHP_SAPI === 'cli') ? $console : $web);