SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

ALTER TABLE `flashcards`.`deck_comments` DROP FOREIGN KEY `deck_id_dc` ;

ALTER TABLE `flashcards`.`deck_comments` 
  ADD CONSTRAINT `deck_id_dc`
  FOREIGN KEY (`deck_id` )
  REFERENCES `flashcards`.`decks` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`my_decks` DROP FOREIGN KEY `deck_id_md` ;

ALTER TABLE `flashcards`.`my_decks` 
  ADD CONSTRAINT `deck_id_md`
  FOREIGN KEY (`deck_id` )
  REFERENCES `flashcards`.`decks` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`cards` DROP FOREIGN KEY `deck_id_c` ;

ALTER TABLE `flashcards`.`cards` 
  ADD CONSTRAINT `deck_id_c`
  FOREIGN KEY (`deck_id` )
  REFERENCES `flashcards`.`decks` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`ratings` DROP FOREIGN KEY `card_id_r` ;

ALTER TABLE `flashcards`.`ratings` 
  ADD CONSTRAINT `card_id_r`
  FOREIGN KEY (`card_id` )
  REFERENCES `flashcards`.`cards` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`group_decks` DROP FOREIGN KEY `deck_id_gd` ;

ALTER TABLE `flashcards`.`group_decks` 
  ADD CONSTRAINT `deck_id_gd`
  FOREIGN KEY (`deck_id` )
  REFERENCES `flashcards`.`decks` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`hints` DROP FOREIGN KEY `card_id_h` ;

ALTER TABLE `flashcards`.`hints` 
  ADD CONSTRAINT `card_id_h`
  FOREIGN KEY (`card_id` )
  REFERENCES `flashcards`.`cards` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`results` DROP FOREIGN KEY `card_id_rs` ;

ALTER TABLE `flashcards`.`results` 
  ADD CONSTRAINT `card_id_rs`
  FOREIGN KEY (`card_id` )
  REFERENCES `flashcards`.`cards` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`my_answers` DROP FOREIGN KEY `card_id_ma` ;

ALTER TABLE `flashcards`.`my_answers` 
  ADD CONSTRAINT `card_id_ma`
  FOREIGN KEY (`card_id` )
  REFERENCES `flashcards`.`cards` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `flashcards`.`deck_tags` DROP FOREIGN KEY `deck_id_dt` ;

ALTER TABLE `flashcards`.`deck_tags` 
  ADD CONSTRAINT `deck_id_dt`
  FOREIGN KEY (`deck_id` )
  REFERENCES `flashcards`.`decks` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
