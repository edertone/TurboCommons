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
use InvalidArgumentException;
use SimpleXMLElement;


/**
 * Common operations related to xml manipulation
 */
class XmlUtils{


	/**
	 * Check if the provided object contains valid xml information.
	 *
	 * @param object $value Object to test for valid XML data. Accepted values are: Strings containing XML data and SimpleXMLElements
	 *
	 * @return boolean True if the received object represent valid XML data. False otherwise.
	 */
	public static function isValidXML($value){

		if(is_string($value)){

			try {

				$value = SerializationUtils::stringToXml($value);

			} catch (Exception $e) {

				return false;
			}
		}

		if(!is_object($value)){

			return false;
		}

		return get_class($value) == 'SimpleXMLElement';
	}


	/**
	 * Check if two provided xml structures represent the same data
	 *
	 * @param object $xml1 A valid string or xml object to compare with the other one
	 * @param object $xml2 A valid string or xml object to compare with the other one
	 * @param boolean $strictChildOrder Set it to true if both xml elements must have the children in the same order to be considered equal. False is the default value which means that having the same children in a different order accepted to consider the two elements equal.
	 * @param boolean $strictAttributeOrder Same as $strictChildOrder but with xml attributes. Defaults to false.
	 * @param boolean $ignoreCase Set it to true to ignore letter case when comparing the two elements (false by default).
	 *
	 * @return boolean true if the two xml elements are considered equal, false if not
	 */
	public static function isEqualTo($xml1, $xml2, $strictChildOrder = false, $strictAttributeOrder = false, $ignoreCase = false){

		// Non xml elements must throw an exception
		if(!self::isValidXML($xml1) || !self::isValidXML($xml2)){

			throw new InvalidArgumentException('XmlUtils->isEqualTo parameters must contain valid xml data');
		}

		// Convert both elements to simplexml elements if strings are received
		if(is_string($xml1)){

			$xml1 = SerializationUtils::stringToXml($xml1);
		}

		if(is_string($xml2)){

			$xml2 = SerializationUtils::stringToXml($xml2);
		}

		// Check that the root element name and value is the same on both xmls
		$xml1RootName = ($ignoreCase) ? strtolower($xml1->getName()) : $xml1->getName();
		$xml2RootName = ($ignoreCase) ? strtolower($xml2->getName()) : $xml2->getName();
		$xml1RootValue = ($ignoreCase) ? strtolower((string)$xml1) : (string)$xml1;
		$xml2RootValue = ($ignoreCase) ? strtolower((string)$xml2) : (string)$xml2;

		if($xml1RootName != $xml2RootName || $xml1RootValue != $xml2RootValue){

			return false;
		}

		// Make sure the number of root element attributes is the same
		$xml1AttributesCount = count($xml1->attributes());
		$xml2AttributesCount = count($xml2->attributes());

		if($xml1AttributesCount != $xml2AttributesCount){

			return false;
		}

		// Check that all root element attributes are the same on both xmls
		$xml1Attributes = $xml1->attributes();
		$xml2Attributes = $xml2->attributes();

		for ($i = 0; $i < $xml1AttributesCount; $i++) {

			$xml1AttributeName = ($ignoreCase) ? strtolower($xml1Attributes[$i]->getName()) : $xml1Attributes[$i]->getName();
			$xml1AttributeValue = ($ignoreCase) ? strtolower((string)$xml1Attributes[$i]) : (string)$xml1Attributes[$i];

			if($strictAttributeOrder){

				$xml2AttributeName = ($ignoreCase) ? strtolower($xml2Attributes[$i]->getName()) : $xml2Attributes[$i]->getName();
				$xml2AttributeValue = ($ignoreCase) ? strtolower((string)$xml2Attributes[$i]) : (string)$xml2Attributes[$i];

				if($xml1AttributeName != $xml2AttributeName || $xml1AttributeValue != $xml2AttributeValue){

					return false;
				}

			}else{

				$attributeFound = false;

				for ($j = 0; $j < $xml2AttributesCount; $j++) {

					$xml2AttributeName = ($ignoreCase) ? strtolower($xml2Attributes[$j]->getName()) : $xml2Attributes[$j]->getName();
					$xml2AttributeValue = ($ignoreCase) ? strtolower((string)$xml2Attributes[$j]) : (string)$xml2Attributes[$j];

					if($xml1AttributeName == $xml2AttributeName && $xml1AttributeValue == $xml2AttributeValue){

						$attributeFound = true;
						break;
					}
				}

				if(!$attributeFound){

					return false;
				}
			}
		}

		// Make sure the number of children is the same
		$xml1ChildrenCount = $xml1->count();
		$xml2ChildrenCount = $xml2->count();

		if($xml1ChildrenCount != $xml2ChildrenCount){

			return false;
		}

		// Loop all child elements and check that they are also equal
		$xml1Children = $xml1->children();
		$xml2Children = $xml2->children();

		for ($i = 0; $i < $xml1ChildrenCount; $i++) {

			if($strictChildOrder){

				if(!self::isEqualTo($xml1Children[$i], $xml2Children[$i], $strictChildOrder, $strictAttributeOrder, $ignoreCase)){

					return false;
				}

			}else{

				$childFound = false;

				for ($j = 0; $j < $xml2ChildrenCount; $j++) {

					if(self::isEqualTo($xml1Children[$i], $xml2Children[$j], $strictChildOrder, $strictAttributeOrder, $ignoreCase)){

						$childFound = true;
						break;
					}
				}

				if(!$childFound){

					return false;
				}
			}
		}

		return true;
	}


	/**
	 * Append an xml element as child to another one, directly under the root node after the last one of its children (if any).
	 * NOTE: Parent element that is provided as parameter will be modified after this method is called.
	 *
	 * @param SimpleXMLElement $parent An xml element that will be modified to have the new child apended after the last of its children.
	 * @param string|SimpleXMLElement $child An xml element that will be added as child to the specified parent. This parameter can be provided as a SimpleXMLElement or a valid xml string
	 *
	 * @return SimpleXMLElement The modified parent object containing the provided child element
	 */
	public static function addChild(SimpleXMLElement $parent, $child){

		// Make sure both elements are SimpleXmlElement
		if(!self::isValidXML($parent) || !self::isValidXML($child)){

			throw new InvalidArgumentException('XmlUtils->addChild parameters must be valid XML data');
		}

		if(is_string($child)){

			$child = SerializationUtils::stringToXml($child);
		}

		// Add the child element
		$newChild = $parent->addChild($child->getName(), $child[0]);

		foreach ($child->attributes() as $name => $value){

			$newChild->addAttribute($name, $value);
		}

		// Recursively add all the child sub elements
		foreach ($child->children() as $c){

			self::addChild($newChild, $c);
		}

		return $parent;
	}
}

?>