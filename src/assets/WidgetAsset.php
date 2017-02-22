<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\assets;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__.'/web';

    public $css = [
        'widgets.less'
    ];

    public $js = [
        'cell.js'
    ];

    public $depends = [
        'dosamigos\ckeditor\CKEditorAsset',
        'beowulfenator\JsonEditor\SelectizeAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}