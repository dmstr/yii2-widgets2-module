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
 * @property string buttonText
 * @property array options
 * @property string method
 * @property array params
 * @property array|string url
 *
 * @property JsExpression successCallback
 * @property JsExpression errorCallback
 *
 */
class AjaxButton extends Widget
{

    public $buttonText;
    public $options;
    public $method = 'post';
    public $params = [];
    public $url = [''];

    public $errorCallback = false;
    public $successCallback = false;


    public function init()
    {
        parent::init();

        $this->registerAssets();

        $this->options['data-ajax-button-id'] = $this->id;
        $this->options['data-method'] = $this->method;
        $this->options['data-params'] = $this->params;
        $this->options['data-url'] = Url::to($this->url);
    }

    /**
     * @return string
     */
    public function run()
    {
        return Html::button($this->buttonText, $this->options);
    }

    protected function registerAssets()
    {

        if ($this->errorCallback === false) {
            $this->errorCallback = new JsExpression('function(xhr) {if (xhr.status === 404) {button.addClass("btn-danger").html("Error")}}');
        }

        $this->view->registerJs(<<<JS
$(document).unbind('click').on('click','button[data-ajax-button-id]', 'click', function() {
    var button = $(this);
    $.ajax({
        url : button.data('url'),
        type : button.data('method'),
        data : button.data('params'),
        {$this->assignCallback($this->errorCallback, 'error')}
        {$this->assignCallback($this->successCallback, 'success')}
    });
});
JS
        );
    }

    /**
     * @param $callback
     * @param $type
     * @return string
     */
    protected function assignCallback($callback, $type)
    {
        if ($callback === false || empty($callback)) {
            return '';
        }
        return "{$type} :  {$callback},";

    }
}