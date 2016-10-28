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

use org\turbocommons\src\main\php\utils\ArrayUtils;
use PHPUnit_Framework_TestCase;
use Exception;


/**
 * ArrayUtils tests
 *
 * @return void
 */
class ArrayUtilsTest extends PHPUnit_Framework_TestCase {


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
		} catch (Exception $e) {}

		try {
			ArrayUtils::isEqualTo(1, 1);
			$exceptionMessage = '1 did not cause exception';
		} catch (Exception $e) {}

		try {
			ArrayUtils::isEqualTo("asfasf1", "345345");
			$exceptionMessage = 'asfasf1 did not cause exception';
		} catch (Exception $e) {}

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
		$this->assertTrue(ArrayUtils::isEqualTo(["hello world"], ["hello world"]));

		// Test different arrays
		$this->assertTrue(!ArrayUtils::isEqualTo([null], []));
		$this->assertTrue(!ArrayUtils::isEqualTo([1], ["1"]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, 3], [1, 3, 2]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, "2,3"], [1, 2, 3]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
		$this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ["4"]]]));
		$this->assertTrue(!ArrayUtils::isEqualTo(["hello world"], ["hello worl1d"]));

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
}

?>