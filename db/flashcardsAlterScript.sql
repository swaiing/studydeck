SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER SCHEMA `studydeck`  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_unicode_ci ;

USE `studydeck`;

ALTER TABLE `studydeck`.`users` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`decks` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`deck_comments` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`my_decks` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`cards` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`ratings` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`groups` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`group_decks` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`group_members` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`hints` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`results` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`my_answers` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`tags` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`deck_tags` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`temp_users` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci ;

ALTER TABLE `studydeck`.`products` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci , CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `studydeck`.`payments` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci , CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `studydeck`.`products_purchased` CHARACTER SET = utf8 , COLLATE = utf8_unicode_ci , CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
