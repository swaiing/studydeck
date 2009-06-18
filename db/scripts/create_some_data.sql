-- create users
INSERT INTO users (email, password, username) VALUES ("steve@studydeck.com", "password", "steve");
INSERT INTO users (email, password, username) VALUES ("scott@studydeck.com", "password", "scott");
INSERT INTO users (email, password, username) VALUES ("nicolo@studydeck.com", "password", "nicolo");

-- create deck
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("SAT Words", 1, 0, 1);
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("State Capitals", 1, 0, 2);
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("World Capitals", 1, 0, 3);

-- create cards
INSERT INTO cards (question, answer, deck_id) VALUES ("abbess", "(n.) The lady superior of a nunnery", 1);
INSERT INTO cards (question, answer, deck_id) VALUES ("abbey", "(n.) The group of buildings which collectively form the dwelling-place of a society of monks or nuns", 1);
INSERT INTO cards (question, answer, deck_id) VALUES ("abbot", "(n.) The superior of a community of monks", 1);
INSERT INTO cards (question, answer, deck_id) VALUES ("abdicate", "(v.) To give up (royal power or the like)", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("abed", "(adv.) In bed; on a bed", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("abeyance", "(n.) A state of suspension or temporary inaction", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("abhorrence", "(n.) The act of detesting extremely", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abhorrent", "(adj.) Very repugnant; hateful", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abidance", "(n.) An abiding", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abject", "(adj.) Sunk to a low condition", 1);
INSERT INTO cards (question, answer, deck_id) VALUES ("able-bodied", "(adj.) Competent for physical service", 2);
