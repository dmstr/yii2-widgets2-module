Yii2 Widget Module
===============

[![Latest Stable Version](https://poser.pugx.org/dmstr/yii2-widgets2-module/v/stable.svg)](https://packagist.org/packages/dmstr/yii2-widgets2-module) 
[![Total Downloads](https://poser.pugx.org/dmstr/yii2-widgets2-module/downloads.svg)](https://packagist.org/packages/dmstr/yii2-widgets2-module)
[![License](https://poser.pugx.org/dmstr/yii2-widgets2-module/license.svg)](https://packagist.org/packages/dmstr/yii2-widgets2-module)

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

Config
---

```
...
'modules => [
	'widgets' => [
		'class' => '\hrzg\widget\Module',
		'layout' => '@backend/views/layouts/main',
		'playgroundLayout' => '@frontend/views/layouts/main',
		'dateBasedAccessControl' => true,
		'datepickerMinutes' => false,
		'timezone' => 'Europe/Berlin',
		// set ajax option for JsonEditor
		'allowAjaxInSchema' => false,
        // If true, the json content properties will be validated against the json schema from the widget_template.
        // To be BC the default is false, but you should enable it
		'validateContentSchema' => false
	]
]
...
```




Settings
---

`availablePhpClasses`
`availableFrontendPhpClasses` - Templates with one of the listed classes in this setting will be outputed in cell widgets dropdown for available templates (default: hrzg\widget\widgets\TwigTemplate)


Usage
---

Once the extension is installed, simply use it in your code by :

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

### Settings

**Section:** widgets

**Key:** availablePhpClasses

**Type:** JSON

####Example:
 
`{"hrzg\\widget\\widgets\\TwigTemplate": "Twig layout"}`

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


Upgrading
---------

### from 0.2 to 0.3

    {{ cell_widget({id: 'header'}) }}

RBAC
---

#### Available Permission items

Name | Description
--- | ---
widgets_default_index | Widgets Manager
widgets_crud_api | Widgets CRUD API
widgets_crud_widget | Widgets CRUD Content
widgets_crud_widget_create | Widgets CRUD Content Create
widgets_crud_widget_delete | Widgets CRUD Content Delete
widgets_crud_widget_index | Widgets CRUD Content Index
widgets_crud_widget_update | Widgets CRUD Content Update
widgets_crud_widget_view | Widgets CRUD Content View
widgets_crud_widget-template | Widgets CRUD Template
widgets_test | Widgets TEST Playground
widgets-cell-edit | Frontend editing
widgets | Widgets Module


Settings
---

<table>
    <thead>
        <tr>
            <th>Section</th>
            <th>Key</th>
            <th>Value</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>widgets</td>
            <td>ckeditor.config</td>
            <td>
<pre>
    {
      "height": "4000px",
      "toolbar": [
        ["Format"],
        ["Link", "Image", "Table", "-", "NumberedList", "BulletedList", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
        ["Source"],
        "/", ["Bold", "Italic", "Underline", "StrikeThrough", "-", "RemoveFormat", "-", "Undo", "Redo", "-", "Paste", "PasteText", "PasteFromWord", "-", "Cut", "Copy", "Find", "Replace", "-", "Outdent", "Indent", "-", "Print"]
      ]
    }
</pre>
            </td>
            <td>OBJECT/JSON</td>
        </tr>
    </tbody>
</table>
