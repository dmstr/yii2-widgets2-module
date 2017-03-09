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
     * @return string
     */
    public function actionLanguage()
    {
        Url::remember();

        $model = new CopyForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            // RUN copy-widgets cli command
            $command = new Command('./yii copy-widgets/language');
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
