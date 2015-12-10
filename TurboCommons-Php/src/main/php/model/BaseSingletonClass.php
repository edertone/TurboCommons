<?php

namespace com\edertone\turboCommons\src\main\php\model;


/**
 * A base class that implements the singleton patter for PHP and can be extended to convert a class to a singleton object
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