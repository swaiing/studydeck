<?php 

//helper that abstracts out relative time code

class RelativeTimeHelper extends AppHelper {
    var $helpers = array('Time');
    
    /**
    * Returns true if specified datetime was within the interval specified, else false.
    * @param mixed $timeStamp the datestring or unix timestamp to compare
    * @param mixed $timeWithin the numeric value with space then time type. Example of valid types: 6 hours, 2 days, 1 minute. Default 3 days
    * @param string $format date format string. defaults to 'd-m-Y'    
    * @return string Formatted date string or @return string Relative time string.
    */    
    function getRelativeTime($timeStamp = null, $timeWithin = null, $dateFormat = null) {
        if($timeWithin == null) {
            $timeWithin = '3 days';
        }
        
        if($dateFormat == null) {
            $dateFormat = 'm-d-y';
        }
        
        $time = new TimeHelper();
        //if it is within the timeWithin, then return a relative time otherwise return a formated date
        if($time->wasWithinLast($timeWithin, $timeStamp)){ 
            return $time->relativeTime($timeStamp);
        }
        else {
            return $time->format($dateFormat,$timeStamp);
        }
        
   }
    
}
?>