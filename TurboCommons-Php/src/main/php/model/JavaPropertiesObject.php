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

use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\EncodingUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * Object that stores java properties file format data
 */
class JavaPropertiesObject extends HashMapObject {


    /**
     * Create a JavaPropertiesObject instance. Java properties is a text file format that stores data
     * into text files with information that is arranged as key/value pairs.
     * For example: tag1=value1
     *
     * @param string $string String containing the contents of a .properties Java file.
     * Note that string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results won't be correct).
     *
     * @see HashMapObject
     * @return JavaPropertiesObject The java properties object with data accessible as key/value pairs.
     */
    public function __construct($string = ''){

        if(!StringUtils::isString($string)){

            throw new UnexpectedValueException('JavaPropertiesObject->__construct value must be a string');
        }

        if(StringUtils::isEmpty($string)){

            return;
        }

        // String must contain at least = or :
        if(strpos($string, '=') === false && strpos($string, ':') === false){

            throw new UnexpectedValueException('JavaPropertiesObject->__construct invalid properties format');
        }

        $key = '';
        $value = '';
        $isWaitingOtherLine = false;

        // Generate an array with the properties lines, ignoring blank lines and comments
        $lines = StringUtils::getLines($string, ['/\s+/', '/ *#.*| *!.*/']);

        foreach($lines as $i=>$line) {

            // Remove all blank spaces at the beginning of the line
            $line = ltrim($line);

            if($isWaitingOtherLine) {

                $value .= EncodingUtils::unicodeEscapedCharsToUtf8($line);

            }else{

                // Find the key/value divider index
                $tmpLine = str_replace(['\\=', '\\:'], 'xx', $line);
                $keyDividerIndex = min([stripos($tmpLine.'=', '='), stripos($tmpLine.':', ':')]);

                // Extract the key from the line
                $key = trim(substr($line, 0, $keyDividerIndex));

                // Add a space to the end if the last character is a \
                if(substr($key, strlen($key) - 1, 1) === '\\'){

                    $key .= ' ';
                }

                $key = str_replace(['\\\\', '\\ ', '\#', '\!', '\=', '\:'], ['\\', ' ', '#', '!', '=', ':'], $key);

                // Extract the value from the line
                $value = ltrim(substr($line, $keyDividerIndex + 1, strlen($line)));
            }

            // Unescape escaped slashes and spaces on the value
            $value = str_replace(['\\\\', '\\ ', '\\r\\n', '\\n', '\\t'], ['\u005C', ' ', "\r\n", "\n", "\t"], $value);

            // Check if ends with single '\'
            if(substr($value, -1) == '\\'){

                // Remove trailing backslash
                $value = substr_replace($value, '', -1);

                $isWaitingOtherLine = true;

            }else{

                $isWaitingOtherLine = false;

                // Decode unicode characters
                $value = EncodingUtils::unicodeEscapedCharsToUtf8($value);
            }

            $this->set($key, $value);

            unset($lines[$i]);
        }

        if($this->length() <= 0){

            throw new UnexpectedValueException('JavaPropertiesObject->__construct string does not contain valid java properties data');
        }
    }


    /**
     * Generate the textual representation for the java properties data stored on this object.
     * The output of this method is ready to be stored on a physical .properties file.
     *
     * @return string A valid Java .properties string ready to be stored on a .properties file
     */
    public function toString(){

        $result = [];
        $keys = $this->getKeys();
        $keysCount = count($keys);

        for ($i = 0; $i < $keysCount; $i++) {

            $key = str_replace(['\\', ' ', '#', '!', '=', ':'], ['\\\\', '\\ ', '\#', '\!', '\=', '\:'], $keys[$i]);

            $value = str_replace(['\\', ' ', "\r\n", "\n", "\t"], ['\\\\', '\\ ', '\\r\\n', '\\n', '\\t'], $this->get($keys[$i]));

            $result[] = $key.'='.EncodingUtils::utf8ToUnicodeEscapedChars($value);
        }

        return implode("\r\n", $result);
    }
}

?>