SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `flashcards`.`decks` ADD COLUMN `product_id` INT(11) NULL DEFAULT NULL  AFTER `description` , 
  ADD CONSTRAINT `product_id_d`
  FOREIGN KEY (`product_id` )
  REFERENCES `flashcards`.`products` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `product_id_d` (`product_id` ASC) ;

CREATE  TABLE IF NOT EXISTS `flashcards`.`products` (
  `id` INT(11) NOT NULL ,
  `deck_id` INT(11) NULL DEFAULT NULL ,
  `name` VARCHAR(127) NOT NULL ,
  `price` DECIMAL NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `deck_id_p` (`deck_id` ASC) ,
  CONSTRAINT `deck_id_p`
    FOREIGN KEY (`deck_id` )
    REFERENCES `flashcards`.`decks` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `flashcards`.`payments` (
  `id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `amount` DECIMAL NOT NULL ,
  `transaction_id` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_p` (`user_id` ASC) ,
  CONSTRAINT `user_id_p`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE  TABLE IF NOT EXISTS `flashcards`.`products_purchased` (
  `id` INT(11) NOT NULL ,
  `payment_id` INT(11) NOT NULL ,
  `product_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `payment_id_p` (`payment_id` ASC) ,
  INDEX `product_id_p` (`product_id` ASC) ,
  INDEX `user_id_pr` (`user_id` ASC) ,
  CONSTRAINT `payment_id_p`
    FOREIGN KEY (`payment_id` )
    REFERENCES `flashcards`.`payments` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `product_id_p`
    FOREIGN KEY (`product_id` )
    REFERENCES `flashcards`.`products` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_id_pr`
    FOREIGN KEY (`user_id` )
    REFERENCES `flashcards`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
