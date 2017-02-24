<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2017 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace hrzg\widget\models\forms;

use hrzg\widget\widgets\Cell;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class CopyForm
 * @package hrzg\widget\models\forms
 * @author Christopher Stebe <c.stebe@herzogkommunikation.de>
 */
class CopyForm extends Model
{
    /**
     * @var integer
     */
    public $sourceLanguage;

    /**
     * @var string
     */
    public $destinationLanguage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sourceLanguage'        => \Yii::t('widgets', 'Source Language'),
            'destinationLanguage' => \Yii::t('widgets', 'Destination Language'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sourceLanguage', 'destinationLanguage'], 'required'],
            [['sourceLanguage', 'destinationLanguage'], 'string'],
            [['sourceLanguage', 'destinationLanguage'], 'validateLanguage'],
            [['destinationLanguage'], 'validateDestinationLanguage'],
        ];
    }

    /**
     * Validate if the target language exists
     * @param $attribute
     */
    public function validateLanguage($attribute)
    {
        // check available languages
        if (!in_array($this->$attribute, self::availableLanguages(), true)) {
            $this->addError($attribute, \Yii::t('widgets', '"{LANGUAGE}" is not available', ['LANGUAGE' => $this->$attribute]));
        }


    }

    /**
     * Source and destination must be different
     * @param $attribute
     */
    public function validateDestinationLanguage($attribute)
    {
        if ($this->$attribute === $this->sourceLanguage) {
            $this->addError($attribute, \Yii::t('widgets', 'Destination language must be different!'));
        }
    }

    /**
     * @return array with available app languages and global domain
     */
    public static function availableLanguages()
    {
        $availableLanguages = [];
        foreach (ArrayHelper::merge([Cell::GLOBAL_ROUTE], \Yii::$app->urlManager->languages) as $language) {
            $availableLanguages[$language] = $language;
        }
        return $availableLanguages;
    }
}
