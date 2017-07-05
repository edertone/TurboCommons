<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\managers;

use org\turbocommons\src\main\php\managers\ValidationManager;
use PHPUnit\Framework\TestCase;
use Throwable;
use stdClass;
use Exception;


/**
 * ValidationManager tests
 *
 * @return void
 */
class ValidationManagerTest extends TestCase {


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

        $this->validationManager = new ValidationManager();
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        unset($this->validationManager);
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
	 * testIsTrue
	 *
	 * @return void
	 */
	public function testIsTrue(){

	    // Test empty values
	    $this->assertTrue(!$this->validationManager->isTrue(null));
	    $this->assertTrue(!$this->validationManager->isTrue([]));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    $this->validationManager->reset();

	    // Test ok values
	    $this->assertTrue($this->validationManager->isTrue(true));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test wrong values
	    $this->assertTrue(!$this->validationManager->isTrue(false));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	    $this->assertTrue(!$this->validationManager->isTrue('121212'));
	    $this->assertTrue(!$this->validationManager->isTrue([1, 78]));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    $this->validationManager->reset();
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test mixed ok and wrong
	    $this->assertTrue(!$this->validationManager->isTrue(false, 'false error', true));
	    $this->assertTrue($this->validationManager->lastMessage === 'false error');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
	    $this->assertTrue($this->validationManager->isTrue(true, 'no error'));
	    $this->assertTrue(!$this->validationManager->isTrue(false, 'false error 2'));
	    $this->assertTrue($this->validationManager->lastMessage === 'false error 2');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * testIsBoolean
	 *
	 * @return void
	 */
	public function testIsBoolean(){

	    // Test empty values
	    $this->assertTrue(!$this->validationManager->isBoolean(null));
	    $this->assertTrue(!$this->validationManager->isBoolean(''));
	    $this->assertTrue(!$this->validationManager->isBoolean([]));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    $this->validationManager->reset();
	    $this->assertTrue($this->validationManager->lastMessage === '');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test ok values
	    $this->assertTrue($this->validationManager->isBoolean(true));
	    $this->assertTrue($this->validationManager->isBoolean(false));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test wrong values
	    $this->assertTrue(!$this->validationManager->isBoolean('hello'));
	    $this->assertTrue(!$this->validationManager->isBoolean(['go', 12]));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	    $this->assertTrue(!$this->validationManager->isBoolean(45));
	    $this->assertTrue(!$this->validationManager->isBoolean(new Exception(), 'custom error'));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	    $this->assertTrue($this->validationManager->lastMessage === 'custom error');

	    $this->validationManager->reset();

	    // Test mixed ok and wrong values
	    $this->assertTrue(!$this->validationManager->isBoolean([12], 'error', true));
	    $this->assertTrue($this->validationManager->lastMessage === 'error');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
	    $this->assertTrue($this->validationManager->isBoolean(true, 'no error'));
	    $this->assertTrue(!$this->validationManager->isBoolean('asdf', 'error 2'));
	    $this->assertTrue($this->validationManager->lastMessage === 'error 2');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * testIsNumeric
	 *
	 * @return void
	 */
	public function testIsNumeric(){

	    // Test empty values
	    $this->assertFalse($this->validationManager->isNumeric(null));
	    $this->assertFalse($this->validationManager->isNumeric(''));
	    $this->assertFalse($this->validationManager->isNumeric([]));
	    $this->assertTrue($this->validationManager->isNumeric(0));

	    $this->validationManager->reset();
	    $this->assertTrue($this->validationManager->lastMessage === '');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test ok values
	    $this->assertTrue($this->validationManager->isNumeric(1));
	    $this->assertTrue($this->validationManager->isNumeric(-1));
	    $this->assertTrue($this->validationManager->isNumeric(145646));
	    $this->assertTrue($this->validationManager->isNumeric(-3453451));
	    $this->assertTrue($this->validationManager->isNumeric(1.34435));
	    $this->assertTrue($this->validationManager->isNumeric(-1.56567));
	    $this->assertTrue($this->validationManager->isNumeric('1'));
	    $this->assertTrue($this->validationManager->isNumeric('-1'));
	    $this->assertTrue($this->validationManager->isNumeric('1.4545645'));
	    $this->assertTrue($this->validationManager->isNumeric('-1.345'));
	    $this->assertTrue($this->validationManager->isNumeric('345341'));
	    $this->assertTrue($this->validationManager->isNumeric('-345341'));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test wrong values
	    $this->assertFalse($this->validationManager->isNumeric([12, 'b']));
	    $this->assertFalse($this->validationManager->isNumeric(new ValidationManager()));
	    $this->assertTrue($this->validationManager->lastMessage === 'value is not a number');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	    $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error'));
	    $this->assertFalse($this->validationManager->isNumeric('1,4356', 'numeric error'));
	    $this->assertTrue($this->validationManager->lastMessage === 'numeric error');
	    $this->assertFalse($this->validationManager->isNumeric('1,4.4545', 'numeric error'));
	    $this->assertFalse($this->validationManager->isNumeric('--345', 'numeric error'));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    $this->validationManager->reset();
	    $this->assertTrue($this->validationManager->lastMessage === '');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test mixed ok and wrong values
	    $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error', true));
	    $this->assertTrue($this->validationManager->lastMessage === 'numeric error');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
	    $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error 2'));
	    $this->assertTrue($this->validationManager->lastMessage === 'numeric error 2');
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * testIsString
	 *
	 * @return void
	 */
	public function testIsString(){

		$this->assertTrue($this->validationManager->isString(''));
		$this->assertTrue($this->validationManager->isString('sfadf'));
		$this->assertTrue($this->validationManager->isString('3453515 532'));
		$this->assertTrue($this->validationManager->isString("\n\n$!"));
		$this->assertTrue($this->validationManager->isString('hello baby how are you'));
		$this->assertTrue($this->validationManager->isString("hello\n\nbably\r\ntest"));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$this->validationManager->isString(null, '', true));
		$this->assertTrue(!$this->validationManager->isString(123, '', true));
		$this->assertTrue(!$this->validationManager->isString(4.879, '', true));
		$this->assertTrue(!$this->validationManager->isString(new ValidationManager(), '', true));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$this->validationManager->isString([]));
		$this->assertTrue(!$this->validationManager->isString(-978));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$this->validationManager->reset();
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsUrl
	 *
	 * @return void
	 */
	public function testIsUrl(){

		// Wrong url cases
		$this->assertTrue(!$this->validationManager->isUrl(''));
		$this->assertTrue(!$this->validationManager->isUrl(null));
		$this->assertTrue(!$this->validationManager->isUrl([]));
		$this->assertTrue(!$this->validationManager->isUrl('    '));
		$this->assertTrue(!$this->validationManager->isUrl('123f56ccaca'));
		$this->assertTrue(!$this->validationManager->isUrl('8/%$144///(!(/"'));
		$this->assertTrue(!$this->validationManager->isUrl('http'));
		$this->assertTrue(!$this->validationManager->isUrl('x.y'));
		$this->assertTrue(!$this->validationManager->isUrl('http://x.y'));
		$this->assertTrue(!$this->validationManager->isUrl('google.com-'));
		$this->assertTrue(!$this->validationManager->isUrl("\n   \t\n"));
		$this->assertTrue(!$this->validationManager->isUrl('http:\\google.com'));
		$this->assertTrue(!$this->validationManager->isUrl('_http://google.com'));
		$this->assertTrue(!$this->validationManager->isUrl('http://www.example..com'));
		$this->assertTrue(!$this->validationManager->isUrl('http://.com'));
		$this->assertTrue(!$this->validationManager->isUrl('http://www.example.'));
		$this->assertTrue(!$this->validationManager->isUrl('http:/www.example.com'));
		$this->assertTrue(!$this->validationManager->isUrl('http://'));
		$this->assertTrue(!$this->validationManager->isUrl('http://.'));
		$this->assertTrue(!$this->validationManager->isUrl('http://??/'));
		$this->assertTrue(!$this->validationManager->isUrl('http://foo.bar?q=Spaces should be encoded'));
		$this->assertTrue(!$this->validationManager->isUrl('rdar://1234'));
		$this->assertTrue(!$this->validationManager->isUrl('http://foo.bar/foo(bar)baz quux'));
		$this->assertTrue(!$this->validationManager->isUrl('http://10.1.1.255'));
		$this->assertTrue(!$this->validationManager->isUrl('http://.www.foo.bar./'));
		$this->assertTrue(!$this->validationManager->isUrl('http://.www.foo.bar/'));
		$this->assertTrue(!$this->validationManager->isUrl('ftp://user:password@host:port/path'));
		$this->assertTrue(!$this->validationManager->isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
		$this->assertTrue(!$this->validationManager->isUrl('C:\\Program Files (x86)'));

		// good url cases
		$this->assertTrue($this->validationManager->isUrl('http://x.ye'));
		$this->assertTrue($this->validationManager->isUrl('http://google.com'));
		$this->assertTrue($this->validationManager->isUrl('ftp://mydomain.com'));
		$this->assertTrue($this->validationManager->isUrl('http://www.example.com:8800'));
		$this->assertTrue($this->validationManager->isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
		// TODO - this test does not pass, but it does pass in JS. We should look for another regex in PHP that passes it also
		// $this->assertTrue($this->validationManager->isUrl('http://www.test.com?pageid=123&testid=1524'));
		$this->assertTrue($this->validationManager->isUrl('http://www.test.com/do.html#A'));
		$this->assertTrue($this->validationManager->isUrl('https://subdomain.test.com/'));
		$this->assertTrue($this->validationManager->isUrl('https://test.com'));
		$this->assertTrue($this->validationManager->isUrl('http://foo.com/blah_blah/'));
		$this->assertTrue($this->validationManager->isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
		$this->assertTrue($this->validationManager->isUrl('http://userid@example.com:8080'));
		$this->assertTrue($this->validationManager->isUrl('http://➡.ws/䨹'));
		$this->assertTrue($this->validationManager->isUrl('http://⌘.ws/'));
		$this->assertTrue($this->validationManager->isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
		$this->assertTrue($this->validationManager->isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
		$this->assertTrue($this->validationManager->isUrl('http://223.255.255.254'));
		$this->assertTrue($this->validationManager->isUrl('ftp://user:password@host.com:8080/path'));

		$this->validationManager->reset();
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		// Test non string values throw exceptions
		$exceptionMessage = '';

		try {
			$this->validationManager->isUrl([12341]);
			$exceptionMessage = '[12341] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			$this->validationManager->isUrl(12341);
			$exceptionMessage = '12341 did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testIsArray
	 *
	 * @return void
	 */
	public function testIsArray(){

		$this->assertTrue($this->validationManager->isArray([]));
		$this->assertTrue($this->validationManager->isArray([1]));
		$this->assertTrue($this->validationManager->isArray(['1']));
		$this->assertTrue($this->validationManager->isArray(['1', 5, []]));
		$this->assertTrue($this->validationManager->isArray([null]));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$this->validationManager->isArray(null, '', true));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$this->validationManager->isArray(1));
		$this->assertTrue(!$this->validationManager->isArray(''));
		$this->assertTrue(!$this->validationManager->isArray(new ValidationManager()));
		$this->assertTrue(!$this->validationManager->isArray('hello'));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$this->validationManager->reset();
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsObject
	 *
	 * @return void
	 */
	public function testIsObject(){

		$this->assertTrue($this->validationManager->isObject(new stdClass()));

		$this->assertTrue($this->validationManager->isObject((object) [
			'1' => 1
		]));

		$this->assertTrue($this->validationManager->isObject((object) [
			'1' => '1'
		]));

		$this->assertTrue($this->validationManager->isObject((object) [
				'1' => '1',
				'5' => 5,
				'array' => []
		]));

		$this->assertTrue($this->validationManager->isObject((object) [
				'novalue' => null
		]));

		$this->assertTrue($this->validationManager->isObject(new ValidationManager()));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);


		$this->assertTrue(!$this->validationManager->isObject(null, '', true));
		$this->assertTrue(!$this->validationManager->isObject([], '', true));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);

		$this->assertTrue(!$this->validationManager->isObject(1));
		$this->assertTrue(!$this->validationManager->isObject(''));
		$this->assertTrue(!$this->validationManager->isObject('hello'));
		$this->assertTrue(!$this->validationManager->isObject([1, 4, 5]));
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$this->validationManager->reset();
		$this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsFilledIn
	 *
	 * @return void
	 */
	public function testIsFilledIn(){

	    // Test empty values
	    $this->assertFalse($this->validationManager->isFilledIn(null));
	    $this->assertFalse($this->validationManager->isFilledIn(''));
	    $this->assertFalse($this->validationManager->isFilledIn([]));
	    $this->assertTrue(!$this->validationManager->isFilledIn(null, [], '', true));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    $this->validationManager->reset();

	    // Test ok values
	    $this->assertTrue($this->validationManager->isFilledIn('adsadf'));
	    $this->assertTrue($this->validationManager->isFilledIn('    sdfasdsf'));
	    $this->assertTrue($this->validationManager->isFilledIn('EMPTY'));
	    $this->assertTrue($this->validationManager->isFilledIn('EMPTY test', ['EMPTY']));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_OK);

	    // Test wrong values
	    $this->assertFalse($this->validationManager->isFilledIn('      ', [], '', true));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
	    $this->assertFalse($this->validationManager->isFilledIn("\n\n  \n"));
	    $this->assertFalse($this->validationManager->isFilledIn("\t   \n     \r\r"));
	    $this->assertFalse($this->validationManager->isFilledIn('EMPTY', ['EMPTY']));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	    $this->assertFalse($this->validationManager->isFilledIn('EMPTY           ', ['EMPTY']));
	    $this->assertFalse($this->validationManager->isFilledIn('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));
	    $this->assertTrue($this->validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

	    // Test exceptions
	    $exceptionMessage = '';

	    try {
	        $this->validationManager->isFilledIn(125);
	        $exceptionMessage = '125 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->validationManager->isFilledIn([125]);
	        $exceptionMessage = '[125] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->validationManager->isFilledIn(new Exception());
	        $exceptionMessage = 'new Exception() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}


	/**
	 * testIsDate
	 *
	 * @return void
	 */
	public function testIsDate(){

		// TODO - copy from js
	}


	/**
	 * testIsMail
	 *
	 * @return void
	 */
	public function testIsMail(){

		// TODO - copy from js
	}


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

		$this->assertTrue($this->validationManager->isEqualTo(null, null));
		$this->assertTrue($this->validationManager->isEqualTo('', ''));
		$this->assertTrue($this->validationManager->isEqualTo(123, 123));
		$this->assertTrue($this->validationManager->isEqualTo(1.56, 1.56));
		$this->assertTrue($this->validationManager->isEqualTo([], []));
		$this->assertTrue($this->validationManager->isEqualTo('hello', 'hello'));
		$this->assertTrue($this->validationManager->isEqualTo(new ValidationManager(), new ValidationManager()));
		$this->assertTrue($this->validationManager->isEqualTo([1, 6, 8, 4], [1, 6, 8, 4]));

		$this->assertTrue(!$this->validationManager->isEqualTo(null, []));
		$this->assertTrue(!$this->validationManager->isEqualTo('', 'hello'));
		$this->assertTrue(!$this->validationManager->isEqualTo(124, 12454));
		$this->assertTrue(!$this->validationManager->isEqualTo(1.45, 1));
		$this->assertTrue(!$this->validationManager->isEqualTo([], new stdClass()));
		$this->assertTrue(!$this->validationManager->isEqualTo('gobaby', 'hello'));
		$this->assertTrue(!$this->validationManager->isEqualTo('hello', new ValidationManager()));
		$this->assertTrue(!$this->validationManager->isEqualTo([5, 2, 8, 5], [1, 6, 9, 5]));

		$this->assertTrue(!$this->validationManager->isEqualTo(((object) [
				'a' => 1,
				'b' => 2
		]), ((object) [
				'c' => 1,
				'b' => 3
		])));
	}
}
?>