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

use PHPUnit_Framework_TestCase;
use org\turbocommons\src\main\php\managers\ValidationManager;
use stdClass;


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

		$this->assertTrue(!$validationManager->isBoolean(undefined));
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

		// TODO - translate from js
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
}


// TODO - Add all missing tests from javascript
?>