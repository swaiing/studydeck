--  CREATE USERS
INSERT INTO users (email, password, username) VALUES ("steve@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "steve");
INSERT INTO users (email, password, username) VALUES ("scott@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "scott");
INSERT INTO users (email, password, username) VALUES ("nicolo@studydeck.com", "5911211b41ad0fc56b09e05fe73ee5cedd42ca23", "nicolo");

-- CREATE DECK
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("EMC Vocabulary", 1, 0, 1);
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("State Capitals", 1, 0, 2);
INSERT INTO decks (deck_name, privacy, view_count, user_id) VALUES ("SAT Words", 1, 0, 3);

-- ADD NECESSARY ASSOCIATIONS (for dashboard)
INSERT INTO my_decks (user_id, deck_id) VALUES (1,1);
INSERT INTO my_decks (user_id, deck_id) VALUES (2,2);
INSERT INTO my_decks (user_id, deck_id) VALUES (3,3);

-- CREATE CARDS

-- deck 1: emc vocabulary
INSERT INTO cards (question, answer, deck_id) VALUES ("Lun Masking","Restricts volume access to specific hosts and/or host clusters.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Zoning","A switch function that allows the nodes within the fabric to be logically segmented into groups that can communicate with each other.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Core-Edge Fabric","In this topology several switches are connected in a 'hub and spoke' configuration.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Mesh Fabric","Can be partial or full mesh.  All switches are connected to one another in full.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Inter-Switch Links","Switches are connected to each other in a fabric using ISLs",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("File system","The general name given to the host-based logical structures and software routines used to control access to data storage.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Three connectivity components associated with hosts","bus (e.g. connecting CPU to memory), ports (i.e. connections to external devices such as printers, scanners or storage), cables (i.e. copper or fiber optic 'wires' connecting a host to internal or external devices).",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Bus Width","This determines how much data can be transmitted at one time.  For example a 16-bit bus can transmit 16 bits of data, whereas a 32-bit bush can transmit 32 bits of data.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("SCSI","Small Computer Systems Interface - Most popular hard disk interface for servers.  More expensive than IDE/ATA, supports simultaneous data access, both parallel and serial forms, transfer speed up to 320 MB/s, can connect many devices to a computer.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("Sector","Smallest individually-addressable unit of storage.  What a track divides into.  Sectors typically hold 512 bytes of user data.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 0","Striped array with no fault tolerance.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 1","Disk mirroring",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 3","Parallel access array with dedicated parity disk.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 4","Striped array with independent disks and a dedicated parity disk.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 5","Striped array with independent disks and distributed parity.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 6","Striped array with independent disks and dual distributed parity.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 0+1","A mirrored array whose basic elements are RAID 0 stripes.",1);
INSERT INTO cards (question, answer, deck_id) VALUES ("RAID 1+0/10","A striped array whose individual elements are RAID 1 arrays - mirrors.",1);

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
INSERT INTO cards (question, answer, deck_id) VALUES ("Alabama", "Montgomery", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Alaska", " Juneau", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Arizona", " Phoenix", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Arkansas", " Little Rock", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("California", "Sacramento", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Colorado", " Denver", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Connecticut", " Hartford", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Delaware", " Dover", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Florida", " Tallahassee", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Georgia", " Atlanta", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Hawaii", " Honolulu", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Idaho", " Boise", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Illinois", " Springfield", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Indiana", " Indianapolis", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Iowa", " Des Moines", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Kansas", " Topeka", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Kentucky", " Frankfort", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Louisiana", " Baton Rouge", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Maine", " Augusta", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Maryland", " Annapolis", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Massachusetts", " Boston", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Michigan", "Lansing", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Minnesota", "Saint Paul", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Mississippi", "Jackson", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Missouri", " Jefferson", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Montana", " Helena", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Nebraska", "Lincoln", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Nevada", " Carson City", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("New Jersey", "Trenton", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("New Hampshire", "Concord", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("New Mexico", " Santa Fe", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("New York", " Albany", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("North Carolina", "Raleigh", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("North Dakota", " Bismarck", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Ohio", " Columbus", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Oklahoma", "Oklahoma City", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Oregon", "Salem", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Pennsylvania", "Harrisburg", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Rhode Island", "Providence", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("South Carolina", "Columbia", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("South Dakota", "Pierre", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Tennessee", "Nashville", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Texas", "Austin", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Utah", "Salt Lake City", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Vermont", " Montpelier", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Virginia", "Richmond", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Washington", "Olympia", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("West Virginia", "Charleston", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Wisconsin", "Madison", 2);
INSERT INTO cards (question, answer, deck_id) VALUES ("Wyoming", "Cheyenne", 2);

-- deck 3: SAT words
INSERT INTO cards (question, answer, deck_id) VALUES ("abbess", "(n.) The lady superior of a nunnery", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abbey", "(n.) The group of buildings which collectively form the dwelling-place of a society of monks or nuns", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abbot", "(n.) The superior of a community of monks", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abdicate", "(v.) To give up (royal power or the like)", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abed", "(adv.) In bed; on a bed", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abeyance", "(n.) A state of suspension or temporary inaction", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abhorrence", "(n.) The act of detesting extremely", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abhorrent", "(adj.) Very repugnant; hateful", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abidance", "(n.) An abiding", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abject", "(adj.) Sunk to a low condition", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("able-bodied", "(adj.) Competent for physical service", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abnegate", "(v.) To renounce (a right or privilege)", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abnormal", "(adj.) Not conformed to the ordinary rule or standard", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abominable", "(adj.) Very hateful", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abominate", "(v.) To hate violently", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abomination", "(n.) A very detestable act or practice", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("aboriginal", "(adj.) Primitive; unsophisticated", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("aborigines", "(n.) The original of earliest known inhabitants of a country", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abrade", "(v.) To wear away the surface or some part of by friction", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abrasion", "(n.) That which is rubbed off", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abridgment", "(n.) A condensed form as of a book or play", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abscess", "(n.) A Collection of pus in a cavity formed within some tissue of the body", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("absence", "(n.) The fact of not being present or available", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("absent-minded", "(adj.) Lacking in attention to immediate surroundings or business", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("absolve", "(v.) To free from sin or its penalties", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("absorption", "(n.) The act or process of absorbing", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abstain", "(v.) To keep oneself back (from doing or using something)", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abstinence", "(n.) Self denial", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abstruse", "(adj.) Dealing with matters difficult to be understood", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("absurd", "(adj.) Inconsistent with reason or common sense", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abundant", "(adj.) Plentiful", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abusive", "(adj.) Employing harsh words or ill treatment", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abut", "(v.) To touch at the end or boundary line", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("abyss", "(n.) Bottomless gulf", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("academy", "(n.) Any institution where the higher branches of learning are taught", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("accede", "(v.) To agree", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("accelerate", "(v.) To move faster", 3);
INSERT INTO cards (question, answer, deck_id) VALUES ("accept", "(v.) To take when offered", 3);
