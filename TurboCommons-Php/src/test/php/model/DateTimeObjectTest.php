<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use DateTime;
use Exception;
use Throwable;
use stdClass;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\model\DateTimeObject;


/**
 * DateTimeObjectTest
 *
 * @return void
 */
class DateTimeObjectTest extends TestCase {


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Used to standarize tests. This value is automatically restored after the script ends.
        date_default_timezone_set('Europe/Berlin');
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';

        // Define a list of values that will be considered as empty string values
        $this->emptyStringValues = [[], null, '', '     ', "\n\n\n", 0];
        $this->emptyStringValuesCount = count($this->emptyStringValues);

        // Define a list of dateTime values that must be considered as valid by the class methods
        $this->validDateTimeValues = [
            '0008', '2008', '2008-11', '0001-01-01', '2008-09-15', '1994-11-05T13:15:30Z', '1994-11-05T08:15:30-05:00',
            '2008-09-15T15:53:00+05:00', '2007-11-03T13:18:05', '2007-11-03T16:18:05Z', '2007-11-03T13:18:05-03:00',
            '2007-11-03T13:18:05+03:00', '0001-01-01T01:01:00+00:00', '2007-11-03T13:18:05.987-03:00', '2010-02-18T16:23:48.541+06:00',
            '2007-11-03 13:18:05+03:00'
        ];
        $this->validDateTimeValuesCount = count($this->validDateTimeValues);

        // Define a list of dateTime values that must be considered as invalid by the class methods
        $this->invalidDateTimeValues = [
            '1', '200', '2010.', '2018-', '2012-1', 'a', 'atyu', '2009-05-19T14a39r', 1, 123, 1234, 123.97, 2018, new Exception(), '2015-12-15  ',
            '2015-12-15T1', '2015-12-15T13:', '2008-13', '200912-01', '01/10/2018', '1/1/2018', '25-2-1997', '2007-11-',
            '2007-31-12', '2007-11-43', '20071-11-13', '2007-11-013', '2007-11-a13', '2007-11-03t13', '2071-11-13-', ' 2071-11-13',
            '2009-05-19T14a39r', '2007-11-03t13:18:05+03:00', '2007-11-03a13:18:05+03:00', '2007-11-03T13:18:05.987-',
            '2007-11-03T13:18:05.987-03!', '2007-11-03T13:18:05.987-3', '2007-11-03T13:18:05.987-03:', '2007-11-03T13:18:05.987-03:0',
            '2007-11-03T33:18:05.987-03:00', '2010-02-18T16:63:48.541-06:00', '2010-02-18T16:23:68.541-06:00', '2010-02-18T16:23:48.541-96:00',
            '2010-02-31T16:23:48.541+06:00', '2010-06-31T16:23:48.541+06:00', '2010-02-31T16:23:48.541+06:00', '2010-11-31T16:23:48.541+06:00',
            '2010-02-30T16:23:48.541+06:00', '0000-00-00T00:00:00.000000+00:00', '2010-02-18t16:23:48.54123-01:00', '2015-05-25T18T16:23:48',
            '2008-18', '2015-01-32', '2015-00-32', [1,2,3], ['abc', 123]
        ];
        $this->invalidDateTimeValuesCount = count($this->invalidDateTimeValues);
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


