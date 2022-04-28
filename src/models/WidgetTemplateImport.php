<?php

namespace hrzg\widget\models;

use hrzg\widget\exceptions\WidgetTemplateCreateException;
use hrzg\widget\helpers\WidgetTemplateExport;
use hrzg\widget\models\crud\WidgetTemplate;
use hrzg\widget\widgets\TwigTemplate;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * --- PROPERTIES ---
 *
 * @property \yii\web\UploadedFile[] $tarFiles
 *
 * @author Elias Luhr
 */
class WidgetTemplateImport extends Model
{
    /**
     * @var \yii\web\UploadedFile[]
     */
    public $tarFiles;

    /**
     * Import extract directory of the generated tar file
     * If this is set with an alias, it will be automatically resolved later on
     *
     * @var string
     */
    public $baseImportDirectory = '@runtime/tmp/widgets/import';

    /**
     * @var string
     */
    protected $_importDirectory;

    /**
     * @var array
     */
    protected $_filenames = [];

    /**
     * @var bool
     */
    protected $_tmpDirectoryWasRemoved = false;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->_importDirectory = \Yii::getAlias($this->baseImportDirectory . DIRECTORY_SEPARATOR . uniqid('widget-template-import',
                false));
    }

    public function rules()
    {
        return [
            ['tarFiles', 'file', 'skipOnEmpty' => false, 'extensions' => 'tar', 'maxFiles' => 20]
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function upload(): bool
    {
        if ($this->validate()) {

            // Create export directory if not exists
            if (FileHelper::createDirectory($this->_importDirectory) === false) {
                throw new InvalidConfigException("Error while creating directory at: $this->_importDirectory");
            }
            foreach ($this->tarFiles as $file) {
                $filename = $this->_importDirectory . DIRECTORY_SEPARATOR . uniqid($file->baseName,
                        false) . '.' . $file->extension;
                if ($file->saveAs($filename) === false) {
                    $this->addError('tarFiles', \Yii::t('widgets', 'Error while uploading file'));
                } else {
                    $this->_filenames[] = $filename;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['tarFiles'] = \Yii::t('widgets', 'Files');
        return $attributeLabels;
    }

    /**
     * @param string $filepath
     * @param string $extractDirectory
     * @return bool
     */
    protected function extractTar(string $filepath, string $extractDirectory): bool
    {
        return (new \PharData($filepath))->extractTo($extractDirectory);
    }

    /**
     * @return bool
     */
    protected function extractFiles(): bool
    {
        foreach ($this->_filenames as $filename) {
            if ($this->extractTar($filename, $this->getExtractDirectory($filename)) === false) {
                $this->addError('tarFiles', \Yii::t('widgets', 'Error while extracting file'));
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getExtractDirectory(string $filename): string
    {
        return $this->_importDirectory . DIRECTORY_SEPARATOR . Inflector::slug(basename($filename));
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function import(): bool
    {
        $transaction = WidgetTemplate::getDb()->beginTransaction();
        if ($transaction && $transaction->getIsActive()) {
            $errors = true;
            foreach ($this->_filenames as $filename) {
                if ($this->importTemplateByDirectory($filename)) {
                    $errors = false;
                } else {
                    $errors = true;
                    break;
                }
            }
            if (!$errors) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
            }
        }
        return false;
    }

    /**
     * @param string $filename
     * @return bool
     */
    protected function importTemplateByDirectory(string $filename): bool
    {
        $extractDirectory = $this->getExtractDirectory($filename);
        $meta = $this->templateMeta($extractDirectory);
        $template = $this->templateContent($extractDirectory);
        $schema = $this->schemaContent($extractDirectory);

        $model = new WidgetTemplate([
            'name' => $meta['name'],
            'php_class' => $meta['php_class'],
            'twig_template' => $template,
            'json_schema' => $schema
        ]);

        if ($model->save() === false) {
            $this->addError('tarFiles', print_r($model->getErrors(), true));
            return false;
        }
        return true;
    }

    /**
     * @param string $extractDirectory
     * @return array
     */
    protected function templateMeta(string $extractDirectory): array
    {
        $metaFilename = $extractDirectory . DIRECTORY_SEPARATOR . WidgetTemplateExport::META_FILE;
        if (is_file($metaFilename)) {
            $content = file_get_contents($metaFilename);
            try {
                $data = Json::decode($content);

                return [
                    'name' => $data['name'] ?? basename($extractDirectory),
                    'php_class' => $data['php_class'] ?? TwigTemplate::class
                ];
            } catch (InvalidArgumentException $e) {
                \Yii::error($e->getMessage());
            }
        }
        return [
            'name' => basename($extractDirectory),
            'php_class' => TwigTemplate::class
        ];
    }

    /**
     * @param string $extractDirectory
     * @return string
     */
    protected function templateContent(string $extractDirectory): string
    {
        $templateFilename = $extractDirectory . DIRECTORY_SEPARATOR . WidgetTemplateExport::TEMPLATE_FILE;
        $fallbackTemplate = '{# not set #}';
        if (is_file($templateFilename)) {
            return file_get_contents($templateFilename) ?: $fallbackTemplate;
        }

        return $fallbackTemplate;
    }

    /**
     * @param string $extractDirectory
     * @return string
     */
    protected function schemaContent(string $extractDirectory): string
    {
        $schemaFilename = $extractDirectory . DIRECTORY_SEPARATOR . WidgetTemplateExport::SCHEMA_FILE;
        $fallbackSchema = '{}';
        if (is_file($schemaFilename)) {
            return file_get_contents($schemaFilename) ?: $fallbackSchema;
        }

        return $fallbackSchema;
    }

    /**
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     */
    public function uploadAndImport(): bool
    {
        if ($this->upload() && $this->extractFiles() && $this->import()) {
            if ($this->cleanupTmpDirectory()) {
                $this->_tmpDirectoryWasRemoved = true;
            }
            return true;
        }
        return false;
    }

    /**
     * @return bool
     * @throws \yii\base\ErrorException
     */
    protected function cleanupTmpDirectory(): bool
    {
        FileHelper::removeDirectory($this->_importDirectory);
        return !is_dir($this->_importDirectory);
    }

    /**
     * @return bool
     */
    public function getTmpDirectoryWasRemoved(): bool
    {
        return $this->_tmpDirectoryWasRemoved;
    }
}
