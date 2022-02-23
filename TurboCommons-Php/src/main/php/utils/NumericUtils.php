<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use UnexpectedValueException;
use InvalidArgumentException;


/**
 * Common operations and tools related with numbers
 */
class NumericUtils {


    /**
     * Tells if the given value is numeric or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is numeric or represents a numeric value, false otherwise
     */
    public static function isNumeric($value){

        if(is_string($value)){

            $value = trim($value);
        }

        return is_numeric($value);
    }


    /**
     * Tells if the given value is a numeric integer or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is a numeric integer or represents a numeric integer value, false otherwise
     */
    public static function isInteger($value){

        if(!self::isNumeric($value)){

            return false;
        }

        return strpos((string) $value, '.') === false;
    }


    /**
     * Strictly check that the provided value is numeric or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forceNumeric($value, string $valueName = '', string $errorMessage = 'must be numeric'){

        if(!self::isNumeric($value)){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Strictly check that the provided value is a positive integer or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forcePositiveInteger($value, string $valueName = '', string $errorMessage = 'must be a positive integer'){

        if(!self::isInteger($value) || $value <= 0){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Get the number represented by the given value
     *
     * @param mixed $value A value to convert to a number
     *
     * @return number The numeric type representation from the given value. For example, a string '0001' will return 1
     */
    public static function getNumeric($value){

        if(self::isNumeric($value)){

            return trim($value) + 0;
        }

        throw new UnexpectedValueException('value is not numeric');
    }


    /**
     * Generate a random integer between the specified range (both extremes are included).
     *
     * @param int $min lowest possible value (negative values are allowed)
     * @param int $max highest possible value (negative values are allowed)
     *
     * @throws UnexpectedValueException if max is equal or less than min.
     *
     * @return int A random integer value between min and max
     */
    public static function generateRandomInteger($min, $max){

        if(!self::isInteger($max) || !self::isInteger($min)){

            throw new UnexpectedValueException('max and min must be integers');
        }

        if($max <= $min){

            throw new UnexpectedValueException('max must be higher than min');
        }

        return floor((mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax()) * ($max - $min + 1)) + $min;
    }
}

?>