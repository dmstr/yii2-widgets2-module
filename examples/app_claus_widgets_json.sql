"id","name","json_schema","editor_settings","form"
1,"Block Widget","{
  \"title\": \"Block Widget\",
  \"type\": \"object\",
  \"properties\": {
    \"blocks\": {
      \"type\": \"array\",
      \"format\": \"table\",
      \"title\": \"Block\",
      \"uniqueItems\": true,
      \"items\": {
        \"type\": \"object\",
        \"title\": \"Block\",
        \"properties\": {
          \"above_subline\": {
            \"type\": \"string\"
          },
          \"headline\": {
            \"type\": \"string\"
          },
          \"subline\": {
            \"type\": \"string\"
          },
          \"image_url\": {
            \"type\": \"string\",
            \"format\": \"url\"
          },
          \"text_html\": {
              \"type\": \"string\",
              \"format\": \"html\",
              \"options\": {
                \"wysiwyg\": true
              }
            },
          \"button\": {
            \"type\": \"string\"
          }
        }
      }
    }
  }
}","",""
2,"Slider Widget","{
  \"title\": \"Slider Widget\",
  \"type\": \"object\",
  \"properties\": {
    \"slides\": {
      \"type\": \"array\",
      \"format\": \"table\",
      \"title\": \"Slides\",
      \"uniqueItems\": true,
      \"items\": {
        \"type\": \"object\",
        \"title\": \"Slide\",
        \"properties\": {
          \"above_subline\": {
            \"type\": \"string\"
          },
          \"picture_url\": {
            \"type\": \"string\",
            \"format\": \"url\"
          },
          \"headline\": {
            \"type\": \"string\"
          },
          \"subline\": {
            \"type\": \"string\"
          },
          \"text_html\": {
            \"type\": \"string\",
              \"format\": \"html\",
              \"options\": {
                \"wysiwyg\": true
              }
          }
        }
      }
    }
  }
}","",""
3,"Video Background Widget","{
  \"title\": \"Video Background Widget\",
  \"type\": \"object\",
  \"properties\": {
          \"youtube_id\": {
            \"type\": \"string\"
          },
          \"above_subline\": {
            \"type\": \"string\"
          },
          \"picture_url\": {
            \"type\": \"string\",
            \"format\": \"url\"
          },
          \"headline\": {
            \"type\": \"string\"
          },
          \"subline\": {
            \"type\": \"string\"
          },
          \"button\": {
            \"type\": \"string\"
          }
  }
}","",""
4,"Icon Slider Widget","{
  \"title\": \"Icon Slider Widget\",
  \"type\": \"object\",
  \"properties\": {
\"above_subline\": {
            \"type\": \"string\"
          },
          \"headline\": {
            \"type\": \"string\"
          },
          \"subline\": {
            \"type\": \"string\"
          },
    \"blocks\": {
      \"type\": \"array\",
      \"format\": \"table\",
      \"title\": \"Icons\",
      \"uniqueItems\": true,
      \"items\": {
        \"type\": \"object\",
        \"title\": \"Icons\",
        \"properties\": {
          \"picture_url\": {
            \"type\": \"string\",
            \"format\": \"url\"
          }
        }
      }
    }
  }
}
","",""
5,"Gallery Widget","{
  \"title\": \"Gallery Widget\",
  \"type\": \"object\",
  \"properties\": {
    \"blocks\": {
      \"type\": \"array\",
      \"format\": \"table\",
      \"title\": \"Images\",
      \"uniqueItems\": true,
      \"items\": {
        \"type\": \"object\",
        \"title\": \"Image\",
        \"properties\": {
          \"picture_url\": {
            \"type\": \"string\",
            \"format\": \"url\"
          },
          \"text_html\": {
            \"type\": \"string\",
              \"format\": \"html\",
              \"options\": {
                \"wysiwyg\": true
              }
          }
        }
      }
    }
  }
}
","",""
