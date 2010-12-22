SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `studydeck` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `studydeck` ;

-- -----------------------------------------------------
-- Table `studydeck`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(127) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `list_of_fears` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `deck_name` VARCHAR(127) NOT NULL ,
  `privacy` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `quiz_count` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `product_id` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_d` (`user_id` ASC) ,
  INDEX `product_id_d` (`product_id` ASC) ,
  CONSTRAINT `user_id_d`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `product_id_d`
    FOREIGN KEY (`product_id` )
    REFERENCES `studydeck`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`products`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`products` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `deck_id` INT NULL DEFAULT NULL ,
  `name` VARCHAR(127) NOT NULL ,
  `price` DECIMAL(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `deck_id_p` (`deck_id` ASC) ,
  CONSTRAINT `deck_id_p`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`deck_comments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`deck_comments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `comment` TEXT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `user_id` INT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_dc` (`user_id` ASC) ,
  INDEX `deck_id_dc` (`deck_id` ASC) ,
  CONSTRAINT `user_id_dc`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_dc`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`my_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`my_decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `quiz_count` INT NOT NULL ,
  `type` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_md` (`user_id` ASC) ,
  INDEX `deck_id_md` (`deck_id` ASC) ,
  CONSTRAINT `user_id_md`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_md`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`cards`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`cards` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `card_order` INT NOT NULL ,
  `question` TEXT NOT NULL ,
  `answer` TEXT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `deck_id_c` (`deck_id` ASC) ,
  CONSTRAINT `deck_id_c`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`ratings` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `rating` INT NOT NULL ,
  `card_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_r` (`user_id` ASC) ,
  INDEX `card_id_r` (`card_id` ASC) ,
  CONSTRAINT `user_id_r`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_r`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeck`.`cards` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_g` (`user_id` ASC) ,
  CONSTRAINT `user_id_g`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`group_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`group_decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group_id_gd` (`group_id` ASC) ,
  INDEX `deck_id_gd` (`deck_id` ASC) ,
  INDEX `user_id_gd` (`user_id` ASC) ,
  CONSTRAINT `group_id_gd`
    FOREIGN KEY (`group_id` )
    REFERENCES `studydeck`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_gd`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gd`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`group_members`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`group_members` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group_id_gm` (`group_id` ASC) ,
  INDEX `user_id_gm` (`user_id` ASC) ,
  CONSTRAINT `group_id_gm`
    FOREIGN KEY (`group_id` )
    REFERENCES `studydeck`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gm`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`hints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`hints` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `card_id` INT NOT NULL ,
  `hint` VARCHAR(255) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_h` (`user_id` ASC) ,
  INDEX `card_id_h` (`card_id` ASC) ,
  CONSTRAINT `user_id_h`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_h`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeck`.`cards` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`results`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`results` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `card_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `last_guess` INT NOT NULL ,
  `total_correct` INT NOT NULL ,
  `total_incorrect` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_rs` (`user_id` ASC) ,
  INDEX `card_id_rs` (`card_id` ASC) ,
  CONSTRAINT `user_id_rs`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_rs`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeck`.`cards` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`my_answers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`my_answers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `card_id` INT NOT NULL ,
  `my_answer` TEXT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_ma` (`user_id` ASC) ,
  INDEX `card_id_ma` (`card_id` ASC) ,
  CONSTRAINT `user_id_ma`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_ma`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeck`.`cards` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tag` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`deck_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`deck_tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `deck_id` INT NOT NULL ,
  `tag_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `deck_id_dt` (`deck_id` ASC) ,
  INDEX `tag_id_dt` (`tag_id` ASC) ,
  CONSTRAINT `deck_id_dt`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeck`.`decks` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `tag_id_dt`
    FOREIGN KEY (`tag_id` )
    REFERENCES `studydeck`.`tags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`search_index`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`search_index` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `association_key` INT(11) NOT NULL ,
  `model` VARCHAR(128) NOT NULL ,
  `data` LONGTEXT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `studydeck`.`temp_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`temp_users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(127) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `list_of_fears` VARCHAR(255) NULL DEFAULT NULL ,
  `confirmation_code` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`payments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `amount` DECIMAL NOT NULL ,
  `transaction_id` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_p` (`user_id` ASC) ,
  CONSTRAINT `user_id_p`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeck`.`purchased_products`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeck`.`purchased_products` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `payment_id` INT NOT NULL ,
  `product_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `payment_id_p` (`payment_id` ASC) ,
  INDEX `product_id_p` (`product_id` ASC) ,
  INDEX `user_id_pr` (`user_id` ASC) ,
  CONSTRAINT `payment_id_p`
    FOREIGN KEY (`payment_id` )
    REFERENCES `studydeck`.`payments` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `product_id_p`
    FOREIGN KEY (`product_id` )
    REFERENCES `studydeck`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_pr`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeck`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
