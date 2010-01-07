//JOIN SQL
SELECT cards.id, cards.question, cards.answer, cards.deck_id, ratings.rating
FROM cards
LEFT JOIN ratings
ON cards.id=ratings.card_id
WHERE cards.deck_id=1 AND (ratings.user_id=1 OR ratings.user_id is NULL) AND ratings.rating=3;

WHERE cards.deck_id=1 AND
(ratings.user_id=1 OR ratings.user_id is NOT NULL) OR (ratings.user_id=null AND ratings.rating=3);

// WHERE SQL
SELECT DISTINCT cards.id, cards.question, cards.answer, cards.deck_id, ratings.rating
FROM cards, decks, ratings
WHERE cards.deck_id=1 AND cards.id=ratings.card_id AND NOT(ratings.rating IN(1));
WHERE cards.deck_id=1 AND cards.id=ratings.card_id AND ratings.rating=2;

-----------------------------------------------------------------------------------------------
// USING SUBQUERIES

SELECT cid AS 'id', cq AS 'question', ca AS 'answer', rid AS 'ratings.id', rr AS 'rating', rcid, ruid

SELECT cid AS 'id', cq AS 'question', ca AS 'answer'
FROM
(SELECT cards.id AS cid, cards.question AS cq, cards.answer AS ca, cards.deck_id AS cdid FROM cards WHERE cards.deck_id=1) AS Cards
LEFT JOIN
(SELECT ratings.id AS rid, ratings.rating AS rr, ratings.card_id AS rcid, ratings.user_id AS ruid FROM ratings WHERE ratings.user_id=1) AS Ratings
ON cid=rcid

WHERE rr in (1);
WHERE rr in (2);
WHERE rr=3 OR rr is null;
WHERE rr in (1,2);
WHERE rr in (1,3) OR rr is null;
WHERE rr in (2,3) OR rr is null;
