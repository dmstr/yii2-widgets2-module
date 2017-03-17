Yii2 Widget Module
===============

Widget manager using twig templates

Installation
---

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require dmstr/yii2-widgets2-module
```

to the require section of your `composer.json` file.


Testing
---

```
cd tests

docker-compose up -d

docker-compose run phpfpm bash

setup.sh
```


Settings
---

`availablePhpClasses`


Usage
---

Once the extension is installed, simply use it in your code by  :

### Layout

Example with `yii2-prototype-module`

- [Yii 2.0 Twig extension](https://github.com/yiisoft/yii2-twig/tree/master/docs/guide)
- [Twig documentation](http://twig.sensiolabs.org/documentation)

```
{{ use ('hrzg/widget/widgets') }}
{{ cell_widget({id: 'main'}) }}
```

### Widget

- Standard Twig widget `hrzg\widget\widgets\TwigTemplate`
- Char-Rank ordering `001`, `10`, `5`, `aa1`, `aa1.2`, `b0` (not numeric) 

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


For more examples, please see the [docs](./docs)


Copy widgets
---

**Console config**

If you use the `dmstr/yii2-pages-module`, set `pagesModule` to `true` and the widget `route` and `request_param` will be recognized.


**In case `pagesModule` to `true`**

*The copy process will only be successful if every widget which is placed on the default pages route `/pages/default/page` has the corresponding page object in the source language before copy. In short: If you have widgets in your database, placed on `route = /pages/default/page` and `request_param = page id` the page with that page id MUST exist. If not, the widget is an orphan and must be deleted before you are able to copy*

```
'controllerMap'       => [
	'copy-widgets' => [
		'class' => '\hrzg\widget\commands\CopyController',
		'pagesModule' => true
	]
]
```

**CLI**

Command: `./yii copy-widgets/language --sourceLanguage --destinationLanguage`

**Web UI**

Url: `/widgets/copy`

**RBAC permission**

`widgets_copy`

Also used to enable `access_domain` input with all available application languages. Default is `false`

**Usage**

* Go to -> `/widgets/copy`
* Select the source language (you can select the global domain if you have permissions to)
* Select the target language (you can select the global domain if you have permissions to)
* Start Copy



Upgrading
---------

### from 0.2 to 0.3

    {{ cell_widget({id: 'header'}) }}
