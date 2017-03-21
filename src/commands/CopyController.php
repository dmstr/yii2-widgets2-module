<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace hrzg\widget\commands;

use hrzg\widget\models\crud\WidgetContent;
use yii\console\Exception;

/**
 * Widgets module copy command
 * @package hrzg\widget\commands
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class CopyController extends \yii\console\Controller
{
    /**
     * @const string
     */
    const DESCRIPTION = "Widgets module copy command";

    /**
     * @var integer
     */
    public $sourceLanguage;

    /**
     * @var string
     */
    public $destinationLanguage;

    /**
     * Support for `dmstr/yii2-pages-module`
     * - will find page ids in the destination language as new request params for the copied widgets
     *
     * @var bool
     */
    public $pagesModule = false;

    /**
     * @param string $id
     *
     * @return array
     */
    public function options($id)
    {
        return array_merge(
            parent::options($id),
            [
                'sourceLanguage',
                'destinationLanguage',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Show information about this command
     */
    public function actionIndex()
    {
        $actions = [
            $this->id . '/language',
        ];
        echo "\n" . self::DESCRIPTION . "\n";
        echo "----------------------------------------\n\n";
        foreach ($actions as $action) {
            echo "yii " . $action . " --sourceLanguage --destinationLanguage\n";
        }
        echo "\n\n";
    }

    /**
     * Copy all widgets from one language to another
     *
     * - Detect static routes
     * - Detect route `/pages/default/page` and request param
     *
     * @param $sourceLanguage
     * @param $destinationLanguage
     *
     * @return bool
     */
    public function actionLanguage($sourceLanguage, $destinationLanguage)
    {
        // Disable access trait access_domain checks in find
        WidgetContent::$activeAccessTrait = false;

        // transaction begin
        $transaction = \Yii::$app->db->beginTransaction();

        // try copy widgets
        try {
            /**
             * Find source widgets
             *
             * @var WidgetContent $sourceWidgets
             */
            $sourceWidgets = WidgetContent::findAll(['access_domain' => mb_strtolower($sourceLanguage)]);
            if ($sourceWidgets === null) {
                throw new Exception(\Yii::t('widgets', 'No widget contents found for source language "{LANGUAGE}"', ['LANGUAGE' => $sourceLanguage]), 404);
            }

            // copy widgets into destination language
            $countNewWidgets = 0;
            foreach ($sourceWidgets as $sourceWidget) {

                /** @var WidgetContent $sourceWidget */

                // check if widget not already exists in destination language
                $destinationWidgetExists = WidgetContent::findOne(['domain_id' => $sourceWidget->domain_id, 'access_domain' => mb_strtolower($destinationLanguage)]);
                if ($destinationWidgetExists !== null) {
                    // skip this widget, already exists
                    continue;
                }

                // copy widget to destination language
                $newWidget = new WidgetContent($sourceWidget->attributes);
                $newWidget->id = null;
                $newWidget->access_domain = mb_strtolower($destinationLanguage);
                $newWidget->copied_from = $sourceWidget->id;

                /**
                 * with pages module usage
                 */
                if ($this->pagesModule && \Yii::$app->getModule('pages') !== null) {

                    $targetPage = (new \dmstr\modules\pages\models\Tree())->sibling($destinationLanguage, $sourceWidget->request_param, $sourceWidget->route);

                    if ($targetPage !== null) {
                        $newWidget->request_param = (string) $targetPage->id;
                    }
                }

                // if one widget cannot been saved, copy none!
                if (!$newWidget->save()) {
                    \Yii::error($newWidget->getErrors(), __METHOD__);
                    throw new Exception(\Yii::t('widgets', 'Widget with domain_id: {DOMAIN_ID} could not been saved. Abort!', ['DOMAIN_ID' => $sourceWidget->domain_id]), 500);
                };
                $countNewWidgets++;
            }

            // success
            $transaction->commit();
            $this->stdout(\Yii::t('widgets', '{COUNT} widgets has been copied into language {LANGUAGE}.', ['COUNT' => $countNewWidgets, 'LANGUAGE' => $destinationLanguage]));
            \Yii::$app->end(0);

        } catch (Exception $e) {
            $transaction->rollBack();
            $this->stderr($e->getMessage());
            \Yii::$app->end(1);
        }
    }
}

