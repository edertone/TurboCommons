<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use DateTime;
use DateTimeZone;
use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * date and time format object abstraction based on ISO 8601
 */
class DateTimeObject{


    /**
     * String that defines the ISO 8601 format to be used when calling the format method on DateTime Php class.
     *
     * @var string
     */
    private $_iso8601FormatString = 'Y-m-d\\TH:i:s.uP';


    /**
     * An ISO 8601 string that contains the current date and time values for this instance
     *
     * @var string
     */
    private $_dateTimeString = '';


    /**
     * Object that represents a date and time value and its related operations.
     * TODO - revisar documentacio
     *
     * All the class methods are based and expect values that follow the ISO 8601 format, which is the international standard for the
     * representation of dates and times. Any other date/time format will be considered as invalid.<br><br>
     * Following is an example of a valid ISO 8601 dateTime value:<br><br>yyyy-mm-ddTHH:MM:SS.UUU+TT:TT<br><br>where:<br>
     * - yyyy is a four digits year value<br>
     * - mm is a two digits month value<br>
     * - dd is a two digits day value<br>
     * - HH is a two digits hour value<br>
     * - MM is a two digits minutes value<br>
     * - SS is a two digits seconds value<br>
     * - UUU is an arbitrary number of digits decimal seconds fraction value<br>
     * - +TT:TT is the timezone offset value, like +03:00
     *
     * @see https://es.wikipedia.org/wiki/ISO_8601
     *
     * @param int $year TODO
     * @param int $month TODO
     * @param int $day TODO
     * @param int $hour TODO
     * @param int $minute TODO
     * @param int $second TODO
     * @param int $microSecond TODO
     * @param int $timeZoneOffset TODO
     * @throws UnexpectedValueException TODO
     *
     * @return DateTimeObject The created instance
     */
    public function __construct(int $dateTimeString = ''){

        // TODO - This method is incomplete and pending
        // TODO - a partir del string rebut, es genera un string ISO8601 valid i es guarda a $this->_dateTimeString
    }


    /**
     * DateTimeObject class operates only with ISO 8601 strings, which is the international standard for the representation of dates and times.
     * Therefore, this method considers a dateTime string value to be valid only if it is a string that follows that standard or a DateTimeObject instance.
     *
     * @param mixed $dateTime A string containing a valid ISO 8601 date/time value or a valid DateTimeObject instance.
     *
     * @see DateTimeObject::__construct
     *
     * @return true if the specified value is ISO 8601 or a DateTimeObject instance, false if value contains invalid information.
     */
    public static function isValidDateTime($dateTime){

        // Validate that is a string and ends only with alphanumeric values
        if(is_string($dateTime) && ctype_alnum(substr($dateTime, -1))){

            $regex = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';

            if (preg_match($regex, $dateTime) > 0){

                // We must also validate that the day, month and year are a correct date value
                $parsedDate = explode('-', $dateTime);

                if(count($parsedDate) >= 3){

                    return checkdate($parsedDate[1], substr($parsedDate[2], 0, 2), $parsedDate[0]);

                }else{

                    return true;
                }
            }
        }

        return get_class($dateTime) === 'org\\turbocommons\\src\\main\\php\\model\\DateTimeObject';
    }


    /**
     * Given two valid dateTime values, this method will check if they represent the same date and time value
     *
     * @param mixed $dateTime1 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     * @param mixed $dateTime2 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @see DateTimeObject::__construct
     *
     * @return boolean True if the date and time values on both elements are the same
     */
    public static function isEqual($dateTime1, $dateTime2){

        return self::compare($dateTime1, $dateTime2) === 0;
    }


