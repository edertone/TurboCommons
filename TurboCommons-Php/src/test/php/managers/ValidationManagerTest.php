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
use stdClass;
use PHPUnit_Framework_TestCase;


/**
 * ValidationManager tests
 *
 * @return void
 */
class ValidationManagerTest extends PHPUnit_Framework_TestCase {


	/**
	 * testIsTrue
	 *
	 * @return void
	 */
	public function testIsTrue(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isTrue(true));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$validationManager->isTrue(false));
		$this->assertTrue(!$validationManager->isTrue(null));
		$this->assertTrue(!$validationManager->isTrue([]));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$validationManager->reset();

		$this->assertTrue(!$validationManager->isTrue(false, 'false error', true));
		$this->assertTrue($validationManager->lastMessage === 'false error');
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$validationManager->isTrue(false, 'false error 2'));
		$this->assertTrue($validationManager->lastMessage === 'false error 2');
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * isBoolean
	 *
	 * @return void
	 */
	public function isBoolean(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isBoolean(true));
		$this->assertTrue($validationManager->isBoolean(false));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$validationManager->isBoolean(null));
		$this->assertTrue(!$validationManager->isBoolean([]));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * testIsNumeric
	 *
	 * @return void
	 */
	public function testIsNumeric(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isNumeric(1));
		$this->assertTrue($validationManager->isNumeric(145646));
		$this->assertTrue($validationManager->isNumeric(-1));
		$this->assertTrue($validationManager->isNumeric(-1.56567));
		$this->assertTrue($validationManager->isNumeric(1.34435));
		$this->assertTrue($validationManager->isNumeric(-3453451));
		$this->assertTrue($validationManager->isNumeric('1'));
		$this->assertTrue($validationManager->isNumeric('1.4545645'));
		$this->assertTrue($validationManager->isNumeric('-1.345'));
		$this->assertTrue($validationManager->isNumeric('-345341'));
		$this->assertTrue($validationManager->isNumeric('1.4564564563456'));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$validationManager->isNumeric([]));
		$this->assertTrue(!$validationManager->isNumeric(new ValidationManager()));
		$this->assertTrue(!$validationManager->isNumeric('hello', 'numeric error'));
		$this->assertTrue(!$validationManager->isNumeric('1,4356', 'numeric error'));
		$this->assertTrue(!$validationManager->isNumeric('1,4.4545', 'numeric error'));
		$this->assertTrue(!$validationManager->isNumeric('--345', 'numeric error'));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$validationManager->reset();

		$this->assertTrue(!$validationManager->isNumeric('hello', 'numeric error', true));
		$this->assertTrue($validationManager->lastMessage === 'numeric error');
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$validationManager->isNumeric('hello', 'numeric error 2'));
		$this->assertTrue($validationManager->lastMessage === 'numeric error 2');
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);
	}


	/**
	 * testIsString
	 *
	 * @return void
	 */
	public function testIsString(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isString(''));
		$this->assertTrue($validationManager->isString('sfadf'));
		$this->assertTrue($validationManager->isString('3453515 532'));
		$this->assertTrue($validationManager->isString("\n\n$!"));
		$this->assertTrue($validationManager->isString('hello baby how are you'));
		$this->assertTrue($validationManager->isString("hello\n\nbably\r\ntest"));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$validationManager->isString(null, '', true));
		$this->assertTrue(!$validationManager->isString(123, '', true));
		$this->assertTrue(!$validationManager->isString(4.879, '', true));
		$this->assertTrue(!$validationManager->isString(new ValidationManager(), '', true));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$validationManager->isString([]));
		$this->assertTrue(!$validationManager->isString(-978));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$validationManager->reset();
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsUrl
	 *
	 * @return void
	 */
	public function testIsUrl(){

		$validationManager = new ValidationManager();

		// Wrong url cases
		$this->assertTrue(!$validationManager->isUrl(''));
		$this->assertTrue(!$validationManager->isUrl(null));
		$this->assertTrue(!$validationManager->isUrl([]));
		$this->assertTrue(!$validationManager->isUrl('    '));
		$this->assertTrue(!$validationManager->isUrl('123f56ccaca'));
		$this->assertTrue(!$validationManager->isUrl('8/%$144///(!(/"'));
		$this->assertTrue(!$validationManager->isUrl('http'));
		$this->assertTrue(!$validationManager->isUrl('x.y'));
		$this->assertTrue(!$validationManager->isUrl('http://x.y'));
		$this->assertTrue(!$validationManager->isUrl('google.com-'));
		$this->assertTrue(!$validationManager->isUrl("\n   \t\n"));
		$this->assertTrue(!$validationManager->isUrl('http:\\google.com'));
		$this->assertTrue(!$validationManager->isUrl('_http://google.com'));
		$this->assertTrue(!$validationManager->isUrl('http://www.example..com'));
		$this->assertTrue(!$validationManager->isUrl('http://.com'));
		$this->assertTrue(!$validationManager->isUrl('http://www.example.'));
		$this->assertTrue(!$validationManager->isUrl('http:/www.example.com'));
		$this->assertTrue(!$validationManager->isUrl('http://'));
		$this->assertTrue(!$validationManager->isUrl('http://.'));
		$this->assertTrue(!$validationManager->isUrl('http://??/'));
		$this->assertTrue(!$validationManager->isUrl('http://foo.bar?q=Spaces should be encoded'));
		$this->assertTrue(!$validationManager->isUrl('rdar://1234'));
		$this->assertTrue(!$validationManager->isUrl('http://foo.bar/foo(bar)baz quux'));
		$this->assertTrue(!$validationManager->isUrl('http://10.1.1.255'));
		$this->assertTrue(!$validationManager->isUrl('http://.www.foo.bar./'));
		$this->assertTrue(!$validationManager->isUrl('http://.www.foo.bar/'));
		$this->assertTrue(!$validationManager->isUrl('ftp://user:password@host:port/path'));
		$this->assertTrue(!$validationManager->isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
		$this->assertTrue(!$validationManager->isUrl('C:\\Program Files (x86)'));

		// good url cases
		$this->assertTrue($validationManager->isUrl('http://x.ye'));
		$this->assertTrue($validationManager->isUrl('http://google.com'));
		$this->assertTrue($validationManager->isUrl('ftp://mydomain.com'));
		$this->assertTrue($validationManager->isUrl('http://www.example.com:8800'));
		$this->assertTrue($validationManager->isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
		// TODO - this test does not pass, but it does pass in JS. We should look for another regex in PHP that passes it also
		// $this->assertTrue($validationManager->isUrl('http://www.test.com?pageid=123&testid=1524'));
		$this->assertTrue($validationManager->isUrl('http://www.test.com/do.html#A'));
		$this->assertTrue($validationManager->isUrl('https://subdomain.test.com/'));
		$this->assertTrue($validationManager->isUrl('https://test.com'));
		$this->assertTrue($validationManager->isUrl('http://foo.com/blah_blah/'));
		$this->assertTrue($validationManager->isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
		$this->assertTrue($validationManager->isUrl('http://userid@example.com:8080'));
		$this->assertTrue($validationManager->isUrl('http://➡.ws/䨹'));
		$this->assertTrue($validationManager->isUrl('http://⌘.ws/'));
		$this->assertTrue($validationManager->isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
		$this->assertTrue($validationManager->isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
		$this->assertTrue($validationManager->isUrl('http://223.255.255.254'));
		$this->assertTrue($validationManager->isUrl('ftp://user:password@host.com:8080/path'));

		$validationManager->reset();
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		// Test non string values throw exceptions
		$this->setExpectedException('Exception');
		$this->assertTrue(!$validationManager->isUrl([12341]));
	}


	/**
	 * testIsArray
	 *
	 * @return void
	 */
	public function testIsArray(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isArray([]));
		$this->assertTrue($validationManager->isArray([1]));
		$this->assertTrue($validationManager->isArray(['1']));
		$this->assertTrue($validationManager->isArray(['1', 5, []]));
		$this->assertTrue($validationManager->isArray([null]));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);

		$this->assertTrue(!$validationManager->isArray(null, '', true));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);
		$this->assertTrue(!$validationManager->isArray(1));
		$this->assertTrue(!$validationManager->isArray(''));
		$this->assertTrue(!$validationManager->isArray(new ValidationManager()));
		$this->assertTrue(!$validationManager->isArray('hello'));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$validationManager->reset();
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsObject
	 *
	 * @return void
	 */
	public function testIsObject(){

		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isObject(new stdClass()));

		$this->assertTrue($validationManager->isObject((object) [
			'1' => 1
		]));

		$this->assertTrue($validationManager->isObject((object) [
			'1' => '1'
		]));

		$this->assertTrue($validationManager->isObject((object) [
				'1' => '1',
				'5' => 5,
				'array' => []
		]));

		$this->assertTrue($validationManager->isObject((object) [
				'novalue' => null
		]));

		$this->assertTrue($validationManager->isObject(new ValidationManager()));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);


		$this->assertTrue(!$validationManager->isObject(null, '', true));
		$this->assertTrue(!$validationManager->isObject([], '', true));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);

		$this->assertTrue(!$validationManager->isObject(1));
		$this->assertTrue(!$validationManager->isObject(''));
		$this->assertTrue(!$validationManager->isObject('hello'));
		$this->assertTrue(!$validationManager->isObject([1, 4, 5]));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		$validationManager->reset();
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}


	/**
	 * testIsFilledIn
	 *
	 * @return void
	 */
	public function testIsFilledIn(){

		$validationManager = new ValidationManager();

		// Test empty strings
		$this->assertTrue(!$validationManager->isFilledIn(null, [], '', true));
		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_WARNING);

		$this->assertTrue(!$validationManager->isFilledIn(null));
		$this->assertTrue(!$validationManager->isFilledIn('      '));
		$this->assertTrue(!$validationManager->isFilledIn("\n\n  \n"));
		$this->assertTrue(!$validationManager->isFilledIn("\t   \n     \r\r"));
		$this->assertTrue(!$validationManager->isFilledIn('EMPTY', ['EMPTY']));
		$this->assertTrue(!$validationManager->isFilledIn('EMPTY           ', ['EMPTY']));
		$this->assertTrue(!$validationManager->isFilledIn('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_ERROR);

		// Test non empty strings
		$validationManager->reset();

		$this->assertTrue($validationManager->isFilledIn('adsadf'));
		$this->assertTrue($validationManager->isFilledIn('    sdfasdsf'));
		$this->assertTrue($validationManager->isFilledIn('EMPTY'));
		$this->assertTrue($validationManager->isFilledIn('EMPTY test', ['EMPTY']));

		$this->assertTrue($validationManager->validationStatus === ValidationManager::VALIDATION_OK);
	}
}


// TODO - Add all missing tests from javascript
?>