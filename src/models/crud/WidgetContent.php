<?php

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\Widget as BaseWidget;
use hrzg\widget\Module;
use hrzg\widget\widgets\Cell;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class WidgetContent
 * @package hrzg\widget\models\crud
 */
class WidgetContent extends BaseWidget
{
    /**
     * Virtual attribute generated from "domain_id"_"access_domain".
     *
     * @var string
     */
    public $name_id;

    /**
     * Timezone that will be used to correct dates.
     *
     * @var string
     */
    public $timezone;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'audit' => [
                    'class' => 'bedezign\yii2\audit\AuditTrailBehavior'
                ]
            ]
        );
    }

    public function init()
    {
        parent::init();

        if ($this->timezone === null) {
            $this->timezone = Module::getInstance()->timezone;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->setNameId($this->domain_id . '_' . $this->access_domain);

        // correct dates by timezone
        if ($this->publish_at) {
            $dateByTimeZone = new \DateTime($this->publish_at, new \DateTimeZone($this->timezone));
            $this->publish_at = $dateByTimeZone->format('Y-m-d H:i');
        }
        if ($this->expire_at) {
            $dateByTimeZone = new \DateTime($this->expire_at, new \DateTimeZone($this->timezone));
            $this->expire_at = $dateByTimeZone->format('Y-m-d H:i');
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
     * Get virtual name_id.
     *
     * @return string
     */
    public function getNameId()
    {
        return $this->name_id;
    }

    /**
     * Generate and Set virtual attribute name_id.
     *
     * @param mixed $name_id
     */
    public function setNameId($name_id)
    {
        $this->name_id = $name_id;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        // generate auto-rank, this is not meant to be unique in all cases
        $rules['unique-default-rank'] = [
            'rank',
            'default',
            'value' => 'a-' . str_pad(self::find()->max('id'), 4, '0', STR_PAD_LEFT) . '0'
        ];
        $rules['unique-default-domain_id'] = [
            'domain_id',
            'default',
            'value' => uniqid()
        ];
        $rules['match_domain_id'] = [
            'domain_id',
            'match',
            'pattern' => '/^[a-z0-9_-]+$/i'
        ];
        $rules['default-access_domain'] = [
            'access_domain',
            'default',
            'value' => self::getDefaultAccessDomain()
        ];
        $rules['default-access_read'] = [
            'access_read',
            'default',
            'value' => self::$_all
        ];
        $rules['default-access_update-delete'] = [
            [
                'access_update',
                'access_delete',
            ],
            'default',
            'value' => self::getDefaultAccessUpdateDelete()
        ];
        $rules['default-publish-expire_at'] = [
            [
                'publish_at',
                'expire_at'
            ],
            'default',
            'value' => null
        ];
        $rules['date-publish-expire_at'] = [
            [
                'publish_at',
                'expire_at'
            ],
            'date',
            'format' => 'yyyy-MM-dd HH:mm'
        ];
        $rules['compare-expire-publish_at'] = [
            'expire_at',
            'compare',
            'compareAttribute' => 'publish_at',
            'operator' => '>',
            'type' => 'datetime'
        ];


        return $rules;
    }

    /**
     * Global route needs empty request param
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->route === Cell::GLOBAL_ROUTE) {
                $this->request_param = Cell::EMPTY_REQUEST_PARAM;
            }

            // ensure lowercase language id
            $this->access_domain = mb_strtolower($this->access_domain);

            return true;
        } else {
            return false;
        }
    }


    /**
     * @return array
     */
    public static function optsStatus()
    {
        return [
            0 => \Yii::t('widgets', 'Offline'),
            1 => \Yii::t('widgets', 'Online'),
        ];
    }

    /**
     * @return array
     */
    public static function optsWidgetTemplateId()
    {
        return ArrayHelper::map(WidgetTemplate::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(WidgetTemplate::className(), ['id' => 'widget_template_id']);
    }

    /**
     * @return string
     */
    public function getViewFile()
    {
        $file = '/' . \Yii::getAlias('@runtime') . '/' . md5($this->template->twig_template) . '.twig';
        file_put_contents($file, $this->template->twig_template);

        return $file;
    }

    public function getFrontendRoute()
    {
        $mapping = \Yii::$app->controller->module->frontendRouteMap[$this->route] ?? false;

        if ($mapping) {
            return Url::to(
                [
                    '/' . $mapping,
                    'pageId' => $this->request_param,
                    '#' => 'widget-' . $this->domain_id
                ]);
        }

        return false;
    }
}
