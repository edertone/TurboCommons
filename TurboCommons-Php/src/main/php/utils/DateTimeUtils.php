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
	 * String that defines the ISO 8601 formatto be used when calling the format method on DateTime Php class.
	 */
	const ISO8601_FORMAT_STRING = 'Y-m-d\\TH:i:s.uP';


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

		return $now->format(self::ISO8601_FORMAT_STRING);
	}


	// TODO - This method is pending
	public static function getDateTimeFromLocalValues($year, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0){

		// TODO - This method is incomplete and pending

		$dateTime = new DateTime(null, new DateTimeZone(date_default_timezone_get()));

		$dateTime->setDate($year, $month, $day);
		$dateTime->setTime($hour, $minute, $second);
		$dateTime->setTimezone(new DateTimeZone('UTC'));

		return $dateTime->format(self::ISO8601_FORMAT_STRING);
	}


	/**
	 * Extract the day from a given dateTime as a numeric value from 1 to 31.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day information (like: 2015-12-31...)
	 *
	 * @return int The day of month from the specified dateTime between 1 and 31.
	 * If the specified dateTime does not contain valid day information, an exception will be thrown
	 */
	public static function getDay($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 3){

				return (int) substr($parsedDate[2], 0, 2);
			}
		}

		throw new UnexpectedValueException('DateTimeUtils->getDay : Provided value is not a valid ISO 8601 date time format or contains invalid day value.');
	}


	/**
	 * Get the numeric day of week (between 1 and 7) from the specified dateTime value, where Sunday is considered
	 * to be the first one:<br>
	 * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day information (like: 2015-12-31...)
	 *
	 * @return int A numeric value between 1 and 7, or an exception if an invalid dateTime value was provided.
	 */
	public static function getDayOfWeek($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 3){

				$dateTimeInstance = new DateTime();

				$dateTimeInstance->setDate($parsedDate[0], $parsedDate[1], substr($parsedDate[2], 0, 2));

				return $dateTimeInstance->format('w') + 1;
			}
		}

		throw new UnexpectedValueException('DateTimeUtils->getDayOfWeek : Provided value is not a valid ISO 8601 date time format or contains invalid date value.');
	}


	/**
	 * Extract the month from a given dateTime as a numeric value from 1 to 12.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month information (like: 2015-12..)
	 *
	 * @return int A value between 1 and 12 or an exception if invalid value is provided.
	 */
	public static function getMonth($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 2){

				return (int) substr($parsedDate[1], 0, 2);
			}
		}

		throw new UnexpectedValueException('DateTimeUtils->getMonth : Provided value is not a valid ISO 8601 date time format or contains invalid month value.');
	}


	/**
	 * Extract the year from a given dateTime as a numeric value.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year information (like: 2015...)
	 *
	 * @return int A numeric value or an exception if invalid value is provided.
	 */
	public static function getYear($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 1){

				return (int) $parsedDate[0];
			}
		}

		throw new UnexpectedValueException('DateTimeUtils->getYear : Provided value is not a valid ISO 8601 date time format or contains invalid year value.');
	}


	// TODO - This method is pending
	public static function getCurrentDay(){
		// TODO - This method is pending
		//return date('w') + 1;
	}


	/**
	 * Get the current day of week based on system time
	 *
	 * @return int the current day of week from 1 to 7 (where Sunday is 1, Monday is 2, ...)
	 */
	public static function getCurrentDayOfWeek(){
		// TODO - This method is pending
		//return date('w') + 1;
	}


	/**
	 * Get the current month based on system time
	 *
	 * @return int the current month from 1 to 12
	 */
	public static function getCurrentMonth(){
		// TODO - This method is pending
		//return date('n');
	}


	/**
	 * Get the current year based on system time
	 *
	 * @return int the current year
	 */
	public static function getCurrentYear(){
		// TODO - This method is pending
		//return date('Y');
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
	 * Convert a valid dateTime value to the timezone that is defined on
	 * the current system.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value.
	 *
	 * @return string A valid ISO 8601 dateTime value that represents the same date and time info as the received value, but for the current local timezone.
	 */
	public static function convertToLocalTimeZone($dateTime){

		if(!self::isValidDateTime($dateTime)){

			throw new UnexpectedValueException('DateTimeUtils->convertToLocalTimeZone : Provided value is not a valid ISO 8601 date time format.');
		}

		$dateTimeInstance = new DateTime($dateTime);

		$dateTimeInstance->setTimezone(new DateTimeZone(date_default_timezone_get()));

		return $dateTimeInstance->format(self::ISO8601_FORMAT_STRING);
	}


	// TODO - This method is pending
	public static function format($dateTime){

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