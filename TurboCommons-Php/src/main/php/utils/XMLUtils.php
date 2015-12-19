<?php

/**
 * TurboCommons-Php
 *
 * PHP Version 5.4
 *
 * @copyright 2015 Edertone advanced solutions (http://www.edertone.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://turbocommons.org
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