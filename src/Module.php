<?php

namespace hrzg\widget;

use dmstr\web\traits\AccessBehaviorTrait;

/**
 * Class Module
 * @package hrzg\widget
 *
 * @property string $widget_page_layout
 */
class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;
    
    public $controllerNamespace = 'hrzg\widget\controllers';

    public $widget_page_layout = '@app/views/layouts/main';

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

    const PAGE_ACCESS_PERMISSION = 'widgets_crud_widget-page';

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
