<?php

namespace hrzg\widget\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class JqueryCookieAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-cookie';
    public $js = [
        'jquery.cookie.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
