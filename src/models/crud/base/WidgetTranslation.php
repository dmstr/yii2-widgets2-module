<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * --- VARIABLES ---
 *
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ArrayDataProvider
 */

namespace hrzg\widget\models\crud\base;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\db\traits\ActiveRecordAccessTrait;
use hrzg\widget\models\crud\query\WidgetTranslationQuery;
use hrzg\widget\models\crud\WidgetContent;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the base-model class for table "hrzg_widget_content_translation".
 *
 * Class WidgetContentTranslation
 * @package hrzg\widget\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property integer $id
 * @property string $language
 * @property integer $widget_content_id
 * @property string $default_properties_json
 * @property string $access_owner
 * @property string $access_read
 * @property string $access_update
 * @property string $access_delete
 * @property string $access_domain
 * @property string $created_at
 * @property string $updated_at
 */
abstract class WidgetTranslation extends \yii\db\ActiveRecord
{
    use ActiveRecordAccessTrait;

    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later.
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural) {
            return Yii::t('widgets', 'Widget Translations');
        } else {
            return Yii::t('widgets', 'Widget Translation');
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()'),
        ];

        $behaviors['audit'] = [
            'class' => AuditTrailBehavior::class
        ];

        return $behaviors;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%hrzg_widget_content_translation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['widget_content_id'], 'integer'],
            [['language'], 'required'],
            [['default_properties_json'], 'string'],
            [['access_owner'], 'string', 'max' => 11],
            [['access_read', 'access_update', 'access_delete'], 'string', 'max' => 255],
            [['access_domain'], 'string', 'max' => 8],
            [
                'access_domain',
                'default',
                'value' => function () {
                    return mb_strtolower(\Yii::$app->language);
                }
            ],
            [
                [
                    'access_read',
                ],
                'default',
                'value' => self::$_all
            ],
            [
                [
                    'access_update',
                    'access_delete',
                ],
                'default',
                'value' => null
            ],
            [
                ['widget_content_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => WidgetContent::class,
                'targetAttribute' => ['widget_content_id' => 'id']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('widgets', 'ID'),
            'widget_content_id' => Yii::t('widgets', 'Content'),
            'default_properties_json' => Yii::t('widgets', 'Widget Properties'),
            'access_owner' => Yii::t('widgets', 'Access Owner'),
            'access_domain' => Yii::t('widgets', 'Access Domain'),
            'access_read' => Yii::t('widgets', 'Access Read'),
            'access_update' => Yii::t('widgets', 'Access Update'),
            'access_delete' => Yii::t('widgets', 'Access Delete'),
            'created_at' => Yii::t('widgets', 'Created At'),
            'updated_at' => Yii::t('widgets', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetContent()
    {
        return $this->hasOne(WidgetContent::class, ['id' => 'widget_content_id']);
    }

    /**
     * @inheritdoc
     * @return WidgetTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WidgetTranslationQuery(get_called_class());
    }
}
