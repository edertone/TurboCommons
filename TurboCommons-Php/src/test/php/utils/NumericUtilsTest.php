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
use Throwable;
use stdClass;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * NumericUtilsTest
 *
 * @return void
 */
class NumericUtilsTest extends TestCase {


	/**
	 * @see TestCase::setUpBeforeClass()
	 *
	 * @return void
	 */
	public static function setUpBeforeClass(){

		// Nothing necessary here
	}


	/**
	 * @see TestCase::setUp()
	 *
	 * @return void
	 */
	protected function setUp(){

		// Nothing necessary here
	}


	/**
	 * @see TestCase::tearDown()
	 *
	 * @return void
	 */
	protected function tearDown(){

		// Nothing necessary here
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
	 * testIsNumeric
	 *
	 * @return void
	 */
	public function testIsNumeric(){

	    // Test empty values
	    $this->assertFalse(NumericUtils::isNumeric(null));
	    $this->assertFalse(NumericUtils::isNumeric(''));
	    $this->assertFalse(NumericUtils::isNumeric([]));
	    $this->assertFalse(NumericUtils::isNumeric(new stdClass()));
	    $this->assertTrue(NumericUtils::isNumeric(0));
	    $this->assertTrue(NumericUtils::isNumeric(-0));

	    // Test ok values
	    $this->assertTrue(NumericUtils::isNumeric(1));
	    $this->assertTrue(NumericUtils::isNumeric(-1));
	    $this->assertTrue(NumericUtils::isNumeric(.1));
	    $this->assertTrue(NumericUtils::isNumeric(-.1));
	    $this->assertTrue(NumericUtils::isNumeric(0.1));
	    $this->assertTrue(NumericUtils::isNumeric(-0.1));
	    $this->assertTrue(NumericUtils::isNumeric(1560));
	    $this->assertTrue(NumericUtils::isNumeric(-1560));
	    $this->assertTrue(NumericUtils::isNumeric(456.987));
	    $this->assertTrue(NumericUtils::isNumeric(-456.987));
	    $this->assertTrue(NumericUtils::isNumeric(0.00001));
	    $this->assertTrue(NumericUtils::isNumeric(-0.00001));
	    $this->assertTrue(NumericUtils::isNumeric(1560345346456));
	    $this->assertTrue(NumericUtils::isNumeric(-1560345346456));
	    $this->assertTrue(NumericUtils::isNumeric('1'));
	    $this->assertTrue(NumericUtils::isNumeric('-1'));
	    $this->assertTrue(NumericUtils::isNumeric('.1'));
	    $this->assertTrue(NumericUtils::isNumeric('-.1'));
	    $this->assertTrue(NumericUtils::isNumeric('0.1'));
	    $this->assertTrue(NumericUtils::isNumeric('-0.1'));
	    $this->assertTrue(NumericUtils::isNumeric('1560'));
	    $this->assertTrue(NumericUtils::isNumeric('-1560'));
	    $this->assertTrue(NumericUtils::isNumeric('456.987'));
	    $this->assertTrue(NumericUtils::isNumeric('-456.987'));
	    $this->assertTrue(NumericUtils::isNumeric('0.00001'));
	    $this->assertTrue(NumericUtils::isNumeric('-0.00001'));
	    $this->assertTrue(NumericUtils::isNumeric('1560345346456'));
	    $this->assertTrue(NumericUtils::isNumeric('-1560345346456'));
	    $this->assertTrue(NumericUtils::isNumeric(' 1'));
	    $this->assertTrue(NumericUtils::isNumeric('1 '));
	    $this->assertTrue(NumericUtils::isNumeric(' 1 '));
	    $this->assertTrue(NumericUtils::isNumeric('    1     '));
	    $this->assertTrue(NumericUtils::isNumeric("1     \n"));

	    // Test wrong values
	    $this->assertFalse(NumericUtils::isNumeric('abc'));
	    $this->assertFalse(NumericUtils::isNumeric('col20'));
	    $this->assertFalse(NumericUtils::isNumeric('1-'));
	    $this->assertFalse(NumericUtils::isNumeric('1,1'));
	    $this->assertFalse(NumericUtils::isNumeric(' '));
	    $this->assertFalse(NumericUtils::isNumeric('!.1'));
	    $this->assertFalse(NumericUtils::isNumeric([1, 2, 3]));
	    $this->assertFalse(NumericUtils::isNumeric(['hello']));
	    $this->assertFalse(NumericUtils::isNumeric(new Exception()));
	    $this->assertFalse(NumericUtils::isNumeric(((object) [
	        '1' => 1
	    ])));
	}


	/**
	 * testIsInteger
	 *
	 * @return void
	 */
	public function testIsInteger(){

	    // Test empty values
	    $this->assertFalse(NumericUtils::isInteger(null));
	    $this->assertFalse(NumericUtils::isInteger(''));
	    $this->assertFalse(NumericUtils::isInteger([]));
	    $this->assertFalse(NumericUtils::isInteger(new stdClass()));
	    $this->assertTrue(NumericUtils::isInteger(0));
	    $this->assertTrue(NumericUtils::isInteger(-0));

	    // Test ok values
	    $this->assertTrue(NumericUtils::isInteger(1));
	    $this->assertTrue(NumericUtils::isInteger(-1));
	    $this->assertTrue(NumericUtils::isInteger(1560));
	    $this->assertTrue(NumericUtils::isInteger(-1560));
	    $this->assertTrue(NumericUtils::isInteger(1560345346456));
	    $this->assertTrue(NumericUtils::isInteger(-1560345346456));
	    $this->assertTrue(NumericUtils::isInteger('1'));
	    $this->assertTrue(NumericUtils::isInteger('-1'));
	    $this->assertTrue(NumericUtils::isInteger('1560'));
	    $this->assertTrue(NumericUtils::isInteger('-1560'));
	    $this->assertTrue(NumericUtils::isInteger('1560345346456'));
	    $this->assertTrue(NumericUtils::isInteger('-1560345346456'));
	    $this->assertTrue(NumericUtils::isInteger('1560345346456356456246235456'));
	    $this->assertTrue(NumericUtils::isInteger('-15603453464564525123524565476546'));
	    $this->assertTrue(NumericUtils::isInteger(' 1'));
	    $this->assertTrue(NumericUtils::isInteger('1 '));
	    $this->assertTrue(NumericUtils::isInteger(' 1 '));
	    $this->assertTrue(NumericUtils::isInteger('    1     '));
	    $this->assertTrue(NumericUtils::isInteger("1     \n"));

	    // Test wrong values
	    $this->assertFalse(NumericUtils::isInteger('.1'));
	    $this->assertFalse(NumericUtils::isInteger('-.1'));
	    $this->assertFalse(NumericUtils::isInteger('0.1'));
	    $this->assertFalse(NumericUtils::isInteger('-0.1'));
	    $this->assertFalse(NumericUtils::isInteger('456.987'));
	    $this->assertFalse(NumericUtils::isInteger('-456.987'));
	    $this->assertFalse(NumericUtils::isInteger('0.00001'));
	    $this->assertFalse(NumericUtils::isInteger('-0.00001'));
	    $this->assertFalse(NumericUtils::isInteger('abc'));
	    $this->assertFalse(NumericUtils::isInteger('1-'));
	    $this->assertFalse(NumericUtils::isInteger('1,1'));
	    $this->assertFalse(NumericUtils::isInteger(' '));
	    $this->assertFalse(NumericUtils::isInteger('!.1'));
	    $this->assertFalse(NumericUtils::isInteger([1, 2, 3]));
	    $this->assertFalse(NumericUtils::isInteger(['hello']));
	    $this->assertFalse(NumericUtils::isInteger(new Exception()));
	    $this->assertFalse(NumericUtils::isInteger(((object) [
	        '1' => 1
	    ])));
	}


	/**
	 * testGetNumeric
	 *
	 * @return void
	 */
	public function testGetNumeric(){

	    // Test empty values
	    $this->assertTrue(NumericUtils::getNumeric(0) == 0);
	    $this->assertTrue(NumericUtils::getNumeric('0') == 0);

	    $exceptionMessage = '';

	    try {
	        NumericUtils::getNumeric(null);
	        $exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::getNumeric('');
	        $exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::getNumeric([]);
	        $exceptionMessage = '[] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    // Test ok values
	    $this->assertTrue(NumericUtils::getNumeric(1) == 1);
	    $this->assertTrue(NumericUtils::getNumeric(10) == 10);
	    $this->assertTrue(NumericUtils::getNumeric(1123134) == 1123134);
	    $this->assertTrue(NumericUtils::getNumeric(1.1) == 1.1);
	    $this->assertTrue(NumericUtils::getNumeric(.1) == .1);
	    $this->assertTrue(NumericUtils::getNumeric(0.00001) == 0.00001);
	    $this->assertTrue(NumericUtils::getNumeric(1.000001) == 1.000001);
	    $this->assertTrue(NumericUtils::getNumeric(-1) == -1);
	    $this->assertTrue(NumericUtils::getNumeric(-10) == -10);
	    $this->assertTrue(NumericUtils::getNumeric(-1123134) == -1123134);
	    $this->assertTrue(NumericUtils::getNumeric(-1.1) == -1.1);
	    $this->assertTrue(NumericUtils::getNumeric(-.1) == -.1);
	    $this->assertTrue(NumericUtils::getNumeric(-0.00001) == -0.00001);
	    $this->assertTrue(NumericUtils::getNumeric(-1.000001) == -1.000001);
	    $this->assertTrue(NumericUtils::getNumeric('1') == 1);
	    $this->assertTrue(NumericUtils::getNumeric('10') == 10);
	    $this->assertTrue(NumericUtils::getNumeric('1123134') == 1123134);
	    $this->assertTrue(NumericUtils::getNumeric('1.1') == 1.1);
	    $this->assertTrue(NumericUtils::getNumeric('.1') == .1);
	    $this->assertTrue(NumericUtils::getNumeric('0.00001') == 0.00001);
	    $this->assertTrue(NumericUtils::getNumeric('1.000001') == 1.000001);
	    $this->assertTrue(NumericUtils::getNumeric('-1') == -1);
	    $this->assertTrue(NumericUtils::getNumeric('-10') == -10);
	    $this->assertTrue(NumericUtils::getNumeric('-1123134') == -1123134);
	    $this->assertTrue(NumericUtils::getNumeric('-1.1') == -1.1);
	    $this->assertTrue(NumericUtils::getNumeric('-.1') == -.1);
	    $this->assertTrue(NumericUtils::getNumeric('-0.00001') == -0.00001);
	    $this->assertTrue(NumericUtils::getNumeric('-1.000001') == -1.000001);
	    $this->assertTrue(NumericUtils::getNumeric('  1 ') == 1);
	    $this->assertTrue(NumericUtils::getNumeric('  .1 ') == 0.1);
	    $this->assertTrue(NumericUtils::getNumeric('  -1 ') == -1);

	    // Test wrong values
	    $exceptionMessage = '';

	    try {
	        NumericUtils::getNumeric('abc');
	        $exceptionMessage = 'abc did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::getNumeric('1-');
	        $exceptionMessage = '1- did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::getNumeric('1,1');
	        $exceptionMessage = '1,1 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::getNumeric(['hello']);
	        $exceptionMessage = 'hello did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}


	/**
	 * testGenerateRandomInteger
	 *
	 * @return void
	 */
	public function testGenerateRandomInteger(){

	    // Test ok values
	    for ($i = 0; $i < 1000; $i+=100) {

	        $min = $i;
	        $max = $i * 2 + 1;

	        $val = NumericUtils::generateRandomInteger($max, $min);
	        $this->assertTrue($val >= $min && $val <= $max);
	        $this->assertTrue(NumericUtils::isInteger($val));

	        $min = NumericUtils::generateRandomInteger($max, $min);
	        $max = $min + NumericUtils::generateRandomInteger($i * 10 + 2, $i + 1);

	        $val = NumericUtils::generateRandomInteger($max, $min);
	        $this->assertTrue($val >= $min && $val <= $max);
	        $this->assertTrue(NumericUtils::isInteger($val));
	    }

	    // Test exceptions
	    $exceptionValues = [null, '', [], new stdClass(), 'hello', -1, .1, 1.1, [1, 2, 3, 4]];
	    $exceptionValuesCount = count($exceptionValues);

	    $exceptionMessage = '';

	    try {
	        NumericUtils::generateRandomInteger(5, 5);
	        $exceptionMessage = '5,5 values did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        NumericUtils::generateRandomInteger(5, 6);
	        $exceptionMessage = '5,6 values did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    for ($i = 0; $i < $exceptionValuesCount; $i++) {

	        for ($j = 0; $j < $exceptionValuesCount; $j++) {

	            try {
	                NumericUtils::generateRandomInteger($exceptionValues[$i], $exceptionValues[$j]);
	                $exceptionMessage = 'exception value did not cause exception';
	            } catch (Throwable $e) {
	                // We expect an exception to happen
	            }
	        }
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}
}

?>