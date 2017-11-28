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
 * Utilities to perform common object operations
 */
class ObjectUtils {


    /**
     * Tells if the given value is an object or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is an object, false otherwise
     */
    public static function isObject($value){

        return is_object($value);
    }


	/**
	 * Get the list of literals for a given object. Note that only 1rst depth keys are providen
	 *
	 * @param object $object A valid object
	 *
	 * @return array List of strings with the first level object key names in the same order as defined on the object instance
	 */
	public static function getKeys($object){

		$res = [];

		if(!ObjectUtils::isObject($object)){

			throw new UnexpectedValueException('ObjectUtils->getKeys: parameter must be an object');
		}

		foreach($object as $key => $value){

			$res[] = (string)$key;
		}

		return $res;
	}


	/**
	 * Check if two provided objects are identical
	 *
	 * @param object $object1 First object to compare
	 * @param object $object2 Second object to compare
	 *
	 * @return boolean true if objects are exactly the same, false if not
	 */
	public static function isEqualTo($object1, $object2){

		$validationManager = new ValidationManager();

		// Both provided values must be objects or an exception will be launched
		if(!$validationManager->isObject($object1) || !$validationManager->isObject($object2)){

			throw new UnexpectedValueException('ObjectUtils->isEqualTo: parameters must be objects');
		}

		$keys1 = self::getKeys($object1);
		$keys2 = self::getKeys($object2);

		// Compare keys can save a lot of time
		if(!ArrayUtils::isEqualTo($keys1, $keys2)){

			return false;
		}

		// Loop all the keys and verify values are identical
		$keys1Len = count($keys1);

		for($i = 0; $i < $keys1Len; $i++){

			$o1 = (array)$object1;
			$o2 = (array)$object2;

			if(!$validationManager->isEqualTo($o1[$keys1[$i]], $o2[$keys2[$i]])){

				return false;
			}
		}

		return true;
	}
}

?>