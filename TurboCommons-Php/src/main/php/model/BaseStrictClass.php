<?php

/**
 * TurboCommons-Php
 *
 * PHP Version 5.4
 *
 * @copyright 2015 Edertone advanced solutions (http://www.edertone.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://turbocommons.org
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

		throw new Exception('ShoppingCartManager property '.$name.' does not exist');
	}


	// TODO: Incloure m�s bones practiques OOP

}

?>