    /**
     * Given two valid dateTime values, this method will check if they have the same timezone offset.
     *
     * @param mixed $dateTime1 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     * @param mixed $dateTime2 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @see DateTimeObject::__construct
     *
     * @throws UnexpectedValueException If an invalid dateTime was provided on any of the two parameters
     *
     * @return boolean True if the time zone on both dateTime values is the same
     */
    public static function isSameTimeZone($dateTime1, $dateTime2){

        if(self::isValidDateTime($dateTime1) && self::isValidDateTime($dateTime2)){

            if(is_string($dateTime1)){

                $dateTime1 = new DateTimeObject($dateTime1);
            }

            if(is_string($dateTime2)){

                $dateTime2 = new DateTimeObject($dateTime2);
            }

            return $dateTime1->getTimeZoneOffset() === $dateTime2->getTimeZoneOffset();
        }

        throw new UnexpectedValueException('DateTimeObject::isSameTimeZone : Provided params are not valid date time values');
    }


    /**
     * Returns the month name from a numeric month value
     *
     * @param int $month A month number between 1 and 12
     *
     * @return string the month name in english and with capital letters, like: JANUARY, FEBRUARY, ...
     */
    public static function getMonthName($month){

        if(!NumericUtils::isNumeric($month) || $month > 12 || $month < 1){

            throw new UnexpectedValueException('DateTimeObject->getMonthName : Provided value is not a valid month number between 1 and 12');
        }

        $months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

        return  $months[$month - 1];
    }


    /**
     * Get the english name representing the given numeric value of a week day (between 1 and 7), where
     * Sunday is considered to be the first one: 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @param int $day A day number between 1 and 7
     *
     * @return string The day name in english and with capital letters, like for example: MONDAY, SATURDAY...
     */
    public static function getDayName($day){

        if(!NumericUtils::isNumeric($day) || $day > 7 || $day < 1){

            throw new UnexpectedValueException('DateTimeObject->getDayName : Provided value is not a valid day number between 1 and 7');
        }

        $days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

        return $days[$day - 1];
    }


    /**
     * Get the year based on current system date and timezone
     *
     * @return int A 4 digits numeric value representing the current year
     */
    public static function getCurrentYear(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('Y');
    }


    /**
     * Get the month based on current system date and timezone as a numeric value from 1 to 12
     *
     * @return int A value between 1 and 12
     */
    public static function getCurrentMonth(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('n');
    }


    /**
     * Get the day based on current system date and timezone as a numeric value from 1 to 31
     *
     * @return int The day of month as a value between 1 and 31
     */
    public static function getCurrentDay(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('j');
    }


    /**
     * Get the numeric day of week (between 1 and 7) based on current system time, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return int A numeric value between 1 and 7  (where Sunday is 1, Monday is 2, ...)
     */
    public static function getCurrentDayOfWeek(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('w') + 1;
    }


    /**
     * Get the hour based on current system date and timezone as a numeric value from 0 to 23
     *
     * @return int The hour as a value between 0 and 23
     */
    public static function getCurrentHour(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('H');
    }


    /**
     * Get the minute based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return int The minute as a value between 0 and 59
     */
    public static function getCurrentMinute(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('i');
    }


    /**
     * Get the seconds based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return int The seconds as a value between 0 and 59
     */
    public static function getCurrentSecond(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('s');
    }


    /**
     * Get the miliseconds based on current system date and timezone as a numeric value up to 3 digits
     *
     * @return int The miliseconds as a value up to 3 digits
     */
    public static function getCurrentMiliSecond(){

        return round(DateTimeObject::getCurrentMicroSecond() / 1000);
    }


    /**
     * Get the microseconds based on current system date and timezone as a numeric value up to 6 digits
     *
     * @return int The microseconds as a value up to 6 digits
     */
    public static function getCurrentMicroSecond(){

        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

        return $now->format('u');
    }


