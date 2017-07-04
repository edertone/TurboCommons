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
use org\turbocommons\src\main\php\utils\ArrayUtils;
use PHPUnit\Framework\TestCase;


/**
 * ArrayUtils tests
 *
 * @return void
 */
class ArrayUtilsTest extends TestCase {


    /**
     * testIsArray
     *
     * @return void
     */
    public function testIsArray(){

        // Test empty values
        $this->assertTrue(!ArrayUtils::isArray(null));
        $this->assertTrue(ArrayUtils::isArray([]));
        $this->assertTrue(!ArrayUtils::isArray(0));

        // Test correct values
        $this->assertTrue(ArrayUtils::isArray([1]));
        $this->assertTrue(ArrayUtils::isArray(['2']));
        $this->assertTrue(ArrayUtils::isArray(['q']));
        $this->assertTrue(ArrayUtils::isArray([true, false]));
        $this->assertTrue(ArrayUtils::isArray([1, 4, 'a']));
        $this->assertTrue(ArrayUtils::isArray([new Exception(), 67]));

        // Test wrong values
        $this->assertTrue(!ArrayUtils::isArray(1));
        $this->assertTrue(!ArrayUtils::isArray('a'));
        $this->assertTrue(!ArrayUtils::isArray(false));
        $this->assertTrue(!ArrayUtils::isArray(new Exception()));
        $this->assertTrue(!ArrayUtils::isArray((object) [
            'a' => 1
        ]));
    }


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

		// Test non array values must launch exception
		$exceptionMessage = '';

