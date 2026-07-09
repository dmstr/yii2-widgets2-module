<?php

namespace hrzg\widget;

use dmstr\web\traits\AccessBehaviorTrait;

class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;
    
    public $controllerNamespace = 'hrzg\widget\controllers';

    /**
     * RBAC full permission to widget module
     */
    const WIDGETS_ACCESS_PERMISSION = 'widgets';

    /**
     * RBAC permission name to widget test
     */
    const TEST_ACCESS_PERMISSION = 'widgets_test';

    /**
     * RBAC permission name to widget content
     */
    const CONTENT_ACCESS_PERMISSION = 'widgets_crud_widget';

    /**
     * RBAC permission name to widget template
     */
    const TEMPLATE_ACCESS_PERMISSION = 'widgets_crud_widget-template';

    /**
     * layout path for the /widgets/test playground actions
     *
     * @var string
     */
    public $playgroundLayout = '@app/views/layouts/main';

    /**
     * Activate / deactivate date based access control
     * @var bool
     */
    public $dateBasedAccessControl = false;

    /**
     * Datepicker minute steps
     * @var bool
     */
    public $datepickerMinutes = false;

    /**
     * timezone for DateTime objects
     * @var string
     */
    public $timezone = 'UTC';

    /**
     * mappings for links
     *
     * can be used to map route and requestParam attributes from WidgetContent
     * Models to frontend URLs
     *
     * the elements can be
     * - simple string to string mappings
     * - string to array mappings where route and requestParam Name can be defined
     *
     * example:
     *  ```php
     *  [
     *    'app/site/index' => '/',
     *    'pages/default/page' => 'pages/default/page',
     *     'frontend/tag/detail' => [
     *          'route' => 'frontend/tag/detail',
     *          'requestParamName' => 'tagId',
     *     ],
     *  ]
     *  ```
     *
     * @see \hrzg\widget\models\crud\WidgetContent::getFrontendRoute
     * @var array
     */
    public $frontendRouteMap = [
        'app/site/index' => '/',
    ];

    /**
     * default name used as RequestParamName when generating frontend URLs
     * can be overwritten for each route in self::$frontendRouteMap
     *
     * BC: define 'pageId' as default
     *
     * @see self::$frontendRouteMap
     * @see \hrzg\widget\models\crud\WidgetContent::getFrontendRoute
     * @var string
     */
    public $frontendDefaultRequestParamName = 'pageId';

    /**
     * set ajax option for JsonEditor
     *
     * @var bool
     */
    public $allowAjaxInSchema = false;

    /**
     * Extra options merged into every JsonEditorWidget rendered by this module's
     * edit forms. Generic passthrough so the module stays agnostic of any concrete
     * editor plugin (file manager, WYSIWYG, ...). The result is deep-merged onto the
     * view's defaults (values here win), so it can set top-level widget options
     * (e.g. `flysystemRestConfig`, `registerJoditAsset`) as well as override
     * `clientOptions`.
     *
     * May be either a plain array (static config) or a callable resolved at render
     * time. Use a callable for anything runtime-dependent — a per-user JWT token,
     * `Url::to()` (routing context is ready then, unlike in the config literal), or
     * config that depends on the current model/schema. The callable receives the
     * WidgetContent model and the decoded schema and must return an array:
     * `function ($model, $schema): array`.
     *
     * Example (application config):
     * ```php
     * // static
     * 'jsonEditorConfig' => [
     *     'flysystemRestConfig' => [
     *         'apiBaseUrl'      => '/filemanager/api',
     *         'imageExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'],
     *     ],
     * ],
     * // or dynamic (e.g. JWT auth mode)
     * 'jsonEditorConfig' => function ($model, $schema) {
     *     return [
     *         'flysystemRestConfig' => [
     *             'apiBaseUrl' => \yii\helpers\Url::to(['/filemanager/api']),
     *             'jwt'        => Yii::$app->myJwtIssuer->tokenFor(Yii::$app->user->id),
     *         ],
     *     ];
     * },
     * ```
     *
     * @var array|callable
     * @see resolveJsonEditorConfig()
     */
    public $jsonEditorConfig = [];

    /**
     * Resolve {@see $jsonEditorConfig} to an array, invoking it if it is a callable.
     *
     * @param mixed $model  the WidgetContent model being edited (passed to the callable)
     * @param mixed $schema the decoded JSON schema (passed to the callable)
     * @return array the extra JsonEditorWidget options
     */
    public function resolveJsonEditorConfig($model = null, $schema = null): array
    {
        $config = $this->jsonEditorConfig;
        if (is_callable($config)) {
            $config = call_user_func($config, $model, $schema);
        }
        return is_array($config) ? $config : [];
    }


    /**
     * If true, the json content properties will be validated against the json schema from the widget_template.
     * To be BC the default is false, but you should enable it
     *
     * @var bool
     */
    public $validateContentSchema = false;

    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        $moduleUrl = '/'.$this->id;
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Widgets module', 'url' => [$moduleUrl]];

        return parent::beforeAction($action);
    }
}
