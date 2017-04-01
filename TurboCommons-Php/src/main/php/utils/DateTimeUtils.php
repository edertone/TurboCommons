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
	private static $_iso8601FormatString = 'Y-m-d\\TH:i:s.uP';


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

		return false;
	}


	public static function isSameDateTime($dateTime1, $dateTime2){

		// TODO

	}


	/**
	 * Given two valid ISO 8601 dateTime values, this method will check if they have the same timezone offset.
	 *
	 * @param string $dateTime1 A valid ISO 8601 dateTime value.
	 * @param string $dateTime2 A valid ISO 8601 dateTime value.
	 *
	 * @see DateTimeUtils
	 *
	 * @return boolean True if the time zone on $dateTime is exactly the same as the one defined on this computer.
	 */
	public static function isSameTimeZone($dateTime1, $dateTime2){

		if(self::isValidDateTime($dateTime1) && self::isValidDateTime($dateTime2)){

			return self::getTimeZoneOffset($dateTime1) === self::getTimeZoneOffset($dateTime2);
		}

		throw new UnexpectedValueException('DateTimeUtils->isSameTimeZone : Provided values are not in valid ISO 8601 date time format');
	}


	// TODO - This method is pending
	public static function getDateTimeFromLocalValues($year, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0){

		// TODO - This method is incomplete and pending

		$dateTime = new DateTime(null, new DateTimeZone(date_default_timezone_get()));

		$dateTime->setDate($year, $month, $day);
		$dateTime->setTime($hour, $minute, $second);
		$dateTime->setTimezone(new DateTimeZone('UTC'));

		return $dateTime->format(self::$_iso8601FormatString);
	}


	/**
	 * Extract the microseconds from a given dateTime as a numeric value up to 6 digit.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing microseconds information (like: 2015-12-31T18:30:45.458763)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The microseconds from the specified dateTime or -1 if no microseconds information was available
	 */
	public static function getMicroSeconds($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('.', $dateTime);

			if(count($parsedDate) == 2){

				$parsedDate = $parsedDate[1];
				$parsedDate = str_replace('-', '+', $parsedDate);
				$parsedDate = explode('+', $parsedDate);
				$parsedDate = preg_replace('/[^0-9]/', '', $parsedDate[0]);

				return (int) str_pad($parsedDate, 6, '0', STR_PAD_RIGHT);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getMicroSeconds : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the miliseconds from a given dateTime as a numeric value up to 3 digit.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing miliseconds information (like: 2015-12-31T18:30:45.458)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The miliseconds from the specified dateTime or -1 if no miliseconds information was available
	 */
	public static function getMiliSeconds($dateTime){

	    $result = self::getMicroSeconds($dateTime);

	    if($result >= 0){

	        $result = (int) substr(str_pad($result, 6, '0', STR_PAD_LEFT), 0, 3);
	    }

	    return $result;
	}


	/**
	 * Extract the seconds from a given dateTime as a numeric value from 0 to 59.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day-hours-minutes-seconds information (like: 2015-12-31T18:30:45...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The seconds from the specified dateTime or -1 if no seconds information was available
	 */
	public static function getSeconds($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode(':', $dateTime);

			if(count($parsedDate) > 2){

				return (int) substr($parsedDate[2], 0, 2);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getSeconds : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the minutes from a given dateTime as a numeric value from 0 to 59.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day-hours-minutes information (like: 2015-12-31T18:30...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The minutes from the specified dateTime or -1 if no minutes information was available
	 */
	public static function getMinutes($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode(':', $dateTime);

			if(count($parsedDate) > 1){

				return (int) substr($parsedDate[1], 0, 2);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getMinutes : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the hour from a given dateTime as a numeric value from 0 to 23.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day-hours information (like: 2015-12-31T18...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The hour from the specified dateTime between 0 and 23 or -1 if no hour information was available
	 */
	public static function getHour($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 3 && strlen($parsedDate[2]) > 2){

				return (int) substr($parsedDate[2], 3, 2);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getHour : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the day from a given dateTime as a numeric value from 1 to 31.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day information (like: 2015-12-31...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int The day of month from the specified dateTime between 1 and 31 or -1 if no day information was available
	 */
	public static function getDay($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 3){

				return (int) substr($parsedDate[2], 0, 2);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getDay : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Get the numeric day of week (between 1 and 7) from the specified dateTime value, where Sunday is considered
	 * to be the first one:<br>
	 * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month-day information (like: 2015-12-31...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int A numeric value between 1 and 7 or -1 if no date information was available
	 */
	public static function getDayOfWeek($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 3){

				$dateTimeInstance = new DateTime();

				$dateTimeInstance->setDate($parsedDate[0], $parsedDate[1], substr($parsedDate[2], 0, 2));

				return $dateTimeInstance->format('w') + 1;
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getDayOfWeek : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the month from a given dateTime as a numeric value from 1 to 12.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year-month information (like: 2015-12..)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int A value between 1 and 12 or -1 if no month information was available
	 */
	public static function getMonth($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 2){

				return (int) substr($parsedDate[1], 0, 2);
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getMonth : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Extract the year from a given dateTime as a numeric value.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year information (like: 2015...)
	 *
	 * @throws UnexpectedValueException If an invalid dateTime was provided
	 *
	 * @return int A 4 digits numeric value or -1 if no year information was available
	 */
	public static function getYear($dateTime){

		if(self::isValidDateTime($dateTime)){

			$parsedDate = explode('-', $dateTime);

			if(count($parsedDate) >= 1){

				return (int) $parsedDate[0];
			}

			return -1;
		}

		throw new UnexpectedValueException('DateTimeUtils->getYear : Provided value is not a valid ISO 8601 date time format');
	}


	/**
	 * Obtain the timezone offset (in seconds) that is defined on the specified dateTime value.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value
	 *
	 * @return int The UTC timezone offset in seconds
	 */
	public static function getTimeZoneOffset($dateTime){

		if(self::isValidDateTime($dateTime)){

			if(substr_count($dateTime, '-') != 3 && substr_count($dateTime, '+') != 1){

				return 0;
			}

			return (new DateTime($dateTime))->getOffset();
		}

		throw new UnexpectedValueException('DateTimeUtils->getTimeZoneOffset : Provided value is not a valid ISO 8601 date time format');
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
	public static function getCurrentDateTime(){

		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

		return $now->format(self::$_iso8601FormatString);
	}


	/**
	 * Get the current day of month based on system time as a numeric value from 1 to 31.
	 *
	 * @return int The day of month as a value between 1 and 31.
	 */
	public static function getCurrentDay(){

		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

		return $now->format('j');
	}


	/**
	 * Get the current numeric day of week (between 1 and 7) based on system time, where Sunday is considered
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
	 * Get the current month based on system time as a numeric value from 1 to 12.
	 *
	 * @return int A value between 1 and 12
	 */
	public static function getCurrentMonth(){

		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

		return $now->format('n');
	}


	/**
	 * Get the current year based on system time
	 *
	 * @return int A 4 digits numeric value representing the current year.
	 */
	public static function getCurrentYear(){

		$now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''), new DateTimeZone('UTC'));

		return $now->format('Y');
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

		if(!is_numeric($day) || $day > 7 || $day < 1){

			throw new UnexpectedValueException('DateTimeUtils->getDayName : Provided value is not a valid day number between 1 and 7');
		}

		$days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

		return $days[$day - 1];
	}


	/**
	 * Returns the month name from a numeric month value
	 *
	 * @param int $month A month number between 1 and 12
	 *
	 * @return string the month name in english and with capital letters, like: JANUARY, FEBRUARY, ...
	 */
	public static function getMonthName($month){

		if(!is_numeric($month) || $month > 12 || $month < 1){

			throw new UnexpectedValueException('DateTimeUtils->getMonthName : Provided value is not a valid month number between 1 and 12');
		}

		$months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

		return  $months[$month - 1];
	}


	/**
	 * Get the first day of month for the given dateTime value.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year and month information (like: 2015-10...)
	 *
	 * @return string A valid ISO8601 dateTime value representing the first day of month for the given dateTime value.
	 */
	public static function getFirstDayOfMonth($dateTime){

		if(self::isValidDateTime($dateTime) && count(explode('-', $dateTime)) >= 2){

			return (new DateTime($dateTime))->format('Y-m-01\\TH:i:s.uP');
		}

		throw new UnexpectedValueException('DateTimeUtils->getFirstDayOfMonth : Provided value is not a valid ISO 8601 date time format or contains invalid date value');
	}


	/**
	 * Get the last day of month for the given dateTime value.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value containing at least year and month information (like: 2015-10...)
	 *
	 * @return string A valid ISO8601 dateTime value representing the last day of month for the given dateTime value.
	 */
	public static function getLastDayOfMonth($dateTime){

		if(self::isValidDateTime($dateTime) && count(explode('-', $dateTime)) >= 2){

			return (new DateTime($dateTime))->format('Y-m-t\\TH:i:s.uP');
		}

		throw new UnexpectedValueException('DateTimeUtils->getLastDayOfMonth : Provided value is not a valid ISO 8601 date time format or contains invalid date value');
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

		if(self::isValidDateTime($dateTime)){

			if(count(explode('-', $dateTime)) >= 3){

				$dateTimeInstance = new DateTime($dateTime);

				$dateTimeInstance->setTimezone(new DateTimeZone(date_default_timezone_get()));

				return $dateTimeInstance->format(self::$_iso8601FormatString);

			}else{

				return $dateTime;
			}
		}

		throw new UnexpectedValueException('DateTimeUtils->convertToLocalTimeZone : Provided value is not a valid ISO 8601 date time format.');
	}


	public static function convertToUTCTimeZone($dateTime){

		// TODO
	}

	/**
	 * Output the specified dateTime value as a custom string.
	 *
	 * @param string $dateTime A valid ISO 8601 dateTime value.
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
	public static function format($dateTime, $formatString){

		if(self::isValidDateTime($dateTime)){

			if(($year = self::getYear($dateTime)) > 0){

				$formatString = str_replace('Y', $year, $formatString);
				$formatString = str_replace('y', substr($year, 2), $formatString);
			}

			if(($month = self::getMonth($dateTime)) > 0){

				$formatString = str_replace('M', str_pad($month, 2, '0', STR_PAD_LEFT), $formatString);
				$formatString = str_replace('m', (int)$month, $formatString);
			}

			if(($day = self::getDay($dateTime)) > 0){

				$formatString = str_replace('D', str_pad($day, 2, '0', STR_PAD_LEFT), $formatString);
				$formatString = str_replace('d', (int)$day, $formatString);
			}

			if(($hour = self::getHour($dateTime)) >= 0){

				$formatString = str_replace('H', str_pad($hour, 2, '0', STR_PAD_LEFT), $formatString);
				$formatString = str_replace('h', (int)$hour, $formatString);
			}

			if(($minutes = self::getMinutes($dateTime)) >= 0){

				$formatString = str_replace('N', str_pad($minutes, 2, '0', STR_PAD_LEFT), $formatString);
				$formatString = str_replace('n', (int)$minutes, $formatString);
            }

			if(($seconds = self::getSeconds($dateTime)) >= 0){

				$formatString = str_replace('S', str_pad($seconds, 2, '0', STR_PAD_LEFT), $formatString);
				$formatString = str_replace('s', (int)$seconds, $formatString);
			}

			if(($miliSeconds = self::getMiliSeconds($dateTime)) >= 0){

			    $formatString = str_replace('u', str_pad($miliSeconds, 3, '0', STR_PAD_LEFT), $formatString);
			}

			if(($microSeconds = self::getMicroSeconds($dateTime)) >= 0){

				$formatString = str_replace('U', str_pad($microSeconds, 6, '0', STR_PAD_LEFT), $formatString);
			}

			return $formatString;
		}

		throw new UnexpectedValueException('DateTimeUtils->format : Provided value is not a valid ISO 8601 date time format.');
	}


	// TODO - This method is pending
	public static function compare(){

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
}

?>