    /**
     * testConstruct
     *
     * @return void
     */
    public function testConstruct(){

        // Test empty values
        for ($i = 0; $i < $this->emptyStringValuesCount; $i++) {

            $d = new DateTimeObject($this->emptyStringValues[$i]);
            $this->assertSame(DateTimeObject::getCurrentYear(), $d->getYear());
            $this->assertSame(DateTimeObject::getCurrentMonth(), $d->getMonth());
            $this->assertSame(DateTimeObject::getCurrentDay(), $d->getDay());
            $this->assertSame(DateTimeObject::getCurrentDayOfWeek(), $d->getDayOfWeek());
            $this->assertSame(DateTimeObject::getCurrentHour(), $d->getHour());
            $this->assertSame(DateTimeObject::getCurrentMinute(), $d->getMinute());
            $this->assertSame(DateTimeObject::getCurrentSecond(), $d->getSecond());
            $this->assertGreaterThanOrEqual($d->getMiliSecond(), DateTimeObject::getCurrentMiliSecond());
            $this->assertGreaterThanOrEqual($d->getMicroSecond(), DateTimeObject::getCurrentMicroSecond());
            $this->assertSame(DateTimeObject::getCurrentTimeZoneOffset(), $d->getTimeZoneOffset());
            $this->assertContains('Europe', $d->getTimeZoneName());
        }

        try {
            $d = new DateTimeObject(new stdClass());
            $this->exceptionMessage = 'new stdClass() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $d = new DateTimeObject('1996');
        $this->assertSame(1996, $d->getYear());
        $this->assertSame(7200, $d->getTimeZoneOffset());
        $this->assertContains('Europe', $d->getTimeZoneName());

        $d = new DateTimeObject('1996-12');
        $this->assertSame(1996, $d->getYear());
        $this->assertSame(12, $d->getMonth());
        $this->assertSame(3600, $d->getTimeZoneOffset());
        $this->assertContains('Europe', $d->getTimeZoneName());

        $d = new DateTimeObject('1996-12-31');
        $this->assertSame(1996, $d->getYear());
        $this->assertSame(12, $d->getMonth());
        $this->assertSame(31, $d->getDay());
        $this->assertSame(3600, $d->getTimeZoneOffset());
        $this->assertContains('Europe', $d->getTimeZoneName());

        $d = new DateTimeObject('1996-06-12T17:55:25');
        $this->assertSame(1996, $d->getYear());
        $this->assertSame(6, $d->getMonth());
        $this->assertSame(12, $d->getDay());
        $this->assertSame(17, $d->getHour());
        $this->assertSame(55, $d->getMinute());
        $this->assertSame(25, $d->getSecond());
        $this->assertSame(7200, $d->getTimeZoneOffset());
        $this->assertContains('Europe', $d->getTimeZoneName());

        $d = new DateTimeObject('1996-06-12T17:55:25.163583+07:00');
        $this->assertSame(1996, $d->getYear());
        $this->assertSame(6, $d->getMonth());
        $this->assertSame(12, $d->getDay());
        $this->assertSame(17, $d->getHour());
        $this->assertSame(55, $d->getMinute());
        $this->assertSame(25, $d->getSecond());
        $this->assertSame(163583, $d->getMicroSecond());
        $this->assertSame(25200, $d->getTimeZoneOffset());
        $this->assertContains('Asia', $d->getTimeZoneName());

        // Test wrong values
        for ($i = 0; $i < $this->invalidDateTimeValuesCount; $i++) {

            try {
                $d = new DateTimeObject($this->invalidDateTimeValues[$i]);
                $this->exceptionMessage = $this->invalidDateTimeValues[$i].' value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test exceptions
        // Already tested at wrong values
    }


    /**
     * testIsValidDateTime
     *
     * @return void
     */
    public function testIsValidDateTime(){

        // test empty values
        $this->assertTrue(DateTimeObject::isValidDateTime(new DateTimeObject()));

        $this->assertFalse(DateTimeObject::isValidDateTime(null));
        $this->assertFalse(DateTimeObject::isValidDateTime([]));
        $this->assertFalse(DateTimeObject::isValidDateTime(''));
        $this->assertFalse(DateTimeObject::isValidDateTime('   '));
        $this->assertFalse(DateTimeObject::isValidDateTime("\n  \t"));

        // Test valid values
        for ($i = 0; $i < $this->validDateTimeValuesCount; $i++) {

            // TODO - this method currently fails with only time values, like : '05:30:12'. it should be improved.
            $this->assertTrue(DateTimeObject::isValidDateTime($this->validDateTimeValues[$i]), 'Failed value : '.$this->validDateTimeValues[$i]);
            $this->assertTrue(DateTimeObject::isValidDateTime(new DateTimeObject($this->validDateTimeValues[$i])), 'Failed value : '.$this->validDateTimeValues[$i]);
        }

        // Test invalid values
        for ($i = 0; $i < $this->invalidDateTimeValuesCount; $i++) {

            $this->assertFalse(DateTimeObject::isValidDateTime($this->invalidDateTimeValues[$i]));

            // Testing valid value via creating a new DateTimeObject instance is not necessary cause it will throw
            // an exception and it is already tested at constructor test
        }

        // Test exceptions
        // This method does not throw exceptions, and it is already tested with invalid values
    }


    /**
     * testIsEqual
     *
     * @return void
     */
    public function testIsEqual(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetMonthName
     *
     * @return void
     */
    public function testGetMonthName(){

        // Test valid values
        $this->assertTrue(DateTimeObject::getMonthName(0001) == 'JANUARY');
        $this->assertTrue(DateTimeObject::getMonthName(1) == 'JANUARY');
        $this->assertTrue(DateTimeObject::getMonthName('1') == 'JANUARY');
        $this->assertTrue(DateTimeObject::getMonthName(' 1') == 'JANUARY');
        $this->assertTrue(DateTimeObject::getMonthName('0001') == 'JANUARY');
        $this->assertTrue(DateTimeObject::getMonthName(6) == 'JUNE');
        $this->assertTrue(DateTimeObject::getMonthName('6') == 'JUNE');
        $this->assertTrue(DateTimeObject::getMonthName(' 6') == 'JUNE');
        $this->assertTrue(DateTimeObject::getMonthName(9) == 'SEPTEMBER');
        $this->assertTrue(DateTimeObject::getMonthName('9') == 'SEPTEMBER');
        $this->assertTrue(DateTimeObject::getMonthName(' 9') == 'SEPTEMBER');
        $this->assertTrue(DateTimeObject::getMonthName(12) == 'DECEMBER');
        $this->assertTrue(DateTimeObject::getMonthName('12') == 'DECEMBER');
        $this->assertTrue(DateTimeObject::getMonthName(' 12') == 'DECEMBER');

        // test exceptions
        $invalidValues = [null, '', 123, 13, 0, -1, [6], 'hello'];

        foreach ($invalidValues as $value) {

            try {
                DateTimeObject::getMonthName($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetDayName
     *
     * @return void
     */
    public function testGetDayName(){

        // Test valid values
        $this->assertTrue(DateTimeObject::getDayName(0001) == 'SUNDAY');
        $this->assertTrue(DateTimeObject::getDayName(1) == 'SUNDAY');
        $this->assertTrue(DateTimeObject::getDayName('1') == 'SUNDAY');
        $this->assertTrue(DateTimeObject::getDayName(' 1') == 'SUNDAY');
        $this->assertTrue(DateTimeObject::getDayName('0001') == 'SUNDAY');
        $this->assertTrue(DateTimeObject::getDayName(3) == 'TUESDAY');
        $this->assertTrue(DateTimeObject::getDayName('3') == 'TUESDAY');
        $this->assertTrue(DateTimeObject::getDayName(' 3') == 'TUESDAY');
        $this->assertTrue(DateTimeObject::getDayName(5) == 'THURSDAY');
        $this->assertTrue(DateTimeObject::getDayName('5') == 'THURSDAY');
        $this->assertTrue(DateTimeObject::getDayName(' 5') == 'THURSDAY');

        // test exceptions
        $invalidValues = [null, '', 123, 13, 0, -1, [6], 'hello'];

        foreach ($invalidValues as $value) {

            try {
                DateTimeObject::getDayName($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetCurrentYear
     *
     * @return void
     */
    public function testGetCurrentYear(){

        $this->assertTrue(DateTimeObject::getCurrentYear() == date('Y'));
        $this->assertTrue(DateTimeObject::getCurrentYear() > 2015);
    }


    /**
     * testGetCurrentMonth
     *
     * @return void
     */
    public function testGetCurrentMonth(){

        $this->assertTrue(DateTimeObject::getCurrentMonth() == date('n'));
        $this->assertTrue(DateTimeObject::getCurrentMonth() <= 12);
        $this->assertTrue(DateTimeObject::getCurrentMonth() >= 1);
    }


    /**
     * testGetCurrentDay
     *
     * @return void
     */
    public function testGetCurrentDay(){

        $this->assertTrue(DateTimeObject::getCurrentDay() == date('j'));
        $this->assertTrue(DateTimeObject::getCurrentDay() <= 31);
        $this->assertTrue(DateTimeObject::getCurrentDay() >= 1);
    }


    /**
     * testGetCurrentDayOfWeek
     *
     * @return void
     */
    public function testGetCurrentDayOfWeek(){

        $this->assertTrue(DateTimeObject::getCurrentDayOfWeek() == (date('w') + 1));
        $this->assertTrue(DateTimeObject::getCurrentDayOfWeek() <= 7);
        $this->assertTrue(DateTimeObject::getCurrentDayOfWeek() >= 1);
    }


    /**
     * testGetCurrentHour
     *
     * @return void
     */
    public function testGetCurrentHour(){

        $this->assertTrue(DateTimeObject::getCurrentHour() == date('H'));
        $this->assertTrue(DateTimeObject::getCurrentHour() <= 23);
        $this->assertTrue(DateTimeObject::getCurrentHour() >= 0);
    }


    /**
     * testGetCurrentMinute
     *
     * @return void
     */
    public function testGetCurrentMinute(){

        $this->assertTrue(DateTimeObject::getCurrentMinute() == date('i'));
        $this->assertTrue(DateTimeObject::getCurrentMinute() <= 59);
        $this->assertTrue(DateTimeObject::getCurrentMinute() >= 0);
    }


    /**
     * testGetCurrentSecond
     *
     * @return void
     */
    public function testGetCurrentSecond(){

        $this->assertTrue(DateTimeObject::getCurrentSecond() == date('s'));
        $this->assertTrue(DateTimeObject::getCurrentSecond() <= 59);
        $this->assertTrue(DateTimeObject::getCurrentSecond() >= 0);
    }


    /**
     * testGetCurrentMiliSecond
     *
     * @return void
     */
    public function testGetCurrentMiliSecond(){

        $this->assertTrue(DateTimeObject::getCurrentMiliSecond() <= 999);
        $this->assertTrue(DateTimeObject::getCurrentMiliSecond() >= 0);
    }


    /**
     * testGetCurrentMicroSecond
     *
     * @return void
     */
    public function testGetCurrentMicroSecond(){

        $this->assertTrue(DateTimeObject::getCurrentMicroSecond() <= 999999);
        $this->assertTrue(DateTimeObject::getCurrentMicroSecond() >= 0);
    }


    /**
     * testGetCurrentTimeZoneName
     *
     * @return void
     */
    public function testGetCurrentTimeZoneName(){

        $this->assertContains('Europe', DateTimeObject::getCurrentTimeZoneName());
    }


    /**
     * testGetCurrentTimeZoneOffset
     *
     * @return void
     */
    public function testGetCurrentTimeZoneOffset(){

        $this->assertSame((new DateTime())->getOffset(), DateTimeObject::getCurrentTimeZoneOffset());
    }


    /**
     * testCompare
     *
     * @return void
     */
    public function testCompare(){

        // Test valid values
        $this->assertTrue(DateTimeObject::compare('2015', '2015') === 0);
        $this->assertTrue(DateTimeObject::compare('2015', '2010') === 1);
        $this->assertTrue(DateTimeObject::compare('2014', '2015') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-06', '2015-06') === 0);
        $this->assertTrue(DateTimeObject::compare('2015-06', '2015-04') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-10', '2015-12') === 2);
        $this->assertTrue(DateTimeObject::compare('2016-06', '2015-06') === 1);
        $this->assertTrue(DateTimeObject::compare('2000-10', '2019-12') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-06-21', '2015-06-21') === 0);
        $this->assertTrue(DateTimeObject::compare('2015-06-18', '2015-06-10') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-10-01', '2015-10-30') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-10-21', '2015-06-21') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-06-18', '2015-12-10') === 2);
        $this->assertTrue(DateTimeObject::compare('2016-01-21', '2015-06-21') === 1);
        $this->assertTrue(DateTimeObject::compare('2010-06-18', '2015-01-10') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:31:05', '2015-06-21T19:31:05') === 0);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:31:15', '2015-06-21T19:31:05') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:31:01', '2015-06-21T19:31:35') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:41:15', '2015-06-21T19:31:05') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:01:01', '2015-06-21T19:31:35') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T19:41:15', '2015-06-21T11:31:05') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-06-21T10:01:01', '2015-06-21T11:31:35') === 2);
        $this->assertTrue(DateTimeObject::compare('1994-11-05T13:15:30.123456Z', '1994-11-05T13:15:30.123456Z') === 0);
        $this->assertTrue(DateTimeObject::compare('1994-11-05T13:15:30.123456+00:00', '1994-11-05T13:15:30.123456+00:00') === 0);
        $this->assertTrue(DateTimeObject::compare('1994-11-05T13:15:30.123456+01:00', '1994-11-05T12:15:30.123456+00:00') === 0);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T13:15:30.123456+05:00', '2015-11-05T08:15:30.123456+00:00') === 0);
        $this->assertTrue(DateTimeObject::compare('2016-11-05T13:15:30.123456+05:00', '2015-11-05T08:15:30.123456+00:00') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T13:15:30.123456+02:00', '2015-11-05T08:15:30.123456+00:00') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T13:15:30.123457+02:00', '2015-11-05T13:15:30.123456+02:00') === 1);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T08:15:30.123456-08:00', '2015-11-05T18:15:30.123456+00:00') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T08:15:30.123456-01:00', '2015-11-05T11:15:30.123456+01:00') === 2);
        $this->assertTrue(DateTimeObject::compare('2015-11-05T08:15:30.123456-01:00', '2015-11-05T11:15:30.123456+01:00') === 2);

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value1) {

            foreach ($this->invalidDateTimeValues as $value2) {

                try {
                    DateTimeObject::compare($value1, $value2);
                    $this->exceptionMessage = $value1.' did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }
    }


    /**
     * testGetYear
     *
     * @return void
     */
    public function testGetYear(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-05'))->getYear() == 2015);
        $this->assertTrue((new DateTimeObject('1915-12-15'))->getYear() == 1915);
        $this->assertTrue((new DateTimeObject('2007-11-03T13:18:05'))->getYear() == 2007);
        $this->assertTrue((new DateTimeObject('1994-06-02T13:15:30+01:00'))->getYear() == 1994);
        $this->assertTrue((new DateTimeObject('2027-02-03T13:18:05.987+01:00'))->getYear() == 2027);
        $this->assertTrue((new DateTimeObject('3010-09-18T16:23:48.54123+01:00'))->getYear() == 3010);

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getYear();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetMonth
     *
     * @return void
     */
    public function testGetMonth(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-05'))->getMonth() == 5);
        $this->assertTrue((new DateTimeObject('2015-12-15'))->getMonth() == 12);
        $this->assertTrue((new DateTimeObject('2007-11-03T13:18:05'))->getMonth() == 11);
        $this->assertTrue((new DateTimeObject('1994-06-02T13:15:30+01:00'))->getMonth() == 6);
        $this->assertTrue((new DateTimeObject('2027-02-03T13:18:05.987+01:00'))->getMonth() == 2);
        $this->assertTrue((new DateTimeObject('2010-09-18T16:23:48.54123+01:00'))->getMonth() == 9);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getMonth();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetDay
     *
     * @return void
     */
    public function testGetDay(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-12-15'))->getDay() == 15);
        $this->assertTrue((new DateTimeObject('2007-11-03T13:18:05'))->getDay() == 3);
        $this->assertTrue((new DateTimeObject('1994-11-05T13:15:30+01:00'))->getDay() == 5);
        $this->assertTrue((new DateTimeObject('2007-11-03T13:18:05.987+01:00'))->getDay() == 3);
        $this->assertTrue((new DateTimeObject('2010-02-18T16:23:48.54123+01:00'))->getDay() == 18);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getDay();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetDayOfWeek
     *
     * @return void
     */
    public function testGetDayOfWeek(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-12-15'))->getDayOfWeek() == 3);
        $this->assertTrue((new DateTimeObject('2007-11-03T13:18:05'))->getDayOfWeek() == 7);
        $this->assertTrue((new DateTimeObject('1994-11-02T13:15:30+01:00'))->getDayOfWeek() == 4);
        $this->assertTrue((new DateTimeObject('2027-02-03T13:18:05.987+01:00'))->getDayOfWeek() == 4);
        $this->assertTrue((new DateTimeObject('2010-09-18T16:23:48.54123+01:00'))->getDayOfWeek() == 7);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getDayOfWeek();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetHour
     *
     * @return void
     */
    public function testGetHour(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-12-15T13:40'))->getHour() == 13);
        $this->assertTrue((new DateTimeObject('2015-12-15 19:40'))->getHour() == 19);
        $this->assertTrue((new DateTimeObject('2007-11-03T00:18:05'))->getHour() == 0);
        $this->assertTrue((new DateTimeObject('1994-11-05T06:15:30+01:00'))->getHour() == 6);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:18:05.987+01:00'))->getHour() == 10);
        $this->assertTrue((new DateTimeObject('2010-02-18 16:23:48.54123+01:00'))->getHour() == 16);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:48.54123+01:00'))->getHour() == 23);
        $this->assertTrue((new DateTimeObject('2010-02-18 23:23:48.54123+01:00'))->getHour() == 23);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getHour();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetMinute
     *
     * @return void
     */
    public function testGetMinute(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2015-12-15T13:40'))->getMinute() == 40);
        $this->assertTrue((new DateTimeObject('2015-12-15 19:40'))->getMinute() == 40);
        $this->assertTrue((new DateTimeObject('2007-11-03T00:18:05'))->getMinute() == 18);
        $this->assertTrue((new DateTimeObject('1994-11-05T06:15:30+01:00'))->getMinute() == 15);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:18:05.987+01:00'))->getMinute() == 18);
        $this->assertTrue((new DateTimeObject('2010-02-18 16:23:48.54123+01:00'))->getMinute() == 23);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:48.54123+01:00'))->getMinute() == 23);
        $this->assertTrue((new DateTimeObject('2010-02-18 23:23:48.54123+01:00'))->getMinute() == 23);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getMinute();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetSecond
     *
     * @return void
     */
    public function testGetSecond(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('1994-11-05T06:15:10'))->getSecond() == 10);
        $this->assertTrue((new DateTimeObject('2007-11-03T00:18:05'))->getSecond() == 5);
        $this->assertTrue((new DateTimeObject('1994-11-05T06:15:30+01:00'))->getSecond() == 30);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.987+01:00'))->getSecond() == 5);
        $this->assertTrue((new DateTimeObject('2010-02-18 16:23:48.54123+01:00'))->getSecond() == 48);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:00.54123+01:00'))->getSecond() == 0);
        $this->assertTrue((new DateTimeObject('2010-02-18 23:23:59.54123+01:00'))->getSecond() == 59);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getSecond();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetMiliSecond
     *
     * @return void
     */
    public function testGetMiliSecond(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.9'))->getMiliSecond() == 900);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.98'))->getMiliSecond() == 980);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.987'))->getMiliSecond() == 987);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.9+01:00'))->getMiliSecond() == 900);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.98+01:00'))->getMiliSecond() == 980);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.987+01:00'))->getMiliSecond() == 987);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.9871+01:00'))->getMiliSecond() == 987);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.98712+01:00'))->getMiliSecond() == 987);
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.987134+01:00'))->getMiliSecond() == 987);
        $this->assertTrue((new DateTimeObject('2010-02-18 16:23:48.00123+01:00'))->getMiliSecond() == 1);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:00.04123-01:00'))->getMiliSecond() == 41);
        $this->assertTrue((new DateTimeObject('2010-02-18 23:23:59.54123+01:00'))->getMiliSecond() == 541);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.54-01:00'))->getMiliSecond() == 540);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000001-11:00'))->getMiliSecond() == 0);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000011-11:00'))->getMiliSecond() == 0);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000111-11:00'))->getMiliSecond() == 0);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.001111-11:00'))->getMiliSecond() == 1);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000000-11:00'))->getMiliSecond() == 0);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.100000+11:00'))->getMiliSecond() == 100);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getMiliSecond();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetMicroSecond
     *
     * @return void
     */
    public function testGetMicroSecond(){

        // Test valid values
        $this->assertTrue((new DateTimeObject('2007-11-03T10:08:05.987+01:00'))->getMicroSecond() == 987000);
        $this->assertTrue((new DateTimeObject('2010-02-18 16:23:48.54123+01:00'))->getMicroSecond() == 541230);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:00.54123-01:00'))->getMicroSecond() == 541230);
        $this->assertTrue((new DateTimeObject('2010-02-18 23:23:59.54123+01:00'))->getMicroSecond() == 541230);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.54-01:00'))->getMicroSecond() == 540000);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000001-11:00'))->getMicroSecond() == 1);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000011-11:00'))->getMicroSecond() == 11);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.000101-11:00'))->getMicroSecond() == 101);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.001001-11:00'))->getMicroSecond() == 1001);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.010001-11:00'))->getMicroSecond() == 10001);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.100000-11:00'))->getMicroSecond() == 100000);
        $this->assertTrue((new DateTimeObject('2010-02-18T23:23:59.100000+11:00'))->getMicroSecond() == 100000);
        $this->assertTrue((new DateTimeObject('1994-11-05T13:15:30.123Z'))->getMicroSecond() == 123000);

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getMicroSecond();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetTimeZoneName
     *
     * @return void
     */
    public function testGetTimeZoneName(){

        // Test valid values
        $this->assertContains('Europe', (new DateTimeObject('2007-11-03T10:08:05.987+01:00'))->getTimeZoneName());
        $this->assertContains('Europe', (new DateTimeObject('2010-02-18 16:23:48.54123+01:00'))->getTimeZoneName());
        $this->assertContains('Atlantic', (new DateTimeObject('2010-02-18T23:23:00.54123-01:00'))->getTimeZoneName());
        $this->assertContains('Europe', (new DateTimeObject('2010-02-18 23:23:59.54123+01:00'))->getTimeZoneName());
        $this->assertContains('Atlantic', (new DateTimeObject('2010-02-18T23:23:59.54-01:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.000001-11:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.000011-11:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.000101-11:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.001001-11:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.010001-11:00'))->getTimeZoneName());
        $this->assertContains('Pacific', (new DateTimeObject('2010-02-18T23:23:59.100000-11:00'))->getTimeZoneName());
        $this->assertContains('TODO', (new DateTimeObject('2010-02-18T23:23:59.100000+11:00'))->getTimeZoneName());
        $this->assertContains('Europe', (new DateTimeObject('1994-11-05T13:15:30.123Z'))->getTimeZoneName());

        // Test invalid values

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getTimeZoneName();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetTimeZoneOffset
     *
     * @return void
     */
    public function testGetTimeZoneOffset(){

        // Test valid values
        $this->assertSame(7200, (new DateTimeObject('2015'))->getTimeZoneOffset());
        $this->assertSame(7200, (new DateTimeObject('2015-05'))->getTimeZoneOffset());
        $this->assertSame(7200, (new DateTimeObject('2015-05-25'))->getTimeZoneOffset());
        $this->assertSame(7200, (new DateTimeObject('2015-05-25T16:23:48'))->getTimeZoneOffset());
        $this->assertSame(3600, (new DateTimeObject('3010-09-18T16:23:48.54123'))->getTimeZoneOffset());
        $this->assertSame(3600, (new DateTimeObject('3010-09-18T16:23:48.54123+01:00'))->getTimeZoneOffset());
        $this->assertSame(7200, (new DateTimeObject('3010-09-18T16:23:48.54123+02:00'))->getTimeZoneOffset());
        $this->assertSame(18000, (new DateTimeObject('3010-09-18T16:23:48.54123+05:00'))->getTimeZoneOffset());
        $this->assertSame(36000, (new DateTimeObject('3010-09-18T16:23:48.54123+10:00'))->getTimeZoneOffset());
        $this->assertSame(6000, (new DateTimeObject('3010-09-18T16:23:48.54123+01:40'))->getTimeZoneOffset());
        $this->assertSame(23400, (new DateTimeObject('3010-09-18T16:23:48.54123+06:30'))->getTimeZoneOffset());
        $this->assertSame(-3600, (new DateTimeObject('3010-09-18T16:23:48.54123-01:00'))->getTimeZoneOffset());
        $this->assertSame(-7200, (new DateTimeObject('3010-09-18T16:23:48.54123-02:00'))->getTimeZoneOffset());
        $this->assertSame(-18000, (new DateTimeObject('3010-09-18T16:23:48.54123-05:00'))->getTimeZoneOffset());
        $this->assertSame(-36000, (new DateTimeObject('3010-09-18T16:23:48.54123-10:00'))->getTimeZoneOffset());
        $this->assertSame(-6000, (new DateTimeObject('3010-09-18T16:23:48.54123-01:40'))->getTimeZoneOffset());
        $this->assertSame(-23400, (new DateTimeObject('3010-09-18T16:23:48.54123-06:30'))->getTimeZoneOffset());

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getTimeZoneOffset();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testGetFirstDayOfMonth
     *
     * @return void
     */
    public function testGetFirstDayOfMonth(){

        // Test valid values
        $this->assertSame('2010-09-01', (new DateTimeObject('2010-09'))->getFirstDayOfMonth()->toString('Y-M-D'));
        $this->assertSame('2012-01-01', (new DateTimeObject('2012-01-18'))->getFirstDayOfMonth()->toString('Y-M-D'));
        $this->assertSame('2000-12-01', (new DateTimeObject('2000-12-18T16:23:48'))->getFirstDayOfMonth()->toString('Y-M-D'));
        $this->assertSame('2010-09-01', (new DateTimeObject('2010-09-18T16:23:48.54123+01:00'))->getFirstDayOfMonth()->toString('Y-M-D'));
        $this->assertSame('2020-01-01', (new DateTimeObject('2020-01-01T16:23:48.54123+01:00'))->getFirstDayOfMonth()->toString('Y-M-D'));

        // test exceptions
        foreach ($this->invalidDateTimeValues as $value) {

            try {
                (new DateTimeObject($value))->getFirstDayOfMonth();
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    // TODO - !!!!!!!!!!!!!! A PARTIR DE AQUI S'HA DE REVISAR TOTTT
    // Cal anar posant els tests en el mateix ordre que estan al datetimeobject les funcions corresponents

































    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
    }











    /**
     * testIsSameDateTime
     *
     * @return void
     */
    public function testIsSameDateTime(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::isSameDateTime('2015', '2015'));
        $this->assertTrue(DateTimeObject::isSameDateTime('2015-06', '2015-06'));
        $this->assertTrue(DateTimeObject::isSameDateTime('2015-06-21', '2015-06-21'));
        $this->assertTrue(DateTimeObject::isSameDateTime('2015-06-21T19:31:05', '2015-06-21T19:31:05'));
        $this->assertTrue(DateTimeObject::isSameDateTime('1994-11-05T13:15:30.123456Z', '1994-11-05T13:15:30.123456Z'));
        $this->assertTrue(DateTimeObject::isSameDateTime('1994-11-05T13:15:30.123456+00:00', '1994-11-05T13:15:30.123456+00:00'));
        $this->assertTrue(DateTimeObject::isSameDateTime('1994-11-05T13:15:30.123456+01:00', '1994-11-05T12:15:30.123456+00:00'));
        $this->assertTrue(DateTimeObject::isSameDateTime('2015-11-05T13:15:30.123456+05:00', '2015-11-05T08:15:30.123456+00:00'));

        // Test invalid values
        $this->assertTrue(!DateTimeObject::isSameDateTime('2015', '2016'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('2015-06', '2015-11'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('2015-06-11', '2015-06-21'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('2015-06-21T19:31:05', '2015-06-21T19:32:05'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('1994-11-05T13:15:30.124456Z', '1994-11-05T13:15:30.123456Z'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('1994-11-05T13:15:30.123456+00:00', '1994-11-05T14:15:30.123456+00:00'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('1994-11-05T13:15:30.123456+02:00', '1994-11-05T12:15:30.123456+00:00'));
        $this->assertTrue(!DateTimeObject::isSameDateTime('2015-11-05T13:15:30.123456+05:00', '2015-11-05T08:15:30.123457+00:00'));

        // test exceptions
        foreach ($this->invalidValues as $value1) {

            foreach ($this->invalidValues as $value2) {

                try {
                    DateTimeObject::isSameDateTime($value1, $value2);
                    $this->exceptionMessage = $value.' did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }
    }





    /**
     * testIsUTC
     *
     * @return void
     */
    public function testIsUTC(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
    }













    /**
     * testGetDateTimeNow
     *
     * @return void
     */
    public function testGetDateTimeNow(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::isValidDateTime(DateTimeObject::getCurrentDateTime()));
    }








    /**
     * testGetLastDayOfMonth
     *
     * @return void
     */
    public function testGetLastDayOfMonth(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::getLastDayOfMonth('2010-09') == '2010-09-30T00:00:00.000000+02:00');
        $this->assertTrue(DateTimeObject::getLastDayOfMonth('2012-01-18') == '2012-01-31T00:00:00.000000+01:00');
        $this->assertTrue(DateTimeObject::getLastDayOfMonth('2000-12-18T16:23:48') == '2000-12-31T16:23:48.000000+01:00');
        $this->assertTrue(DateTimeObject::getLastDayOfMonth('2010-09-18T16:23:48.54123+01:00') == '2010-09-30T16:23:48.541230+01:00');
        $this->assertTrue(DateTimeObject::getLastDayOfMonth('2020-01-01T16:23:48.54123+01:00') == '2020-01-31T16:23:48.541230+01:00');

        // test exceptions
        $invalidValues = array_merge($this->invalidValues, ['2010', '2010-13', '2015-01-32']);

        foreach ($invalidValues as $value) {

            try {
                DateTimeObject::getLastDayOfMonth($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testConvertToLocalTimeZone
     *
     * @return void
     */
    public function testConvertToLocalTimeZone(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2015') == '2015');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2015-11') == '2015-11');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2015-01-01') == '2015-01-01T00:00:00.000000+01:00');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2010-02-18T16:23:48.541+06:00') == '2010-02-18T11:23:48.541000+01:00');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2007-11-03T13:18:05') == '2007-11-03T13:18:05.000000+01:00');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('2008-09-15T15:53:00+05:00') == '2008-09-15T12:53:00.000000+02:00');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('1994-11-05T08:15:30-05:00') == '1994-11-05T14:15:30.000000+01:00');
        $this->assertTrue(DateTimeObject::convertToLocalTimeZone('1994-11-05T13:15:30Z') == '1994-11-05T14:15:30.000000+01:00');

        // test exceptions
        foreach ($this->invalidValues as $value) {

            try {
                DateTimeObject::convertToLocalTimeZone($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testConvertToUTCTimeZone
     *
     * @return void
     */
    public function testConvertToUTCTimeZone(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2015') == '2015');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2015-11') == '2015-11');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2015-01-01') == '2014-12-31T23:00:00.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2015-11-11') == '2015-11-10T23:00:00.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2010-02-18T16:23:48.541+06:00') == '2010-02-18T10:23:48.541000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2007-11-03T13:18:05') == '2007-11-03T12:18:05.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2008-09-15T15:53:00+05:00') == '2008-09-15T10:53:00.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('1994-11-05T08:15:30-05:00') == '1994-11-05T13:15:30.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('1994-11-05T13:15:30Z') == '1994-11-05T13:15:30.000000+00:00');
        $this->assertTrue(DateTimeObject::convertToUTCTimeZone('2001-12-31T23:59:59.12+04:00') == '2001-12-31T19:59:59.120000+00:00');

        // test exceptions
        foreach ($this->invalidValues as $value) {

            try {
                DateTimeObject::convertToUTCTimeZone($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testFormat
     *
     * @return void
     */
    public function testFormat(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid values
        $this->assertTrue(DateTimeObject::format('2015', 'y') == '15');
        $this->assertTrue(DateTimeObject::format('2015', 'Y') == '2015');
        $this->assertTrue(DateTimeObject::format('2015', 'm-D-y h:m:s-U') == 'm-D-15 h:m:s-U');
        $this->assertTrue(DateTimeObject::format('2015-01', 'm-y') == '1-15');
        $this->assertTrue(DateTimeObject::format('2015-01', 'M-y') == '01-15');
        $this->assertTrue(DateTimeObject::format('2015-11', 'M-Y') == '11-2015');
        $this->assertTrue(DateTimeObject::format('2015-01', 'm-Y') == '1-2015');
        $this->assertTrue(DateTimeObject::format('2015-01-01', 'd-m-y') == '1-1-15');
        $this->assertTrue(DateTimeObject::format('2015-01-01', 'D-M-Y') == '01-01-2015');
        $this->assertTrue(DateTimeObject::format('1998-12-06', 'd/m/y') == '6/12/98');
        $this->assertTrue(DateTimeObject::format('1998-12-06', 'D/M/Y') == '06/12/1998');
        $this->assertTrue(DateTimeObject::format('2015-01-01', 'd_M_Y') == '1_01_2015');
        $this->assertTrue(DateTimeObject::format('2010-02-18T16', 'd-m-y h:n:s') == '18-2-10 16:n:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T06', 'd-m-y h:n:s') == '18-2-10 6:n:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T06', 'd-m-y H:n:s') == '18-2-10 06:n:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T16:20', 'd-m-y h:n:s') == '18-2-10 16:20:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T16:01', 'd-m-y h:n:s') == '18-2-10 16:1:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T16:01', 'd-m-y h:N:s') == '18-2-10 16:01:s');
        $this->assertTrue(DateTimeObject::format('2010-02-18T00:00:00.541+06:00', 'd-m-y h:n:s') == '18-2-10 0:0:0');
        $this->assertTrue(DateTimeObject::format('2010-02-18T16:23:48.541+06:00', 'd-m-y h:n:s') == '18-2-10 16:23:48');
        $this->assertTrue(DateTimeObject::format('2010-02-18T01:03:08.541+06:00', 'D-M-Y h:n:s') == '18-02-2010 1:3:8');
        $this->assertTrue(DateTimeObject::format('2010-02-18T01:03:08.541+06:00', 'D-M-Y H:N:S') == '18-02-2010 01:03:08');
        $this->assertTrue(DateTimeObject::format('2008-09-15T15:03:10.876+05:00', 'm-d-Y h:n:s:u') == '9-15-2008 15:3:10:876');
        $this->assertTrue(DateTimeObject::format('2008-09-15T15:53:10.876467+05:00', 'M-D-y h:n:s-U') == '09-15-08 15:53:10-876467');
        $this->assertTrue(DateTimeObject::format('2008-09-15T15:53:10.876467+05:00', 'M-D-y h:n:s-u') == '09-15-08 15:53:10-876');
        $this->assertTrue(DateTimeObject::format('2008-09-15T15:53:10.000001+05:00', 'M-D-y h:n:s-u') == '09-15-08 15:53:10-000');
        $this->assertTrue(DateTimeObject::format('2008-09-15T15:53:10.001+05:00', 'M-D-y h:n:s-U') == '09-15-08 15:53:10-001000');
        $this->assertTrue(DateTimeObject::format('1994-11-05T13:15:30Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:U');
        $this->assertTrue(DateTimeObject::format('1994-11-05T13:15:30.123Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:123000');
        $this->assertTrue(DateTimeObject::format('1994-11-05T13:15:30.123456Z', 'y-d-m h:n:s:U') == '94-5-11 13:15:30:123456');

        // test exceptions
        foreach ($this->invalidValues as $value) {

            try {
                DateTimeObject::format($value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }



    /**
     * testAdd
     *
     * @return void
     */
    public function testAdd(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // Test valid year values
        $this->assertTrue(DateTimeObject::add('2015', 1, 'years') === '2016');
        $this->assertTrue(DateTimeObject::add('2015', -1, 'years') === '2014');
        $this->assertTrue(DateTimeObject::add('2015', 10, 'years') === '2025');
        $this->assertTrue(DateTimeObject::add('2015', -10, 'years') === '2005');
        $this->assertTrue(DateTimeObject::add('2015-08', 1, 'years') === '2016-08');
        $this->assertTrue(DateTimeObject::add('2015-08', -1, 'years') === '2014-08');
        $this->assertTrue(DateTimeObject::add('2015-08', 7, 'years') === '2022-08');
        $this->assertTrue(DateTimeObject::add('2015-08', -9, 'years') === '2006-08');
        $this->assertTrue(DateTimeObject::add('2015-01-12', 1, 'years') === '2016-01-12');
        $this->assertTrue(DateTimeObject::add('2000-10-01', -1, 'years') === '1999-10-01');
        $this->assertTrue(DateTimeObject::add('1996-12-31', 7, 'years') === '2003-12-31');
        $this->assertTrue(DateTimeObject::add('2025-04-23', -9, 'years') === '2016-04-23');
        $this->assertTrue(DateTimeObject::add('1996-12-31T15', 7, 'years') === '2003-12-31T15');
        $this->assertTrue(DateTimeObject::add('2025-04-23T23', -9, 'years') === '2016-04-23T23');
        $this->assertTrue(DateTimeObject::add('1996-12-31T15:45', 7, 'years') === '2003-12-31T15:45');
        $this->assertTrue(DateTimeObject::add('2025-04-23T23:59', -9, 'years') === '2016-04-23T23:59');
        $this->assertTrue(DateTimeObject::add('1996-12-31T15:45:00', 127, 'years') === '2123-12-31T15:45:00');
        $this->assertTrue(DateTimeObject::add('2025-04-23T23:59:32', -900, 'years') === '1125-04-23T23:59:32');
        $this->assertTrue(DateTimeObject::add('2015-11-05T08:15:30.123456-01:00', 1, 'years') === '2016-11-05T08:15:30.123456-01:00');
        $this->assertTrue(DateTimeObject::add('2015-11-05T08:15:30.123456-01:00', 8, 'years') === '2023-11-05T08:15:30.123456-01:00');
        $this->assertTrue(DateTimeObject::add('2015-11-05T08:15:30.123456-01:00', -1, 'years') === '2014-11-05T08:15:30.123456-01:00');
        $this->assertTrue(DateTimeObject::add('2015-11-05T08:15:30.123456-01:00', -8, 'years') === '2007-11-05T08:15:30.123456-01:00');

        // Test valid month values
        // TODO - Pending

        // Test valid day values
        // TODO - Pending

        // Test valid hour values
        // TODO - Pending

        // Test valid minutes values
        // TODO - Pending

        // Test valid seconds values
        // TODO - Pending

        // Test valid miliseconds values
        // TODO - Pending

        // Test valid microseconds values
        // TODO - Pending

        // test exceptions
        foreach ($this->invalidValues as $value) {

            try {
                DateTimeObject::add($value, 1, 'y');
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $invalidValueValues = ['a', null, new Exception(), 1.1, 0.1, 56.9];

        foreach ($invalidValueValues as $value) {

            try {
                DateTimeObject::add('2015', $value, 'y');
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $invalidTypeValues = ['a', null, new Exception(), 1, 'ya', '12', 'y', 'Y', 'm', 'month', 'year', 'second'];

        foreach ($invalidTypeValues as $value) {

            try {
                DateTimeObject::add('2015', 1, $value);
                $this->exceptionMessage = $value.' did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testSubstract
     *
     * @return void
     */
    public function testSubstract(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        // TODO
    }
}

?>