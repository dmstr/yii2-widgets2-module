<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\controllers;

use hrzg\widget\models\forms\CopyForm;
use mikehaertl\shellcommand\Command;
use rmrevin\yii\fontawesome\AssetBundle;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class CopyController
 * @package hrzg\widget\controllers
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class CopyController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'language';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Register font-awesome asset bundle
        AssetBundle::register(\Yii::$app->view);
    }

    /**
     * Copy all widgets from one language to another
     *
     * @return string|\yii\web\Response
     */
    public function actionLanguage()
    {
        Url::remember();

        $model = new CopyForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            // RUN copy-widgets cli command
            $yiiCommandPath = \Yii::getAlias('@vendor') . '/../yii';
            if ( ! file_exists($yiiCommandPath) || ! is_executable($yiiCommandPath)) {
                \Yii::$app->session->setFlash(
                    'danger',
                    \Yii::t(
                        'widgets',
                        'yii binary not found or is not executable in path {PATH}',
                        ['PATH' => $yiiCommandPath]
                    )
                );
                return $this->refresh();
            }
            $command = new Command($yiiCommandPath . ' copy-widgets/language');
            $command->addArg('--sourceLanguage', $model->sourceLanguage);
            $command->addArg('--destinationLanguage', $model->destinationLanguage);
            if ($command->execute() && empty($command->getError())) {
                \Yii::$app->session->setFlash('success', $command->getOutput());
            } else {
                \Yii::$app->session->setFlash('danger', $command->getError());
            }

            return $this->refresh();
        }
        return $this->render('language', ['copyForm' => $model]);
    }
}
