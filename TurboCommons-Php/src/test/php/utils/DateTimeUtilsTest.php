<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\utils;

use Exception;
use PHPUnit_Framework_TestCase;
use org\turbocommons\src\main\php\utils\DateTimeUtils;


/**
 * DateTimeUtilsTest
 *
 * @return void
 */
class DateTimeUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * @see PHPUnit_Framework_TestCase::setUpBeforeClass()
	 *
	 * @return void
	 */
	public static function setUpBeforeClass(){

		// Used to standarize tests. This value is automatically restored after the script ends.
		date_default_timezone_set('Europe/Berlin');
	}


	/**
	 * @see PHPUnit_Framework_TestCase::setUp()
	 *
	 * @return void
	 */
	protected function setUp(){

		// Define a list of dateTime values that must be considered as invalid by the class methods
	    $this->invalidValues = [null, [], '', 1, 'a', '   ', [1,5,6,6], '2009-05-19T14a39r', 1234, new Exception(), '2015-12-15  ', '2015-12-15T1', '2015-12-15T13:', '0000-00-00T00:00:00.000000+00:00', '2010-02-18t16:23:48.54123-01:00', '2015-05-25T18T16:23:48', '2008-18', '2015-01-32', '2015-00-32'];
	}


	/**
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 *
	 * @return void
	 */
	protected function tearDown(){

		// Nothing necessary here
	}


	/**
	 * @see PHPUnit_Framework_TestCase::tearDownAfterClass()
	 *
	 * @return void
	 */
	public static function tearDownAfterClass(){

		// Nothing necessary here
	}


	/**
	 * testIsValidDateTime
	 *
	 * @return void
	 */
	public function testIsValidDateTime(){

		// test empty values
		$this->assertTrue(!DateTimeUtils::isValidDateTime(null));
		$this->assertTrue(!DateTimeUtils::isValidDateTime([]));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(''));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('   '));
		$this->assertTrue(!DateTimeUtils::isValidDateTime("\n  \t"));

		// Test valid values
		$this->assertTrue(DateTimeUtils::isValidDateTime('0008'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2008'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2008-11'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2008-09-15'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('1994-11-05T13:15:30Z'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('1994-11-05T08:15:30-05:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2008-09-15T15:53:00+05:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03T13:18:05'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03T16:18:05Z'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03T13:18:05-03:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03T13:18:05+03:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('0001-01-01T01:01:00+00:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2010-02-18T16:23:48.541+06:00'));
		$this->assertTrue(DateTimeUtils::isValidDateTime('2007-11-03 13:18:05+03:00'));
		// TODO - this method currently fails with only time values, like : '05:30:12'. it should be improved.

		// Test invalid values
		$this->assertTrue(!DateTimeUtils::isValidDateTime('a'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('1'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('atyu'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('200'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010.'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2018-'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2012-1'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2008-13'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('200912-01'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('01/10/2018'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('1/1/2018'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('25-2-1997'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-31-12'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-43'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('20071-11-13'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-013'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-a13'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03t13'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2071-11-13-'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(' 2071-11-13'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2009-05-19T14a39r'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03t13:18:05+03:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03a13:18:05+03:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03!'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-3'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03:'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03:0'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T33:18:05.987-03:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:63:48.541-06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:23:68.541-06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:23:48.541-96:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-31T16:23:48.541+06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-06-31T16:23:48.541+06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-31T16:23:48.541+06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-11-31T16:23:48.541+06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-30T16:23:48.541+06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(123));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(123.97));
		$this->assertTrue(!DateTimeUtils::isValidDateTime([1,2,3]));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(new Exception()));
	}


	/**
	 * testIsSameDateTime
	 *
	 * @return void
	 */
	public function testIsSameDateTime(){

		// TODO
	}


	/**
	 * testIsSameTimeZone
	 *
	 * @return void
	 */
	public function testIsSameTimeZone(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2008', '1997'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2008-11', '2018-09'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2015-12-15', '1915-02-10'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2007-11-03T13:18:05', '2017-01-23T13:18:05'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2007-11-03T13:18:05', '2017-01-23T23:18:05+00:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('1994-11-05T13:15:30+01:00', '2017-01-23T23:18:05+01:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2007-11-03T13:18:05.987+01:00', '1007-01-01T00:00:01.000001+01:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123-05:00', '1998-12-30T06:43:48-05:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123-00:00', '1998-12-30T06:43:48+00:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123-15:00', '1998-12-30T06:43:48-15:00'));
		$this->assertTrue(DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123+15:00', '1998-12-30T06:43:48+15:00'));

		// Test invalid values
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123+15:00', '1998-12-30T06:43:48+14:00'));
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123-15:00', '1998-12-30T06:43:48-14:00'));
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123+05:10', '1998-12-30T06:43:48+05:00'));
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123+00:10', '1998-12-30T06:43:48+00:00'));
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123-05:10', '1998-12-30T06:43:48+01:00'));
		$this->assertTrue(!DateTimeUtils::isSameTimeZone('2010-02-18T16:23:48.54123+05:10', '1998-12-30T06:43:48+05:09'));

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value1) {

			foreach ($this->invalidValues as $value2) {

				try {
					DateTimeUtils::isSameTimeZone($value1, $value2);
					$exceptionMessage = $value1.' and '.$value2.' did not cause exception';
				} catch (Exception $e) {
					// We expect an exception to happen
				}
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDateTimeFromLocalValues
	 *
	 * @return void
	 */
	public function testGetDateTimeFromLocalValues(){

		// TODO - This tests are pending

		// Test empty values

		// Test valid values
		// TODO $this->assertTrue(DateTimeUtils::getDateTimeFromLocalValues(2010, 12, 20, 16, 30, 26) == '2010-12-20T16:30:26+00:00');

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetMicroSeconds
	 *
	 * @return void
	 */
	public function testGetMicroSeconds(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2007-11-03T10:08:05.987+01:00') == 987000);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18 16:23:48.54123+01:00') == 541230);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:00.54123-01:00') == 541230);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18 23:23:59.54123+01:00') == 541230);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.54-01:00') == 540000);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.000001-11:00') == 1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.000011-11:00') == 11);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.000101-11:00') == 101);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.001001-11:00') == 1001);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.010001-11:00') == 10001);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.100000-11:00') == 100000);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2010-02-18T23:23:59.100000+11:00') == 100000);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('1994-11-05T13:15:30.123Z') == 123000);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12-15') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12-15T13') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12-15 13') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12-15T13:40') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2015-12-15 19:40') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('1994-11-05T06:15:10') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('2007-11-03T00:18:05') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('1994-11-05T06:15:30+01:00') == -1);
		$this->assertTrue(DateTimeUtils::getMicroSeconds('1994-11-05T13:15:30Z') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getMicroSeconds($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetMiliSeconds
	 *
	 * @return void
	 */
	public function testGetMiliSeconds(){

	    // Test valid values
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.9') == 900);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.98') == 980);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.987') == 987);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.9+01:00') == 900);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.98+01:00') == 980);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.987+01:00') == 987);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.9871+01:00') == 987);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.98712+01:00') == 987);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T10:08:05.987134+01:00') == 987);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18 16:23:48.00123+01:00') == 1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:00.04123-01:00') == 41);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18 23:23:59.54123+01:00') == 541);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.54-01:00') == 540);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.000001-11:00') == 0);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.000011-11:00') == 0);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.000111-11:00') == 0);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.001111-11:00') == 1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.000000-11:00') == 0);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2010-02-18T23:23:59.100000+11:00') == 100);

	    // Test invalid values
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12-15') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12-15T13') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12-15 13') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12-15T13:40') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2015-12-15 19:40') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('1994-11-05T06:15:10') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('2007-11-03T00:18:05') == -1);
	    $this->assertTrue(DateTimeUtils::getMiliSeconds('1994-11-05T06:15:30+01:00') == -1);

	    // test exceptions
	    $exceptionMessage = '';

	    foreach ($this->invalidValues as $value) {

	        try {
	            DateTimeUtils::getMiliSeconds($value);
	            $exceptionMessage = $value.' did not cause exception';
	        } catch (Exception $e) {
	            // We expect an exception to happen
	        }
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}


	/**
	 * testGetSeconds
	 *
	 * @return void
	 */
	public function testGetSeconds(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getSeconds('1994-11-05T06:15:10') == 10);
		$this->assertTrue(DateTimeUtils::getSeconds('2007-11-03T00:18:05') == 5);
		$this->assertTrue(DateTimeUtils::getSeconds('1994-11-05T06:15:30+01:00') == 30);
		$this->assertTrue(DateTimeUtils::getSeconds('2007-11-03T10:08:05.987+01:00') == 5);
		$this->assertTrue(DateTimeUtils::getSeconds('2010-02-18 16:23:48.54123+01:00') == 48);
		$this->assertTrue(DateTimeUtils::getSeconds('2010-02-18T23:23:00.54123+01:00') == 0);
		$this->assertTrue(DateTimeUtils::getSeconds('2010-02-18 23:23:59.54123+01:00') == 59);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getSeconds('2015') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12-15') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12-15T13') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12-15 13') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12-15T13:40') == -1);
		$this->assertTrue(DateTimeUtils::getSeconds('2015-12-15 19:40') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getSeconds($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetMinutes
	 *
	 * @return void
	 */
	public function testGetMinutes(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12-15T13:40') == 40);
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12-15 19:40') == 40);
		$this->assertTrue(DateTimeUtils::getMinutes('2007-11-03T00:18:05') == 18);
		$this->assertTrue(DateTimeUtils::getMinutes('1994-11-05T06:15:30+01:00') == 15);
		$this->assertTrue(DateTimeUtils::getMinutes('2007-11-03T10:18:05.987+01:00') == 18);
		$this->assertTrue(DateTimeUtils::getMinutes('2010-02-18 16:23:48.54123+01:00') == 23);
		$this->assertTrue(DateTimeUtils::getMinutes('2010-02-18T23:23:48.54123+01:00') == 23);
		$this->assertTrue(DateTimeUtils::getMinutes('2010-02-18 23:23:48.54123+01:00') == 23);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getMinutes('2015') == -1);
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12') == -1);
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12-15') == -1);
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12-15T13') == -1);
		$this->assertTrue(DateTimeUtils::getMinutes('2015-12-15 13') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getMinutes($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetHour
	 *
	 * @return void
	 */
	public function testGetHour(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getHour('2015-12-15T13') == 13);
		$this->assertTrue(DateTimeUtils::getHour('2015-12-15 13') == 13);
		$this->assertTrue(DateTimeUtils::getHour('2015-12-15T13:40') == 13);
		$this->assertTrue(DateTimeUtils::getHour('2015-12-15 19:40') == 19);
		$this->assertTrue(DateTimeUtils::getHour('2007-11-03T00:18:05') == 0);
		$this->assertTrue(DateTimeUtils::getHour('1994-11-05T06:15:30+01:00') == 6);
		$this->assertTrue(DateTimeUtils::getHour('2007-11-03T10:18:05.987+01:00') == 10);
		$this->assertTrue(DateTimeUtils::getHour('2010-02-18 16:23:48.54123+01:00') == 16);
		$this->assertTrue(DateTimeUtils::getHour('2010-02-18T23:23:48.54123+01:00') == 23);
		$this->assertTrue(DateTimeUtils::getHour('2010-02-18 23:23:48.54123+01:00') == 23);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getHour('2015') == -1);
		$this->assertTrue(DateTimeUtils::getHour('2015-12') == -1);
		$this->assertTrue(DateTimeUtils::getHour('2015-12-15') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getHour($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDay
	 *
	 * @return void
	 */
	public function testGetDay(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getDay('2015-12-15') == 15);
		$this->assertTrue(DateTimeUtils::getDay('2007-11-03T13:18:05') == 3);
		$this->assertTrue(DateTimeUtils::getDay('1994-11-05T13:15:30+01:00') == 5);
		$this->assertTrue(DateTimeUtils::getDay('2007-11-03T13:18:05.987+01:00') == 3);
		$this->assertTrue(DateTimeUtils::getDay('2010-02-18T16:23:48.54123+01:00') == 18);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getDay('2015') == -1);
		$this->assertTrue(DateTimeUtils::getDay('2015-12') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getDay($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDayOfWeek
	 *
	 * @return void
	 */
	public function testGetDayOfWeek(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2015-12-15') == 3);
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2007-11-03T13:18:05') == 7);
		$this->assertTrue(DateTimeUtils::getDayOfWeek('1994-11-02T13:15:30+01:00') == 4);
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2027-02-03T13:18:05.987+01:00') == 4);
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2010-09-18T16:23:48.54123+01:00') == 7);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2015') == -1);
		$this->assertTrue(DateTimeUtils::getDayOfWeek('2015-10') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getDayOfWeek($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetMonth
	 *
	 * @return void
	 */
	public function testGetMonth(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getMonth('2015-05') == 5);
		$this->assertTrue(DateTimeUtils::getMonth('2015-12-15') == 12);
		$this->assertTrue(DateTimeUtils::getMonth('2007-11-03T13:18:05') == 11);
		$this->assertTrue(DateTimeUtils::getMonth('1994-06-02T13:15:30+01:00') == 6);
		$this->assertTrue(DateTimeUtils::getMonth('2027-02-03T13:18:05.987+01:00') == 2);
		$this->assertTrue(DateTimeUtils::getMonth('2010-09-18T16:23:48.54123+01:00') == 9);

		// Test invalid values
		$this->assertTrue(DateTimeUtils::getMonth('2015') == -1);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getMonth($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetYear
	 *
	 * @return void
	 */
	public function testGetYear(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getYear('2015-05') == 2015);
		$this->assertTrue(DateTimeUtils::getYear('1915-12-15') == 1915);
		$this->assertTrue(DateTimeUtils::getYear('2007-11-03T13:18:05') == 2007);
		$this->assertTrue(DateTimeUtils::getYear('1994-06-02T13:15:30+01:00') == 1994);
		$this->assertTrue(DateTimeUtils::getYear('2027-02-03T13:18:05.987+01:00') == 2027);
		$this->assertTrue(DateTimeUtils::getYear('3010-09-18T16:23:48.54123+01:00') == 3010);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getYear($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetTimeZoneOffset
	 *
	 * @return void
	 */
	public function testGetTimeZoneOffset(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('2015') == 0);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('2015-05') == 0);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('2015-05-25') == 0);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('2015-05-25T16:23:48') == 0);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123') == 0);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+01:00') == 3600);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+02:00') == 7200);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+05:00') == 18000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+10:00') == 36000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+01:40') == 6000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123+06:30') == 23400);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-01:00') == -3600);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-02:00') == -7200);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-05:00') == -18000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-10:00') == -36000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-01:40') == -6000);
		$this->assertTrue(DateTimeUtils::getTimeZoneOffset('3010-09-18T16:23:48.54123-06:30') == -23400);

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::getTimeZoneOffset($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDateTimeNow
	 *
	 * @return void
	 */
	public function testGetDateTimeNow(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::isValidDateTime(DateTimeUtils::getCurrentDateTime()));
	}


	/**
	 * testGetCurrentDay
	 *
	 * @return void
	 */
	public function testGetCurrentDay(){

		$this->assertTrue(DateTimeUtils::getCurrentDay() == date('j'));
		$this->assertTrue(DateTimeUtils::getCurrentDay() <= 31);
		$this->assertTrue(DateTimeUtils::getCurrentDay() >= 1);
	}


	/**
	 * testGetCurrentDayOfWeek
	 *
	 * @return void
	 */
	public function testGetCurrentDayOfWeek(){

		$this->assertTrue(DateTimeUtils::getCurrentDayOfWeek() == (date('w') + 1));
		$this->assertTrue(DateTimeUtils::getCurrentDayOfWeek() <= 7);
		$this->assertTrue(DateTimeUtils::getCurrentDayOfWeek() >= 1);
	}


	/**
	 * testGetCurrentMonth
	 *
	 * @return void
	 */
	public function testGetCurrentMonth(){

		$this->assertTrue(DateTimeUtils::getCurrentMonth() == date('n'));
		$this->assertTrue(DateTimeUtils::getCurrentMonth() <= 12);
		$this->assertTrue(DateTimeUtils::getCurrentMonth() >= 1);
	}


	/**
	 * testGetCurrentYear
	 *
	 * @return void
	 */
	public function testGetCurrentYear(){

		$this->assertTrue(DateTimeUtils::getCurrentYear() == date('Y'));
		$this->assertTrue(DateTimeUtils::getCurrentYear() > 2015);
	}


	/**
	 * testGetDayName
	 *
	 * @return void
	 */
	public function testGetDayName(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getDayName(0001) == 'SUNDAY');
		$this->assertTrue(DateTimeUtils::getDayName(1) == 'SUNDAY');
		$this->assertTrue(DateTimeUtils::getDayName('1') == 'SUNDAY');
		$this->assertTrue(DateTimeUtils::getDayName(' 1') == 'SUNDAY');
		$this->assertTrue(DateTimeUtils::getDayName('0001') == 'SUNDAY');
		$this->assertTrue(DateTimeUtils::getDayName(3) == 'TUESDAY');
		$this->assertTrue(DateTimeUtils::getDayName('3') == 'TUESDAY');
		$this->assertTrue(DateTimeUtils::getDayName(' 3') == 'TUESDAY');
		$this->assertTrue(DateTimeUtils::getDayName(5) == 'THURSDAY');
		$this->assertTrue(DateTimeUtils::getDayName('5') == 'THURSDAY');
		$this->assertTrue(DateTimeUtils::getDayName(' 5') == 'THURSDAY');

		// test exceptions
		$exceptionMessage = '';

		$invalidValues = [null, '', 123, 13, 0, -1, [6], 'hello'];

		foreach ($invalidValues as $value) {

			try {
				DateTimeUtils::getDayName($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetMonthName
	 *
	 * @return void
	 */
	public function testGetMonthName(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getMonthName(0001) == 'JANUARY');
		$this->assertTrue(DateTimeUtils::getMonthName(1) == 'JANUARY');
		$this->assertTrue(DateTimeUtils::getMonthName('1') == 'JANUARY');
		$this->assertTrue(DateTimeUtils::getMonthName(' 1') == 'JANUARY');
		$this->assertTrue(DateTimeUtils::getMonthName('0001') == 'JANUARY');
		$this->assertTrue(DateTimeUtils::getMonthName(6) == 'JUNE');
		$this->assertTrue(DateTimeUtils::getMonthName('6') == 'JUNE');
		$this->assertTrue(DateTimeUtils::getMonthName(' 6') == 'JUNE');
		$this->assertTrue(DateTimeUtils::getMonthName(9) == 'SEPTEMBER');
		$this->assertTrue(DateTimeUtils::getMonthName('9') == 'SEPTEMBER');
		$this->assertTrue(DateTimeUtils::getMonthName(' 9') == 'SEPTEMBER');
		$this->assertTrue(DateTimeUtils::getMonthName(12) == 'DECEMBER');
		$this->assertTrue(DateTimeUtils::getMonthName('12') == 'DECEMBER');
		$this->assertTrue(DateTimeUtils::getMonthName(' 12') == 'DECEMBER');

		// test exceptions
		$exceptionMessage = '';

		$invalidValues = [null, '', 123, 13, 0, -1, [6], 'hello'];

		foreach ($invalidValues as $value) {

			try {
				DateTimeUtils::getMonthName($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetFirstDayOfMonth
	 *
	 * @return void
	 */
	public function testGetFirstDayOfMonth(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getFirstDayOfMonth('2010-09') == '2010-09-01T00:00:00.000000+02:00');
		$this->assertTrue(DateTimeUtils::getFirstDayOfMonth('2012-01-18') == '2012-01-01T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::getFirstDayOfMonth('2000-12-18T16:23:48') == '2000-12-01T16:23:48.000000+01:00');
		$this->assertTrue(DateTimeUtils::getFirstDayOfMonth('2010-09-18T16:23:48.54123+01:00') == '2010-09-01T16:23:48.541230+01:00');
		$this->assertTrue(DateTimeUtils::getFirstDayOfMonth('2020-01-01T16:23:48.54123+01:00') == '2020-01-01T16:23:48.541230+01:00');

		// test exceptions
		$exceptionMessage = '';

		$invalidValues = array_merge($this->invalidValues, ['2010', '2010-13', '2015-01-32']);

		foreach ($invalidValues as $value) {

			try {
				DateTimeUtils::getFirstDayOfMonth($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetLastDayOfMonth
	 *
	 * @return void
	 */
	public function testGetLastDayOfMonth(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::getLastDayOfMonth('2010-09') == '2010-09-30T00:00:00.000000+02:00');
		$this->assertTrue(DateTimeUtils::getLastDayOfMonth('2012-01-18') == '2012-01-31T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::getLastDayOfMonth('2000-12-18T16:23:48') == '2000-12-31T16:23:48.000000+01:00');
		$this->assertTrue(DateTimeUtils::getLastDayOfMonth('2010-09-18T16:23:48.54123+01:00') == '2010-09-30T16:23:48.541230+01:00');
		$this->assertTrue(DateTimeUtils::getLastDayOfMonth('2020-01-01T16:23:48.54123+01:00') == '2020-01-31T16:23:48.541230+01:00');

		// test exceptions
		$exceptionMessage = '';

		$invalidValues = array_merge($this->invalidValues, ['2010', '2010-13', '2015-01-32']);

		foreach ($invalidValues as $value) {

			try {
				DateTimeUtils::getLastDayOfMonth($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testConvertToLocalTimeZone
	 *
	 * @return void
	 */
	public function testConvertToLocalTimeZone(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015') == '2015');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015-11') == '2015-11');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015-01-01') == '2015-01-01T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2010-02-18T16:23:48.541+06:00') == '2010-02-18T11:23:48.541000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2007-11-03T13:18:05') == '2007-11-03T13:18:05.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2008-09-15T15:53:00+05:00') == '2008-09-15T12:53:00.000000+02:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('1994-11-05T08:15:30-05:00') == '1994-11-05T14:15:30.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('1994-11-05T13:15:30Z') == '1994-11-05T14:15:30.000000+01:00');

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::convertToLocalTimeZone($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}

	/**
	 * testFormat
	 *
	 * @return void
	 */
	public function testFormat(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::format('2015', 'y') == '15');
		$this->assertTrue(DateTimeUtils::format('2015', 'Y') == '2015');
		$this->assertTrue(DateTimeUtils::format('2015', 'm-D-y h:m:s-U') == 'm-D-15 h:m:s-U');
		$this->assertTrue(DateTimeUtils::format('2015-01', 'm-y') == '1-15');
		$this->assertTrue(DateTimeUtils::format('2015-01', 'M-y') == '01-15');
		$this->assertTrue(DateTimeUtils::format('2015-11', 'M-Y') == '11-2015');
		$this->assertTrue(DateTimeUtils::format('2015-01', 'm-Y') == '1-2015');
		$this->assertTrue(DateTimeUtils::format('2015-01-01', 'd-m-y') == '1-1-15');
		$this->assertTrue(DateTimeUtils::format('2015-01-01', 'D-M-Y') == '01-01-2015');
		$this->assertTrue(DateTimeUtils::format('1998-12-06', 'd/m/y') == '6/12/98');
		$this->assertTrue(DateTimeUtils::format('1998-12-06', 'D/M/Y') == '06/12/1998');
		$this->assertTrue(DateTimeUtils::format('2015-01-01', 'd_M_Y') == '1_01_2015');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T16', 'd-m-y h:n:s') == '18-2-10 16:n:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T06', 'd-m-y h:n:s') == '18-2-10 6:n:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T06', 'd-m-y H:n:s') == '18-2-10 06:n:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T16:20', 'd-m-y h:n:s') == '18-2-10 16:20:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T16:01', 'd-m-y h:n:s') == '18-2-10 16:1:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T16:01', 'd-m-y h:N:s') == '18-2-10 16:01:s');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T00:00:00.541+06:00', 'd-m-y h:n:s') == '18-2-10 0:0:0');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T16:23:48.541+06:00', 'd-m-y h:n:s') == '18-2-10 16:23:48');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T01:03:08.541+06:00', 'D-M-Y h:n:s') == '18-02-2010 1:3:8');
		$this->assertTrue(DateTimeUtils::format('2010-02-18T01:03:08.541+06:00', 'D-M-Y H:N:S') == '18-02-2010 01:03:08');
		$this->assertTrue(DateTimeUtils::format('2008-09-15T15:03:10.876+05:00', 'm-d-Y h:n:s:u') == '9-15-2008 15:3:10:876');
		$this->assertTrue(DateTimeUtils::format('2008-09-15T15:53:10.876467+05:00', 'M-D-y h:n:s-U') == '09-15-08 15:53:10-876467');
		$this->assertTrue(DateTimeUtils::format('2008-09-15T15:53:10.876467+05:00', 'M-D-y h:n:s-u') == '09-15-08 15:53:10-876');
		$this->assertTrue(DateTimeUtils::format('2008-09-15T15:53:10.000001+05:00', 'M-D-y h:n:s-u') == '09-15-08 15:53:10-000');
		$this->assertTrue(DateTimeUtils::format('2008-09-15T15:53:10.001+05:00', 'M-D-y h:n:s-U') == '09-15-08 15:53:10-001000');
        $this->assertTrue(DateTimeUtils::format('1994-11-05T13:15:30Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:U');
		$this->assertTrue(DateTimeUtils::format('1994-11-05T13:15:30.123Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:123000');
		$this->assertTrue(DateTimeUtils::format('1994-11-05T13:15:30.123456Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:123456');

		// test exceptions
		$exceptionMessage = '';

		foreach ($this->invalidValues as $value) {

			try {
				DateTimeUtils::format($value);
				$exceptionMessage = $value.' did not cause exception';
			} catch (Exception $e) {
				// We expect an exception to happen
			}
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}

	// TODO add all missing tests
}

?>