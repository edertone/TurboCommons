<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboDB\src\test\php\managers;


use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\utils\StringUtils;


/**
 * Stringutils tests
 *
 * @return void
 */
class StringUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * TestisEmpty
	 *
	 * @return void
	 */
	public function testIsEmpty(){

		$this->assertTrue(StringUtils::isEmpty(''));
		$this->assertTrue(StringUtils::isEmpty('      '));
		$this->assertTrue(StringUtils::isEmpty("\n\n  \n"));
		$this->assertTrue(StringUtils::isEmpty("\t   \n     \r\r"));
		$this->assertTrue(!StringUtils::isEmpty('adsadf'));
		$this->assertTrue(!StringUtils::isEmpty('    sdfasdsf'));
	}

}

?>