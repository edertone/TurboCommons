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
use org\turbocommons\src\main\php\utils\ConversionUtils;
use org\turbocommons\src\main\php\managers\ValidationManager;
use Exception;


/**
 * ConversionUtils tests
 *
 * @return void
 */
class ConversionUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testStringToBase64
	 *
	 * @return void
	 */
	public function testStringToBase64(){

		$this->assertTrue(ConversionUtils::stringToBase64(null) === '');
		$this->assertTrue(ConversionUtils::stringToBase64('') === '');

		// Try correct values
		$this->assertTrue(ConversionUtils::stringToBase64('f') === 'Zg==');
		$this->assertTrue(ConversionUtils::stringToBase64('fo') === 'Zm8=');
		$this->assertTrue(ConversionUtils::stringToBase64('foo') === 'Zm9v');
		$this->assertTrue(ConversionUtils::stringToBase64('foob') === 'Zm9vYg==');
		$this->assertTrue(ConversionUtils::stringToBase64('fooba') === 'Zm9vYmE=');
		$this->assertTrue(ConversionUtils::stringToBase64('foobar') === 'Zm9vYmFy');
		$this->assertTrue(ConversionUtils::stringToBase64("line1\nline2\nline3") === 'bGluZTEKbGluZTIKbGluZTM=');
		$this->assertTrue(ConversionUtils::stringToBase64('{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 }') === 'eyAwLCAxLCAyLCAzLCA0LCA1LCA2LCA3LCA4LCA5IH0=');
		$this->assertTrue(ConversionUtils::stringToBase64('AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz') === 'QWFCYkNjRGRFZUZmR2dIaElpSmpLa0xsTW1Obk9vUHBRcVJyU3NUdFV1VnZXd1h4WXlaeg==');

		// Try some wrong values
		$exceptionMessage = '';

		try {
			ConversionUtils::stringToBase64([]);
			$exceptionMessage = '[] did not cause exception';
		} catch (Exception $e) {}

		try {
			ConversionUtils::stringToBase64(98345);
			$exceptionMessage = '98345 did not cause exception';
		} catch (Exception $e) {}

		try {
			ConversionUtils::stringToBase64(new ValidationManager());
			$exceptionMessage = 'ValidationManager did not cause exception';
		} catch (Exception $e) {}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testBase64ToString
	 *
	 * @return void
	 */
	public function testBase64ToString(){

		$this->assertTrue(ConversionUtils::base64ToString(null) === '');
		$this->assertTrue(ConversionUtils::base64ToString('') === '');

		// Try correct values
		$this->assertTrue(ConversionUtils::base64ToString('Zg==') === 'f');
		$this->assertTrue(ConversionUtils::base64ToString('Zm8=') === 'fo');
		$this->assertTrue(ConversionUtils::base64ToString('Zm9v') === 'foo');
		$this->assertTrue(ConversionUtils::base64ToString('Zm9vYg==') === 'foob');
		$this->assertTrue(ConversionUtils::base64ToString('Zm9vYmE=') === 'fooba');
		$this->assertTrue(ConversionUtils::base64ToString('Zm9vYmFy') === 'foobar');

		// Try some random values encoded with stringToBase64
		for ($i = 0; $i < 50; $i++) {

			$s = substr(sha1(rand()), 0, 20);

			$this->assertTrue(ConversionUtils::base64ToString(ConversionUtils::stringToBase64($s)) === $s);
		}

		// Try some wrong values
		$exceptionMessage = '';

		try {
			ConversionUtils::base64ToString([]);
			$exceptionMessage = '[] did not cause exception';
		} catch (Exception $e) {}

		try {
			ConversionUtils::base64ToString(98345);
			$exceptionMessage = '98345 did not cause exception';
		} catch (Exception $e) {}

		try {
			ConversionUtils::base64ToString(new ValidationManager());
			$exceptionMessage = 'ValidationManager did not cause exception';
		} catch (Exception $e) {}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}
}

?>