widget Module
===============

Yii 2.0 Framework Widget Manager

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require hrzg/yii2-widgets2-module
```

to the require section of your `composer.json` file.


Startup
-------

```
cd tests

docker-compose up -d

docker-compose run phpfpm bash

setup.sh
```


### Settings

`availablePhpClasses`


Usage
-----

Once the extension is installed, simply use it in your code by  :

### Layout

Example with `yii2-prototype-module`

- [Yii 2.0 Twig extension](https://github.com/yiisoft/yii2-twig/tree/master/docs/guide)
- [Twig documentation](http://twig.sensiolabs.org/documentation)

```
{{ use ('hrzg/widget/widgets') }}
{{ widget_container_widget({id: 'layout'}) }}
```

### Widget

Standard Twig widget `hrzg\widget\widgets\TwigTemplate`


### JSON

- [JSON schema editor](https://github.com/jdorn/json-editor)


### Widget example

#### Basic

```
{
    "title": "Basic",
    "type": "object",
    "properties": {
        "headline": {
            "type": "string",
            "default": "Avo vole tioma profitanto ts,"
        },
        "text_html": {
            "type": "string",
            "format": "html",
            "default": "Ja sub kiam aliu, fo unt fora danke helpverbo, dev bv tele kibi piedpilko.",
            "options": {
                "wysiwyg": true
            }
        }
    }
}
```

```
<h2>{{ headline }}</h2>
<p>{{ text_html }}</p>
```

:warning: Workaround required for editor asset.

```
<?php \franciscomaya\sceditor\SCEditorAsset::register($this) ?>
```

For more examples, please see the [docs](./docs)
