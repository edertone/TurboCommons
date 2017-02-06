<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use org\turbocommons\src\test\php\resources\model\baseDependantClass\DependantClass;
use PHPUnit_Framework_TestCase;
use Exception;
use org\turbocommons\src\test\php\resources\model\baseDependantClass\DependantClass2;
use org\turbocommons\src\main\php\managers\ValidationManager;


/**
 * BaseDependantClass tests
 *
 * @return void
 */
class BaseDependantClassTest extends PHPUnit_Framework_TestCase {


	/**
	 * testSetDependency
	 *
	 * @return void
	 */
	public function testSetDependency(){

		// Create a dependant class
		$dependantClass = new DependantClass();

		// Test null values work ok if still not set
		$dependantClass->setDependency('_property1', null);
		$dependantClass->setDependency('_property2', null);

		$exceptionMessage = '';

		// test setting wrong types cause exceptions
		try {
			$dependantClass->setDependency('_property1', '');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('_property1', ['a', 'b', 'c']);
			$exceptionMessage = 'array did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('_property2', 'ertert');
			$exceptionMessage = 'string ertert did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		//Test setting values to the dependencies
		$dependantClass->setDependency('_property1', new DependantClass());
		$dependantClass->setDependency('_property1', new DependantClass());
		$dependantClass->setDependency('_property2', new DependantClass2());

		// Test changing dependency type throws exception
		try {
			$dependantClass->setDependency('_property1', null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('_property1', 235);
			$exceptionMessage = '235 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('_property1', 'string');
			$exceptionMessage = 'string did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('_property1', new DependantClass2());
			$exceptionMessage = 'DependantClass2 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test non existing property throw exception
		try {
			$dependantClass->setDependency('', null);
			$exceptionMessage = '"" property did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency([], null);
			$exceptionMessage = '[] property did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->setDependency('test1', null);
			$exceptionMessage = 'test1 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test setting a public property as dependency throws exception
		try {
			$dependantClass->setDependency('publicProp', 123);
			$exceptionMessage = 'publicProp did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDependency
	 *
	 * @return void
	 */
	public function testGetDependency(){

		// Create two dependant classes
		$dependantClass = new DependantClass();
		$dependantClass2 = new DependantClass2();

		// Set each one as dependency for the other
		$dependantClass->setDependency('_property1', $dependantClass2);
		$dependantClass2->setDependency('_property1', $dependantClass);

		// Check that dependencies are the same after retrieving them
		$validationManager = new ValidationManager();

		$this->assertTrue($validationManager->isEqualTo($dependantClass->getDependency('_property1'), $dependantClass2));
		$this->assertTrue($validationManager->isEqualTo($dependantClass2->getDependency('_property1'), $dependantClass));
		$this->assertTrue($validationManager->isEqualTo($dependantClass2->getDependency('_property2'), null));

		// Test getting non existing dependencies throws exception
		$exceptionMessage = '';

		try {
			$dependantClass->getDependency('nonexisting');
			$exceptionMessage = 'nonexisting did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test getting a public property as dependency throws exception
		try {
			$dependantClass->getDependency('publicProp');
			$exceptionMessage = 'publicProp did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test getting wrong property values throw exception
		try {
			$dependantClass->getDependency('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$dependantClass->getDependency([1]);
			$exceptionMessage = '[1] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}
}

?>