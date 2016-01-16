<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Operations that are used to make transformations on date values
 */
class DateUtils {


	/** List of day names, used by some class methods */
	private static $_days = array('SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');


	/** List of month names, used by some class methods */
	private static $_months = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');


	/**
	 * Get the current date in the yyyy-mm-dd format. Use conversion utils if you need it in another format
	 *
	 * @param boolean $includeTime Set it to true to get als the hour/minute/second information. False by default
	 *
	 * @return string the current date
	 */
	public static function getCurrentDate($includeTime = false){

		return $includeTime ? date('Y-m-d H:i:s') : date('Y-m-d');
	}


	/**
	 * Get the current month day based on system time
	 *
	 * @return int the current day from 1 to 31
	 */
	public static function getCurrentDay(){

		return date('j');
	}


	/**
	 * Get the current day of week based on system time
	 *
	 * @return int the current day of week from 1 to 7 (where Sunday is 1, Monday is 2, ...)
	 */
	public static function getCurrentDayOfWeek(){

		return date('w') + 1;
	}


	/**
	 * Get the current month based on system time
	 *
	 * @return int the current month from 1 to 12
	 */
	public static function getCurrentMonth(){

		return date('n');
	}


	/**
	 * Get the current year based on system time
	 *
	 * @return int the current year
	 */
	public static function getCurrentYear(){

		return date('Y');
	}


	/**
	 * Extract the day from a given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return int The day of month from the specified date, between 1 and 31
	 */
	public static function getDay($date){

		$time = strtotime($date);

		return date('d', $time);
	}


	/**
	 * Get the day of week based on the specified date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return int the day of week from 1 to 7 (where Sunday is 1, Monday is 2, ...)
	 */
	public static function getDayOfWeek($date){

		$time = strtotime($date);

		return date('w', $time) + 1;
	}


	/**
	 * Extract the numeric month from a given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return int The number of the month, between 1 and 12
	 */
	public static function getMonth($date){

		$time = strtotime($date);

		return date('m', $time);
	}


	/**
	 * Extract the numeric year from a given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return int The number of the year with 4 digits
	 */
	public static function getYear($date){

		$time = strtotime($date);

		return date('Y', $time);
	}



	/**
	 * Returns the day name from a numeric day value
	 *
	 * @param int $day A day number from 1 to 7 (SUNDAY = 1)
	 *
	 * @return string
	 */
	public static function getDayName($day){

		return  self::$_days[$day - 1];
	}


	/**
	 * Returns the month name from a numeric month value
	 *
	 * @param int $month A month number from 1 to 12
	 *
	 * @return string the month name in english and with capital letters, so we can use it with localized constants, for example: constant('LOC_'.DateUtils::getMonthName(1))
	 */
	public static function getMonthName($month){

		return  self::$_months[$month - 1];
	}


	/**
	 * Get the first day for the received date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return string A date representing the last day of month for the specified date
	 */
	public static function getFirstDayOfMonthDate($date){

		return date('Y-m-01', strtotime($date));
	}


	/**
	 * Get the last day for the received date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 *
	 * @return string A date representing the last day of month for the specified date
	 */
	public static function getLastDayOfMonthDate($date){

		return date('Y-m-t', strtotime($date));
	}


	/**
	 * Get the next business (laborable) day following the specified date.
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param boolean $allowSaturday Set it to true to consider the saturday as a business day. False by default.
	 * @param array $nonBusinessDays A list with dates that must be explicitly considered as non business days, so this method will not return any of them. Instead, the first non business day found will be returned.
	 *
	 * @return string A date representing the following day that is a business day
	 */
	public static function getNextBusinessDay($date, $allowSaturday = false, array $nonBusinessDays = null){

		$res = '';

		if($allowSaturday && self::getDayOfWeek($date) == 6){

			$res = date('Y-m-d', strtotime($date.' +1 day'));

		}else{

			$res = date('Y-m-d', strtotime($date.' +1 Weekday'));
		}

		if(count($nonBusinessDays) > 0){

			foreach ($nonBusinessDays as $nonBusinessDay){

				if($res == date('Y-m-d', strtotime($nonBusinessDay))){

					return self::getNextBusinessDay($res, $allowSaturday, $nonBusinessDays);
				}
			}
		}

		return $res;
	}


	/**
	 * Get a list with the N business days following the specified date.
	 * NOTE: The given date is not included on the resulting list
	 *
	 * @param int $number The number of business days to return
	 * @param string $date The starting date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param boolean $allowSaturday See DateUtils::getNextBusinessDay
	 * @param array $nonBusinessDays See DateUtils::getNextBusinessDay
	 *
	 * @see DateUtils::getNextBusinessDay
	 *
	 * @return array A list with the first consecutive business days found after the given date.
	 */
	public static function getNextBusinessDays($number, $date, $allowSaturday = false, array $nonBusinessDays = null){

		$res = array();

		$nextDay = $date;

		for($i = 0; $i < $number; $i ++){

			$nextDay = self::getNextBusinessDay($nextDay, $allowSaturday, $nonBusinessDays);

			array_push($res, $nextDay);
		}

		return $res;
	}


	/**
	 * Adds the specified amount of days to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of days to add
	 *
	 * @return string A date representing the specified date plus the number of specified days
	 */
	public static function addDays($date, $n){

		if($n < 0){

			trigger_error('Dateutils::addDays Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' day'));
		}
	}


	/**
	 * Adds the specified amount of weeks to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of weeks to add
	 *
	 * @return string A date representing the specified date plus the number of specified weeks
	 */
	public static function addWeeks($date, $n){

		if($n < 0){

			trigger_error('Dateutils::addWeeks Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' week'));
		}
	}


	/**
	 * Adds the specified amount of months to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of months to add
	 *
	 * @return string A date representing the specified date plus the number of specified months
	 */
	public static function addMonths($date, $n){

		if($n < 0){

			trigger_error('Dateutils::addMonths Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' month'));
		}
	}


	/**
	 * Adds the specified amount of years to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of years to add
	 *
	 * @return string A date representing the specified date plus the number of specified years
	 */
	public static function addYears($date, $n){

		if($n < 0){

			trigger_error('Dateutils::addYears Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' +'.$n.' year'));
		}
	}


	/**
	 * Substracts the specified amount of days to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of days to substract
	 *
	 * @return string A date representing the specified date minus the number of specified days
	 */
	public static function substractDays($date, $n){

		if($n < 0){

			trigger_error('Dateutils::substractDays Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' day'));
		}
	}


	/**
	 * Substracts the specified amount of weeks to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of weeks to substract
	 *
	 * @return string A date representing the specified date minus the number of specified weeks
	 */
	public static function substractWeeks($date, $n){

		if($n < 0){

			trigger_error('Dateutils::substractWeeks Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' week'));
		}
	}


	/**
	 * Substracts the specified amount of months to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of months to substract
	 *
	 * @return string A date representing the specified date minus the number of specified months
	 */
	public static function substractMonths($date, $n){

		if($n < 0){

			trigger_error('Dateutils::substractMonths Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' month'));
		}
	}


	/**
	 * Substracts the specified amount of years to the given date
	 *
	 * @param string $date A date in the yyyy-mm-dd format. Use conversion utils if your date is not in this format
	 * @param int $n An integer indicating the amount of years to substract
	 *
	 * @return string A date representing the specified date minus the number of specified years
	 */
	public static function substractYears($date, $n){

		if($n < 0){

			trigger_error('Dateutils::substractYears Error: An unsigned integer is expected', E_USER_WARNING);

			return '';

		}else{

			return date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)).' -'.$n.' year'));
		}
	}

}

?>