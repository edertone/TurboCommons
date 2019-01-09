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

        $this->exceptionMessage = '';

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);
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

        $objectThatMustNotBeAltered = ((object) ['value' => " 15  "]);
        $this->assertTrue(NumericUtils::isNumeric($objectThatMustNotBeAltered->value));
        $this->assertSame($objectThatMustNotBeAltered->value, " 15  ");

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

        try {
            NumericUtils::getNumeric(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::getNumeric('');
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::getNumeric([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
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
        try {
            NumericUtils::getNumeric('abc');
            $this->exceptionMessage = 'abc did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::getNumeric('1-');
            $this->exceptionMessage = '1- did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::getNumeric('1,1');
            $this->exceptionMessage = '1,1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::getNumeric(['hello']);
            $this->exceptionMessage = 'hello did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGenerateRandomInteger
     *
     * @return void
     */
    public function testGenerateRandomInteger(){

        // Test empty values
        try {
            NumericUtils::generateRandomInteger(0, 0);
            $this->exceptionMessage = '0,0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < $this->emptyValuesCount; $j++) {

                if($this->emptyValues[$i] !== 0 || $this->emptyValues[$j] !== 0){

                    try {
                        NumericUtils::generateRandomInteger($this->emptyValues[$i], $this->emptyValues[$j]);
                        $this->exceptionMessage = 'non integer values did not cause exception';
                    } catch (Throwable $e) {
                        // We expect an exception to happen
                    }
                }
            }
        }

        // Test ok values
        for ($i = 0; $i < 1000; $i+=100) {

            // Both positive
            $min = $i;
            $max = $i * 2 + 1;

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));

            // Both negative
            $min = - NumericUtils::generateRandomInteger($min, $max);
            $max = $min + NumericUtils::generateRandomInteger($i + 1, $i * 10 + 2);

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));

            // Negative min, positive max
            $min = -$i - 1;
            $max = $i * 2 + 1;

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));
        }

        // Test wrong values
        try {
            NumericUtils::generateRandomInteger(10, 0);
            $this->exceptionMessage = '10,0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::generateRandomInteger(10, 10);
            $this->exceptionMessage = '10,10 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            NumericUtils::generateRandomInteger(-10, -20);
            $this->exceptionMessage = '-10,-20 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        $exceptionValues = [new Exception(), 'hello', .1, 1.1, [1, 2, 3, 4]];

        for ($i = 0; $i < count($exceptionValues); $i++) {

            for ($j = 0; $j < count($exceptionValues); $j++) {

                try {
                    NumericUtils::getNumeric(NumericUtils::generateRandomInteger($exceptionValues[$i], $exceptionValues[$j]));
                    $this->exceptionMessage = 'wrong values did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }
    }
}

?>