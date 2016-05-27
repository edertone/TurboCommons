<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Contains several informative methods related to the turbo commons library.
 */
class TurboCommonsUtils{


	/**
	 * Get the current library version, for example: 1.2.3456
	 *
	 * @return string The build number for this version of the library
	 */
	public static function buildVersion(){

		return '@@package-build-version@@';
	}
}

?>