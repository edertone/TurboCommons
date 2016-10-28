<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use ReflectionClass;
use Exception;


/**
 * A base class that implements easy dependency injection management
 */
abstract class BaseDependantClass extends BaseStrictClass {


	/**
	 * This class is used to perform all reflection operations on this class
	 */
	private $_reflectionClass;


	/**
	 * Class constructor initializes required objects
	 */
	function __construct() {

		$this->_reflectionClass = new ReflectionClass($this);
	}


	/**
	 * Injects the specified value to the specified property that contains a class dependency.
	 * Class dependencies must be defined as private properties.
	 *
	 * @param string $property The name for the property that contains the dependency we want to change
	 * @param class $value A class instance that will be stored on the specified property. Normally singletons or complex objects are assigned as dependencies
	 *
	 * @return void
	 */
	public function setDependency($property, $value) {

		// Get all the current class properties
		$thisProperties = $this->_reflectionClass->getProperties();

		// Find the requested property
		foreach ($thisProperties as $prop) {

			if($property == $prop->getName()){

				// Specified dependency property must be private
				if(!$prop->isPrivate()){

					throw new Exception(get_class($this).' property '.$property.' must be private');
				}

				// To prevent mistakes, specified property can only be assigned with object instances
				if($value !== null && !is_object($value)){

					throw new Exception(get_class($this).' property '.$property.' must be an object');
				}

				$prop->setAccessible(true);

				$propValue = $prop->getValue($this);

				// Specified property can be assigned if not defined yet or is the same class of the previous value
				if($propValue === null || get_class($propValue) === get_class($value)){

					$prop->setValue($this, $value);
					return;
				}

				throw new Exception(get_class($this).' property '.$property.' type does not match the specified value');
			}
		}

		// If property is not found, an exception will be thrown
		throw new Exception(get_class($this).' property '.$property.' does not exist');
	}


	/**
	 * Get the dependency that is stored on the specified property
	 *
	 * @param string $property The name for the property that contains the dependency we want to get
	 *
	 * @return object The dependency object that is stored under the specified property
	 */
	public function getDependency($property) {

		// Get all the current class properties
		$thisProperties = $this->_reflectionClass->getProperties();

		// Find the requested property
		foreach ($thisProperties as $prop) {

			if($property == $prop->getName()){

				// Specified dependency property must be private
				if(!$prop->isPrivate()){

					throw new Exception(get_class($this).' property '.$property.' must be private');
				}

				$prop->setAccessible(true);

				$propValue = $prop->getValue($this);

				// To prevent mistakes, specified property can only be assigned with object instances
				if($propValue !== null && !is_object($propValue)){

					throw new Exception(get_class($this).' property '.$property.' must be an object');
				}

				return $propValue;
			}
		}

		// If property is not found, an exception will be thrown
		throw new Exception(get_class($this).' property '.$property.' does not exist');
	}
}

?>