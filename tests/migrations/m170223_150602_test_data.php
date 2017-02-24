<?php

use yii\db\Migration;

class m170223_150602_test_data extends Migration
{
    public function up()
    {
        $this->execute("
        INSERT INTO `hrzg_widget_template` (`id`, `name`, `php_class`, `json_schema`, `twig_template`)
        VALUES
            (1, '01 - Simple HTML', 'hrzg\\widget\\widgets\\TwigTemplate', '{\n    \"title\": \"HTML Widget\",\n    \"type\": \"object\",\n    \"properties\": {\n        \"content\": {\n            \"type\": \"array\",\n            \"format\": \"tabs\",\n            \"title\": \"HTML\",\n            \"items\": {\n                \"type\": \"object\",\n                \"title\": \"Code\",\n                \"properties\": {\n                    \"code\": {\n                        \"type\": \"string\",\n                        \"default\": \"Switch to source mode and paste your iFrames or plain HTML code\",\n                        \"format\": \"html\",\n                        \"options\": {\n                            \"wysiwyg\": true\n                        }\n                    }\n                }\n            }\n        }\n    }\n}', '<section class=\"widget-wrapper\">\r\n    <div class=\"container\">\r\n    {% for con in content %}\r\n        <div>{{ con.code|raw }}</div>\r\n    {% endfor %}\r\n    </div>\r\n</section>');

        INSERT INTO `hrzg_widget_content` (`id`, `status`, `domain_id`, `widget_template_id`, `default_properties_json`, `route`, `request_param`, `container_id`, `rank`, `access_owner`, `access_domain`, `access_read`, `access_update`, `access_delete`, `created_at`, `updated_at`)
        VALUES
            (1, '1', '58b00134b2d86', 1, '{\"content\":[{\"code\":\"Switch to source mode and paste your iFrames or plain HTML code\"}]}', 'app/site/index', '', 'main', 'a-58b00134', '', 'en', '', '', '', '2017-02-24 09:47:32', '2017-02-24 09:47:32');
        ");
    }

    public function down()
    {
        echo "m170223_150602_test_data cannot be reverted.\n";

        return false;
    }
}