    /**
     * This method compares two dateTime values and tells if they are exactly the same or
     * which one represents a later time value than the other.
     * Timezones from the specified dateTime values are taken into consideration for the comparison.
     *
     * @param mixed $dateTime1 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     * @param mixed $dateTime2 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws UnexpectedValueException
     *
     * @return int 0 If the two dateTime values represent the exact same time, 1 if dateTime1 > dateTime2 or 2 if dateTime2 > dateTime1
     */
    public static function compare($dateTime1, $dateTime2){

        if(self::isValidDateTime($dateTime1) && self::isValidDateTime($dateTime2)){

            if(is_string($dateTime1)){

                $dateTime1 = new DateTimeObject($dateTime1);
            }

            if(is_string($dateTime2)){

                $dateTime2 = new DateTimeObject($dateTime2);
            }

            $dateTime1->convertToUTCTimeZone();
            $dateTime2->convertToUTCTimeZone();

            $date1 = $dateTime1->toString();
            $date2 = $dateTime2->toString();

            if($date1 === $date2){

                return 0;
            }

            $sortedDates = [$date1, $date2];

            sort($sortedDates, SORT_STRING);

            return ($sortedDates[0] == $date1) ? 2 : 1;
        }

        throw new UnexpectedValueException('DateTimeObject->compare : Provided value is not a valid ISO 8601 date time format');
    }


    /**
     * Get this instance's defined year as a numeric value
     *
     * @return int A 4 digits numeric value or -1 if no year information is available
     */
    public function getYear(){

        $parsedDate = explode('-', $this->_dateTimeString);

        if(count($parsedDate) >= 1){

            return (int) $parsedDate[0];
        }

        return -1;
    }


    /**
     * Get this instance's defined month as a numeric value from 1 to 12
     *
     * @return int A value between 1 and 12 or -1 if no month information is available
     */
    public function getMonth(){

        $parsedDate = explode('-', $this->_dateTimeString);

        if(count($parsedDate) >= 2){

            return (int) substr($parsedDate[1], 0, 2);
        }

        return -1;
    }


    /**
     * Get this instance's defined month name as an english upper case string
     *
     * @return string The month name in english and with capital letters, like for example: JANUARY, FEBRUARY...
     */
    public function getMonthName(){

        self::getMonthName($this->getMonth());
    }


    /**
     * Get this instance's defined day as a numeric value from 1 to 31
     *
     * @return int A value between 1 and 31 or -1 if no day information is available
     */
    public function getDay(){

        $parsedDate = explode('-', $this->_dateTimeString);

        if(count($parsedDate) >= 3){

            return (int) substr($parsedDate[2], 0, 2);
        }

        return -1;
    }


    /**
     * Get this instance's defined month name as an english upper case string
     *
     * @return string The month name in english and with capital letters, like for example: JANUARY, FEBRUARY...
     */
    public function getDayName(){

        self::getDayName($this->getDay());
    }


    /**
     * Get this instance's defined day of week as a numeric value from 1 to 7, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return int A numeric value between 1 and 7 or -1 if no day of week information is available
     */
    public function getDayOfWeek(){

        $parsedDate = explode('-', $this->_dateTimeString);

        if(count($parsedDate) >= 3){

            $dateTimeInstance = new DateTime();

            $dateTimeInstance->setDate($parsedDate[0], $parsedDate[1], substr($parsedDate[2], 0, 2));

            return $dateTimeInstance->format('w') + 1;
        }

        return -1;
    }


    /**
     * Get this instance's defined hour as a numeric value from 0 to 23
     *
     * @return int A value between 0 and 23 or -1 if no hour information is available
     */
    public function getHour(){

        $parsedDate = explode('-', $this->_dateTimeString);

        if(count($parsedDate) >= 3 && strlen($parsedDate[2]) > 2){

            return (int) substr($parsedDate[2], 3, 2);
        }

        return -1;
    }


    /**
     * Get this instance's defined minute as a numeric value from 0 to 59
     *
     * @return int A value between 0 and 59 or -1 if no minutes information is available
     */
    public function getMinute(){

        $parsedDate = explode(':', $this->_dateTimeString);

        if(count($parsedDate) > 1){

            return (int) substr($parsedDate[1], 0, 2);
        }

        return -1;
    }


