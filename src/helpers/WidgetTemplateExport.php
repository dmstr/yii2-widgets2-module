<?php

namespace hrzg\widget\helpers;

use hrzg\widget\models\crud\base\WidgetTemplate;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * --- PUBLIC PROPERTIES ---
 *
 * @property-write  WidgetTemplate $widgetTemplate
 * @property string $tarFileName
 *
 * --- MAGIC GETTERS ---
 *
 * @property-read string $tarFilePath
 *
 * @author Elias Luhr
 */
class WidgetTemplateExport extends BaseObject
{
    public const META_FILE = 'meta.json';
    public const TEMPLATE_FILE = 'template.twig';
    public const SCHEMA_FILE = 'schema.json';


    /**
     * Will be set to a tmp directory with a bit of randomness to prevent race conditions
     * @var string
     */
    protected $_exportDirectory;

    /**
     * Optional name for the tar file. If not set, the value of the $name attribute from the widget template is used.
     * The name must contain the file extension
     *
     * @var string
     */
    public $tarFileName;

    /**
     * Instance of the to be exported widget template instance
     *
     * @var WidgetTemplate
     */
    protected $_widgetTemplate;

    /**
     * @return WidgetTemplate
     */
    protected function getWidgetTemplate(): WidgetTemplate
    {
        return $this->_widgetTemplate;
    }

    /**
     * @param WidgetTemplate $widgetTemplate
     */
    public function setWidgetTemplate(WidgetTemplate $widgetTemplate): void
    {
        $this->_widgetTemplate = $widgetTemplate;
    }

    /**
     * @return void
     * @throws \yii\base\Exception if the export directory could not be created due to an php error
     * @throws \yii\base\InvalidConfigException if either the export directory is not set or the export directory cannot
     * be created due to permission errors or the widget template is configured incorrectly
     */
    public function init()
    {
        parent::init();

        // Check if instance of widget template is not a new record or a new instance
        if ($this->widgetTemplate instanceof WidgetTemplate && $this->widgetTemplate->getIsNewRecord() === true) {
            throw new InvalidConfigException('Widget template must be saved at least once');
        }

        // Set tar name to widget template name if not set
        if (empty($this->tarFileName)) {
            $this->tarFileName = Inflector::slug($this->getWidgetTemplate()->name) . '.tar';
        }

        $this->_exportDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('widget-template-export', false);

        // Create export directory if not exists
        if (FileHelper::createDirectory($this->_exportDirectory) === false) {
            throw new InvalidConfigException("Error while creating directory at: $this->_exportDirectory");
        }
    }

    /**
     * Absolute file path to the tar file
     *
     * @return string
     */
    public function getTarFilePath(): string
    {
        return $this->_exportDirectory . DIRECTORY_SEPARATOR . $this->tarFileName;
    }

    /**
     * Generate a tar file with the following contents
     *  - template file (twig): the content from the twig_template attribute of the given widget template
     *  - schema file (json): the content from the twig_template attribute of the given widget template
     *  - meta file (json): this contains some information about the export
     *
     * @return bool
     */
    public function generateTar(): bool
    {
        // Remove existing tar file
        if (is_file($this->getTarFilePath()) && unlink($this->getTarFilePath()) === false) {
            return false;
        }

        // Create the tar archive
        $phar = new \PharData($this->getTarFilePath());
        // Add files by string
        $phar->addFromString(self::TEMPLATE_FILE, $this->getWidgetTemplate()->twig_template);
        $phar->addFromString(self::SCHEMA_FILE, $this->getWidgetTemplate()->json_schema);
        $phar->addFromString(self::META_FILE, $this->metaFileContent());

        return true;
    }

    /**
     * Generate json file content based on the value of the widget template $json_schema property
     *
     * @return string
     */
    protected function metaFileContent(): string
    {
        $data = [
            'id' => $this->getWidgetTemplate()->id,
            'name' => $this->getWidgetTemplate()->name,
            'php_class' => $this->getWidgetTemplate()->php_class,
            'created_at' => $this->getWidgetTemplate()->created_at,
            'updated_at' => $this->getWidgetTemplate()->updated_at,
            'exported_at' => date('Y-m-d H:i:s'),
            'download_url' => \Yii::$app->getRequest()->getAbsoluteUrl()
        ];
        return Json::encode($data);
    }

}
