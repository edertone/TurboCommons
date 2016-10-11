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

use org\turbocommons\src\main\php\utils\HTTPUtils;
use PHPUnit_Framework_TestCase;
use Exception;


/**
 * MOCK class to prevent real url http calls
 */
class HTTPUtilsMocked extends HTTPUtils{


	/**
	 * Overrides original method so it mocks internet addresses calls
	 *
	 * @param string $url an url
	 *
	 * @return array mocked values
	 */
	public static function getUrlHeaders($url){

		switch ($url){

			case 'http://facebook.com':
				return ['200'];
				break;

			case 'http://google.com':
				return ['200'];
				break;

			case 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js':
				return ['200'];
				break;
		}

		return ['404'];
	}
}


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
		$this->assertTrue(!HTTPUtilsMocked::urlExists(null));
		$this->assertTrue(!HTTPUtilsMocked::urlExists(''));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('         '));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('dsgsdfgdfg'));
		$this->assertTrue(!HTTPUtilsMocked::urlExists('http://ertwertert.com/er3453566767terter.pdf'));

		// Valid urls
		$this->assertTrue(HTTPUtilsMocked::urlExists('http://facebook.com'));
		$this->assertTrue(HTTPUtilsMocked::urlExists('http://google.com'));
		$this->assertTrue(HTTPUtilsMocked::urlExists('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'));

		// Test non string value gives exception
		try {
			HTTPUtilsMocked::urlExists(123);
			$this->fail('Expected exception');
		} catch (Exception $e) {}

		try {
			HTTPUtilsMocked::urlExists([]);
			$this->fail('Expected exception');
		} catch (Exception $e) {}
	}

	// TODO - add all missing tests
}

?>