    /**
     * Get this instance's defined second as a numeric value from 0 to 59
     *
     * @return int A value between 0 and 59 or -1 if no seconds information is available
     */
    public function getSecond(){

        $parsedDate = explode(':', $this->_dateTimeString);

        if(count($parsedDate) > 2){

            return (int) substr($parsedDate[2], 0, 2);
        }

        return -1;
    }


    /**
     * Get this instance's defined miliseconds as a numeric value up to 3 digit
     *
     * @return int A value up to 3 digit or -1 if no miliseconds information is available
     */
    public function getMiliSecond(){

        $result = $this->getMicroSecond();

        if($result >= 0){

            $result = round($result / 1000);
        }

        return $result;
    }


    /**
     * Get this instance's defined microseconds as a numeric value up to 6 digit
     *
     * @return int A value up to 6 digit or -1 if no microseconds information is available
     */
    public function getMicroSecond(){

        $parsedDate = explode('.', $this->_dateTimeString);

        if(count($parsedDate) == 2){

            $parsedDate = $parsedDate[1];
            $parsedDate = str_replace('-', '+', $parsedDate);
            $parsedDate = explode('+', $parsedDate);
            $parsedDate = preg_replace('/[^0-9]/', '', $parsedDate[0]);

            return (int) str_pad($parsedDate, 6, '0', STR_PAD_RIGHT);
        }

        return -1;
    }


    /**
     * Get this instance's defined timezone offset as a numeric value (in seconds)
     *
     * @return int The UTC timezone offset in seconds
     */
    public function getTimeZoneOffset(){

        $dateTime = $this->_dateTimeString;

        if(substr_count($dateTime, '-') != 3 && substr_count($dateTime, '+') != 1){

            return 0;
        }

        return (new DateTime($dateTime))->getOffset();
    }


    /**
     * Get the first day of month for the current dateTime value.
     *
     * @return DateTimeObject A dateTime object representing the first day of month based on the current instance
     */
    public function getFirstDayOfMonth(){

        return (new DateTime($this->_dateTimeString))->format('Y-m-01\\TH:i:s.uP');
    }


    /**
     * Get the last day of month for the current dateTime value.
     *
     * @return DateTimeObject A dateTime object representing the last day of month based on the current instance
     */
    public function getLastDayOfMonth(){

        return (new DateTime($this->_dateTimeString))->format('Y-m-t\\TH:i:s.uP');
    }


    /**
     * Output the current dateTime instance data as a custom formatted string.
     *
     * @param string $formatString A string containing the output format like 'd/m/Y' or 'm-d-y'
     * where the following characters will be automatically replaced:<br><br>
     * - Y with a four digit year value<br>
     * - y with a one or two digit year value<br>
     * - M with a two digit month value<br>
     * - m with a one or two digit month value<br>
     * - D with a two digit day value<br>
     * - d with a one or two digit day value<br>
     * - H with a two digit hour value<br>
     * - h with a one or two digit hour value<br>
     * - N with a two digit minutes value<br>
     * - n with a one or two digit minutes value<br>
     * - S with a two digit seconds value<br>
     * - s with a one or two digit seconds value<br>
     * - U with a 6 digit microseconds value<br>
     * - u with a 3 digit miliseconds value
     *
     * @return string The dateTime with the specified format.
     */
    public function toString($formatString = 'Y-M-DTH:N:S.U+TimeZone'){

        if(($year = self::getYear($this->_dateTimeString)) > 0){

            $formatString = str_replace('Y', $year, $formatString);
            $formatString = str_replace('y', substr($year, 2), $formatString);
        }

        if(($month = self::getMonth($this->_dateTimeString)) > 0){

            $formatString = str_replace('M', str_pad($month, 2, '0', STR_PAD_LEFT), $formatString);
            $formatString = str_replace('m', (int)$month, $formatString);
        }

        if(($day = self::getDay($this->_dateTimeString)) > 0){

            $formatString = str_replace('D', str_pad($day, 2, '0', STR_PAD_LEFT), $formatString);
            $formatString = str_replace('d', (int)$day, $formatString);
        }

        if(($hour = self::getHour($this->_dateTimeString)) >= 0){

            $formatString = str_replace('H', str_pad($hour, 2, '0', STR_PAD_LEFT), $formatString);
            $formatString = str_replace('h', (int)$hour, $formatString);
        }

        if(($minutes = self::getMinutes($this->_dateTimeString)) >= 0){

            $formatString = str_replace('N', str_pad($minutes, 2, '0', STR_PAD_LEFT), $formatString);
            $formatString = str_replace('n', (int)$minutes, $formatString);
        }

        if(($seconds = self::getSeconds($this->_dateTimeString)) >= 0){

            $formatString = str_replace('S', str_pad($seconds, 2, '0', STR_PAD_LEFT), $formatString);
            $formatString = str_replace('s', (int)$seconds, $formatString);
        }

        if(($miliSeconds = self::getMiliSeconds($this->_dateTimeString)) >= 0){

            $formatString = str_replace('u', str_pad($miliSeconds, 3, '0', STR_PAD_LEFT), $formatString);
        }

        if(($microSeconds = self::getMicroSeconds($this->_dateTimeString)) >= 0){

            $formatString = str_replace('U', str_pad($microSeconds, 6, '0', STR_PAD_LEFT), $formatString);
        }

        return $formatString;
    }


