<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;


/**
 * Utilities related to string and text character encoding,
 * converting between formats, and perform common encoding operations.
 */
class EncodingUtils{


	/**
	 * Convert a string with unicode escaped sequence of characters (\u00ed, \u0110, ....) to an utf8 string.
	 *
	 * @param string $string A string containing unicode escaped characters.
	 *
	 * @return string An utf8 string conversion of the unicode encoded input.
	 */
    public static function unicodeEscapedCharsToUtf8($string){

        return json_decode('"'.str_replace('"', '\\"', $string).'"');
	}


	/**
	 * Convert a utf8 string to a string with unicode escaped sequence of characters (\u00ed, \u0110, ...).
	 *
	 * @param string $string A string containing an utf8 valid string.
	 *
	 * @return string A string containing escaped sequences for all the original utf8 characters
	 */
	public static function utf8ToUnicodeEscapedChars($string){

	    return trim(json_encode($string, JSON_UNESCAPED_SLASHES + JSON_HEX_QUOT), '"');
	}
}

?>