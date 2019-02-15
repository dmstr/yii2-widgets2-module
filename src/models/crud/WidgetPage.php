<?php

namespace hrzg\widget\models\crud;

use dosamigos\translateable\TranslateableBehavior;
use \hrzg\widget\models\crud\base\WidgetPage as BaseWidgetPage;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * Class WidgetPage
 * @package hrzg\widget\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * -- TRANSLATABLE PROPERTIES ---
 *
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property int $status
 */
class WidgetPage extends BaseWidgetPage
{

    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['translatable'] = [
            'class' => TranslateableBehavior::class,
            'relation' => 'widgetPageTranslations',
            'skipSavingDuplicateTranslation' => true,
            'translationAttributes' => [
                'title',
                'description',
                'keywords',
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE,
            'restrictDeletion' => TranslateableBehavior::DELETE_LAST,
        ];
        $behaviors['translatable-meta'] = [
            'class' => TranslateableBehavior::class,
            'relation' => 'widgetPageMetas',
            'fallbackLanguage' => false,
            'skipSavingDuplicateTranslation' => false,
            'translationAttributes' => [
                'status'
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE,
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function transactions()
    {
        $transactions = parent::transactions();
        $transactions[self::SCENARIO_DEFAULT] = self::OP_DELETE;
        return $transactions;
    }

//    /**
//     * @return array
//     */
//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $scenarios['crud'] = [
//            'view',
//            'title',
//            'descripton',
//            'keywords',
//            'status',
//            'access_owner',
//            'access_domain',
//            'access_read',
//            'access_update',
//            'access_delete',
//        ];
//        return $scenarios;
//    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), $this->translatableRules());
    }

    /**
     * get validation rules from translation* relationModels
     * @return array
     */
    protected function translatableRules()
    {
        $rules = [];
        foreach ([$this->getBehavior('translatable'), $this->getBehavior('translatable-meta')] as $key => $behavoir) {
            if ($behavoir instanceof TranslateableBehavior) {
                $translation_model_class = $this->getRelation($behavoir->relation)->modelClass;
                $import_rules = (new $translation_model_class)->rules();
                foreach ($import_rules as $rule) {
                    foreach ($rule[0] as $rule_key => $attribute) {
                        if (!in_array($attribute, $behavoir->translationAttributes, true)) {
                            unset ($rule[0][$rule_key]);
                        }
                    }
                    if (!empty($rule[0])) {
                        $rules[] = $rule;
                    }
                }
            } else {
                continue;
            }
        }
        return $rules;
    }

    /**
     * @return array
     */
    public static function optsStatus() {
        return [
          self::STATUS_ACTIVE => Yii::t('widgets','Active'),
          self::STATUS_DRAFT => Yii::t('widgets','Draft'),
        ];
    }
}
