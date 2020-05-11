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
use dmstr\activeRecordPermissions\ActiveRecordAccessTrait;
use hrzg\widget\models\crud\query\WidgetTranslationMetaQuery;
use hrzg\widget\models\crud\query\WidgetTranslationQuery;
use hrzg\widget\models\crud\WidgetContent;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the base-model class for table "hrzg_widget_content_translation_meta".
 *
 * Class WidgetContentTranslationMeta
 * @package hrzg\widget\models\crud
 * @author Carsten Brandt <mail@cebe.cc>
 *
 * @property integer $id
 * @property string $language
 * @property integer $widget_content_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
abstract class WidgetTranslationMeta extends \yii\db\ActiveRecord
{
    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later.
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural) {
            return Yii::t('widgets', 'Widget Metadata Translations');
        } else {
            return Yii::t('widgets', 'Widget Metadata Translation');
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
        return '{{%hrzg_widget_content_translation_meta}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['widget_content_id'], 'integer'],
            [['language'], 'required'],
            [['status'], 'string', 'max' => 32],
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
            'status' => Yii::t('widgets', 'Status'),
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
     * @return \app\modules\crud\models\query\EventTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WidgetTranslationMetaQuery(get_called_class());
    }
}
