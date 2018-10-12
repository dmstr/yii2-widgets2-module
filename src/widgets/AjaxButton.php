<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hrzg\widget\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class AjaxButton
 * @package hrzg\widget\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * --- PROPERTIES ---
 *
 * Content enclosed between button start and end tag
 * @property string content
 *
 * HTML tag attributes in terms of name value pairs
 * @property array options
 *
 * HTTP method to use for the request e.g. 'post', 'get', 'put' default is 'post'
 * @property string method
 *
 * Values which will be send in the request body
 * @property array params
 *
 * Url creating via Url::to [[yii\helpers\BaseUrl]]
 * @property array|string url
 *
 * Can be either string or array. If property is an array, items will be exploded and merged together as a string with ',' as a separator
 * @property array|string ajaxSettings
 *
 * In JS callbacks the 'button' variable can be used to reference to the ajax button itself
 * @property JsExpression successExpression
 * @property JsExpression errorExpression
 *
 * --- USAGE ---
 *
 *  Example usage of ajax button widget
 *
 * Basic usage
 *
 * ```
 *  hrzg\widget\widgets\AjaxButton::widget([
 *      'method' => 'get',
 *      'url' => ['/path/to/action','name' => 'key']
 *  ]);
 * ```
 *
 * Advanced usage
 * ```
 *  hrzg\widget\widgets\AjaxButton::widget([
 *      'method' => 'post',
 *      'params' => ['status' => 'active'],
 *      'url' => ['/path/to/action'],
 *      'options' => ['class' => 'btn btn-primary'],
 *      'errorExpression' => new JsExpression('function(resp,status,xhr) {if (xhr.status === 200) {button.addClass("btn-success").html("Success")}}'),
 *      'successExpression' => new JsExpression('function(xhr) {if (xhr.status === 404) {button.addClass("btn-danger").html("Error");console.error(xhr.responseJSON)}}')
 *  ]);
 *
 */
class AjaxButton extends Widget
{

    public $content;
    public $options;
    public $method = 'post';
    public $params = [];
    public $url;

    public $errorExpression = false;
    public $successExpression = false;

    public $ajaxSettings;

    public function init()
    {
        parent::init();

        $this->registerAssets();

        $this->options['data-ajax-button-id'] = $this->id;
        $this->options['data-ajax-button-method'] = $this->method;
        $this->options['data-ajax-button-params'] = $this->params;
        $this->options['data-ajax-button-url'] = Url::to($this->url);
    }

    /**
     * @return string
     *
     * Returns html button element with given text and options (html attributes)
     */
    public function run()
    {
        return Html::button($this->content, $this->options);
    }

    /**
     * @return void
     *
     * Registers JS for sending ajax request with given method to defined url
     */
    protected function registerAssets()
    {

        if (\is_array($this->ajaxSettings)) {
            $this->ajaxSettings = explode(',', $this->ajaxSettings);
        }

        $this->view->registerJs(<<<JS
$(document).on('click','button[data-ajax-button-id]', 'click', function() {
    var button = $(this);
    $.ajax({
        url : button.data('ajax-button-url'),
        type : button.data('ajax-button-method'),
        data : button.data('ajax-button-params'),
        {$this->jsCallback($this->errorExpression, 'error')}
        {$this->jsCallback($this->successExpression, 'success')}
        {$this->ajaxSettings}
    });
});
JS
        );
    }

    /**
     * @param $callbackString string
     * @param $type string
     * @return string
     *
     * For more informations on callbacks visit https://api.jquery.com/jQuery.ajax/
     */
    protected function jsCallback($callbackString, $type)
    {
        if (empty($callbackString)) {
            return '';
        }
        return "{$type} :  {$callbackString},";

    }
}