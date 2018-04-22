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

use org\turbocommons\src\main\php\model\JavaPropertiesObject;


/**
 * Contains methods that allow us to convert data from one complex data structure
 * format to another complex data structure format
 */
class SerializationManager{


    /**
     * When set to true, the structures that are passed as serialization sources must match the structures
     * that are passed as serialization targets: All keys or properties that are defined on the serialization sources
     * must exist on the serialization targets, otherwise an exception will be thrown
     */
    public $strictMode = true;


    /**
     * TODO - review from TS
     */
    public function classToJson(){

        // TODO - review from TS
    }


    /**
     * TODO - review from TS
     */
    public function classToObject(){

        // TODO - review from TS
    }


    /**
     * TODO - review from TS
     */
    public function hashMapObjectToClass(){

        // TODO - review from TS
    }


    /**
     * Convert a JavaPropertiesObject instance to a string that is valid so it can be saved to a .properties file.
     *
     * @param JavaPropertiesObject $javaProperties An instance of a JavaPropertiesObject
     *
     * @return string An ISO-8859-1 string containing valid properties data, ready to be stored as a .properties java format file.
     */
    public function javaPropertiesObjectToString(JavaPropertiesObject $javaProperties){

        return $javaProperties->toString();
    }


    // TODO
    public function jsonToClass(){

        // TODO - review from TS
    }


    // TODO
    public function objectToClass(){

        // TODO - review from TS
    }


    /**
     * Convert a string containing the contents of a Java properties file to a JavaPropertiesObject instance
     * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results may not be correct).
     *
     * @param string $string String containing the contents of a .properties Java file
     *
     * @return JavaPropertiesObject The properties format parsed as an object
     */
    public function stringToJavaPropertiesObject($string){

        return new JavaPropertiesObject($string);
    }
}

?>