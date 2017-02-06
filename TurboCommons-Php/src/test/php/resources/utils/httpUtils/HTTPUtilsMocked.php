<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\resources\utils\httpUtils;

use org\turbocommons\src\main\php\utils\HTTPUtils;


/**
 * MOCK class to prevent real url http calls
 */
class HTTPUtilsMocked extends HTTPUtils{


	/**
	 * Overrides original method so it mocks internet addresses calls
	 *
	 * @param string $url an url
	 *
	 * @return array mocked values
	 */
	public static function getUrlHeaders($url){

		switch ($url){

			case 'http://facebook.com':
				return ['200'];
				break;

			case 'http://google.com':
				return ['200'];
				break;

			case 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js':
				return ['200'];
				break;
		}

		return ['404'];
	}
}

?>