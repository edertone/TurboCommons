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

use Throwable;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\test\php\resources\utils\httpUtils\HTTPUtilsMocked;


/**
 * HTTPUtils tests
 *
 * @return void
 */
class HTTPUtilsTest extends TestCase {


	/**
	 * testUrlExists
	 *
	 * @return void
	 */
	public function testUrlExists(){

		// Invalid urls
		$this->assertTrue(!HTTPUtilsMocked::urlExists(null));
		$this->assertTrue(!HTTPUtilsMocked::urlExists(''));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('         '));
		$this->assertTrue(!HTTPUtilsMocked::urlExists([]));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('dsgsdfgdfg'));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('http://ertwertert.com/er3453566767terter.pdf'));

		// Valid urls
		$this->assertTrue(HTTPUtilsMocked::urlExists('http://facebook.com'));
		$this->assertTrue(HTTPUtilsMocked::urlExists('http://google.com'));
		$this->assertTrue(HTTPUtilsMocked::urlExists('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'));

		// Test non string value gives exception
		$exceptionMessage = '';

		try {
			HTTPUtilsMocked::urlExists(123);
			$exceptionMessage = '123 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}

	// TODO - add all missing tests
}

?>