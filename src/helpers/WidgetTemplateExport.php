<?php

namespace hrzg\widget\helpers;

use hrzg\widget\models\crud\base\WidgetTemplate;
use yii\base\BaseObject;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * --- PUBLIC PROPERTIES ---
 *
 * @property string $exportDirectory
 * @property string $templateFilename
 * @property string $schemaFilename
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
     * Export directory of the generated tar file
     * If this is set with an alias, it will be automatically resolved later on
     *
     * @var string
     */
    public $exportDirectory = '@runtime/tmp/dmstr/widgets/templates';

    /**
     * Name of the file which is generated from the content of the attribute $twig_template from the widget template
     * The name must contain the file extension
     *
     * @var string
     */
    public $templateFilename = self::TEMPLATE_FILE;

    /**
     * Name of the file which is generated from the content of the attribute $json_schema from the widget template
     * The name must not contain the file extension
     *
     * @var string
     */
    public $schemaFilename = self::SCHEMA_FILE;

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

        // Ensure that the export directory for the generated files is set
        if (empty($this->exportDirectory)) {
            throw new InvalidConfigException('$exportDirectory must be set');
        }

        // Check if instance of widget template is not a new record or a new instance
        if ($this->widgetTemplate instanceof WidgetTemplate && $this->widgetTemplate->getIsNewRecord() === true) {
            throw new InvalidConfigException('Widget template must be saved at least once');
        }

        // Set tar name to widget template name if not set
        if (empty($this->tarFileName)) {
            $this->tarFileName = Inflector::slug($this->getWidgetTemplate()->name) . '.tar';
        }

        // Make sure that if an alias is set, it is resolved correctly
        $this->exportDirectory = \Yii::getAlias($this->exportDirectory);

        // Create export directory if not exists
        if (FileHelper::createDirectory($this->exportDirectory) === false) {
            throw new InvalidConfigException("Error while creating directory at: $this->exportDirectory");
        }
    }

    /**
     * Absolute file path to the tar file
     *
     * @return string
     */
    public function getTarFilePath(): string
    {
        return $this->exportDirectory . DIRECTORY_SEPARATOR . $this->tarFileName;
    }

    /**
     * Absolute file path to the template file
     *
     * @return string
     */
    protected function getTemplateFilePath(): string
    {
        return $this->exportDirectory . DIRECTORY_SEPARATOR . $this->templateFilename;
    }

    /**
     * Absolute file path to the twig file
     *
     * @return string
     */
    protected function getSchemaFilePath(): string
    {
        return $this->exportDirectory . DIRECTORY_SEPARATOR . $this->schemaFilename;
    }

    /**
     * Absolute file path to the meta file
     *
     * @return string
     */
    protected function getMetaFilePath(): string
    {
        return $this->exportDirectory . DIRECTORY_SEPARATOR . self::META_FILE;
    }

    /**
     * Generate a tar file with the following contents
     *  - template file (twig): the content from the twig_template attribute of the given widget template
     *  - schema file (json): the content from the twig_template attribute of the given widget template
     *  - meta file (json): this contains some information about the export
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function generateTar(): bool
    {
        // Remove existing tar file
        if (is_file($this->getTarFilePath()) && unlink($this->getTarFilePath()) === false) {
            return false;
        }

        // Generate needed files
        if ($this->generateTemplateFile() === false) {
            throw new ErrorException('Error while creating template file');
        }
        if ($this->generateSchemaFile() === false) {
            throw new ErrorException('Error while creating schema file');
        }
        if ($this->generateMetaFile() === false) {
            throw new ErrorException('Error while creating schema file');
        }

        // Create the tar archive
        $phar = new \PharData($this->getTarFilePath());
        // Add generated files
        $phar->addFile($this->getTemplateFilePath(), $this->templateFilename);
        $phar->addFile($this->getSchemaFilePath(), $this->schemaFilename);
        $phar->addFile($this->getMetaFilePath(), self::META_FILE);

        // Remove generated files
        if (is_file($this->getTemplateFilePath()) && unlink($this->getTemplateFilePath()) === false) {
            return false;
        }
        if (is_file($this->getSchemaFilePath()) && unlink($this->getSchemaFilePath()) === false) {
            return false;
        }
        if (is_file($this->getMetaFilePath()) && unlink($this->getMetaFilePath()) === false) {
            return false;
        }

        return true;
    }

    /**
     * Generate a twig file based on the value of the widget template $twig_template property
     *
     * @return bool
     */
    protected function generateTemplateFile(): bool
    {
        return file_put_contents($this->getTemplateFilePath(), $this->widgetTemplate->twig_template) !== false;
    }

    /**
     * Generate a json file based on the value of the widget template $json_schema property
     *
     * @return bool
     */
    protected function generateSchemaFile(): bool
    {
        return file_put_contents($this->getSchemaFilePath(), $this->widgetTemplate->json_schema) !== false;
    }

    /**
     * Generate a json file based on the value of the widget template $json_schema property
     *
     * @return bool
     */
    protected function generateMetaFile(): bool
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
        return file_put_contents($this->getMetaFilePath(), Json::encode($data)) !== false;
    }

}
