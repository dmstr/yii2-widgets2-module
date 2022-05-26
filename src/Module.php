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
     * @var 'string
     */
    public $timezone = 'UTC';

    /**
     * @var array mappings for links
     */
    public $frontendRouteMap = [
        'app/site/index' => '/',
    ];

    /**
     * set ajax option for JsonEditor
     *
     * @var bool
     */
    public $allowAjaxInSchema = false;


    /**
     * If true, the json content properties will be validated against the json schema from the widget_template.
     * To be BC the default is false, but you should enable it
     *
     * @var bool
     */
    public $validateContentSchema = false;

    /**
     * If true, this options allows the user to edit content directly
     *
     * @var bool
     */
    public $enableFrontendEditing = false;

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
