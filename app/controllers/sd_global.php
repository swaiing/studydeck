<?php
class SD_Global
{
    // Constants for deck privacy values
    public static $PRIVATE_DECK = 0;
    public static $PUBLIC_DECK = 1;

    // Constants for card rating values
    public static $EASY_CARD = 1;
    public static $MEDIUM_CARD = 2;
    public static $HARD_CARD = 3;
    public static $TOTAL_CARD = 99;

    // Constants for the last guessed answer for a card
    public static $INCORRECT_RESULT = 0;
    public static $CORRECT_RESULT = 1;

    public static $NULL_STR = "null";

    // Constants for session arrays
    public static $SESSION_USERS_KEY = 'Users';
    public static $SESSION_RATING_KEY = 'Rating';
    public static $SESSION_RESULT_KEY = 'Result';
    public static $SESSION_ID_KEY = 'id';
    public static $SESSION_RATING_VAL_KEY = 'rating';
    public static $SESSION_RESULT_VAL_KEY = 'last_guess';
    public static $SESSION_RATINGS_SELECTED_KEY = 'sess_ratings_selected';

    // Constants for Model field names
    public static $MODEL_ID = 'id';
    public static $MODEL_CARD_ID = 'card_id';
    public static $MODEL_CARD = 'Card';
    public static $MODEL_USER_ID = 'user_id';
    public static $MODEL_RATING_RATING = 'rating';
    public static $MODEL_RESULT = 'Result';
    public static $MODEL_RESULT_LAST_GUESS = 'last_guess';
    public static $MODEL_RESULT_TOT_CORRECT = 'total_correct';
    public static $MODEL_RESULT_TOT_INCORRECT = 'total_incorrect';

    // Constants for TempUser Clean Up
    //delete any temp accounts older than $DAYS_TO_KEEP
    public static $DAYS_TO_KEEP = 14;
    
    // Constants for My_Deck Types
    public static $RECENTLY_VIEWED_DECK = 1;
    public static $USER_CREATED = 2;
    public static $USER_SAVED = 3;

}
?>
