<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\assets;

use dmstr\jsoneditor\JsonEditorPluginsAsset;
use dosamigos\ckeditor\CKEditorAsset;
use dosamigos\selectize\SelectizeAsset;
use uran1980\yii\assets\jQueryEssential\JqueryCookieAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__.'/web';

    /**
     * @var array
     */
    public $css = [
        'widgets.less'
    ];

    /**
     * @var array
     */
    public $js = [
        'cell.js'
    ];

    /**
     * @var array $publishOptions
     */
    public $publishOptions = [
        'forceCopy' => false,
    ];

    /**
     * @var array
     */
    public $depends = [
        CKEditorAsset::class,
        SelectizeAsset::class,
        \rmrevin\yii\fontawesome\AssetBundle::class,
        JqueryCookieAsset::class,
        JsonEditorPluginsAsset::class,
        BootstrapPluginAsset::class
    ];
}
