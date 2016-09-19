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


		// TODO: these tests must fail
		// $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(null), []));
	}
}

?>