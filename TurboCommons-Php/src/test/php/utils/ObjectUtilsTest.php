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

use PHPUnit_Framework_TestCase;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;
use stdClass;
use Exception;


/**
 * ObjectUtils tests
 *
 * @return void
 */
class ObjectUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testGetKeys
	 *
	 * @return void
	 */
	public function testGetKeys(){

		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(new stdClass()), []));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'1' => 1
		])), ['1']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
			'a' => 1
		])), ['a']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'a' => 1,
				'b' => 2,
				'c' => 3
		])), ['a', 'b', 'c']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'a' => 1,
				'b' => (object) [
						'a' => 1,
						'x' => 0
					],
				'c' => 3
		])), ['a', 'b', 'c']));

		// Test exceptions
		try {
			ObjectUtils::getKeys(null);
			$this->fail('Expected exception');
		} catch (Exception $e) {}

		try {
			ObjectUtils::getKeys([]);
			$this->fail('Expected exception');
		} catch (Exception $e) {}

		try {
			ObjectUtils::getKeys([1, 2, 3]);
			$this->fail('Expected exception');
		} catch (Exception $e) {}
	}


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

		// Test identic values
		$this->assertTrue(ObjectUtils::isEqualTo(new stdClass(), new stdClass()));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home'
		]), ((object) [
				'hello' => 'home'
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
			'1' => 1
		]), ((object) [
			'1' => 1
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1
		]), ((object) [
				'hello' => 'home',
				'number' => 1
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		]), ((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'array' => ((object) [
						'hello' => 'home',
						'number' => 1
				])
		]), ((object) [
				'hello' => 'home',
				'array' => ((object) [
						'hello' => 'home',
						'number' => 1
				])
		])));

		// Test different values
		$this->assertTrue(!ObjectUtils::isEqualTo(new stdClass(), ((object) [
				'1' => 1
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'1' => 1
		]), ((object) [
				'1' => 2
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'guys'
		]), ((object) [
				'1' => 2
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'guys'
		]), ((object) [
				'hell' => 'guys'
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 3]
		]), ((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		])));

		// Test exceptions with non objects
		try {
			ObjectUtils::isEqualTo(null, null);
			$this->fail('Expected exception');
		} catch (Exception $e) {}

		try {
			ObjectUtils::isEqualTo([], []);
			$this->fail('Expected exception');
		} catch (Exception $e) {}

		try {
			ObjectUtils::isEqualTo("hello", "hello");
			$this->fail('Expected exception');
		} catch (Exception $e) {}
	}
}

?>