    /**
     * Compares the current datetime instance values to the given one and tells if they
     * are exactly the same or which one represents a later time value than the other.
     * Timezones from the both dateTime values are taken into consideration for the comparison.
     *
     * @param mixed $dateTime A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws UnexpectedValueException
     *
     * @see DateTimeObject::compare
     *
     * @return int 0 If the two dateTime values represent the exact same time, 1 if this instance > dateTime or 2 if dateTime > this instance
     */
    public function compareTo($dateTime){

        return self::compare($this, $dateTime);
    }


    /**
     * Check if the provided ISO 8601 dateTime value is identical to the date and time from this instance
     *
     * @param mixed $dateTime A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @return boolean True if both dateTime values are equivalent to the exact same date and time
     */
    public function isEqualTo($dateTime){

        return self::compare($this, $dateTime) === 0;
    }


    /**
     * Adds the specified amount of time to the given dateTime value
     * TODO - This method currently only works with years, and PHPDoc is incomplete!
     *
     * @param string $value The numeric amount that will be added to this DateTimeObject instance
     * @param string $type TODO - this paramenter description
     *
     * @return void
     */
    public function add(int $value, $type = 'minutes'){

        switch (strtolower($type)) {

            case 'years':
                return (substr($dateTime, 0, 4) + $value).substr($dateTime, 4);

            case 'months':
                throw new UnexpectedValueException('DateTimeUtils->add : months type is not implemented yet');
                break;

            case 'days':
                throw new UnexpectedValueException('DateTimeUtils->add : days type is not implemented yet');
                break;

            case 'hours':
                throw new UnexpectedValueException('DateTimeUtils->add : hours type is not implemented yet');
                break;

            case 'minutes':
                throw new UnexpectedValueException('DateTimeUtils->add : minutes type is not implemented yet');
                break;

            case 'seconds':
                throw new UnexpectedValueException('DateTimeUtils->add : seconds type is not implemented yet');
                break;

            case 'miliseconds':
                throw new UnexpectedValueException('DateTimeUtils->add : miliseconds type is not implemented yet');
                break;

            case 'microseconds':
                throw new UnexpectedValueException('DateTimeUtils->add : microseconds type is not implemented yet');
                break;

            default:
                throw new UnexpectedValueException('DateTimeUtils->add : Invalid type specified');
        }
    }


    // TODO - This method is pending
    public function substract($value, $type = 'minutes'){

        return $this->add(-$value, $type);
    }
}

?>