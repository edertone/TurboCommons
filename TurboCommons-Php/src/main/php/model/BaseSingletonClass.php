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


/**
 * A base class that implements the singleton pattern for PHP and can be extended to convert a class to a singleton object
 */
class BaseSingletonClass extends BaseStrictClass{


	/**
	 * Returns the *Singleton* instance of this class.
	 * IMPORTANT: If you want eclipse to perform autocomplete for the returned instance, you can assign it to a variable using doc annotations like this:
	 * /* @var $shoppingCartManager ShoppingCartManager *&#47;<br>$shoppingCartManager = ShoppingCartManager::getInstance();
	 *
	 * @staticvar Singleton $instance The *Singleton* instances of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function getInstance(){

		static $instance = null;

		if(null === $instance){

			$instance = new static();
		}

		return $instance;
	}


	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct(){

	}


	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone(){

	}


	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup(){

	}
}

?>