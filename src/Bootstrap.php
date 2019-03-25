<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\pages;

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
        $app->urlManager->addRules(
            [
                [
                    'class' => WidgetPageUrlRule::class
                ],
                [
                    'pattern' => 'p/<' . WidgetPage::REQUEST_PARAM_PATH . ':[a-zA-Z0-9_\-\./\+]*>/<' . WidgetPage::REQUEST_PARAM_SLUG . ':[a-zA-Z0-9_\-\.]*>-<' . WidgetPage::REQUEST_PARAM_ID . ':[0-9]*>.html',
                    'route' => 'widgets/default/page',
                    'encodeParams' => false,
                ],
                'p/<' . WidgetPage::REQUEST_PARAM_SLUG . ':[a-zA-Z0-9_\-\.]*>-<' . WidgetPage::REQUEST_PARAM_ID . ':[0-9]*>.html' => 'widgets/default/page',
            ]);
    }
}
