<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget;

use hrzg\widget\models\crud\WidgetPage;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 *
 * @package dmstr\modules\pages
 * @author Marc Mautz <marc@diemeisterei.de>
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            'p/<slugged_menu_name>-<' . WidgetPage::REQUEST_PARAM_ID . ':[0-9]*>' => 'widgets/default/page',
            'p/<slugged_menu_path>/<slugged_menu_name>-<' . WidgetPage::REQUEST_PARAM_ID . ':[0-9]*>' => 'widgets/default/page'
        ]);
    }
}
