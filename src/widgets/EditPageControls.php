<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;


use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class EditPageControls
 *
 * @package hrzg\widget\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string|array $edit_page_url
 */
class EditPageControls extends Widget
{
    public $edit_page_url = [''];

    /**
     * @return string
     */
    public function run()
    {
        return Html::a(
            Yii::t('widgets', '{icon} Edit page', ['icon' => FA::icon(FA::_EDIT)]),
            $this->edit_page_url,
            [
                'class' => 'btn btn-xs btn-primary'
            ]);
    }
}