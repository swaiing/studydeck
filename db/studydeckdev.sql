SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `studydeckdev` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `studydeckdev`;

-- -----------------------------------------------------
-- Table `studydeckdev`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(127) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `username` VARCHAR(45) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `list_of_fears` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `deck_name` VARCHAR(127) NOT NULL ,
  `privacy` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `view_count` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_d` (`user_id` ASC) ,
  CONSTRAINT `user_id_d`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`deck_comments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`deck_comments` (
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
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_dc`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeckdev`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`my_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`my_decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_md` (`user_id` ASC) ,
  INDEX `deck_id_md` (`deck_id` ASC) ,
  CONSTRAINT `user_id_md`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_md`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeckdev`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`cards`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`cards` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `question` TEXT NOT NULL ,
  `answer` TEXT NOT NULL ,
  `deck_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `deck_id_c` (`deck_id` ASC) ,
  CONSTRAINT `deck_id_c`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeckdev`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`ratings` (
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
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_r`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeckdev`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_g` (`user_id` ASC) ,
  CONSTRAINT `user_id_g`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`group_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`group_decks` (
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
    REFERENCES `studydeckdev`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_gd`
    FOREIGN KEY (`deck_id` )
    REFERENCES `studydeckdev`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gd`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`group_members`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`group_members` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group_id_gm` (`group_id` ASC) ,
  INDEX `user_id_gm` (`user_id` ASC) ,
  CONSTRAINT `group_id_gm`
    FOREIGN KEY (`group_id` )
    REFERENCES `studydeckdev`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gm`
    FOREIGN KEY (`user_id` )
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`hints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`hints` (
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
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_h`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeckdev`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`results`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`results` (
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
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_rs`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeckdev`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`my_answers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`my_answers` (
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
    REFERENCES `studydeckdev`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_ma`
    FOREIGN KEY (`card_id` )
    REFERENCES `studydeckdev`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tag` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `studydeckdev`.`deck_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `studydeckdev`.`deck_tags` (
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
    REFERENCES `studydeckdev`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tag_id_dt`
    FOREIGN KEY (`tag_id` )
    REFERENCES `studydeckdev`.`tags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
