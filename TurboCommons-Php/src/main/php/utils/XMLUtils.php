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
 * The most common and generic XML processing and modification utilities
 */
class XMLUtils{


	/**
	 * Append a SimpleXMLElement as a child of another one
	 *
	 * @param SimpleXMLElement $dest The xml that will have a new child node
	 * @param SimpleXMLElement $source The xml that will be added as a child to the dest one
	 *
	 * @return void
	 */
	public static function simpleXmlAppend(SimpleXMLElement $dest, SimpleXMLElement $source){

		$newDest = $dest->addChild($source->getName(), $source[0]);

		foreach ($source->attributes() as $name => $value){
			$newDest->addAttribute($name, $value);
		}

		foreach ($source->children() as $child){
			self::simpleXmlAppend($newDest, $child);
		}
	}


	/**
     * Convert an associative array to a SimpleXMLElement and add it as a child of another one
     *
     * @param SimpleXMLElement $simpleXMLElement the SimpleXMLElement that will be modified
     * @param array $array associative and bidimensional
     * @param string $itemTag the item tag which will be included to the SimpleXMLElement
     * @param string $parentTag the parent tag which will be included to the SimpleXMLElement
     *
     * @return SimpleXMLElement The SimpleXMLElement with the added child node as <simplexml><parentTag><itemTag>...
     */
	public static function simpleXmlAppendArray($simpleXMLElement, $array, $itemTag, $parentTag){

		$result = $simpleXMLElement->addChild($parentTag);

		$arrayCount = count($array);

    	for ($i = 0; $i < $arrayCount; $i++){

			$item = $result->addChild($itemTag);

			$arrayKeys = array_keys($array[$i]);

			foreach($arrayKeys as $key){

				$item->addChild($key, htmlspecialchars($array[$i][$key], ENT_QUOTES));
			}
		}

    	return $simpleXMLElement;
    }
}

?>