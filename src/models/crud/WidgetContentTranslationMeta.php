<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\models\crud;

use hrzg\widget\models\crud\base\WidgetTranslationMeta;
use yii\caching\TagDependency;


/**
 * Class WidgetContentTranslation
 * @package hrzg\widget\models\crud
 * @author Carsten Brandt <mail@cebe.cc>
 */
class WidgetContentTranslationMeta extends WidgetTranslationMeta
{
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
}