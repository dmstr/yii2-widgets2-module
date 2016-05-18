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

```
{{ use ('hrzg/widget/widgets') }}
{{ widget_container_widget({id: 'main'}) }}
```