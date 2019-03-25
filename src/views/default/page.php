<?php

use dmstr\modules\prototype\widgets\TwigWidget;
use hrzg\widget\models\crud\WidgetPage;

/**
 * @var WidgetPage $widget_page
 */

echo TwigWidget::widget(['key' => $widget_page->uuid]);