		try {
			ArrayUtils::isEqualTo(null, null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ArrayUtils::isEqualTo(1, 1);
			$exceptionMessage = '1 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ArrayUtils::isEqualTo('asfasf1', '345345');
			$exceptionMessage = 'asfasf1 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}

		// Test identic arrays
		$this->assertTrue(ArrayUtils::isEqualTo([null], [null]));
		$this->assertTrue(ArrayUtils::isEqualTo([], []));
		$this->assertTrue(ArrayUtils::isEqualTo([[]], [[]]));
		$this->assertTrue(ArrayUtils::isEqualTo([[1]], [[1]]));
		$this->assertTrue(ArrayUtils::isEqualTo([1, 2, 3], [1, 2, 3]));
		$this->assertTrue(ArrayUtils::isEqualTo([1, 2, 1, 2], [1, 2, 1, 2]));
		$this->assertTrue(ArrayUtils::isEqualTo([1, 2, [3, 4]], [1, 2, [3, 4]]));
		$this->assertTrue(ArrayUtils::isEqualTo(['hello world'], ['hello world']));

		// Test different arrays
		$this->assertTrue(!ArrayUtils::isEqualTo([null], []));
		$this->assertTrue(!ArrayUtils::isEqualTo([1], ['1']));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, 3], [1, 3, 2]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, '2,3'], [1, 2, 3]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ['4']]]));
		$this->assertTrue(!ArrayUtils::isEqualTo(['hello world'], ['hello worl1d']));

		// Test identic objects
		$this->assertTrue(ArrayUtils::isEqualTo([(object) [
				'a' => 1
		]], [(object) [
				'a' => 1
		]]));

		$this->assertTrue(ArrayUtils::isEqualTo([(object) [
				'a' => 1,
				'b' => [1, 2, 3],
				'c' => 'hello'
		]], [(object) [
				'a' => 1,
				'b' => [1, 2, 3],
				'c' => 'hello'
		]]));

		// Test different objects
		$this->assertTrue(!ArrayUtils::isEqualTo([(object) [
				'a' => 1,
				'b' => [1, 4, 3],
				'c' => 'hello'
		]], [(object) [
				'a' => 1,
				'b' => [1, 2, 3],
				'c' => 'hello'
		]]));
	}

	/**
	 * testRemoveElement
	 *
	 * @return void
	 */
	public function testRemoveElement(){

		// TODO - Translate from JS
	}


	/**
	 * testRemoveDuplicateElements
	 *
	 * @return void
	 */
	public function testRemoveDuplicateElements(){

	    // Test empty values
	    $exceptionMessage = '';

	    try {
	        ArrayUtils::removeDuplicateElements(null);
	        $exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::removeDuplicateElements('');
	        $exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::removeDuplicateElements(new stdClass());
	        $exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    $this->assertEquals([], ArrayUtils::removeDuplicateElements([]));
	    $this->assertEquals([null], ArrayUtils::removeDuplicateElements([null]));
	    $this->assertEquals([null], ArrayUtils::removeDuplicateElements([null, null]));

	    // Test ok values
	    $this->assertEquals([1], ArrayUtils::removeDuplicateElements([1, 1]));
	    $this->assertEquals(['1'], ArrayUtils::removeDuplicateElements(['1', '1']));
	    $this->assertEquals([1, 0], ArrayUtils::removeDuplicateElements([1, 0, 1]));
	    $this->assertEquals(['1', '0'], ArrayUtils::removeDuplicateElements(['1', '0', '1']));
	    $this->assertEquals([1, 2, 3, 4], ArrayUtils::removeDuplicateElements([1, 2, 3, 4, 2]));
	    $this->assertEquals(['hello', 'go'], ArrayUtils::removeDuplicateElements(['hello', 'go', 'hello']));
	    $this->assertEquals([new Exception(), 'go', 'hello'], ArrayUtils::removeDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

	    // Test wrong values
	    $this->assertEquals([1], ArrayUtils::removeDuplicateElements([1]));
	    $this->assertEquals([1, 2], ArrayUtils::removeDuplicateElements([1, 2]));
	    $this->assertEquals(['1', '2'], ArrayUtils::removeDuplicateElements(['1', '2']));
	    $this->assertEquals([1, 2, 3, 4, 5, 6], ArrayUtils::removeDuplicateElements([1, 2, 3, 4, 5, 6]));
	    $this->assertEquals(['1', 1], ArrayUtils::removeDuplicateElements(['1', 1]));
	    $this->assertEquals([new Exception(), 'go', 'hello'], ArrayUtils::removeDuplicateElements([new Exception(), 'go', 'hello']));

	    // Test exceptions
	    // Already tested with empty values
	}


	/**
	 * testHasDuplicateElements
	 *
	 * @return void
	 */
	public function testHasDuplicateElements(){

	    // Test empty values
	    $exceptionMessage = '';

	    try {
	        ArrayUtils::hasDuplicateElements(null);
	        $exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::hasDuplicateElements('');
	        $exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::hasDuplicateElements(new stdClass());
	        $exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    $this->assertFalse(ArrayUtils::hasDuplicateElements([]));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements([null]));

	    // Test ok values
	    $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 1]));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements(['1', '1']));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 0, 1]));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements(['1', '0', '1']));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 2, 3, 4, 2]));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements(['hello', 'go', 'hello']));
	    $this->assertTrue(ArrayUtils::hasDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

	    $array = [];

	    for ($i = 0; $i < 100; $i++) {

	        for ($j = 0; $j < 100; $j++) {

	            $array[] = $j;
	        }

	        $array[] = $i;

	        $this->assertTrue(ArrayUtils::hasDuplicateElements($array));
	    }

	    // Test wrong values
	    $this->assertFalse(ArrayUtils::hasDuplicateElements([1]));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements([1, 2]));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements(['1', '2']));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements([1, 2, 3, 4, 5, 6]));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements(['1', 1]));
	    $this->assertFalse(ArrayUtils::hasDuplicateElements([new Exception(), 'go', 'hello']));

	    // Test exceptions
	    // Already tested with empty values
	}


	/**
	 * testGetDuplicateElements
	 *
	 * @return void
	 */
	public function testGetDuplicateElements(){

	    // Test empty values
	    $exceptionMessage = '';

	    try {
	        ArrayUtils::getDuplicateElements(null);
	        $exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::getDuplicateElements('');
	        $exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        ArrayUtils::getDuplicateElements(new stdClass());
	        $exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    $this->assertEquals([], ArrayUtils::getDuplicateElements([]));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements([null]));

	    // Test ok values
	    $this->assertEquals([1], ArrayUtils::getDuplicateElements([1, 1]));
	    $this->assertEquals(['1'], ArrayUtils::getDuplicateElements(['1', '1']));
	    $this->assertEquals([1], ArrayUtils::getDuplicateElements([1, 0, 1]));
	    $this->assertEquals(['1'], ArrayUtils::getDuplicateElements(['1', '0', '1']));
	    $this->assertEquals([2], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 2]));
	    $this->assertEquals([2, 3], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 2, 3, 3, 3]));
	    $this->assertEquals(['hello'], ArrayUtils::getDuplicateElements(['hello', 'go', 'hello']));
	    $this->assertEquals([new Exception()], ArrayUtils::getDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

	    // Test wrong values
	    $this->assertEquals([], ArrayUtils::getDuplicateElements([1]));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements([1, 2]));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements(['1', '2']));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 5, 6]));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements(['1', 1]));
	    $this->assertEquals([], ArrayUtils::getDuplicateElements([new Exception(), 'go', 'hello']));

	    // Test exceptions
	    // Already tested with empty values
	}
}

?>