<?php

namespace com\edertone\libTurboPhp\src\main\php\utils;


/**
 * Array related utilities
 */
class ArrayUtils{


	// TODO - tot això és basura del sergi

	/**
	 * It removes all similar values defined to the values array
	 *
	 * @param array $array The array that its similar values will be removed
	 * @param array $values The array containing the values to be removed from the input array
	 *
	 * @return array The resulting array with the removed values
	 */
	public static function removeValues(array $array, array $values){

		$isAssoc = self::isAssociative($array);

		foreach($values as $v){
			if(($key = array_search($v, $array)) !== false) {
				unset($array[$key]);
			}
		}

		return $isAssoc ? $array : array_values($array);
	}


	/**
	 * Know if an array is associative or not
	 *
	 * @param array $array	The input array
	 *
	 * @return boolean
	 */
	public static function isAssociative(array $array){

		return array_keys($array) !== range(0, count($array) - 1);
	}

}

?>