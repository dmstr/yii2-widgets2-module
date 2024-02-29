<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\Widget;
use hrzg\widget\models\crud\base\WidgetTranslation;
use hrzg\widget\validators\ClientSideJsonValidator;
use JsonSchema\Validator;
use yii\caching\TagDependency;
use yii\helpers\Json;


/**
 * Class WidgetContentTranslation
 * @package hrzg\widget\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class WidgetContentTranslation extends WidgetTranslation
{

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [
            'default_properties_json',
            ClientSideJsonValidator::class
        ];

        // add json schema validation if enabled in module
        if (!empty(\Yii::$app->controller->module->validateContentSchema)) {
            $rules['validate_properties_json'] = [
                'default_properties_json',
                function ($attribute) {

                    /** @var Widget $baseWidget */
                    $baseWidget = $this->getWidgetContent()->one();
                    /** @var WidgetTemplate $tmpl */
                    $tmpl = $baseWidget->getTemplate()->one();
                    if (!$tmpl) {
                        return;
                    }
                    $schema    = $tmpl->json_schema;
                    $validator = new Validator();
                    $obj       = Json::decode($schema, false);
                    $data      = Json::decode($this->{$attribute}, false);
                    $validator->check($data, $obj);

                    if ($validator->getErrors()) {
                        foreach ($validator->getErrors() as $error) {
                            $this->addError($error['property'], "{$error['property']}: {$error['message']}");
                        }
                    }

                }

            ];
        }
        return $rules;
    }

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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
    }

    public function afterDelete()
    {
        parent::afterDelete();
        TagDependency::invalidate(\Yii::$app->cache, 'widgets');
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