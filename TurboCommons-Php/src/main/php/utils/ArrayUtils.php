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
use UnexpectedValueException;


/**
 * Utilities to perform common array operations
 */
class ArrayUtils {


    /**
	 * Tells if the given value is an array or not
	 *
	 * @param mixed $value A value to check
	 *
	 * @return boolean true if the given value is an array, false otherwise
	 */
    public static function isArray($value){

        return is_array($value);
    }


	/**
	 * Check if two provided arrays are identical
	 *
	 * @param array $array1 First array to compare
	 * @param array $array2 Second array to compare
	 *
	 * @return boolean true if arrays are exactly the same, false if not
	 */
	public static function isEqualTo(array $array1, array $array2){

		$validationManager = new ValidationManager();

		// Both provided values must be arrays or an exception will be launched
		if(!$validationManager->isArray($array1) || !$validationManager->isArray($array2)){

			throw new UnexpectedValueException('ArrayUtils->isEqualTo: Provided parameters must be arrays');
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


	/**
	 * remove all the duplicate values on the provided array
	 * Duplicate values with different data types won't be considered as equal ('1', 1 will not be removed)
	 *
	 * @param array $array An array with possible duplicate values
	 *
	 * @return array The same provided array but without duplicate elements
	 */
	public static function removeDuplicateElements(array $array){

	    $result = [];
	    $numElements = count($array);

	    $validationManager = new ValidationManager();

	    for ($i = 0; $i < $numElements; $i++) {

	        $found = false;

	        $resultCount = count($result);

	        for ($j = 0; $j < $resultCount; $j++) {

	            if($validationManager->isEqualTo($array[$i], $result[$j])){

	                $found = true;
	                break;
	            }
	        }

	        if(!$found){

	            $result[] = $array[$i];
	        }
	    }

	    return $result;
	}


	/**
	 * Check if the given array contains duplicate values or not.
	 * Duplicate values with different data types won't be considered as equal ('1', 1 will return false)
	 *
	 * @param array $array An array containing some elements to test
	 *
	 * @return boolean True if there are duplicate values, false otherwise
	 */
	public static function hasDuplicateElements(array $array){

	    $numElements = count($array);

	    $validationManager = new ValidationManager();

	    for ($i = 0; $i < $numElements; $i++) {

	        for ($j = $i + 1; $j < $numElements; $j++) {

	            if($validationManager->isEqualTo($array[$i], $array[$j])){

	                return true;
	            }
	        }
	    }

	    return false;
	}


	/**
	 * Get all the duplicate values on the provided array
	 * Duplicate values with different data types won't be considered as equal ('1', 1 will return false)
	 *
	 * @param array $array An array containing some elements to test
	 *
	 * @return array list with all the elements that are duplicated on the provided array
	 */
	public static function getDuplicateElements(array $array){

	    $result = [];
	    $numElements = count($array);

	    $validationManager = new ValidationManager();

	    for ($i = 0; $i < $numElements; $i++) {

	        for ($j = $i + 1; $j < $numElements; $j++) {

	            if($validationManager->isEqualTo($array[$i], $array[$j])){

	                $result[] = $array[$i];
	            }
	        }
	    }

	    return self::removeDuplicateElements($result);
	}
}

?>