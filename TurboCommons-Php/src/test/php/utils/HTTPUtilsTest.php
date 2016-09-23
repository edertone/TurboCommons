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
use org\turbocommons\src\main\php\utils\HTTPUtils;


/**
 * HTTPUtils tests
 *
 * @return void
 */
class HTTPUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testUrlExists
	 *
	 * @return void
	 */
	public function testUrlExists(){

		// Invalid urls
		$this->assertTrue(!HTTPUtils::urlExists(null));
		$this->assertTrue(!HTTPUtils::urlExists(''));
		$this->assertTrue(!HTTPUtils::urlExists('         '));
		$this->assertTrue(!HTTPUtils::urlExists('dsgsdfgdfg'));
		$this->assertTrue(!HTTPUtils::urlExists('http://ertwertert.com/er3453566767terter.pdf'));

		// Valid urls
		$this->assertTrue(HTTPUtils::urlExists('http://facebook.com'), 'Could not load url. Internet connection must be available');
		$this->assertTrue(HTTPUtils::urlExists('http://google.com'), 'Could not load url. Internet connection must be available');
		$this->assertTrue(HTTPUtils::urlExists('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'), 'Could not load url. Internet connection must be available');

		// Test non string value gives exception
		$this->setExpectedException('Exception');
		HTTPUtils::urlExists(123);
	}

	// TODO - add all missing tests
}

?>