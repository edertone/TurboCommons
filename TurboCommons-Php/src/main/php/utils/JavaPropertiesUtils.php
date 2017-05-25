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
use UnexpectedValueException;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;


/**
 * Utilities to perform common java properties data format operations
 */
class JavaPropertiesUtils {


    /**
     * Tells if the given value contains valid Java Properties data information or not
     *
     * @param mixed $value A value to check (a string or a JavaPropertiesObject instance)
     *
     * @return boolean true if the given value contains valid Java Properties data, false otherwise
     */
    public static function isJavaProperties($value){

        if(StringUtils::isString($value)){

            if(StringUtils::isEmpty($value)){

                return false;
            }

            // test that received string contains valid properties info
            try {

                $p = new JavaPropertiesObject($value);

                return $p->length() >= 0;

            } catch (Exception $e) {

                return false;
            }
        }

        try {

            return get_class($value) === 'org\turbocommons\src\main\php\model\JavaPropertiesObject';

        } catch (Exception $e) {

            return false;
        }
    }


    /**
     * Check if two provided java properties are identical.
     * Only data is compared: Any comment that is found on both provided properties will be ignored.
     *
     * @param mixed $properties1 First java properties value to compare (a string or a JavaPropertiesObject instance)
     * @param mixed $properties2 Second java properties value to compare (a string or a JavaPropertiesObject instance)
     * @param boolean $strictOrder If set to true, both properties elements must have the same keys with the same order. Otherwise differences in key sorting will be accepted
     *
     * @return boolean true if both java properties data is exactly the same, false if not
     */
    public static function isEqualTo($properties1, $properties2, $strictOrder = false){

        if(!JavaPropertiesUtils::isJavaProperties($properties1)){

            throw new UnexpectedValueException('JavaPropertiesUtils->isEqualTo properties1 does not contain valid java properties data');
        }

        if(!JavaPropertiesUtils::isJavaProperties($properties2)){

            throw new UnexpectedValueException('JavaPropertiesUtils->isEqualTo properties2 does not contain valid java properties data');
        }

        $object1 = StringUtils::isString($properties1) ? new JavaPropertiesObject($properties1) : $properties1;

        $object2 = StringUtils::isString($properties2) ? new JavaPropertiesObject($properties2) : $properties2;

        $object1Keys = $object1->getKeys();
        $object2Keys = $object2->getKeys();

        $validationManager = new ValidationManager();

        if(count($object1Keys) != count($object2Keys) || ($strictOrder && !ArrayUtils::isEqualTo($object1Keys, $object2Keys))){

            return false;
        }

        foreach ($object1Keys as $key1) {

            if(!$object2->isKey($key1)){

                return false;
            }

            if(!$validationManager->isEqualTo($object1->get($key1), $object2->get($key1))){

                return false;
            }
        }

        return true;
    }
}

?>