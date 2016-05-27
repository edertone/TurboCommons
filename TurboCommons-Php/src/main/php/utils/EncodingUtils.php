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
 * Utilities related to string and text character encoding,
 * converting between formats, and perform common encoding operations.
 */
class EncodingUtils{


	/**
	 * Convert a string with unicode escaped characters (\u00ed, \u0110) to an utf8 string.
	 *
	 * @param string $str A string containing unicode escaped characters.
	 *
	 * @return string An utf8 string conversion of the unicode encoded input.
	 */
	public static function unicodeCharsToUtf8($str){

		return json_decode('"'.str_replace('"', '\\"', $str).'"');
	}
}

?>