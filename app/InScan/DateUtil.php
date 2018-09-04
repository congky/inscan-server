<?php
namespace App\InScan;

/**
 * Class DateUtil
 * @package FLA\Core\Util
 *
 * @author Congky, 2018-05-10
 */
class DateUtil
{
    public static function currentDate() {
        return date('Ymd');
    }

    public static function currentDatetime() {
        return date('YmdHis');
    }

    public static function currentWeek(){
        return date('W');
    }

    public static function weekOfDate(string $date){
        return date('W', strtotime($date));
    }

    public static function dateOfDayInCurrentWeek(string $day){
        return date('Ymd', strtotime($day." this week"));
    }

    public static function dateOfDayInNextWeek(string $day){
        return date('Ymd', strtotime($day." this week"));
    }

    public static function mondayCurrentWeek(){
        return date('Ymd', strtotime("monday this week"));
    }

    public static function mondayNextWeek(){
        return date('Ymd', strtotime("monday next week"));
    }

    public static function fridayCurrentWeek(){
        return date('Ymd', strtotime("friday this week"));
    }

    public static function fridayNextWeek(){
        return date('Ymd', strtotime("friday next week"));
    }

    public static function sundayCurrentWeek(){
        return date('Ymd', strtotime("sunday this week"));
    }

    public static function sundayNextWeek(){
        return date('Ymd', strtotime("sunday next week"));
    }

    public static function validateDate(string $date, string $format = "Ymd")
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
}