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

		// Nothing necessary here
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
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03!'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-3'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03:'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T13:18:05.987-03:0'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2007-11-03T33:18:05.987-03:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:63:48.541-06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:23:68.541-06:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime('2010-02-18T16:23:48.541-96:00'));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(123));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(123.97));
		$this->assertTrue(!DateTimeUtils::isValidDateTime([1,2,3]));
		$this->assertTrue(!DateTimeUtils::isValidDateTime(new Exception()));
	}


	/**
	 * testIsLocalTimeZone
	 *
	 * @return void
	 */
	public function testIsLocalTimeZone(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2008'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2008-11'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2015-12-15'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2007-11-03T13:18:05'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('1994-11-05T13:15:30+01:00'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2007-11-03T13:18:05.987+01:00'));
		$this->assertTrue(DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+01:00'));

		// Test invalid values
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123-01:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123-02:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+02:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123-03:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+03:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123-04:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+04:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-12-20T16:23:48.54123-05:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+05:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+06:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+07:00'));
		$this->assertTrue(!DateTimeUtils::isLocalTimeZone('2010-02-18T16:23:48.54123+08:00'));

		// test exceptions
		$exceptionMessage = '';

		try {
			DateTimeUtils::isLocalTimeZone(null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone([]);
			$exceptionMessage = '[] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone('   ');
			$exceptionMessage = '"    " did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone("\n  \t");
			$exceptionMessage = '"\n  \t" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone(123);
			$exceptionMessage = '123 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone([1,5,6,6]);
			$exceptionMessage = '[1,5,6,6] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone(new Exception());
			$exceptionMessage = 'new Exception() did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone('asdfasdf');
			$exceptionMessage = 'asdfasdf did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone('2009-05-19T14a39r');
			$exceptionMessage = '2009-05-19T14a39r did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
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
		$this->assertTrue(DateTimeUtils::isValidDateTime(DateTimeUtils::getDateTimeNow()));

		$this->assertTrue(!DateTimeUtils::isLocalTimeZone(DateTimeUtils::getDateTimeNow()));
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

		// test exceptions
		$exceptionMessage = '';

		try {
			DateTimeUtils::getDay(null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::getDay('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone(123);
			$exceptionMessage = '123 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::isLocalTimeZone([1,5,6,6]);
			$exceptionMessage = '[1,5,6,6] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::getDay('2008');
			$exceptionMessage = '2008 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::getDay('2008-11');
			$exceptionMessage = '2008 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
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

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetMonth
	 *
	 * @return void
	 */
	public function testGetMonth(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetYear
	 *
	 * @return void
	 */
	public function testGetYear(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetCurrentDay
	 *
	 * @return void
	 */
	public function testGetCurrentDay(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetCurrentDayOfWeek
	 *
	 * @return void
	 */
	public function testGetCurrentDayOfWeek(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetCurrentMonth
	 *
	 * @return void
	 */
	public function testGetCurrentMonth(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetCurrentYear
	 *
	 * @return void
	 */
	public function testGetCurrentYear(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetDayName
	 *
	 * @return void
	 */
	public function testGetDayName(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetMonthName
	 *
	 * @return void
	 */
	public function testGetMonthName(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetFirstDayOfMonth
	 *
	 * @return void
	 */
	public function testGetFirstDayOfMonth(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testGetLastDayOfMonth
	 *
	 * @return void
	 */
	public function testGetLastDayOfMonth(){

		// TODO

		// Test empty values

		// Test valid values

		// Test invalid values

		// Test exceptions
	}


	/**
	 * testConvertToLocalTimeZone
	 *
	 * @return void
	 */
	public function testConvertToLocalTimeZone(){

		// Test valid values
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015') == '2015-01-01T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015-11') == '2015-11-01T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2015-01-01') == '2015-01-01T00:00:00.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2010-02-18T16:23:48.541+06:00') == '2010-02-18T11:23:48.541000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2007-11-03T13:18:05') == '2007-11-03T13:18:05.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('2008-09-15T15:53:00+05:00') == '2008-09-15T12:53:00.000000+02:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('1994-11-05T08:15:30-05:00') == '1994-11-05T14:15:30.000000+01:00');
		$this->assertTrue(DateTimeUtils::convertToLocalTimeZone('1994-11-05T13:15:30Z') == '1994-11-05T14:15:30.000000+01:00');

		// Test invalid values
		$exceptionMessage = '';

		try {
			DateTimeUtils::convertToLocalTimeZone(null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::convertToLocalTimeZone('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::convertToLocalTimeZone(123);
			$exceptionMessage = '123 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::convertToLocalTimeZone([1,5,6,6]);
			$exceptionMessage = '[1,5,6,6] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::convertToLocalTimeZone('asdfasdf');
			$exceptionMessage = 'asdfasdf did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			DateTimeUtils::convertToLocalTimeZone('2009-05-19T14a39r');
			$exceptionMessage = '2009-05-19T14a39r did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}



	// TODO add all missing tests
}

?>