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

use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\utils\NumericUtils;
use Throwable;
use stdClass;
use Exception;
use PHPUnit\Framework\TestCase;


/**
 * Stringutils tests
 *
 * @return void
 */
class StringUtilsTest extends TestCase {


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
	 * testIsString
	 *
	 * @return void
	 */
	public function testIsString(){

	    $this->assertTrue(StringUtils::isString(''));
	    $this->assertTrue(StringUtils::isString('      '));
	    $this->assertTrue(StringUtils::isString('1'));
	    $this->assertTrue(StringUtils::isString('a'));
	    $this->assertTrue(StringUtils::isString('hello'));
	    $this->assertTrue(StringUtils::isString("hello\n\nguys"));

	    $this->assertTrue(!StringUtils::isString(null));
	    $this->assertTrue(!StringUtils::isString(0));
	    $this->assertTrue(!StringUtils::isString(15));
	    $this->assertTrue(!StringUtils::isString([]));
	    $this->assertTrue(!StringUtils::isString([1]));
	    $this->assertTrue(!StringUtils::isString(['a', 'cd']));
	    $this->assertTrue(!StringUtils::isString(new stdClass()));
	    $this->assertTrue(!StringUtils::isString(new Exception()));
	}


	/**
	 * testIsUrl
	 *
	 * @return void
	 */
	public function testIsUrl(){

	    // Wrong url cases
	    $this->assertFalse(StringUtils::isUrl(''));
	    $this->assertFalse(StringUtils::isUrl(null));
	    $this->assertFalse(StringUtils::isUrl([]));
	    $this->assertFalse(StringUtils::isUrl('    '));
	    $this->assertFalse(StringUtils::isUrl('123f56ccaca'));
	    $this->assertFalse(StringUtils::isUrl('8/%$144///(!(/"'));
	    $this->assertFalse(StringUtils::isUrl('http'));
	    $this->assertFalse(StringUtils::isUrl('x.y'));
	    $this->assertFalse(StringUtils::isUrl('http://x.y'));
	    $this->assertFalse(StringUtils::isUrl('google.com-'));
	    $this->assertFalse(StringUtils::isUrl("\n   \t\n"));
	    $this->assertFalse(StringUtils::isUrl('./test/file.js'));
	    $this->assertFalse(StringUtils::isUrl('http:\\google.com'));
	    $this->assertFalse(StringUtils::isUrl('_http://google.com'));
	    $this->assertFalse(StringUtils::isUrl('http://www.example..com'));
	    $this->assertFalse(StringUtils::isUrl('http://.com'));
	    $this->assertFalse(StringUtils::isUrl('http://www.example.'));
	    $this->assertFalse(StringUtils::isUrl('http:/www.example.com'));
	    $this->assertFalse(StringUtils::isUrl('http://'));
	    $this->assertFalse(StringUtils::isUrl('http://.'));
	    $this->assertFalse(StringUtils::isUrl('http://??/'));
	    $this->assertFalse(StringUtils::isUrl('http://foo.bar?q=Spaces should be encoded'));
	    $this->assertFalse(StringUtils::isUrl('rdar://1234'));
	    $this->assertFalse(StringUtils::isUrl('http://foo.bar/foo(bar)baz quux'));
	    $this->assertFalse(StringUtils::isUrl('http://10.1.1.255'));
	    $this->assertFalse(StringUtils::isUrl('http://.www.foo.bar./'));
	    $this->assertFalse(StringUtils::isUrl('http://.www.foo.bar/'));
	    $this->assertFalse(StringUtils::isUrl('ftp://user:password@host:port/path'));
	    $this->assertFalse(StringUtils::isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
	    $this->assertFalse(StringUtils::isUrl('C:\\Program Files (x86)'));
	    $this->assertFalse(StringUtils::isUrl('http://www.google.com\\test.html'));

	    // good url cases
	    $this->assertTrue(StringUtils::isUrl('http://x.ye'));
	    $this->assertTrue(StringUtils::isUrl('http://google.com'));
	    $this->assertTrue(StringUtils::isUrl('ftp://mydomain.com'));
	    $this->assertTrue(StringUtils::isUrl('http://www.example.com:8800'));
	    $this->assertTrue(StringUtils::isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
	    $this->assertTrue(StringUtils::isUrl('http://www.test.com/do.html#A'));
	    $this->assertTrue(StringUtils::isUrl('https://subdomain.test.com/'));
	    $this->assertTrue(StringUtils::isUrl('https://test.com'));
	    $this->assertTrue(StringUtils::isUrl('http://foo.com/blah_blah/'));
	    $this->assertTrue(StringUtils::isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
	    $this->assertTrue(StringUtils::isUrl('http://userid@example.com:8080'));
	    $this->assertTrue(StringUtils::isUrl('http://➡.ws/䨹'));
	    $this->assertTrue(StringUtils::isUrl('http://⌘.ws/'));
	    $this->assertTrue(StringUtils::isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
	    $this->assertTrue(StringUtils::isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
	    $this->assertTrue(StringUtils::isUrl('http://223.255.255.254'));
	    $this->assertTrue(StringUtils::isUrl('ftp://user:password@host.com:8080/path'));
	    $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1'));
	    $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2'));
	    $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2?c=3'));
	    $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2?????'));
	    // TODO - this test does not pass, but it does pass in JS. We should look for another regex in PHP that passes it also
	    // $this->assertTrue(StringUtils::isUrl('http://www.test.com?pageid=123&testid=1524'));

	    // Test non string values throw exceptions
	    try {
	        StringUtils::isUrl([12341]);
	        $this->exceptionMessage = '[12341] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::isUrl(12341);
	        $this->exceptionMessage = '12341 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testIsEmpty
	 *
	 * @return void
	 */
	public function testIsEmpty(){

	    $this->assertTrue(StringUtils::isEmpty(null));
	    $this->assertTrue(StringUtils::isEmpty(0));
		$this->assertTrue(StringUtils::isEmpty(''));
		$this->assertTrue(StringUtils::isEmpty([]));
		$this->assertTrue(StringUtils::isEmpty('      '));
		$this->assertTrue(StringUtils::isEmpty("\n\n  \n"));
		$this->assertTrue(StringUtils::isEmpty("\t   \n     \r\r"));
		$this->assertTrue(StringUtils::isEmpty('EMPTY', ['EMPTY']));
		$this->assertTrue(StringUtils::isEmpty('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

		$this->assertTrue(!StringUtils::isEmpty('adsadf'));
		$this->assertTrue(!StringUtils::isEmpty('    sdfasdsf'));
		$this->assertTrue(!StringUtils::isEmpty('EMPTY'));
		$this->assertTrue(!StringUtils::isEmpty('EMPTY test', ['EMPTY']));
		$this->assertTrue(!StringUtils::isEmpty('EMPTY       void   hole    XX', ['EMPTY', 'void', 'hole']));

		// Test non string value gives exception
		try {
			StringUtils::isEmpty(123);
			$this->exceptionMessage = '123 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * testIsCamelCase
	 *
	 * @return void
	 */
	public function testIsCamelCase(){

		// test empty cases
		$this->assertTrue(!StringUtils::isCamelCase(null));
		$this->assertTrue(!StringUtils::isCamelCase(''));
		$this->assertTrue(!StringUtils::isCamelCase([]));
		$this->assertTrue(!StringUtils::isCamelCase('       '));
		$this->assertTrue(!StringUtils::isCamelCase("\n\n\n"));

		// Test correct FORMAT_CAMEL_CASE strings
		$this->assertTrue(StringUtils::isCamelCase('c'));
		$this->assertTrue(StringUtils::isCamelCase('camel'));
		$this->assertTrue(StringUtils::isCamelCase('Hello'));
		$this->assertTrue(StringUtils::isCamelCase('CamelCase'));
		$this->assertTrue(StringUtils::isCamelCase('camelCase'));
		$this->assertTrue(StringUtils::isCamelCase('CamelCCase'));
		$this->assertTrue(StringUtils::isCamelCase('CamelCA'));
		$this->assertTrue(StringUtils::isCamelCase('IFoo'));
		$this->assertTrue(StringUtils::isCamelCase('HTTPConnection'));
		$this->assertTrue(StringUtils::isCamelCase('CAMELcA'));
		$this->assertTrue(StringUtils::isCamelCase('FooBarFizzBuzz'));
		$this->assertTrue(StringUtils::isCamelCase('Camel01C'));
		$this->assertTrue(StringUtils::isCamelCase('Camel01C01'));
		$this->assertTrue(StringUtils::isCamelCase('Camel0a1C1'));
		$this->assertTrue(StringUtils::isCamelCase('Camel0ac1b1C1'));
		$this->assertTrue(StringUtils::isCamelCase('CamelC'));
		$this->assertTrue(StringUtils::isCamelCase('CamelC1'));
		$this->assertTrue(StringUtils::isCamelCase('CamelCa1'));
		$this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressionSuite'));
		$this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressioN'));

		// Test incorrect FORMAT_CAMEL_CASE strings
		$this->assertTrue(!StringUtils::isCamelCase('0'));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01'));
		$this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1'));
		$this->assertTrue(!StringUtils::isCamelCase('Camel_Case'));
		$this->assertTrue(!StringUtils::isCamelCase('CAMELCASE'));
		$this->assertTrue(!StringUtils::isCamelCase('CCC'));
		$this->assertTrue(!StringUtils::isCamelCase('123123'));
		$this->assertTrue(!StringUtils::isCamelCase('_=CA'));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01c01'));
		$this->assertTrue(!StringUtils::isCamelCase('001Camel'));
		$this->assertTrue(!StringUtils::isCamelCase('.CamelCase'));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCase.'));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCa_1'));
		$this->assertTrue(!StringUtils::isCamelCase('cámel.Case'));
		$this->assertTrue(!StringUtils::isCamelCase('ü'));
		$this->assertTrue(!StringUtils::isCamelCase('ÚMEL'));

		// Test correct FORMAT_UPPER_CAMEL_CASE strings
		$this->assertTrue(StringUtils::isCamelCase('CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelCASE', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelCCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('IFoo', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('HTTPConnection', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CAMELcA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('FooBarFizzBuzz', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Camel01C', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Camel01C01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Camel0a1C1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Camel0ac1b1C1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelC', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelC1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelCa1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressioN', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('CamelCA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Camel', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('C', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Ú', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('Úmel', StringUtils::FORMAT_UPPER_CAMEL_CASE));

		// Test incorrect FORMAT_UPPER_CAMEL_CASE strings
		$this->assertTrue(!StringUtils::isCamelCase('camelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('camelcase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CAMELCASE', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('123123', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('9/yth1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel_Case', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CCC', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('_=CA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01c01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('001Camel', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('.CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCase.', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCa_1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('cümelCasè', StringUtils::FORMAT_UPPER_CAMEL_CASE));


		// Test correct FORMAT_LOWER_CAMEL_CASE strings
		$this->assertTrue(StringUtils::isCamelCase('camelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelCASE', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelCCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('iFoo', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('hTTPConnection', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('cAMELcA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('fooBarFizzBuzz', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camel01C', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camel01C01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camel0a1C1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camel0ac1b1C1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelC', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelC1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelCa1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('ibsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('ibsReleaseTestVerificationRegressioN', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camelCA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(StringUtils::isCamelCase('camel', StringUtils::FORMAT_LOWER_CAMEL_CASE));

		// Test incorrect FORMAT_LOWER_CAMEL_CASE strings
		$this->assertTrue(!StringUtils::isCamelCase('CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('___', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('3456346', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('****', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('_cAMEL', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('HTTPConnection', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('C', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CAMELCASE', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('123123', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('9/yth1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel_Case', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CCC', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('_=CA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Camel01c01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('001Camel', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('.CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCase.', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('CamelCa_1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
		$this->assertTrue(!StringUtils::isCamelCase('Úamel', StringUtils::FORMAT_LOWER_CAMEL_CASE));

		// Test exceptions
		try {
			StringUtils::isCamelCase(123);
			$this->exceptionMessage = '123 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isCamelCase([1,5,8]);
			$this->exceptionMessage = '[1,5,8] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isCamelCase(new Exception());
			$this->exceptionMessage = 'new Exception did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isCamelCase('CamelCase', 67);
			$this->exceptionMessage = '67 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * testIsSnakeCase
	 *
	 * @return void
	 */
	public function testIsSnakeCase(){

		// test empty cases
		$this->assertTrue(!StringUtils::isSnakeCase(null));
		$this->assertTrue(!StringUtils::isSnakeCase(''));
		$this->assertTrue(!StringUtils::isSnakeCase([]));
		$this->assertTrue(!StringUtils::isSnakeCase('       '));
		$this->assertTrue(!StringUtils::isSnakeCase("\n\n\n"));

		// Test correct FORMAT_SNAKE_CASE strings
		$this->assertTrue(StringUtils::isSnakeCase('snake'));
		$this->assertTrue(StringUtils::isSnakeCase('snake_case'));
		$this->assertTrue(StringUtils::isSnakeCase('Snake'));
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE'));
		$this->assertTrue(StringUtils::isSnakeCase('Snake_case'));
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE_case'));
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE12_case4'));
		$this->assertTrue(StringUtils::isSnakeCase('c_s'));
		$this->assertTrue(StringUtils::isSnakeCase('c'));
		$this->assertTrue(StringUtils::isSnakeCase('C12'));
		$this->assertTrue(StringUtils::isSnakeCase('1A_2'));

		// Test incorrect FORMAT_SNAKE_CASE strings
		$this->assertTrue(!StringUtils::isSnakeCase('_'));
		$this->assertTrue(!StringUtils::isSnakeCase('___'));
		$this->assertTrue(!StringUtils::isSnakeCase('!"(·'));
		$this->assertTrue(!StringUtils::isSnakeCase('1212'));
		$this->assertTrue(!StringUtils::isSnakeCase('_te'));
		$this->assertTrue(!StringUtils::isSnakeCase('_SNAKE'));
		$this->assertTrue(!StringUtils::isSnakeCase('snake_'));
		$this->assertTrue(!StringUtils::isSnakeCase('snake-case'));
		$this->assertTrue(!StringUtils::isSnakeCase('snake_case.'));
		$this->assertTrue(!StringUtils::isSnakeCase('1_2'));
		$this->assertTrue(!StringUtils::isSnakeCase('snake__case'));

		// Test correct FORMAT_UPPER_SNAKE_CASE strings
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE_CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('SNAKE12_CASE4', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('C_S', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('C', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('C12', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('1A_2', StringUtils::FORMAT_UPPER_SNAKE_CASE));

		// Test incorrect FORMAT_UPPER_SNAKE_CASE strings
		$this->assertTrue(!StringUtils::isSnakeCase('_', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('___', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('!"(·', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('1212', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('_te', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('_SNAKE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('SNAKE_', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('SNAKE-CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('SNAKE_CASE.', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('1_2', StringUtils::FORMAT_UPPER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('SNAKE__CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));

		// Test correct FORMAT_LOWER_SNAKE_CASE strings
		$this->assertTrue(StringUtils::isSnakeCase('snake', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('snake_case', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('snake12_case4', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('c_s', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('c', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('c12', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(StringUtils::isSnakeCase('1a_2', StringUtils::FORMAT_LOWER_SNAKE_CASE));

		// Test imcorrect FORMAT_LOWER_SNAKE_CASE strings
		$this->assertTrue(!StringUtils::isSnakeCase('_', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('___', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('!"(·', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('1212', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('_te', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('_snake', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('snake_', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('snake-case', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('snake_case.', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('1_2', StringUtils::FORMAT_LOWER_SNAKE_CASE));
		$this->assertTrue(!StringUtils::isSnakeCase('snake__case', StringUtils::FORMAT_LOWER_SNAKE_CASE));

		// Test exceptions
		try {
			StringUtils::isSnakeCase(123);
			$this->exceptionMessage = '123 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isSnakeCase([1,5,8]);
			$this->exceptionMessage = '[1,5,8] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isSnakeCase(new Exception());
			$this->exceptionMessage = 'new Exception did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::isSnakeCase('SnakeCase', 67);
			$this->exceptionMessage = '67 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * testReplace
	 *
	 * @return void
	 */
	public function testReplace(){

	    // TODO - translate from TS
	}


	/**
	 * testTrim
	 *
	 * @return void
	 */
	public function testTrim(){

	    // TODO - translate from TS
	}


	/**
	 * testTrimLeft
	 *
	 * @return void
	 */
	public function testTrimLeft(){

	    // TODO - translate from TS
	}


	/**
	 * testTrimRight
	 *
	 * @return void
	 */
	public function testTrimRight(){

	    // TODO - translate from TS
	}


	/**
	 * testCountStringOccurences
	 *
	 * @return void
	 */
	public function testCountStringOccurences(){

		$this->assertSame(StringUtils::countStringOccurences('       ', ' '), 7);
		$this->assertSame(StringUtils::countStringOccurences('hello', 'o'), 1);
		$this->assertSame(StringUtils::countStringOccurences('hello baby', 'b'), 2);
		$this->assertSame(StringUtils::countStringOccurences('hello baby', 'B'), 0);
		$this->assertSame(StringUtils::countStringOccurences("tRy\nto\r\n\t\ngo\r\nUP", 'o'), 2);
		$this->assertSame(StringUtils::countStringOccurences("     \n      \r\n", 'a'), 0);
		$this->assertSame(StringUtils::countStringOccurences(" AEÉÜ    \n   1   \r\nÍË", 'É'), 1);
		$this->assertSame(StringUtils::countStringOccurences("heLLo Baby\nhellÓ àgaiN and go\n\n\r\nUp!", 'a'), 3);
		$this->assertSame(StringUtils::countStringOccurences("helló bàbÝ\n   whats Up Todäy? are you feeling better? GOOD!", 'T'), 1);

		// Test exceptions
		try {
			StringUtils::countStringOccurences(null, null);
			$this->exceptionMessage = 'null did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::countStringOccurences('', '');
			$this->exceptionMessage = '"" did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::countStringOccurences('  ', '');
			$this->exceptionMessage = '"" did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * testCountCapitalLetters
	 *
	 * @return void
	 */
	public function testCountCapitalLetters(){

		$this->assertTrue(StringUtils::countCapitalLetters(null) == 0);
		$this->assertTrue(StringUtils::countCapitalLetters('') == 0);
		$this->assertTrue(StringUtils::countCapitalLetters('  ') == 0);
		$this->assertTrue(StringUtils::countCapitalLetters('       ') == 0);
		$this->assertTrue(StringUtils::countCapitalLetters('hello') == 0);
		$this->assertTrue(StringUtils::countCapitalLetters('hello baby') == 0);
		$this->assertTrue(StringUtils::countCapitalLetters("tRy\nto\r\n\t\ngo\r\nUP") == 3);
		$this->assertTrue(StringUtils::countCapitalLetters("     \n      \r\n") == 0);
		$this->assertTrue(StringUtils::countCapitalLetters(" AEÉÜ    \n   1   \r\nÍË") == 6);
		$this->assertTrue(StringUtils::countCapitalLetters("heLLo Baby\nhellÓ agaiN and go\n\n\r\nUp!") == 6);
		$this->assertTrue(StringUtils::countCapitalLetters("helló bàbÝ\n   whats Up todäy? are you feeling better? GOOD!") == 6);
	}


	/**
	 * testCountWords
	 *
	 * @return void
	 */
	public function testCountWords(){

		$this->assertTrue(StringUtils::countWords(null) == 0);
		$this->assertTrue(StringUtils::countWords('') == 0);
		$this->assertTrue(StringUtils::countWords('  ') == 0);
		$this->assertTrue(StringUtils::countWords('       ') == 0);
		$this->assertTrue(StringUtils::countWords('hello') == 1);
		$this->assertTrue(StringUtils::countWords('hello baby') == 2);
		$this->assertTrue(StringUtils::countWords('Üèó ï á étwer') == 4);
		$this->assertTrue(StringUtils::countWords("try\nto\r\n\t\ngo\r\nup") == 4);
		$this->assertTrue(StringUtils::countWords("     \n      \r\n") == 0);
		$this->assertTrue(StringUtils::countWords("     \n   1   \r\n") == 1);
		$this->assertTrue(StringUtils::countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
		$this->assertTrue(StringUtils::countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
	}


	/**
	 * testLimitLen
	 *
	 * @return void
	 */
	public function testLimitLen(){

		$this->assertTrue(StringUtils::limitLen(null, 10) === '');
		$this->assertTrue(StringUtils::limitLen('', 10) === '');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 1) === ' ');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 2) === ' .');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 3) === ' ..');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 4) === ' ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 5) === 'h ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 18) === 'hello dear how ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 19) === 'hello dear how  ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 20) === 'hello dear how a ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 22) === 'hello dear how are you');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 50) === 'hello dear how are you');

		// Test invalid values give exception
		try {
		    StringUtils::limitLen('', 0);
		    $this->exceptionMessage = '0 did not cause exception';
		} catch (Throwable $e) {
		    // We expect an exception to happen
		}

		try {
			StringUtils::limitLen('hello', [1, 2]);
			$this->exceptionMessage = '[1, 2] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
		    StringUtils::limitLen('hello', null);
		    $this->exceptionMessage = 'hello did not cause exception';
		} catch (Throwable $e) {
		    // We expect an exception to happen
		}
	}


	/**
	 * testGetDomainFromUrl
	 *
	 * @return void
	 */
	public function testGetDomainFromUrl(){

		// TODO - copy tests from js
	}


	/**
	 * testGetHostNameFromUrl
	 *
	 * @return void
	 */
	public function testGetHostNameFromUrl(){

		// TODO - copy tests from js
	}


	/**
	 * testGetLines
	 *
	 * @return void
	 */
	public function testGetLines(){

		$this->assertTrue(StringUtils::getLines(null) === []);
		$this->assertTrue(StringUtils::getLines('') === []);
		$this->assertTrue(StringUtils::getLines('          ') === []);
		$this->assertTrue(StringUtils::getLines('single line') === ['single line']);
		$this->assertTrue(StringUtils::getLines("line1\nline2\nline3") === ['line1', 'line2', 'line3']);
		$this->assertTrue(StringUtils::getLines("line1\rline2\rline3") === ['line1', 'line2', 'line3']);
		$this->assertTrue(StringUtils::getLines("line1\r\nline2\r\nline3") === ['line1', 'line2', 'line3']);
		$this->assertTrue(StringUtils::getLines("line1\n        \nline2") === ['line1', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\n\n\n\t\r       \nline2") === ['line1', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\r\n   \r\nline2") === ['line1', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\n 1  \nline2") === ['line1', ' 1  ', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\r\n 1  \n\r\r\nline2") === ['line1', ' 1  ', 'line2']);

		$this->assertTrue(StringUtils::getLines('          ', []) === ['          ']);
		$this->assertTrue(StringUtils::getLines("line1\r   \rline2", []) === ['line1', '   ', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\n   \nline2", []) === ['line1', '   ', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\r\n   \r\nline2", []) === ['line1', '   ', 'line2']);
		$this->assertTrue(StringUtils::getLines("line1\n\n\n\t\r       \nline2", []) === ['line1', "\t", '       ', 'line2']);
	}


	/**
	 * testGetKeyWords
	 *
	 * @return void
	 */
	public function testGetKeyWords(){

		$this->assertTrue(StringUtils::getKeyWords(null) === []);
		$this->assertTrue(StringUtils::getKeyWords('') === []);
		$this->assertTrue(StringUtils::getKeyWords('hello') === ['hello']);

		// TODO: add lot more tests
	}


	/**
	 * testGetFileNameWithExtension
	 *
	 * @return void
	 */
	public function testGetFileNameWithExtension(){

		$this->assertTrue(StringUtils::getFileNameWithExtension(null) === '');
		$this->assertTrue(StringUtils::getFileNameWithExtension('') === '');
		$this->assertTrue(StringUtils::getFileNameWithExtension('       ') === '');
		$this->assertTrue(StringUtils::getFileNameWithExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::getFileNameWithExtension('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::getFileNameWithExtension('//folder/folder2/folder3/file.txt') == 'file.txt');
		$this->assertTrue(StringUtils::getFileNameWithExtension('CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::getFileNameWithExtension('\\\\\\CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::getFileNameWithExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::getFileNameWithExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'CCleaner64.exe');
	}


	/**
	 * testGetFileNameWithoutExtension
	 *
	 * @return void
	 */
	public function testGetFileNameWithoutExtension(){

		$this->assertTrue(StringUtils::getFileNameWithoutExtension(null) === '');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('') === '');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('       ') === '');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('//folder/folder2/folder3/file.txt') == 'file');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('\\\\\\CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::getFileNameWithoutExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'CCleaner64');
	}


	/**
	 * testGetFileExtension
	 *
	 * @return void
	 */
	public function testGetFileExtension(){

		$this->assertTrue(StringUtils::getFileExtension(null) === '');
		$this->assertTrue(StringUtils::getFileExtension('') === '');
		$this->assertTrue(StringUtils::getFileExtension('       ') === '');
		$this->assertTrue(StringUtils::getFileExtension('C:\Program Files\\CCleaner\\CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::getFileExtension('\\Files/CCleaner/CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::getFileExtension('//folder/folder2/folder3/file.txt') == 'txt');
		$this->assertTrue(StringUtils::getFileExtension('CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::getFileExtension('\\\\\\CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::getFileExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::getFileExtension('CCleaner64.EXE') == 'EXE');
		$this->assertTrue(StringUtils::getFileExtension('\\\\\\CCleaner64.eXEfile') == 'eXEfile');
		$this->assertTrue(StringUtils::getFileExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'exe');
	}


	/**
	 * testGetSchemeFromUrl
	 *
	 * @return void
	 */
	public function testGetSchemeFromUrl(){

		// TODO - copy from js!!

	}


	/**
	 * testFormatCase
	 *
	 * @return void
	 */
	public function testFormatCase(){

		/** Defines the list of string case available formats */
		$caseFormats = ['',
				StringUtils::FORMAT_SENTENCE_CASE,
				StringUtils::FORMAT_START_CASE,
				StringUtils::FORMAT_ALL_UPPER_CASE,
				StringUtils::FORMAT_ALL_LOWER_CASE,
		        StringUtils::FORMAT_FIRST_UPPER_REST_LOWER,
				StringUtils::FORMAT_CAMEL_CASE,
				StringUtils::FORMAT_UPPER_CAMEL_CASE,
				StringUtils::FORMAT_LOWER_CAMEL_CASE,
				StringUtils::FORMAT_SNAKE_CASE,
				StringUtils::FORMAT_UPPER_SNAKE_CASE,
				StringUtils::FORMAT_LOWER_SNAKE_CASE
		];

		// test empty cases on all possible formats
		foreach ($caseFormats as $caseFormat) {

		    try {
		        StringUtils::formatCase(null, $caseFormat);
		        $this->exceptionMessage = 'null did not cause exception';
		    } catch (Throwable $e) {
		        // We expect an exception to happen
		    }
		    try {
		        StringUtils::formatCase([], $caseFormat);
		        $this->exceptionMessage = '[] did not cause exception';
		    } catch (Throwable $e) {
		        // We expect an exception to happen
		    }
			$this->assertTrue(StringUtils::formatCase('', $caseFormat) === '');
			$this->assertTrue(StringUtils::formatCase('       ', $caseFormat) === '       ');
			$this->assertTrue(StringUtils::formatCase("\n\n\n", $caseFormat) === "\n\n\n");
		}

		// Test FORMAT_SENTENCE_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_SENTENCE_CASE) === 'H');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_SENTENCE_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello');
		$this->assertTrue(StringUtils::formatCase('Helló. únder Ü??', StringUtils::FORMAT_SENTENCE_CASE) === 'Helló. Únder Ü??');
		$this->assertTrue(StringUtils::formatCase('óyeà!!!üst??', StringUtils::FORMAT_SENTENCE_CASE) === 'Óyeà!!!Üst??');
		$this->assertTrue(StringUtils::formatCase('Hello. people', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello. People');
		$this->assertTrue(StringUtils::formatCase('Hello. pEPOLE', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello. PEPOLE');
		$this->assertTrue(StringUtils::formatCase('Hello  .  p !!', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello  .  P !!');
		$this->assertTrue(StringUtils::formatCase('hellO.   hOw, are YOU? today!, , and ??', StringUtils::FORMAT_SENTENCE_CASE) === 'HellO.   HOw, are YOU? Today!, , And ??');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!?you.!  ", StringUtils::FORMAT_SENTENCE_CASE) === "Över! Còmpléx.   \n\n\n\t\t   Ís test!Is?For!?!?You.!  ");
		$this->assertTrue(StringUtils::formatCase('this is a sentence. this is another sentence. wow! what?', StringUtils::FORMAT_SENTENCE_CASE) === 'This is a sentence. This is another sentence. Wow! What?');
		$this->assertTrue(StringUtils::formatCase("some sentence,test. we will check.\n\n if this works! now", StringUtils::FORMAT_SENTENCE_CASE) === "Some sentence,test. We will check.\n\n If this works! Now");
		$this->assertTrue(StringUtils::formatCase("heLLo.\n\npEoPle\t\t.no!way!!", StringUtils::FORMAT_SENTENCE_CASE) === "HeLLo.\n\nPEoPle\t\t.No!Way!!");

		// Test FORMAT_START_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_START_CASE) === 'H');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_START_CASE) === 'Hi');
		$this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_START_CASE) === 'Hello');
		$this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_START_CASE) === 'Helló. Únder Ü??');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_START_CASE) === 'Óyeà!!! Üst??');
		$this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_START_CASE) === 'Hello People');
		$this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_START_CASE) === 'Hello People');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_START_CASE) === "Över! Còmpléx.   \n\n\n\t\t   Ís Test!is?for!?!? You.!  ");

		// Test FORMAT_ALL_UPPER_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_ALL_UPPER_CASE) === 'H');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO');
		$this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLÓ. ÚNDER Ü??');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_ALL_UPPER_CASE) === 'ÓYEÀ!!! ÜST??');
		$this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
		$this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_ALL_UPPER_CASE) === "ÖVER! CÒMPLÉX.   \n\n\n\t\t   ÍS TEST!IS?FOR!?!? YOU.!  ");

		// Test FORMAT_ALL_LOWER_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_ALL_LOWER_CASE) === 'h');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hi');
		$this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello');
		$this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_ALL_LOWER_CASE) === 'helló. únder ü??');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_ALL_LOWER_CASE) === 'óyeà!!! üst??');
		$this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello people');
		$this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello people');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_ALL_LOWER_CASE) === "över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

		// Test FORMAT_FIRST_UPPER_REST_LOWER values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'H');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hi');
		$this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello');
		$this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Helló. únder ü??');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Óyeà!!! üst??');
		$this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
		$this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === "Över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

		// Test FORMAT_SNAKE_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_SNAKE_CASE) === 'h');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_SNAKE_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_SNAKE_CASE) === 'ü');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_SNAKE_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_SNAKE_CASE) === 'Camel_Case');
		$this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_SNAKE_CASE) === 'Camel_C_Case');
		$this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_SNAKE_CASE) === 'cámel.Case');
		$this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_SNAKE_CASE) === 'Camel01_C01');
		$this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_SNAKE_CASE) === 'HTTP_Connection');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_SNAKE_CASE) === 'sNake_Case');
		$this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_SNAKE_CASE) === 'Ibs_Release_Test_Verification_Regression_Suite');
		$this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_SNAKE_CASE) === 'hello_World');
		$this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_SNAKE_CASE) === 'üéllòWorld');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_SNAKE_CASE) === 'óyeà!!!_üst??');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_SNAKE_CASE) === 'this_is_some_random_text');
		$this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_SNAKE_CASE) === 'óTher_Randóm_tëxt');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_SNAKE_CASE) === "över!_còmpléx.___\n\n\n\t\t___ís_test!is?for!?!?_you.!__");

		// Test FORMAT_UPPER_SNAKE_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'H');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_UPPER_SNAKE_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'Ü');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL_CASE');
		$this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL_C_CASE');
		$this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CÁMEL.CASE');
		$this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL01_C01');
		$this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HTTP_CONNECTION');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'SNAKE_CASE');
		$this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'IBS_RELEASE_TEST_VERIFICATION_REGRESSION_SUITE');
		$this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HELLO_WORLD');
		$this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÜÉLLÒWORLD');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÓYEÀ!!!_ÜST??');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'THIS_IS_SOME_RANDOM_TEXT');
		$this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÓTHER_RANDÓM_TËXT');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_UPPER_SNAKE_CASE) === "ÖVER!_CÒMPLÉX.___\n\n\n\t\t___ÍS_TEST!IS?FOR!?!?_YOU.!__");

		// Test FORMAT_LOWER_SNAKE_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'h');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_LOWER_SNAKE_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'ü');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'hi');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel_case');
		$this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel_c_case');
		$this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'cámel.case');
		$this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel01_c01');
		$this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'http_connection');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'snake_case');
		$this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'ibs_release_test_verification_regression_suite');
		$this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'hello_world');
		$this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'üéllòworld');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'óyeà!!!_üst??');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'this_is_some_random_text');
		$this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'óther_randóm_tëxt');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_LOWER_SNAKE_CASE) === "över!_còmpléx.___\n\n\n\t\t___ís_test!is?for!?!?_you.!__");

		// Test FORMAT_CAMEL_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_CAMEL_CASE) === 'h');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_CAMEL_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_CAMEL_CASE) === 'u');
		$this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_CAMEL_CASE) === 'hI');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_CAMEL_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_CAMEL_CASE) === 'U');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_CAMEL_CASE) === 'CamelCase');
		$this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_CAMEL_CASE) === 'camelCase');
		$this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_CAMEL_CASE) === 'camelCaSE');
		$this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_CAMEL_CASE) === 'camelCaSE');
		$this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_CAMEL_CASE) === 'CamelCase');
		$this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_CAMEL_CASE) === 'HTTPConnection');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_CAMEL_CASE) === 'sNakeCase');
		$this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
		$this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_CAMEL_CASE) === 'uelloWorld');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_CAMEL_CASE) === 'oyeaUst');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_CAMEL_CASE) === 'thisIsSomeRandomText');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_CAMEL_CASE) === 'overComplexIsTestIsForYou');

		// Test FORMAT_UPPER_CAMEL_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'H');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_UPPER_CAMEL_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'U');
		$this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HI');
		$this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'U');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
		$this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
		$this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
		$this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
		$this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
		$this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HTTPConnection');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'SNakeCase');
		$this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
		$this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'UelloWorld');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'OyeaUst');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'ThisIsSomeRandomText');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'OverComplexIsTestIsForYou');

		// Test FORMAT_LOWER_CAMEL_CASE values
		$this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'h');
		$this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_LOWER_CAMEL_CASE) === '0');
		$this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'u');
		$this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hI');
		$this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hI');
		$this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'u');
		$this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
		$this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
		$this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
		$this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
		$this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
		$this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hTTPConnection');
		$this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'sNakeCase');
		$this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'ibsReleaseTestVerificationRegressionSuite');
		$this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'uelloWorld');
		$this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'oyeaUst');
		$this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'thisIsSomeRandomText');
		$this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'overComplexIsTestIsForYou');

		// Test exception cases
		try {
			StringUtils::formatCase('helloWorld', '');
			$this->exceptionMessage = 'empty string did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::formatCase(1, StringUtils::FORMAT_SENTENCE_CASE);
			$this->exceptionMessage = '1 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::formatCase([1,2,3], StringUtils::FORMAT_SENTENCE_CASE);
			$this->exceptionMessage = '[1,2,3] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::formatCase('Hello', 'invalidformat');
			$this->exceptionMessage = 'invalidformat did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * TestFormatPath
	 *
	 * @return void
	 */
	public function testFormatPath(){

		$this->assertTrue(StringUtils::formatPath(null) === '');
		$this->assertTrue(StringUtils::formatPath('') === '');
		$this->assertTrue(StringUtils::formatPath('       ') === '       ');
		$this->assertTrue(StringUtils::formatPath('test//test/') == 'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('////test//////test////') == DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('\\\\////test//test/') == DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('test\\test/hello\\\\') == 'test'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'hello');

		// Test non string paths throw exception
		try {
			StringUtils::formatPath(['1']);
			$this->exceptionMessage = '[1] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			StringUtils::formatPath(1);
			$this->exceptionMessage = '1 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}
	}


	/**
	 * testFormatUrl
	 *
	 * @return void
	 */
	public function testFormatUrl(){

		// TODO copy from js
	}


	/**
	 * testFormatForFullTextSearch
	 *
	 * @return void
	 */
	public function testFormatForFullTextSearch(){

		$this->assertTrue(StringUtils::formatForFullTextSearch(null) === '');
		$this->assertTrue(StringUtils::formatForFullTextSearch('') === '');

		// TODO!!

	}


	/**
	 * testGenerateRandom
	 *
	 * @return void
	 */
	public function testGenerateRandom(){

	    // Test empty values
	    $this->assertTrue(StringUtils::generateRandom(0, 0) === '');

	    try {
	        StringUtils::generateRandom(null, null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::generateRandom(1, 1, null);
	        $this->exceptionMessage = '[null] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::generateRandom(1, 1, ['']);
	        $this->exceptionMessage = "[''] did not cause exception";
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

        // Test ok values
        $this->assertTrue(StringUtils::generateRandom(1, 1, ['T']) === 'T');
        $this->assertTrue(StringUtils::generateRandom(3, 3, ['0']) === '000');
        $this->assertTrue(StringUtils::generateRandom(5, 5, ['a']) === 'aaaaa');

        for ($i = 1; $i < 30; $i++) {

            $this->assertTrue(strlen(StringUtils::generateRandom($i, $i)) === $i);

            // test only numeric string
            $s = StringUtils::generateRandom($i, $i*2, ['0-9']);
            $this->assertTrue(NumericUtils::isNumeric($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['3-6']);
            $this->assertTrue(NumericUtils::isNumeric($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('012', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('789', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('3456', substr($s, $j, 1)) !== false);
            }

            // test only lowercase alphabetic strings
            $s = StringUtils::generateRandom($i, $i*2, ['a-z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['g-r']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strtolower(substr($s, $j, 1)) === substr($s, $j, 1));
                $this->assertTrue(strpos('abcdef', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('stuvwxyz', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('ghijkmnopqr', substr($s, $j, 1)) !== false);
            }

            // test only uppercase alphabetic strings
            $s = StringUtils::generateRandom($i, $i*2, ['A-Z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['I-M']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strtoupper(substr($s, $j, 1)) === substr($s, $j, 1));
                $this->assertTrue(strpos('ABCDEFGH', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('NOPQRSTUVWXYZ', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('IJKM', substr($s, $j, 1)) !== false);
            }

            // Test numbers and upper case and lower case letters
            $s = StringUtils::generateRandom($i, $i*2, ['0-9', 'a-z', 'A-Z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('0123456789', substr($s, $j, 1)) !== false ||
                    strpos('abcdefghijkmnopqrstuvwxyz', substr($s, $j, 1)) !== false ||
                    strpos('ABCDEFGHIJKMNOPQRSTUVWXYZ', substr($s, $j, 1)) !== false);
            }

            // Test fixed characters set
            $s = StringUtils::generateRandom($i, $i*2, ['97hjrfHNgbf71']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('97hjrfHNgbf71', substr($s, $j, 1)) !== false);
            }

            $s = StringUtils::generateRandom(1, 500, ['&/$hb\\-81679Ç+\\-']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= 1 && strlen($s) <= 500);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('&/$hb-81679Ç+-', substr($s, $j, 1)) !== false);
            }
        }

        // Test wrong values
        // not necessary

        try {
            StringUtils::generateRandom(-1, 1);
            $this->exceptionMessage = "-1 1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, -1);
            $this->exceptionMessage = "1, -1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom('some string', 1);
            $this->exceptionMessage = "some string, 1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 'some string');
            $this->exceptionMessage = "1, some string did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 2, 'ertr');
            $this->exceptionMessage = "1, 2, ertr did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 2, new stdClass());
            $this->exceptionMessage = "1, 2, new stdClass() did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
	}


	/**
	 * testRemoveNewLineCharacters
	 *
	 * @return void
	 */
	public function testRemoveNewLineCharacters(){

	    // Test empty values
	    $this->assertTrue(StringUtils::removeNewLineCharacters(null) === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters('') === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters('     ') === '     ');

	    // Test ok values
	    $this->assertTrue(StringUtils::removeNewLineCharacters("\n") === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("\n\n") === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("\r\n") === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("\n\r\r") === '');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb") === 'ab');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb\rc") === 'abc');
	    $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb\rc\n\nd") === 'abcd');

	    // Test wrong values
	    $this->assertTrue(StringUtils::removeNewLineCharacters('heollo') === 'heollo');
	    $this->assertTrue(StringUtils::removeNewLineCharacters('string    and spaces') === 'string    and spaces');
	    $this->assertTrue(StringUtils::removeNewLineCharacters('\t\t\t') === '\t\t\t');
	    $this->assertTrue(StringUtils::removeNewLineCharacters('a\t\t') === 'a\t\t');

	    // Test exceptions
	    try {
	        StringUtils::removeNewLineCharacters(1);
	        $this->exceptionMessage = '1 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::removeNewLineCharacters([]);
	        $this->exceptionMessage = '[] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::removeNewLineCharacters(new stdClass());
	        $this->exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        StringUtils::removeNewLineCharacters([1,2,3,4]);
	        $this->exceptionMessage = '[1,2,3,4] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testRemoveAccents
	 *
	 * @return void
	 */
	public function testRemoveAccents(){

		$this->assertTrue(StringUtils::removeAccents(null) === '');
		$this->assertTrue(StringUtils::removeAccents('') === '');
		$this->assertTrue(StringUtils::removeAccents('        ') === '        ');
		$this->assertTrue(StringUtils::removeAccents('Fó Bår') === 'Fo Bar');
		$this->assertTrue(StringUtils::removeAccents("|!€%'''") === "|!€%'''");
		$this->assertTrue(StringUtils::removeAccents('hiweury asb fsuyr weqr') === 'hiweury asb fsuyr weqr');
		$this->assertTrue(StringUtils::removeAccents('!iYgh65541tGY%$$73267yt') === '!iYgh65541tGY%$$73267yt');
		$this->assertTrue(StringUtils::removeAccents('hello 12786,.123123') === 'hello 12786,.123123');
		$this->assertTrue(StringUtils::removeAccents('check this `^+*´--_{}[]') === 'check this `^+*´--_{}[]');
		$this->assertTrue(StringUtils::removeAccents('hellóóóóóí´ 12786,.123123"') === 'helloooooi´ 12786,.123123"');
		$this->assertTrue(StringUtils::removeAccents("hello\nbaby\r\ntest it well !!!!!") === "hello\nbaby\r\ntest it well !!!!!");
		$this->assertTrue(StringUtils::removeAccents('óíéàùú hello') === 'oieauu hello');
		$this->assertTrue(StringUtils::removeAccents("óóó èèè\núùúùioler    \r\noughúíééanh hello") === "ooo eee\nuuuuioler    \r\noughuieeanh hello");
		$this->assertTrue(StringUtils::removeAccents('öïüíúóèà go!!.;') === 'oiuiuoea go!!.;');
	}


	/**
	 * testRemoveWordsShorterThan
	 *
	 * @return void
	 */
	public function testRemoveWordsShorterThan(){

		$this->assertTrue(StringUtils::removeWordsShorterThan(null) === '');
		$this->assertTrue(StringUtils::removeWordsShorterThan('') === '');
		$this->assertTrue(StringUtils::removeWordsShorterThan('', 0) === '');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello', 0) === 'hello');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello', 2) === 'hello');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello', 5) === 'hello');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello', 6) === '');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello by today', 6) === '  ');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello by today', 5) === 'hello  today');
		$this->assertTrue(StringUtils::removeWordsShorterThan('hello by today', 1) === 'hello by today');
		$this->assertTrue(StringUtils::removeWordsShorterThan("Line1\nline2\r\nline3 and  \nline4", 4) === "Line1\nline2\r\nline3   \nline4");
		// TODO: multi line strings do not work!
		// $this->assertTrue(StringUtils::removeWordsShorterThan("Line1 line12\nline2\r\nline3 and  \nline4", 6) === " line12\n\r\n   \n");
		// TODO: add more multi line tests
	}


	/**
	 * testRemoveWordsLongerThan
	 *
	 * @return void
	 */
	public function testRemoveWordsLongerThan(){

		$this->assertTrue(StringUtils::removeWordsLongerThan(null) === '');
		$this->assertTrue(StringUtils::removeWordsLongerThan('') === '');
		$this->assertTrue(StringUtils::removeWordsLongerThan('', 0) === '');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello', 0) === '');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello', 2) === '');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello', 5) === 'hello');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello', 6) === 'hello');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello by today', 6) === 'hello by today');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello by today', 5) === 'hello by today');
		$this->assertTrue(StringUtils::removeWordsLongerThan('hello by today', 1) === '  ');
		// TODO: multi line strings do not work!
		// $this->assertTrue(StringUtils::removeWordsLongerThan("Line1\nline2\r\nline3 and  \nline4", 4) === "\n\r\n and  \n");
		// $this->assertTrue(StringUtils::removeWordsLongerThan("Line1 line12\nline2\r\nline3 and  \nline4", 6) === " line12\n\r\n   \n");
		// TODO: add more multi line tests
	}


	/**
	 * testRemoveUrls
	 *
	 * @return void
	 */
	public function testRemoveUrls(){

	    // TODO!!
	}


	/**
	 * testRemoveHtmlCode
	 *
	 * @return void
	 */
	public function testRemoveHtmlCode(){

	    // TODO!!
	}


	/**
	 * testRemoveMultipleSpaces
	 *
	 * @return void
	 */
	public function testRemoveMultipleSpaces(){

	    // TODO!!
	}
}

?>