<?php

namespace hrzg\widget\models\crud;

use bedezign\yii2\audit\AuditTrailBehavior;
use dosamigos\translateable\TranslateableBehavior;
use \hrzg\widget\models\crud\base\WidgetPage as BaseWidgetPage;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;


/**
 * Class WidgetPage
 *
 * @package hrzg\widget\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * -- TRANSLATABLE PROPERTIES ---
 *
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property bool $is_visible
 * @property bool $is_accessible
 * @property string $uuid
 * @property int $status
 */
class WidgetPage extends BaseWidgetPage
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    const ACCESS_ALL = '*';
    const EDIT_PRIVILEGE = 'widgets_default_update';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit'] = [
            'class' => AuditTrailBehavior::class
        ];
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
        $behaviors['translatable_meta'] = [
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
        foreach ([$this->getBehavior('translatable'), $this->getBehavior('translatable_meta')] as $key => $behavoir) {
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
     * @return bool
     */
    public function getIs_visible()
    {
        // page is active, access domain is either global or current language
        return $this->status === static::STATUS_ACTIVE && ($this->access_domain === static::ACCESS_ALL|| $this->access_domain === Yii::$app->language);
    }

    /**
     * @return bool
     */
    public function getIs_accessible()
    {
        return $this->access_read === static::ACCESS_ALL || Yii::$app->user->can($this->access_read);
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return 'widget-page-' . $this->id;
    }


    /**
     * @return array
     */
    public static function optsStatus()
    {
        return [
            static::STATUS_ACTIVE => Yii::t('widgets', 'Active'),
            static::STATUS_DRAFT => Yii::t('widgets', 'Draft'),
        ];
    }

    /**
     * @return mixed
     */
    public static function optsAccessDomain() {
        $access_domains[static::ACCESS_ALL] = Yii::t('widgets','Global');
        return $access_domains;
    }
    /**
     * @return mixed
     */
    public static function optsAccessPrivileges() {
        $access_domains[static::ACCESS_ALL] = Yii::t('widgets','All');
        return $access_domains;
    }
}
