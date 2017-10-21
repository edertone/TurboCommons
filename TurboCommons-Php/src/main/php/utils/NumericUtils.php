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

        throw new UnexpectedValueException('NumericUtils->getNumeric : Provided value is not numeric');
    }


    /**
     * Generate a random integer
     *
     * @param int $max highest value to be returned
     * @param int $min lowest value to be returned (default: 0)
     *
     * @return int A random integer value between min (or 0) and max
     * @throws UnexpectedValueException if max is equal or less than min.
     */
    public static function generateRandomInteger($max, $min = 0){

        if(!self::isInteger($max) || $max < 0 || !self::isInteger($min) || $min < 0){

            throw new UnexpectedValueException('NumericUtils->generateRandomInteger : Provided max and min must be positive integers');
        }

        if($max <= $min){

            throw new UnexpectedValueException('NumericUtils->generateRandomInteger : Provided max must be higher than min');
        }

        return mt_rand($min, $max);
    }
}

?>