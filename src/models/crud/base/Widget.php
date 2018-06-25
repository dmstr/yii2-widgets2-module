<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace hrzg\widget\models\crud\base;

use dmstr\db\traits\ActiveRecordAccessTrait;
use dosamigos\translateable\TranslateableBehavior;
use hrzg\widget\models\crud\WidgetContentTranslation;
use hrzg\widget\models\crud\WidgetContentTranslationMeta;
use Yii;

/**
 * This is the base-model class for table "app_hrzg_widget".
 *
 * @property integer $id
 * @property string $status
 * @property string $domain_id
 * @property integer $widget_template_id
 * @property string $default_properties_json
 * @property string $route
 * @property string $request_param
 * @property string $container_id
 * @property string $rank
 * @property string $access_owner
 * @property string $access_domain
 * @property string $access_read
 * @property string $access_update
 * @property string $access_delete
 * @property string $publish_at
 * @property string $expire_at
 * @property integer $copied_from
 * @property string $created_at
 * @property string $updated_at
 * @property string $aliasModel
 */
abstract class Widget extends \yii\db\ActiveRecord
{
    use ActiveRecordAccessTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['translatable'] = [
            'class' => TranslateableBehavior::className(),
            'languageField' => 'language',
            'skipSavingDuplicateTranslation' => true,
            'translationAttributes' => [
                'default_properties_json'
            ]
        ];
        $behaviors['translation_meta'] = [
            'class' => TranslateableBehavior::className(),
            'relation' => 'translationsMeta',
            'languageField' => 'language',
            'fallbackLanguage' => false,
            'skipSavingDuplicateTranslation' => false,
            'translationAttributes' => [
                'status'
            ]
        ];

        return $behaviors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(WidgetContentTranslation::className(), ['widget_content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslationsMeta()
    {
        return $this->hasMany(WidgetContentTranslationMeta::className(), ['widget_content_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hrzg_widget_content}}';
    }

    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later.
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural) {
            return Yii::t('widgets', 'Widgets');
        } else {
            return Yii::t('widgets', 'Widget');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'widget_template_id', 'container_id', 'route'], 'required'],
            [['default_properties_json'], 'string'],
            [['publish_at', 'expire_at'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 32],
            [['container_id', 'route'], 'string', 'max' => 128],
            [['domain_id'], 'string', 'max' => 64],
            [['widget_template_id', 'copied_from'], 'integer'],
            [['rank', 'access_owner'], 'string', 'max' => 11],
            [['request_param', 'access_read', 'access_update', 'access_delete'], 'string', 'max' => 255],
            [['access_domain'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('widgets', 'ID'),
            'status' => Yii::t('widgets', 'Status'),
            'widget_template_id' => Yii::t('widgets', 'Template'),
            'default_properties_json' => Yii::t('widgets', 'Widget Properties'),
            'domain_id' => Yii::t('widgets', 'Domain ID'),
            'name_id' => Yii::t('widgets', 'Name ID'),
            'container_id' => Yii::t('widgets', 'Container ID'),
            'rank' => Yii::t('widgets', 'Rank'),
            'route' => Yii::t('widgets', 'Route'),
            'request_param' => Yii::t('widgets', 'Request Param'),
            'access_owner' => Yii::t('widgets', 'Access Owner'),
            'access_domain' => Yii::t('widgets', 'Access Domain'),
            'access_read' => Yii::t('widgets', 'Access Read'),
            'access_update' => Yii::t('widgets', 'Access Update'),
            'access_delete' => Yii::t('widgets', 'Access Delete'),
            'publish_at' => Yii::t('models', 'Publish At'),
            'expire_at' => Yii::t('models', 'Expire At'),
            'copied_from' => Yii::t('widgets', 'Copied From'),
            'created_at' => Yii::t('widgets', 'Created At'),
            'updated_at' => Yii::t('widgets', 'Updated At'),
        ];
    }

    public function isVisibleFrontend()
    {
        return (!$this->getTranslation(Yii::$app->language)->id || !$this->status) ? false : true;
    }
}
