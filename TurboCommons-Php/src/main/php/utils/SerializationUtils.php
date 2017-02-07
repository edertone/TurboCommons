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

use Exception;
use SimpleXMLElement;


/**
 * Contains methods that allow us to convert data from one data format or type to another data format or type
 */
class SerializationUtils{


	/**
	 * TODO fer aixo
	 */
	public static function arrayToObject($string){

		// TODO fer aixo
	}


	/**
	 * Stores key/value data from an arbitrary object to a class instance.
	 * Class property values will be set to the hash map values which key is the same as the property name.
	 * Class properties that are not found on the given hash will remain untouched.
	 *
	 * @param Object $hashMap An object that contains data which is organized as a hash map. For example: An associative array or an object with key / value pairs
	 * @param Object $classInstance A class instance that will be filled with all the values that are found on the hash.
	 *
	 * @return The provided class instance filled with all the hash values that match key = property name
	 */
	public static function hashMapToClass($hashMap, $classInstance){

		foreach($hashMap as $key => $value){

			if(property_exists($classInstance, $key)){

				$classInstance->{$key} = $value;
			}
		}

		return $classInstance;
	}


	/**
	 * Convert a string containing the contents of a Java properties file to an associative array.
	 * For example: tag1=value1 will be converted to ['tag1' => 'value1'].<br><br>
	 * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
	 * properties file format (Otherwise results may not be correct).
	 *
	 * @param string $string String containing the contents of a .properties Java file
	 *
	 * @return array The properties format parsed as an associative array
	 */
	public static function javaPropertiesToArray($string){

		$key = '';
		$result = [];
		$isWaitingOtherLine = false;

		// Generate an array with the properties lines, ignoring blank lines and comments
		$lines = StringUtils::extractLines($string, ['/\s+/', '/ *#.*| *!.*/']);

		foreach($lines as $i=>$line) {

			// Remove all blank spaces at the beginning of the line
			$line = ltrim($line);

			if($isWaitingOtherLine) {

				$value .= EncodingUtils::unicodeCharsToUtf8($line);

			}else{

				// Find the key/value divider index
				$tmpLine = str_replace(['\\=', '\\:'], 'xx', $line);
				$keyDividerIndex = min([stripos($tmpLine.'=', '='), stripos($tmpLine.':', ':')]);

				// Extract the key from the line
				$key = trim(substr($line, 0, $keyDividerIndex));
				$key = str_replace(['\\ ', '\#', '\!', '\=', '\:'], [' ', '#', '!', '=', ':'], $key);

				// Extract the value from the line
				$value = ltrim(substr($line, $keyDividerIndex + 1, strlen($line)));
			}

			// Unescape escaped slashes on the value
			$value = str_replace(['\\\\'], ['\u005C'], $value);

			// Check if ends with single '\'
			if(substr($value, -1) == '\\'){

				// Remove trailing backslash
				$value = substr_replace($value, '', -1);

				$isWaitingOtherLine = true;

			}else{

				$isWaitingOtherLine = false;

				// Decode unicode characters
				$value = EncodingUtils::unicodeCharsToUtf8($value);
			}

			$result[$key] = $value;

			unset($lines[$i]);
		}

		return $result;
	}


	/**
	 * TODO fer aixo
	 */
	public static function javaPropertiesToObject($string){

		// TODO fer aixo
	}


	/**
	 * Convert a string containing a well formed XML structure to a SimpleXmlElement instance
	 *
	 * @param string $string A string containing xml data
	 *
	 * @return SimpleXMLElement The representation of the given string as an xml object
	 */
	public static function stringToXml($string){

		if(StringUtils::isEmpty($string)){

			return null;
		}

		$xml = simplexml_load_string(trim($string));

		if(!$xml){

			throw new Exception('SerializationUtils->stringToXml could not convert string to SimpleXMLElement');
		}

		return $xml;
	}


	/**
	 * Convert an Xml object to its string representation
	 *
	 * @param SimpleXMLElement $xml An instance of an xml element
	 *
	 * @return string The textual representation of the given xml data
	 */
	public static function xmlToString(SimpleXMLElement $xml){

		return $xml->asXML();
	}
}

?>