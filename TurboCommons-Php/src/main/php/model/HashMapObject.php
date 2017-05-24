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

use InvalidArgumentException;
use org\turbocommons\src\main\php\utils\StringUtils;
use Doctrine\Instantiator\Exception\UnexpectedValueException;


/**
 * An Object that defines a sorted collection of key/value pairs and all their related operations.
 */
class HashMapObject{


    /**
     * Sort mode that compares values as strings (alphabetically)
     */
    const SORT_STRING = 'SORT_STRING';


    /**
     * Sort mode that compares values as numbers (Avoid using it with non numeric values)
     */
    const SORT_NUMERIC = 'SORT_NUMERIC';


    /**
     * Structure that contains the HashMapObject data
     * @var array
     */
    private $_array = [];


    /**
     * Stores the number of elements inside the HashMapObject
     * @var integer
     */
    private $_length = 0;


    /**
     * Define a key / value pair and add it to the collection.
     * If the key already exists, value will be replaced.
     *
     * @param string $key A string that labels the provided value
     * @param mixed $value A value to be stored with the provided key
     *
     * @throws InvalidArgumentException If invalid key is provided
     * @return mixed The value after being stored to the collection
     */
    public function set($key, $value){

        if(!StringUtils::isString($key) || StringUtils::isEmpty($key)){

            throw new InvalidArgumentException('HashMapObject->set: key must be a string');
        }

        $this->_array[$key] = $value;

        $this->_length = count($this->_array);

        return $this->_array[$key];
    }


    /**
     * Get the number of key/value pairs that are currently stored on this HashMapObject instance
     *
     * @return integer The number of items inside the collection
     */
    public function length(){

        return $this->_length;
    }


    /**
     * Get the value that is associated to a key from an existing key/value pair
     *
     * @param string $key The key we are looking for
     *
     * @throws InvalidArgumentException If key does not exist or is invalid
     * @return mixed The value that is associated to the provided key
     */
    public function get($key){

        if(!StringUtils::isString($key) || StringUtils::isEmpty($key)){

            throw new InvalidArgumentException('HashMapObject->get: key must be a string');
        }

        if(!$this->isKey($key)){

            throw new InvalidArgumentException('HashMapObject->get: key does not exist');
        }

        return $this->_array[$key];
    }


    /**
     * Get a list with all the keys from the HashMapObject with the same order as they are stored.
     *
     * @return array List of strings containing all the HashMapObject sorted keys.
     */
    public function getKeys(){

        $result = [];
        $keys = array_keys($this->_array);

        // Keys must be strictly converted to strings
        foreach ($keys as $k) {
            $result[] = (string)$k;
        }

        return $result;
    }


    /**
     * Tells if the provided value matches a key that's stored inside the HashMapObject
     *
     * @param string $key A value to find on the currently stored keys.
     * @return boolean
     */
    public function isKey($key){

        return in_array($key, array_keys($this->_array));
    }


    /**
     * Delete a key/value pair from the HashMapObject, given it's key.
     *
     * @param string $key The key for the key/value pair we want to delete
     *
     * @throws InvalidArgumentException
     * @return mixed The value from the key/value pair that's been deleted.
     */
    public function remove($key){

        if(!StringUtils::isString($key) || StringUtils::isEmpty($key)){

            throw new InvalidArgumentException('HashMapObject->delete: key must be a string');
        }

        if($this->isKey($key)){

            $value = $this->_array[$key];

            unset($this->_array[$key]);

            $this->_length -= 1;

            return $value;

        }else{

            throw new InvalidArgumentException('HashMapObject->rename: key does not exist '.$key);
        }
    }


    /**
     * Change the name for an existing key
     *
     * @param string $key The name we want to change
     * @param string $newKey The new name that will replace the previous one
     *
     * @throws InvalidArgumentException
     * @return boolean True if rename was successful
     */
    public function rename($key, $newKey){

        if(!StringUtils::isString($key) || StringUtils::isEmpty($key)){

            throw new InvalidArgumentException('HashMapObject->rename: key must be a string');
        }

        if(!StringUtils::isString($newKey) || StringUtils::isEmpty($newKey)){

            throw new InvalidArgumentException('HashMapObject->rename: newKey must be a string');
        }

        if($this->isKey($key)){

            $result = [];
            $keys = $this->getKeys();

            foreach ($keys as $k) {

                if($k == $key){

                    $result[$newKey] = $this->_array[$key];

                }else{

                    $result[$k] = $this->_array[$k];
                }
            }

            $this->_array = $result;

            return true;

        }else{

            throw new InvalidArgumentException('HashMapObject->rename: key does not exist '.$key);
        }
    }


