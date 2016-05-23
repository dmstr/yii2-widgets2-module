widget Module
===============

Yii 2.0 Framework Widget Manager

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hrzg/yii2-widgets2-module "*"
```

or add

```
"hrzg/yii2-widgets2-module": "*"
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


Usage
-----

Once the extension is installed, simply use it in your code by  :

### Layout

Example with `yii2-prototype-module`

- [Yii 2.0 Twig extension](https://github.com/yiisoft/yii2-twig/tree/master/docs/guide)

```
{{ use ('hrzg/widget/widgets') }}
{{ widget_container_widget({id: 'main'}) }}
```

### Widget

Standard Twig widget `hrzg\widget\widgets\TwigTemplate`


### JSON

- [JSON schema editor](https://github.com/jdorn/json-editor)


### Widget example

```
Basic

{
    "title": "Basic",
    "type": "object",
    "properties": {
        "headline": {
            "type": "string"
        },
        "text": {
            "type": "string"
        },
    }
}
```

```
<h2>{{ headline }}</h2>
<p>{{ text }}</p>
```

For more examples, please see the [docs](./docs)
