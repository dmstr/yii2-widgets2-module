<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\models;


use hrzg\widget\models\crud\WidgetContent;
use Yii;
use yii\base\Model;

/**
 * @package hrzg\widget\widgets\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class ReOrderWidgets extends Model
{
    public $containerId;
    public $orderedWidgetIds = [];

    public function formName()
    {
        return '';
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['required'] = [
            [
                'containerId',
                'orderedWidgetIds'
            ],
            'required'
        ];
        return $rules;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function reorder()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $newRank = 0;

        $wasSuccessful = true;
        foreach ($this->orderedWidgetIds as $widgetContentId) {
            $widgetModel = WidgetContent::findOne($widgetContentId);
            if ($widgetModel) {
                $newRank++;
                $widgetModel->container_id = $this->containerId;
                $widgetModel->rank = WidgetContent::rankByData($newRank);
                if (!$widgetModel->save()) {
                    $wasSuccessful = false;
                    break;
                }
            } else {
                $wasSuccessful = false;
                break;
            }

        }
        if ($wasSuccessful) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
        return $wasSuccessful;
    }
}
