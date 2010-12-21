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
    //public static $SESSION_DECK_KEY = 'DeckObject_';
    public static $SESSION_COMMIT_RATING_TYPE = "COMMIT_RATING";
    public static $SESSION_COMMIT_RESULT_TYPE = "COMMIT_RESULT";
    public static $SESSION_DECK_RATING_KEY = 'DeckObject_Rating_';
    public static $SESSION_DECK_RESULT_KEY = 'DeckObject_Result_';
    public static $SESSION_DECK_MODE_KEY = 'StudyMode_';
    public static $SESSION_DECK_MODE_LEARN = 'LEARN';
    public static $SESSION_DECK_MODE_QUIZ = 'QUIZ';
    public static $SESSION_DECK_MODE_QUIZZED = "StudyMode_HasQuizzed";
    public static $SESSION_USERS_KEY = 'Users';
    public static $SESSION_RATING_KEY = 'Rating';
    public static $SESSION_RESULT_KEY = 'Result';
    public static $SESSION_ID_KEY = 'id';
    public static $SESSION_RATING_VAL_KEY = 'rating';
    public static $SESSION_RESULT_VAL_KEY = 'last_guess';
    public static $SESSION_RATINGS_SELECTED_KEY = 'sess_ratings_selected';
    public static $SESSION_SHUFFLE_DECK_KEY = 'sess_shuffle_deck';

    // Constants for Model field names
    public static $MODEL_ID = 'id';
    public static $MODEL_DECK_ID = 'deck_id';
    public static $MODEL_DECK_QUIZ_COUNT = 'quiz_count';
    public static $MODEL_MYDECK_TYPE = 'type';
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

    // Page titles
    // Hard-coded in /app/views/pages/home.ctp
    //public static $PAGE_TITLE_HOME = "Studydeck | Online flashcards made simple";
    public static $PAGE_TITLE_DASHBOARD = "Studydeck | Dashboard";
    public static $PAGE_TITLE_SETTINGS = "Studydeck | Settings";
    public static $PAGE_TITLE_PRODUCTS = "Studydeck | Store";
    public static $PAGE_TITLE_EXPLORE = "Studydeck | Explore";
    public static $PAGE_TITLE_REGISTER = "Studydeck | Register";
    public static $PAGE_TITLE_LOGIN = "Studydeck | Login";
    public static $PAGE_TITLE_INFO = "Studydeck | ";
    public static $PAGE_TITLE_LEARN = "Studydeck | Learn";
    public static $PAGE_TITLE_QUIZ = "Studydeck | Quiz";
    public static $PAGE_TITLE_CREATE = "Studydeck | Create";
    public static $PAGE_TITLE_EDIT = "Studydeck | Edit ";
    
    //public static $PAGE_TITLE_HELP = "Help – Studydeck | Help";
}
?>
