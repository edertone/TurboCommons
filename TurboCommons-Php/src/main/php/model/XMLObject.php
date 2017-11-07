<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use Exception;
use InvalidArgumentException;
use SimpleXMLElement;
use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\SerializationUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


// ******************************************************************************************************************
// TODO - NOT WORKING!!!!!!!!!!
// TODO - This class must be fully reviewed to synchronize it with the typescript version
// ******************************************************************************************************************

/**
 * Xml data abstraction
 */
class XMLObject{


    /**
     * Stores the internal SimpleXMLElement object instance
     *
     *@var SimpleXMLElement
     */
    private $_xml = null;


    /**
     * XMLObject stores all the information for an XML document and provides easy
     * access to all the attributes, elements and document data.
     *
     * @param string $string The textual representation of a valid xml document that will be parsed to construct an XMLObject. The smallest possible value for this parameter is a single root node like: '&lt;root/&gt;'
     *
     * @return XMLObject The constructed XMLObject
     */
    public function __construct($string){

        $previous = libxml_use_internal_errors(true);

        $this->_xml = simplexml_load_string($string);

        if($this->_xml === false) {

            libxml_clear_errors();

            throw new UnexpectedValueException('XMLObject->constructor a well formed xml string is required');
        }

        libxml_use_internal_errors($previous);
    }


    /**
     * Obtain the document root element name.<br>
     *
     * @example This method will output 'myNode' when called on the following XMLObject structure:
     * <br><br>&lt;myNode&gt;&lt;child1/&gt;&lt;child2/&gt;&lt;/myNode&gt;
     *
     * @return string The root xml element name<br>
     */
    public function getName(){

        return $this->_xml->getName();
    }


    /**
     * Obtain the document root element value as a string.<br>
     * Value of an xml element is the textual part that is located inside the element. Child elements are not considered
     * part of an element value.
     *
     * @example This method will output 'my value is this' when called on the following XMLObject structure:<br><br>
     * &lt;myNode&gt;my &lt;child1/&gt;value &lt;child2/&gt;is this&lt;/myNode&gt;
     *
     * @return string The root xml element value<br>
     */
    public function getValue(){

        return (string)$this->_xml;
    }


    /**
     * Get the number of attributes that are defined on the root element.<br>
     *
     * @example This method will output 3 when called on the following XMLObject structure:<br><br>
     * &lt;myNode a="1" b="2" c="3"/&gt;
     *
     * @return integer The number of root element attributes<br>
     */
    public function countAttributes(){

        return count($this->_xml->attributes());
    }


    /**
     * TODO
     * @return HashMapObject
     */
    public function getAttributes(){

        $result = new HashMapObject();

        foreach ($this->_xml->attributes() as $attribute => $value) {

            $result->set($attribute, $value);
        }

        return $result;
    }


    /**
     * TODO
     */
    public function countChildren(){

        return count($this->_xml->children());
    }


    /**
     * TODO
     */
    public function getChildren(){

        return $this->_xml->children();
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
    public function addChild($child){

        // Make sure both elements are SimpleXmlElement
        if(!self::isXML($this->_xml) || !self::isXML($child)){

            throw new InvalidArgumentException('XmlUtils->addChild parameters must be valid XML data');
        }

        if(is_string($child)){

            $child = SerializationUtils::stringToXml($child);
        }

        // Add the child element
        $newChild = $this->_xml->addChild($child->getName(), $child[0]);

        foreach ($child->attributes() as $name => $value){

            $newChild->addAttribute($name, $value);
        }

        // Recursively add all the child sub elements
        foreach ($child->children() as $c){

            self::addChild($newChild, $c);
        }
    }




    /**
     * Check if the provided object contains valid xml information.
     *
     * @param mixed $value Object to test for valid XML data. Accepted values are: Strings containing XML data or XMLObject elements
     *
     * @return boolean True if the received object represent valid XML data. False otherwise.
     */
    public static function isXML($value){

        if(is_string($value)){

            try {

                $value = SerializationUtils::stringToXmlObject($value);

            } catch (Exception $e) {

                return false;
            }
        }

        if(!is_object($value)){

            return false;
        }

        return (get_class($value) == 'org\\turbocommons\\src\\main\\php\\model\\XMLObject');
    }


    /**
     * Check if two provided xml structures represent the same data
     *
     * @param object $xml2 A valid string or XMLObject to compare with the other one
     * @param boolean $strictChildOrder Set it to true if both xml elements must have the children in the same order to be considered equal. False is the default value which means that having the same children in a different order accepted to consider the two elements equal.
     * @param boolean $strictAttributeOrder Same as $strictChildOrder but with xml attributes. Defaults to false.
     * @param boolean $ignoreCase Set it to true to ignore letter case when comparing the two elements (false by default).
     *
     * @return boolean true if the two xml elements are considered equal, false if not
     */
    public function isEqualTo($xml2, $strictChildOrder = false, $strictAttributeOrder = false, $ignoreCase = false){

        // Non xml elements must throw an exception
        if(!self::isXML($xml1) || !self::isXML($xml2)){

            throw new InvalidArgumentException('XmlUtils->isEqualTo parameters must contain valid xml data');
        }

        // Convert both elements to simplexml elements if strings are received
        if(is_string($xml1)){

            $xml1 = SerializationUtils::stringToXmlObject($xml1);
        }

        if(is_string($xml2)){

            $xml2 = SerializationUtils::stringToXmlObject($xml2);
        }

        // Check that the root element name and value is the same on both xmls
        $xml1RootName = ($ignoreCase) ? strtolower($xml1->getRootName()) : $xml1->getRootName();
        $xml2RootName = ($ignoreCase) ? strtolower($xml2->getRootName()) : $xml2->getRootName();
        $xml1RootValue = ($ignoreCase) ? strtolower($xml1->getRootValue()) : $xml1->getRootValue();
        $xml2RootValue = ($ignoreCase) ? strtolower($xml2->getRootValue()) : $xml2->getRootValue();

        if($xml1RootName != $xml2RootName || $xml1RootValue != $xml2RootValue){

            return false;
        }

        // Make sure the number of root element attributes is the same
        $xml1AttributesCount = count($xml1->getRootAttributes());
        $xml2AttributesCount = count($xml2->getRootAttributes());

        if($xml1AttributesCount != $xml2AttributesCount){

            return false;
        }

        // Check that all root element attributes are the same on both xmls
        $xml1Attributes = $xml1->getRootAttributes();
        $xml2Attributes = $xml2->getRootAttributes();

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
     * TODO
     */
    public function toString(){

        if(StringUtils::isEmpty($this->_xml)){

            return '';
        }

        return $this->_xml->asXML();
    }
}

?>