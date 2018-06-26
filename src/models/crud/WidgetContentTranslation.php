<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\WidgetTranslation;


/**
 * Class WidgetContentTranslation
 * @package hrzg\widget\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class WidgetContentTranslation extends WidgetTranslation
{
    /**
     * Global route needs empty request param
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // ensure lowercase access domain
            $this->access_domain = mb_strtolower($this->access_domain);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public static function optsAccessDomain()
    {
        $availableLanguages = [];
        foreach (\Yii::$app->urlManager->languages as $availablelanguage) {
            $availableLanguages[mb_strtolower($availablelanguage)] = mb_strtolower($availablelanguage);
        }
        return $availableLanguages;
    }
}