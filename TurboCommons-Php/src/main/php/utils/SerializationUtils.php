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

use UnexpectedValueException;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\model\XMLObject;
use org\turbocommons\src\main\php\model\HashMapObject;



// ******************************************************************************************************************
// TODO - NOT WORKING!!!!!!!!!!
// TODO - This class must be fully reviewed
// ******************************************************************************************************************



/**
 * Contains methods that allow us to convert data from one data format or type to another data format or type
 */
class SerializationUtils{


    /**
     * Stores key/value data from an arbitrary object to a class instance.
     * Class property values will be set to the hash map values which key is the same as the property name.
     * Class properties that are not found on the given hash will remain untouched.
     *
     * @param HashMapObject $hashMap An object that contains data which is organized as a hash map. For example: An associative array or an object with key / value pairs
     * @param Object $classInstance A class instance that will be filled with all the values that are found on the hash.
     *
     * @return mixed The provided class instance filled with all the hash values that match key = property name
     */
    public static function hashMapObjectToClass(HashMapObject $hashMap, $classInstance){

        foreach($hashMap as $key => $value){

            if(property_exists($classInstance, $key)){

                $classInstance->{$key} = $value;
            }
        }

        return $classInstance;
    }


    /**
     * Convert a JavaPropertiesObject instance to a string that is valid so it can be saved to a .properties object.
     *
     * @param JavaPropertiesObject $javaProperties An instance of a JavaPropertiesObject
     *
     * @return string An ISO-8859-1 string containing valid properties data, ready to be stored as a .properties java format file.
     */
    public static function javaPropertiesObjectToString(JavaPropertiesObject $javaProperties){

        return $javaProperties->toString();
    }


    /**
     * Convert a string containing the contents of a Java properties file to a JavaPropertiesObject instance
     * For example: tag1=value1 will be converted to ['tag1' => 'value1'].<br><br>
     * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results may not be correct).
     *
     * @param string $string String containing the contents of a .properties Java file
     *
     * @return JavaPropertiesObject The properties format parsed as an object
     */
    public static function stringToJavaPropertiesObject($string){

        return new JavaPropertiesObject($string);
    }


    /**
     * Convert a string containing a well formed XML structure to an XMLObject instance
     *
     * @param string $string A string containing xml data
     *
     * @return XMLObject The representation of the given string as an xml object
     */
    public static function stringToXmlObject($string){

        if(StringUtils::isEmpty($string)){

            throw new UnexpectedValueException('SerializationUtils->stringToXmlObject Empty string is not a valid xml value');
        }

        return new XMLObject($string);
    }


    /**
     * Convert an Xml object to its string representation
     *
     * @param XMLObject $xml An xml object instance
     *
     * @return string The textual valid representation of the given xml object
     */
    public static function xmlObjectToString(XMLObject $xml){

        return $xml->toString();
    }
}

?>