    /**
     * Exchange the positions for two key/value pairs on the HashMapObject sorted elements list
     *
     * @param string $key1 The first key to exchange
     * @param string $key2 The second key to exchange
     *
     * @return boolean True if the two key/value pairs positions were correctly exchanged
     * @throws InvalidArgumentException If any of the two provided keys does not exist or is invalid
     */
    public function swap($key1, $key2){

        if(!StringUtils::isString($key1) || StringUtils::isEmpty($key1)){

            throw new InvalidArgumentException('HashMapObject->swap: key1 must be a string');
        }

        if(!StringUtils::isString($key2) || StringUtils::isEmpty($key2)){

            throw new InvalidArgumentException('HashMapObject->swap: key2 must be a string');
        }

        if(!$this->isKey($key1)){

            throw new InvalidArgumentException('HashMapObject->swap: key1 does not exist '.$key1);
        }

        if(!$this->isKey($key2)){

            throw new InvalidArgumentException('HashMapObject->swap: key2 does not exist '.$key2);
        }

        $result = [];
        $keys = $this->getKeys();
        $key1Value = $this->get($key1);
        $key2Value = $this->get($key2);

        foreach ($keys as $k) {

            switch ($k) {

                case $key1:
                    $result[$key2] = $key2Value;
                    break;

                case $key2:
                    $result[$key1] = $key1Value;
                    break;

                default:
                    $result[$k] = $this->_array[$k];
                    break;
            }
        }

        $this->_array = $result;

        return true;
    }


    /**
     * Sort the key/value pairs inside the HashMapObject by their key values.
     *
     * @param string $method Defines sort mode: HashMapObject::SORT_STRING or HashMapObject::SORT_NUMERIC
     *
     * @throws InvalidArgumentException
     * @return boolean True if sort was successful
     */
    public function sortByKey($method = self::SORT_STRING){

        if($method === self::SORT_STRING){

            ksort($this->_array, SORT_STRING);
            return true;
        }

        if($method === self::SORT_NUMERIC){

            ksort($this->_array, SORT_NUMERIC);
            return true;
        }

        throw new InvalidArgumentException('HashMapObject->sortByKey: Unknown sort method');
    }


    /**
     * Sort the key/value pairs inside the HashMapObject by their values.
     *
     * @param string $method Defines sort mode: HashMapObject::SORT_STRING or HashMapObject::SORT_NUMERIC
     *
     * @throws InvalidArgumentException
     * @return boolean True if sort was successful
     */
    public function sortByValue($method = self::SORT_NUMERIC){

        if($method === self::SORT_STRING){

            asort($this->_array, SORT_STRING);
            return true;
        }

        if($method === self::SORT_NUMERIC){

            asort($this->_array, SORT_NUMERIC);
            return true;
        }

        throw new InvalidArgumentException('HashMapObject->sortByValue: Unknown sort method');
    }


    /**
     * Remove and get the first element value from the HashMapObject sorted list
     *
     * @throws UnexpectedValueException If the HashMapObject is empty
     * @return mixed The value on the first element of the list
     */
    public function shift(){

        if($this->_length <= 0){

            throw new UnexpectedValueException('HashMapObject->shift: No elements');
        }

        $this->_length -= 1;

        return array_shift($this->_array);
    }


    /**
     * Remove and get the last element value from the HashMapObject sorted list
     *
     * @throws UnexpectedValueException If the HashMapObject is empty
     * @return mixed The value on the last element of the list
     */
    public function pop(){

        if($this->_length <= 0){

            throw new UnexpectedValueException('HashMapObject->pop: No elements');
        }

        $this->_length -= 1;

        return array_pop($this->_array);
    }


    /**
     * Reverse the order of the HashMapObject elements
     *
     * @return void
     */
    public function reverse(){

        $this->_array = array_reverse($this->_array);

        return true;
    }
}

?>