SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `flashcards` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `flashcards`;

-- -----------------------------------------------------
-- Table `flashcards`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`users` (
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
-- Table `flashcards`.`decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`decks` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `deck_name` VARCHAR(127) NOT NULL ,
  `privacy` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  `view_count` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  `description` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_d` (`user_id` ASC) ,
  CONSTRAINT `user_id_d`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`deck_comments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`deck_comments` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_dc`
    FOREIGN KEY (`deck_id` )
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`my_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`my_decks` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_md`
    FOREIGN KEY (`deck_id` )
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`cards`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`cards` (
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
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`ratings` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_r`
    FOREIGN KEY (`card_id` )
    REFERENCES `flashcards`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`groups`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_name` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `user_id` INT NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_g` (`user_id` ASC) ,
  CONSTRAINT `user_id_g`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`group_decks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`group_decks` (
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
    REFERENCES `flashcards`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `deck_id_gd`
    FOREIGN KEY (`deck_id` )
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gd`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`group_members`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`group_members` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `group_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `group_id_gm` (`group_id` ASC) ,
  INDEX `user_id_gm` (`user_id` ASC) ,
  CONSTRAINT `group_id_gm`
    FOREIGN KEY (`group_id` )
    REFERENCES `flashcards`.`groups` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_gm`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`hints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`hints` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_h`
    FOREIGN KEY (`card_id` )
    REFERENCES `flashcards`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`results`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`results` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_rs`
    FOREIGN KEY (`card_id` )
    REFERENCES `flashcards`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`my_answers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`my_answers` (
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
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `card_id_ma`
    FOREIGN KEY (`card_id` )
    REFERENCES `flashcards`.`cards` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tag` VARCHAR(127) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `flashcards`.`deck_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `flashcards`.`deck_tags` (
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
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `tag_id_dt`
    FOREIGN KEY (`tag_id` )
    REFERENCES `flashcards`.`tags` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;