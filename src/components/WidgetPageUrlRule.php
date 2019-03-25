<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\pages\components;

use \dmstr\modules\pages\models\WidgetPage;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

/**
 * Class PageUrlRule
 * @package dmstr\modules\pages\components
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class WidgetPageUrlRule extends BaseObject implements UrlRuleInterface
{
    /**
     * @param \yii\web\UrlManager $manager
     * @param string $route
     * @param array $params
     *
     * @return bool|string
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === WidgetPage::DEFAULT_PAGE_ROUTE) {

            /**
             * Build page url
             */
            $pageId = '';
            if (isset($params[WidgetPage::REQUEST_PARAM_ID])) {
                $pageId = '-' . $params[WidgetPage::REQUEST_PARAM_ID];
                unset($params[WidgetPage::REQUEST_PARAM_ID]);
            }

            $pageSlug = '';
            if (isset($params[WidgetPage::REQUEST_PARAM_SLUG])) {
                $pageSlug = $params[WidgetPage::REQUEST_PARAM_SLUG];
                unset($params[WidgetPage::REQUEST_PARAM_SLUG]);
            }

            $pagePath = '';
            if (isset($params[WidgetPage::REQUEST_PARAM_PATH])) {
                $pagePath = $params[WidgetPage::REQUEST_PARAM_PATH] . '/';
                unset($params[WidgetPage::REQUEST_PARAM_PATH]);
            }

            $pageUrl = $pagePath . $pageSlug . $pageId;

            /**
             * Add additional request params if set
             */
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $pageUrl .= '?' . $query;
            }

            return $pageUrl;
        }

        return false;
    }

    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     *
     * @return bool
     */
    public function parseRequest($manager, $request)
    {
        return false;
    }
}
