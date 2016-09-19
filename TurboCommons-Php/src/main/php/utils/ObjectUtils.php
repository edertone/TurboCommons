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
/**
 * Utilities to perform common object operations
 */
class ObjectUtils {


	/**
	 * Get the list of literals for a given object. Note that only 1rst depth keys are providen
	 *
	 * @param object $object A valid object
	 *
	 * @return array List of strings with the first level object key names in the same order as defined on the object instance
	 */
	public static function getKeys($object){

		$res = [];
		$validationManager = new ValidationManager();

		if(!$validationManager->isObject($object)){

			throw new Exception('ObjectUtils->getKeys: Provided parameter must be an object');
		}

		foreach($object as $key => $value){

			array_push($res, $key);
		}

		return $res;
	}
}

?>