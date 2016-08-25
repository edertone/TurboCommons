<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use org\turbocommons\src\main\php\model\BaseSingletonClass;


/**
 * SINGLETON class that lets us interact with the current browser
 */
class BrowserManager extends BaseSingletonClass{


	/**
	 * Returns the global singleton instance.
	 *
	 * @return BrowserManager The singleton instance.
	 */
	public static function getInstance(){

		// This method is overriden from the singleton one simply to get correct
		// autocomplete annotations when returning the instance
		 $instance = parent::getInstance();

		 return $instance;
	}


	/**
	 * TODO
	 */
	public function someMethod(){

		// TODO: Move php code from the old library utils to this manager

	}
}

?>