SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `flashcards`.`decks` DROP COLUMN `view_count` , ADD COLUMN `quiz_count` INT(11) NOT NULL  AFTER `created` ;

ALTER TABLE `flashcards`.`my_decks` DROP COLUMN `study_count` , ADD COLUMN `quiz_count` INT(11) NOT NULL  AFTER `modified` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
