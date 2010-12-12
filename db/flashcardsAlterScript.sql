SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';
DROP TABLE IF EXISTS `studydeck`.`products_purchased` ;

CREATE  TABLE IF NOT EXISTS `studydeck`.`purchased_products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `payment_id` INT(11) NOT NULL ,
  `product_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
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
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;




SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
