SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER SCHEMA `flashcards`  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_unicode_ci ;

USE `flashcards`;

ALTER TABLE `flashcards`.`users` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`decks` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`deck_comments` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`my_decks` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`cards` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`ratings` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`groups` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`group_decks` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`group_members` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`hints` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`results` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`my_answers` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`tags` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`deck_tags` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;

ALTER TABLE `flashcards`.`temp_users` CHARACTER SET = utf8 , COLLATE = utf8_general_ci ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
