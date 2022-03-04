<?php

namespace hrzg\widget\commands;

use yii\console\Controller;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 * @deprecated
 */
class CopyController extends Controller
{
    /**
     * @deprecated
     * @return bool|int
     */
    public function actionIndex()
    {
        return $this->stderr('copy function has been removed');
    }

    /**
     * @deprecated
     * @return bool|int
     */
    public function actionLanguage()
    {
        return $this->stderr('copy function has been removed');
    }
}
