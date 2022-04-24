<?php

namespace hrzg\widget\models;

use hrzg\widget\helpers\WidgetTemplateExport;
use hrzg\widget\models\crud\WidgetTemplate;
use hrzg\widget\widgets\TwigTemplate;
use yii\base\ErrorException;
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

    protected $_fileInfos = [];

    /**
     * Import extract directory of the generated tar file
     * If this is set with an alias, it will be automatically resolved later on
     *
     * @var string
     */
    public $importDirectory = '@runtime/tmp/dmstr/widgets/templates/import';

    /**
     * @return void
     * @throws \yii\base\Exception if the export directory could not be created due to an php error
     * @throws \yii\base\InvalidConfigException if either the import directory is not set or the export directory cannot
     * be created due to permission errors
     */
    public function init()
    {
        parent::init();

        // Ensure that the import directory for the generated files is set
        if (empty($this->importDirectory)) {
            throw new InvalidConfigException('$importDirectory must be set');
        }

        // Make sure that if an alias is set, it is resolved correctly
        $this->importDirectory = \Yii::getAlias($this->importDirectory);

        // recreate import directory
        try {
            FileHelper::removeDirectory($this->importDirectory);
        } catch (ErrorException $e) {
            \Yii::error($e->getMessage());
            throw new InvalidConfigException("Error while recreating directory at: $this->importDirectory");
        }
        if (FileHelper::createDirectory($this->importDirectory) === false) {
            throw new InvalidConfigException("Error while creating directory at: $this->importDirectory");
        }
    }

    public function rules()
    {
        return [
            ['tarFiles', 'file', 'skipOnEmpty' => false, 'extensions' => 'tar', 'maxFiles' => 20]
        ];
    }

    public function upload(): bool
    {
        if ($this->validate()) {
            foreach ($this->tarFiles as $file) {
                if ($file->saveAs($this->importDirectory . DIRECTORY_SEPARATOR . $file->baseName . '.' . $file->extension) === false) {
                    $this->addError('tarFiles', \Yii::t('widgets', 'Error while saving file'));
                }
            }
            return true;
        }

        return false;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['tarFiles'] = \Yii::t('widgets', 'Files');
        return $attributeLabels;
    }

    public function removeImportDirectory(): bool
    {
        try {
            FileHelper::removeDirectory($this->importDirectory);
            return true;
        } catch (\ErrorException $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }

    protected function extractTar(string $filepath, string $extractDirectory): bool
    {
        return (new \PharData($filepath))->extractTo($extractDirectory);
    }

    public function extractFiles(): bool
    {
        foreach ($this->tarFiles as $file) {
            $filepath = $this->importDirectory . DIRECTORY_SEPARATOR . $file->baseName . '.' . $file->extension;
            if ($this->extractTar($filepath, $this->extractDirectory($file->baseName)) === false) {
                $this->addError('tarFiles', \Yii::t('widgets', 'Error while extracting file'));
                return false;
            }
        }
        return true;
    }

    protected function extractDirectory(string $filename): string
    {
        return $this->importDirectory . DIRECTORY_SEPARATOR . Inflector::slug(basename($filename));
    }

    public function import()
    {
        $transaction = WidgetTemplate::getDb()->beginTransaction();
        if ($transaction && $transaction->getIsActive()) {
            foreach ($this->tarFiles as $file) {
                $extractDirectory = $this->extractDirectory($file->baseName);
                if ($this->importTemplateByDirectory($extractDirectory) === false) {
                    $this->addError('tarFiles', \Yii::t('widgets', 'Error while importing widget template'));
                    $transaction->rollBack();
                    return false;
                }

                FileHelper::removeDirectory($extractDirectory);
            }
            $transaction->commit();
            return true;
        }
        return false;
    }

    protected function importTemplateByDirectory(string $extractDirectory): bool
    {
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
            \Yii::error($model->getErrors());
            return false;
        }
        return true;
    }

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

    protected function templateContent(string $extractDirectory): string
    {
        $templateFilename = $extractDirectory . DIRECTORY_SEPARATOR . WidgetTemplateExport::TEMPLATE_FILE;
        $fallbackTemplate = '{# not set #}';
        if (is_file($templateFilename)) {
            return file_get_contents($templateFilename) ?: $fallbackTemplate;
        }

        return $fallbackTemplate;
    }

    protected function schemaContent(string $extractDirectory): string
    {
        $schemaFilename = $extractDirectory . DIRECTORY_SEPARATOR . WidgetTemplateExport::SCHEMA_FILE;
        $fallbackSchema = '{}';
        if (is_file($schemaFilename)) {
            return file_get_contents($schemaFilename) ?: $fallbackSchema;
        }

        return $fallbackSchema;
    }
}
