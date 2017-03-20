<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use DateTime;
use DateTimeZone;
use UnexpectedValueException;


/**
 * Operations related with obtaining and manipulating date and time values.
 * All the class methods use and expect values that follow the ISO 8601 format, which is the international standard for the
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
 */
class DateTimeUtils {


	/**
	 * DateTimeUtils class operates only with ISO 8601 strings, which is the international standard for the representation of dates and times.
	 * Therefore, this method considers a dateTime string value to be valid only if it follows the standard.
	 *
	 * @param string $dateTime A string containing a valid ISO 8601 date/time value.
	 *
	 * @see DateTimeUtils
	 *
	 * @return true if the specified value is ISO 8601, false if value is not a string or contains invalid information.
	 */
	public static function isValidDateTime($dateTime){

		if($dateTime == null || !is_string($dateTime)){

			return false;
		}

		// Validate that string ends only with alphanumeric values
		if(!ctype_alnum(substr($dateTime, -1))){

			return false;
		}

		$regex = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';

		return (preg_match($regex, $dateTime) > 0);
	}


	/**
	 * Given a valid ISO 8601 dateTime value, this method will check if its timezone is the same as
	 * the one currently defined on this computer.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value.
	 *
	 * @see DateTimeUtils
	 *
	 * @return boolean True if the time zone on $dateTime is exactly the same as the one defined on this computer.
	 */
	public static function isLocalTimeZone($dateTime){

		if(!self::isValidDateTime($dateTime)){

			throw new UnexpectedValueException('DateTimeUtils->isLocalTimeZone : Provided value is not a valid ISO 8601 date time format.');
		}

		$dateTimeInstance = new DateTime($dateTime);
		$localTimeInstance = new DateTime();

		return ($dateTimeInstance->getOffset() === $localTimeInstance->getOffset());
	}


	/**
	 * Generates an ISO 8601 string containing the current full date and time with microseconds precision.
	 * The value is formatted using UTC, so it does not have a timezone offset. To get the local timezone
	 * date and time, use DateTimeUtils::convertToLocalTimeZone().
	 *
	 * @see DateTimeUtils
	 * @see DateTimeUtils::convertToLocalTimeZone
	 *
	 * @return string The current date and time as a valid full ISO 8601 UTC value (no timezone offset).
	 */
	public static function getDateTimeNow(){

		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

		return $now->format('Y-m-d\\TH:i:s.uP');
	}


	// TODO - This method is pending
	public static function getDateTimeFromLocalValues($year, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0){

		// TODO - this is a bit complicated

		$dateTime = new DateTime(null, new DateTimeZone(date_default_timezone_get()));

		$dateTime->setDate($year, $month, $day);
		$dateTime->setTime($hour, $minute, $second);
		$dateTime->setTimezone(new DateTimeZone('UTC'));

		return $dateTime->format('Y-m-d\\TH:i:s.uP');
	}


	// TODO - This method is pending
	public static function convertToLocalTimeZone($dateTime){

		if(!self::isValidDateTime($dateTime)){

			throw new UnexpectedValueException('DateTimeUtils->convertToLocalTimeZone : Provided value is not a valid ISO 8601 date time format.');
		}


	}


	// TODO - This method is pending
	public static function format($dateTime){

	}


	// TODO - This method is pending
	public static function getDay($dateTime){
// 		$time = strtotime($date);

// 		return date('d', $time);
// 		return date('j');
	}


	// TODO - This method is pending
	public static function getDayOfWeek(){
// 		$time = strtotime($date);

// 		return date('w', $time) + 1;
// 		return date('w') + 1;
	}


	// TODO - This method is pending
	public static function getMonth(){

// 		$time = strtotime($date);

// 		return date('m', $time);
// 		return date('n');
	}


	// TODO - This method is pending
	public static function getYear(){

// 		$time = strtotime($date);

// 		return date('Y', $time);
// 		return date('Y');
	}


	/**
	 * Returns the day name from a numeric day value
	 *
	 * @param int $day A day number from 1 to 7 (SUNDAY = 1)
	 *
	 * @return string
	 */
	public static function getDayName($day){

		// TODO - This method is pending

	/** List of day names, used by some class methods */
		//private static $_days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

		//return  self::$_days[$day - 1];
	}


	/**
	 * Returns the month name from a numeric month value
	 *
	 * @param int $month A month number from 1 to 12
	 *
	 * @return string the month name in english and with capital letters, so we can use it with localized constants, for example: constant('LOC_'.DateUtils::getMonthName(1))
	 */
	public static function getMonthName($month){

		// TODO - This method is pending

		/** List of month names, used by some class methods */
		//private static $_months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

		//return  self::$_months[$month - 1];
	}


	/**
	 * Get the first day for the received date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return string A date representing the last day of month for the specified date
	 */
	// TODO - This method is pending
	public static function getFirstDayOfMonth($dateTime){

// 		return date('Y-m-01', strtotime($dateTime));
	}


	/**
	 * Get the last day for the received date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return string A date representing the last day of month for the specified date
	 */
	// TODO - This method is pending
	public static function getLastDayOfMonth($dateTime){

// 		return date('Y-m-t', strtotime($dateTime));
	}


	/**
	 * Adds the specified amount of days to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of days to add
	 *
	 * @return string A date representing the specified date plus the number of specified days
	 */
	// TODO - This method is pending
	public static function add($dateTime, $years = 0, $months = 0, $weeks, $days = 0, $seconds = 0, $microseconds = 0){

// 		if($n < 0){

// 			trigger_error('Dateutils::addDays Error: An unsigned integer is expected', E_USER_WARNING);

// 			return '';

// 		}else{

// 			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' day'));
// 		}

// 		public static function addWeeks($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::addWeeks Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' week'));
// 			}
// 		}

// 		public static function addMonths($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::addMonths Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' month'));
// 			}
// 		}

// 		public static function addYears($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::addYears Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' year'));
// 			}
// 		}
	}


	/**
	 * Substracts the specified amount of days to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of days to substract
	 *
	 * @return string A date representing the specified date minus the number of specified days
	 */
	// TODO - This method is pending
	public static function substract($dateTime, $years = 0, $months = 0, $weeks, $days = 0, $seconds = 0, $microseconds = 0){

// 		if($n < 0){

// 			trigger_error('Dateutils::substractDays Error: An unsigned integer is expected', E_USER_WARNING);

// 			return '';

// 		}else{

// 			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' day'));
// 		}

// 		public static function substractWeeks($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::substractWeeks Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' week'));
// 			}
// 		}

// 		public static function substractMonths($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::substractMonths Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' month'));
// 			}
// 		}


// 		public static function substractYears($dateTime, $n){

// 			if($n < 0){

// 				trigger_error('Dateutils::substractYears Error: An unsigned integer is expected', E_USER_WARNING);

// 				return '';

// 			}else{

// 				return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' year'));
// 			}
// 		}
	}


	// TODO - This method is pending
	public static function compare(){

	}
}

?>