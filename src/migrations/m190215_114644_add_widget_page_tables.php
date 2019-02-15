<?php

use yii\db\Migration;

/**
 * Class m190215_114644_add_widget_page_tables
 */
class m190215_114644_add_widget_page_tables extends Migration
{

    /**
     * @return bool|void
     */
    public function up()
    {
        $table_prefix = Yii::$app->db->tablePrefix;
$this->execute(<<<SQL
CREATE TABLE IF NOT EXISTS `{$table_prefix}hrzg_widget_page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `view` VARCHAR(128) NOT NULL,
  `access_owner` INT NULL,
  `access_domain` VARCHAR(128) NULL,
  `access_read` VARCHAR(128) NULL,
  `access_update` VARCHAR(128) NULL,
  `access_delete` VARCHAR(128) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `{$table_prefix}hrzg_widget_page_translation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `widget_page_id` INT NOT NULL,
  `language` CHAR(5) NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `description` VARCHAR(255) NULL,
  `keywords` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_hrzg_widget_page_translation_hrzg_widget_page_idx` (`widget_page_id` ASC),
  UNIQUE INDEX `uq_widget_page_id_language0` (`widget_page_id` ASC, `language` ASC),
  CONSTRAINT `fk_hrzg_widget_page_translation_hrzg_widget_page`
    FOREIGN KEY (`widget_page_id`)
    REFERENCES `{$table_prefix}hrzg_widget_page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `{$table_prefix}hrzg_widget_page_meta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `widget_page_id` INT NOT NULL,
  `language` CHAR(5) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_hrzg_widget_page_meta_hrzg_widget_page1_idx` (`widget_page_id` ASC),
  UNIQUE INDEX `uq_widget_page_id_language0` (`widget_page_id` ASC, `language` ASC),
  CONSTRAINT `fk_hrzg_widget_page_meta_hrzg_widget_page1`
    FOREIGN KEY (`widget_page_id`)
    REFERENCES `{$table_prefix}hrzg_widget_page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
SQL
);
    }


    /**
     * @return false
     */
    public function down()
    {
        echo "m190215_114644_add_widget_page_tables cannot be reverted.\n";
        return false;
    }
}
