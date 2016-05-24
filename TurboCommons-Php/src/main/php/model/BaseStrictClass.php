<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\model;

use Exception;


/**
 * A base class that can be extended to protect created objects from access/read to undefined properties, and all other defensive OOP best practices
 */
class BaseStrictClass {


	/**
	 * Protection to prevent accessing undefined properties to this class
	 *
	 * @param string $name The property name
	 *
	 * @return void
	 */
	public function __get($name) {

		throw new Exception(get_class($this).' property '.$name.' does not exist');
	}


	/**
	 * Protection to prevent creating extra properties to this class
	 *
	 * @param string $name The property name
	 * @param string $value The property value
	 *
	 * @return void
	 */
	public function __set($name, $value) {

		throw new Exception(get_class($this).' property '.$name.' does not exist');
	}


	// TODO: Incloure m�s bones practiques OOP

}

?>