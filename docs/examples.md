APP CLAUS WIDGET TEMPLATES (JSON)
=================================

## Layout

```
{{ use ('hrzg/widget/widgets') }}
{{ cell_widget({id: 'header'}) }}
<div class="container">
    {{ cell_widget({id: 'container'}) }}
</div>
```


```
{{ use ('hrzg/widget/widgets') }}
{{ cell_widget({id: 'header'}) }}
<div class="row">
    <div class="col-sm-4">
        {{ cell_widget({id: 'block1'}) }}
    </div>
    <div class="col-sm-4">
        {{ cell_widget({id: 'block2'}) }}
    </div>
    <div class="col-sm-4">
        {{ cell_widget({id: 'block3'}) }}
    </div>
</div>
```

*Block Widget*
----------------

```
{
  "title": "Block Widget",
  "type": "object",
  "properties": {
    "blocks": {
      "type": "array",
      "format": "table",
      "title": "Block",
      "uniqueItems": true,
      "items": {
        "type": "object",
        "title": "Block",
        "properties": {
          "above_subline": {
            "type": "string"
          },
          "headline": {
            "type": "string"
          },
          "subline": {
            "type": "string"
          },
          "image_url": {
            "type": "string"
          },
          "text_html": {
            "type": "string"
          },
          "button": {
            "type": "string"
          }
        }
      }
    }
  }
}
```

*Icon Slider Widget*
----------------

```
{
  "title": "Icon Slider Widget",
  "type": "object",
  "properties": {
"above_subline": {
            "type": "string"
          },
          "headline": {
            "type": "string"
          },
          "subline": {
            "type": "string"
          },
    "blocks": {
      "type": "array",
      "format": "table",
      "title": "Icons",
      "uniqueItems": true,
      "items": {
        "type": "object",
        "title": "Icons",
        "properties": {
          "picture_url": {
            "type": "string",
            "format": "url"
          }
        }
      }
    }
  }
}
```

*Slider Widget*
---------------

```
{
  "title": "Slider Widget",
  "type": "object",
  "properties": {
    "slides": {
      "type": "array",
      "format": "table",
      "title": "Slides",
      "uniqueItems": true,
      "items": {
        "type": "object",
        "title": "Slide",
        "properties": {
          "above_subline": {
            "type": "string"
          },
          "picture_url": {
            "type": "string",
            "format": "url"
          },
          "headline": {
            "type": "string"
          },
          "subline": {
            "type": "string"
          },
          "text_html": {
              "type": "string",
              "format": "html",
              "options": {
                 "wysiwyg": true
              }
          }
        }
      }
    }
  }
}
```

*Video Background Widget*
----------------

```
{
  "title": "Video Background Widget",
  "type": "object",
  "properties": {
          "youtube_id": {
            "type": "string"
          },
          "above_subline": {
            "type": "string"
          },
          "picture_url": {
            "type": "string",
            "format": "url"
          },
          "headline": {
            "type": "string"
          },
          "subline": {
            "type": "string"
          },
          "button": {
            "type": "string"
          }
  }
}
```

*Gallery Widget*
----------------

```
{
  "title": "Gallery Widget",
  "type": "object",
  "properties": {
    "blocks": {
      "type": "array",
      "format": "table",
      "title": "Images",
      "uniqueItems": true,
      "items": {
        "type": "object",
        "title": "Image",
        "properties": {
          "picture_url": {
            "type": "string",
            "format": "html"
          },
          "text_html": {
            "type": "string",
            "format": "html",
            "options": {
               "wysiwyg": true
            }
          }
        }
      }
    }
  }
}

```

*Single type schema*
--------------------

```json
{
  "type": "string"
}
```

```twig
{{ value }}
```
