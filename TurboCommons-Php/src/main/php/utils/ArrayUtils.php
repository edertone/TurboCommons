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

use org\turbocommons\src\main\php\managers\ValidationManager;
use Exception;


/**
 * Utilities to perform common array operations
 */
class ArrayUtils {


	/**
	 * Check if two provided arrays are identical
	 *
	 * @param array $array1 First array to compare
	 * @param array $array2 Second array to compare
	 *
	 * @return boolean true if arrays are exactly the same, false if not
	 */
	public static function isEqualTo($array1, $array2){

		$validationManager = new ValidationManager();

		// Both provided values must be arrays or an exception will be launched
		if(!$validationManager->isArray($array1) || !$validationManager->isArray($array2)){

			throw new Exception('ArrayUtils->isEqualTo: Provided parameters must be arrays');
		}

		// Compare lengths can save a lot of time
		if(count($array1) != count($array2)){

			return false;
		}

		$array1Count = count($array1);

		for($i = 0, $l = $array1Count; $i < $l; $i++){

			// Check if we have nested arrays
			if($validationManager->isArray($array1[$i]) && $validationManager->isArray($array2[$i])){

				if(!self::isEqualTo($array1[$i], $array2[$i])){

					return false;
				}

			}else{

				if($validationManager->isObject($array1[$i]) && $validationManager->isObject($array2[$i])){

					if(!ObjectUtils::isEqualTo($array1[$i], $array2[$i])){

						return false;
					}

				}else if($array1[$i] !== $array2[$i]){

					return false;
				}
			}
		}

		return true;
	}



	/**
	 * TODO - translate from js
	 */
	public static function removeElement(){

		// TODO - translate from js
	}
}

?>