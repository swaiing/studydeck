--  CREATE USERS
INSERT INTO users (email, password, username) VALUES ("steve@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "steve");
INSERT INTO users (email, password, username) VALUES ("scott@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "scott");
INSERT INTO users (email, password, username) VALUES ("nicolo@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "nicolo");

-- CREATE DECK
INSERT INTO decks (deck_name, privacy, quiz_count, user_id, description, created) VALUES ("SAN Terms", 1, 0, 1, "Storage Area Networks terms and definitions.", NOW());
INSERT INTO decks (deck_name, privacy, quiz_count, user_id, description, created) VALUES ("State Capitals", 1, 0, 2, "All 50 states of the United States and the respective capital cities.", NOW());
INSERT INTO decks (deck_name, privacy, quiz_count, user_id, description, created) VALUES ("SAT Words", 1, 0, 3, "SAT prep words and definitions.", NOW());
INSERT INTO decks (deck_name, privacy, quiz_count, user_id, description, created) VALUES ("Types of Wine", 1, 0, 1, "Different types of red and white wine.", NOW());

-- CREATE SOME TAGS
INSERT INTO tags (tag, created) VALUES ("storage", NOW());
INSERT INTO tags (tag, created) VALUES ("geography", NOW());
INSERT INTO tags (tag, created) VALUES ("united states", NOW());
INSERT INTO tags (tag, created) VALUES ("capitals", NOW());
INSERT INTO tags (tag, created) VALUES ("vocabulary", NOW());
INSERT INTO tags (tag, created) VALUES ("sat", NOW());

-- ADD NECESSARY ASSOCIATIONS (for dashboard)
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (1,1,2,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (1,4,2,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (1,2,3,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (1,3,3,0,NOW());

INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (2,2,2,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (2,1,3,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (2,3,3,0,NOW());

INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (3,3,2,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (3,1,3,0,NOW());
INSERT INTO my_decks (user_id, deck_id, type, quiz_count, created) VALUES (3,1,3,0,NOW());

-- CREATE TAG ASSOCIATIONS
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (1, 1, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (1, 5, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (2, 2, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (2, 3, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (2, 4, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (3, 6, NOW());
INSERT INTO deck_tags (deck_id, tag_id, created) VALUES (3, 5, NOW());

-- CREATE CARDS

-- deck 1: emc vocabulary
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Lun Masking","Restricts volume access to specific hosts and/or host clusters.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (2,"Zoning","A switch function that allows the nodes within the fabric to be logically segmented into groups that can communicate with each other.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (3,"Core-Edge Fabric","In this topology several switches are connected in a 'hub and spoke' configuration.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (4,"Mesh Fabric","Can be partial or full mesh.  All switches are connected to one another in full.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (5,"Inter-Switch Links","Switches are connected to each other in a fabric using ISLs",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (6,"File system","The general name given to the host-based logical structures and software routines used to control access to data storage.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (7,"Three connectivity components associated with hosts","bus (e.g. connecting CPU to memory), ports (i.e. connections to external devices such as printers, scanners or storage), cables (i.e. copper or fiber optic 'wires' connecting a host to internal or external devices).",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (8,"Bus Width","This determines how much data can be transmitted at one time.  For example a 16-bit bus can transmit 16 bits of data, whereas a 32-bit bush can transmit 32 bits of data.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (9,"SCSI","Small Computer Systems Interface - Most popular hard disk interface for servers.  More expensive than IDE/ATA, supports simultaneous data access, both parallel and serial forms, transfer speed up to 320 MB/s, can connect many devices to a computer.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (10,"Sector","Smallest individually-addressable unit of storage.  What a track divides into.  Sectors typically hold 512 bytes of user data.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (11,"RAID 0","Striped array with no fault tolerance.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (12,"RAID 1","Disk mirroring",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (13,"RAID 3","Parallel access array with dedicated parity disk.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (14,"RAID 4","Striped array with independent disks and a dedicated parity disk.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (15,"RAID 5","Striped array with independent disks and distributed parity.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (16,"RAID 6","Striped array with independent disks and dual distributed parity.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (17,"RAID 0+1","A mirrored array whose basic elements are RAID 0 stripes.",1);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (18,"RAID 1+0/10","A striped array whose individual elements are RAID 1 arrays - mirrors.",1);

-- deck 1: create ratings
INSERT INTO ratings (rating, card_id, user_id) VALUES (1,1,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,2,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,3,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,4,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (1,5,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (1,6,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,7,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,8,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,9,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,10,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,11,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,12,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (1,13,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,14,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (1,15,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (2,16,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,17,1);
INSERT INTO ratings (rating, card_id, user_id) VALUES (3,18,1);

-- deck 1: create results
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (1,1,0,1,5);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (2,1,1,6,0);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (3,1,0,2,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (4,1,0,2,2);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (5,1,1,1,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (6,1,0,5,6);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (7,1,1,2,0);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (8,1,1,0,0);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (9,1,0,1,2);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (10,1,1,2,4);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (11,1,0,3,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (12,1,1,3,6);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (13,1,0,3,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (14,1,1,2,33);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (15,1,1,3,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (16,1,1,2,1);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (17,1,0,9,8);
INSERT INTO results (card_id, user_id, last_guess, total_correct, total_incorrect) VALUES (18,1,1,0,1);

-- deck 2: state capitals
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Alabama", "Montgomery", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (2,"Alaska", " Juneau", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (3,"Arizona", " Phoenix", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (4,"Arkansas", " Little Rock", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (5,"California", "Sacramento", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (6,"Colorado", " Denver", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (7,"Connecticut", " Hartford", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (8,"Delaware", " Dover", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (9,"Florida", " Tallahassee", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (10,"Georgia", " Atlanta", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (11,"Hawaii", " Honolulu", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (12,"Idaho", " Boise", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (13,"Illinois", " Springfield", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (14,"Indiana", " Indianapolis", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (15,"Iowa", " Des Moines", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (16,"Kansas", " Topeka", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (17,"Kentucky", " Frankfort", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (18,"Louisiana", " Baton Rouge", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (19,"Maine", " Augusta", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (20,"Maryland", " Annapolis", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (21,"Massachusetts", " Boston", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (22,"Michigan", "Lansing", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (23,"Minnesota", "Saint Paul", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (24,"Mississippi", "Jackson", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (25,"Missouri", " Jefferson", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (26,"Montana", " Helena", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (27,"Nebraska", "Lincoln", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (28,"Nevada", " Carson City", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (29,"New Jersey", "Trenton", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (30,"New Hampshire", "Concord", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (31,"New Mexico", " Santa Fe", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (32,"New York", " Albany", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (33,"North Carolina", "Raleigh", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (34,"North Dakota", " Bismarck", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (35,"Ohio", " Columbus", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (36,"Oklahoma", "Oklahoma City", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (37,"Oregon", "Salem", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (38,"Pennsylvania", "Harrisburg", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (39,"Rhode Island", "Providence", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (40,"South Carolina", "Columbia", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (41,"South Dakota", "Pierre", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (42,"Tennessee", "Nashville", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (43,"Texas", "Austin", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (44,"Utah", "Salt Lake City", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (45,"Vermont", " Montpelier", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (46,"Virginia", "Richmond", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (47,"Washington", "Olympia", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (48,"West Virginia", "Charleston", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (49,"Wisconsin", "Madison", 2);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (50,"Wyoming", "Cheyenne", 2);

-- deck 3: SAT words
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"abbess", "(n.) The lady superior of a nunnery", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (2,"abbey", "(n.) The group of buildings which collectively form the dwelling-place of a society of monks or nuns", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (3,"abbot", "(n.) The superior of a community of monks", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (4,"abdicate", "(v.) To give up (royal power or the like)", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (5,"abed", "(adv.) In bed; on a bed", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (6,"abeyance", "(n.) A state of suspension or temporary inaction", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (7,"abhorrence", "(n.) The act of detesting extremely", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (8,"abhorrent", "(adj.) Very repugnant; hateful", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (9,"abidance", "(n.) An abiding", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (10,"abject", "(adj.) Sunk to a low condition", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (11,"able-bodied", "(adj.) Competent for physical service", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (12,"abnegate", "(v.) To renounce (a right or privilege)", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (13,"abnormal", "(adj.) Not conformed to the ordinary rule or standard", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (14,"abominable", "(adj.) Very hateful", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (15,"abominate", "(v.) To hate violently", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (16,"abomination", "(n.) A very detestable act or practice", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (17,"aboriginal", "(adj.) Primitive; unsophisticated", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (18,"aborigines", "(n.) The original of earliest known inhabitants of a country", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (19,"abrade", "(v.) To wear away the surface or some part of by friction", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (20,"abrasion", "(n.) That which is rubbed off", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (21,"abridgment", "(n.) A condensed form as of a book or play", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (22,"abscess", "(n.) A Collection of pus in a cavity formed within some tissue of the body", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (23,"absence", "(n.) The fact of not being present or available", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (24,"absent-minded", "(adj.) Lacking in attention to immediate surroundings or business", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (25,"absolve", "(v.) To free from sin or its penalties", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (26,"absorption", "(n.) The act or process of absorbing", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (27,"abstain", "(v.) To keep oneself back (from doing or using something)", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (28,"abstinence", "(n.) Self denial", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (29,"abstruse", "(adj.) Dealing with matters difficult to be understood", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (30,"absurd", "(adj.) Inconsistent with reason or common sense", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (31,"abundant", "(adj.) Plentiful", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (32,"abusive", "(adj.) Employing harsh words or ill treatment", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (33,"abut", "(v.) To touch at the end or boundary line", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (34,"abyss", "(n.) Bottomless gulf", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (35,"academy", "(n.) Any institution where the higher branches of learning are taught", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (36,"accede", "(v.) To agree", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (37,"accelerate", "(v.) To move faster", 3);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (38,"accept", "(v.) To take when offered", 3);

-- deck 4: Wines
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Chardonnay", "White - with the crisp similarities of apples and peaches.  Good wines for shellfish and full-flavored fish like flounder, bluefin and tuna; barbecued chicken and grilled pork chops.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Sauvignon Blanc", "White - strikingly spicy penetrating aromas and flavor are at once musky and tart; crisp refreshing and lively.  Good with food cooked with herbs, such as fish with dill or oregano and chicken with sage or thyme.  It also makes a good fit with chiles, lemongrass, ginger, garlic, and cilantro, so it's often a perfect match for Thai, Indian, and Mexican dishes.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Riesling", "White - as a light table wine with a hint of sweetness, it's unfashionable in an age of dry wines, and the fact that the grape is often made into intensely sweet dessert-style wines creates further confusion among consumers.  Bold rieslings of Australia or New Zealand are perfect wines for the spice and savor of Chinese, Thai and Mexican dishes or stir-fries, grilled shellfish, and barbecued pork.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Pinot Grigio", "White - not generally considered much of a match for most meals, but when it's good, it's quite pleasant as a drink--relatively low in acid, medium-bodied and quite dry.  Wines from the same grape made in the Alsace region of France and in Oregon, known as Pinot Gris, can provide more consistent pleasure, on their own or as good companions to light hot or cold fish dishes, salmon mousse, fish cakes, or gravlax, and pasta with pesto or cheese-based sauces.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Cabernet Sauvignon", "Red - a very tannic wine to begin with and most of the words used to describe it are almost disparaging: 'hard', 'angular', 'austere'.  It tends to be blended with small amounts of grapes to add aroma, spice and fruitiness.  Original home in Bordeaux, over five years it begins to soften, and the fruitiness comes to the fore.  Best partnered with roast lamb or grilled lamb chops, char-grilled steak, filet mignon, calf's liver with bacon and onions, venison, squab and any red meat with a rich, reduced sauce.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Merlot", "Red - another red wine of Bordeaux, mellower from the start so more ready to drink at an earlier age.  A smooth and lively fine wine, a splendid partner for roast lamb or duck, grilled steak, lamb or beef stew, and braised meat in reduced sauces.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Zinfandel", "Red - (the original deep red wine, not to be confused with white zinfandel) was long known as \"California's mystery grape\" because no one knew where it had originated.  The mystery has been solved, it has descended from a southern Italian variety known as Primitivo.  It has been a very successful transplant indeed--robust in every way, bursting with ripe blackberry jam flavor, vibrantly acidic and fairly high in alcohol with a bracing jolt of astringent tannin.  A well-made Zinfandel will be superb five years after the vintage date and can easily age for at least ten years.  A good match for hearty food such as roast pork with gravy or rich sauce, osso bucco, and slow-braised lamb shanks, as well as pasta with spicy sausages, tomato-based chicken fricasses, and even first-rate cheeseburgers.", 4);
INSERT INTO cards (card_order, question, answer, deck_id) VALUES (1,"Pinot Noir", "Red - for many winemakers and connoisseurs this is the ultimate wine.  Originally from Burgunday in France, it has been celebrated for nearly a thousand years.  It is smooth and relatively light, but with a persistent lingering flavor, a unique combination of delicacy and power, often characterized as an iron fist in a velvet glove.  Classically paired with a rare roast beef.  Also dark-meat poultry is another favorite, especially duck and quail, while it's lightness makes it a good red for turkey.", 4);
