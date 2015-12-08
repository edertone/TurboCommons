<?php

namespace com\edertone\libTurboPhp\src\main\php\model;

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

		throw new Exception('ShoppingCartManager property '.$name.' does not exist');
	}


	// TODO: Incloure m�s bones practiques OOP

}

?>