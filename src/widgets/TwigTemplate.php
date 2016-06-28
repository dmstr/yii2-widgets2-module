<?php
/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace hrzg\widget\widgets;

use yii\base\Widget;

class TwigTemplate extends Widget
{
    private $_view;
    private $_properties = [];

    public function run()
    {
        try {
            $output = $this->renderFile($this->_view, $this->_properties);

            return $output;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getProperties()
    {
        return $this->_properties;
    }

    public function setProperties($value)
    {
        $this->_properties = $value;
    }

    public function setView($value)
    {
        $this->_view = $value;
    }
}
