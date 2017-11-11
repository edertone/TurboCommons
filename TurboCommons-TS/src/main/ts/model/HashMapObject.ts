/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from "../utils/StringUtils";
import { ObjectUtils } from "../utils/ObjectUtils";
import { ArrayUtils } from "../utils/ArrayUtils";

    
/**
 * HashMapObject abstraction
 */
export class HashMapObject {
    
    /**
     * Sort mode that compares values as strings (alphabetically)
     */
    static readonly SORT_METHOD_STRING = 'SORT_METHOD_STRING';
    
    
    /**
     * Sort mode that compares values as numbers (Avoid using it with non numeric values)
     */
    static readonly SORT_METHOD_NUMERIC = 'SORT_METHOD_NUMERIC';
    
    
    /**
     * Defines that elements will be sorted upward
     */
    static readonly SORT_ORDER_ASCENDING = 'SORT_ORDER_ASCENDING';
    
    
    /**
     * Defines that elements will be sorted downward
     */
    static readonly SORT_ORDER_DESCENDING = 'SORT_ORDER_DESCENDING';
    
    
    /**
     * Structure that contains the HashMapObject data
     */
    protected _data: {[key:string] : any} = {};
    
    
    /**
     * Stores the number of elements inside the HashMapObject
     */
    protected _length = 0;
    
    
    /**
     * An Object that defines a sorted collection of key/value pairs and all their related operations.
     *
     * @param data A value that will be used to initialize the HashMapObject. It can be an object instance 
     * (where each key/value will be directly assigned to the HashMap), or a plain array in which case the keys will be
     * created from each element numeric index
     */
    constructor(data: any = null){
        
        if(data == null){
            
            return;
        }
        
        if(ObjectUtils.isObject(data)){

            for (var key in data) {

                this.set(String(key), data[key]);
            }

        }else{
            
            if(ArrayUtils.isArray(data)){
                
                let dataCount = data.length;
                
                for (var i = 0; i < dataCount; i++) {

                    this.set(String(i), data[i]);
                }
            
            }else{

                throw new Error('HashMapObject: invalid data');
            }
        }        
    }
    
    
    /**
     * Define a key / value pair and add it to the collection.
     * If the key already exists, value will be replaced.
     *
     * @param key A string that labels the provided value
     * @param value A value to be stored with the provided key
     *
     * @return The value after being stored to the collection
     */
    set(key:string, value: any){
    
        // Check if key is a non empty string.
        // We use the same logic as StringUtils.isEmpty but with some simplification for better performance
        // This should be a call to this._validateKeyFormat but we inline it here to get a big performance improvement
        if(StringUtils.isString(key) && key.replace(/ |\n|\r|\t/gi, '') !== ''){
    
            this._data[key] = value;
    
            this._length = ObjectUtils.getKeys(this._data).length;
    
            return value;
        }
    
        throw new Error('HashMapObject: key must be a non empty string');
    }
    
    
    /**
     * Get the number of key/value pairs that are currently stored on this HashMapObject instance
     *
     * @return integer The number of items inside the collection
     */
    length(){

        return this._length;
    }
    
    
    /**
     * Get the value that is associated to a key from an existing key/value pair
     *
     * @param key The key we are looking for
     *
     * @throws error If key does not exist or is invalid
     * @return The value that is associated to the provided key
     */
    get(key: string){

        if(this._data.hasOwnProperty(key)){

            return this._data[key];
        }

        throw new Error('HashMapObject->get: key does not exist or is invalid');
    }
    
    
    /**
     * Get a list with all the keys from the HashMapObject with the same order as they are stored.
     *
     * @return List of strings containing all the HashMapObject sorted keys.
     */
    getKeys(){

        let result = [];
        let keys =  ObjectUtils.getKeys(this._data);

        // Keys must be strictly converted to strings
        for (var i = 0; i < keys.length; i++) {
	
            result.push(keys[i]);
        }

        return result;
    }
    
    
    /**
     * Get a list with all the values from the HashMapObject with the same order as they are stored.
     *
     * @return List of elements containing all the HashMapObject sorted values
     */
    getValues(){

        let result = [];
        let keys = this.getKeys();

        // Keys must be strictly converted to strings
        for (var i = 0; i < keys.length; i++) {
	
            result.push(this.get(keys[i]));
        }

        return result;
    }
    
    
    /**
     * Tells if the provided value matches a key that's stored inside the HashMapObject
     *
     * @param A value to find on the currently stored keys.
     *
     * @return True if the provided value is a valid HashMap key, false in any other case
     */
    isKey(key: any){

        return StringUtils.isString(key) && this._data.hasOwnProperty(key);
    }
    
    
    /**
     * Delete a key/value pair from the HashMapObject, given it's key.
     *
     * @param key The key for the key/value pair we want to delete
     *
     * @throws Error
     * @return The value from the key/value pair that's been deleted.
     */
    remove(key: any){

        if(this._data.hasOwnProperty(key)){

            let value = this._data[key];

            delete this._data[key];

            this._length -= 1;

            return value;
        }

        this._validateKeyFormat(key);

        throw new Error('HashMapObject->rename: key does not exist ' + key);
    }
    
    
    /**
     * Change the name for an existing key
     *
     * @param key The name we want to change
     * @param newKey The new name that will replace the previous one
     *
     * @throws Error
     * @return True if rename was successful
     */
    rename(key: any, newKey: any){

        this._validateKeyFormat(key);
        this._validateKeyFormat(newKey);

        if(this.isKey(newKey)){

            throw new Error('HashMapObject->rename: newKey ' + newKey + ' already exists');
        }

        if(this.isKey(key)){

            let result: {[key:string] : any} = {};
            let keys = this.getKeys();

            for (var i = 0; i < keys.length; i++) {

                if(keys[i] == key){

                    result[newKey] = this._data[key];

                }else{

                    result[keys[i]] = this._data[keys[i]];
                }
            }

            this._data = result;

            return true;

        }else{

            throw new Error('HashMapObject->rename: key does not exist ' + key);
        }
    }
    
    
    /**
     * Exchange the positions for two key/value pairs on the HashMapObject sorted elements list
     *
     * @param key1 The first key to exchange
     * @param key2 The second key to exchange
     *
     * @return True if the two key/value pairs positions were correctly exchanged
     * @throws Error If any of the two provided keys does not exist or is invalid
     */
    swap(key1: string, key2: string){

        this._validateKeyFormat(key1);
        this._validateKeyFormat(key2);

        if(!this.isKey(key1)){

            throw new Error('HashMapObject->swap: key1 does not exist ' + key1);
        }

        if(!this.isKey(key2)){

            throw new Error('HashMapObject->swap: key2 does not exist ' + key2);
        }

        let result: {[key:string] : any} = {};
        let keys = this.getKeys();
        let key1Value = this.get(key1);
        let key2Value = this.get(key2);

        for (var i = 0; i < keys.length; i++) {

            switch (keys[i]) {

                case key1:
                    result[key2] = key2Value;
                    break;

                case key2:
                    result[key1] = key1Value;
                    break;

                default:
                    result[keys[i]] = this._data[keys[i]];
                    break;
            }
        }

        this._data = result;

        return true;
    }
    
    
    /**
     * Sort the key/value pairs inside the HashMapObject by their key values.
     *
     * @param method Defines sort mode: HashMapObject.SORT_STRING or HashMapObject.SORT_NUMERIC
     * @param order Defines the order for the sorted elements: HashMapObject.SORT_ORDER_ASCENDING (default) or HashMapObject.SORT_ORDER_DESCENDING
     *
     * @throws Error
     * @return True if sort was successful false on failure
     */
    sortByKey(method = HashMapObject.SORT_METHOD_STRING, order = HashMapObject.SORT_ORDER_ASCENDING){

        let methodFlag = null;

        if(method === HashMapObject.SORT_METHOD_STRING){

            methodFlag = undefined;
        }

        if(method === HashMapObject.SORT_METHOD_NUMERIC){

            methodFlag = null; //function(a, b){return a-b};
        }

        if(methodFlag === null){

            throw new Error('HashMapObject->sortByKey: Unknown sort method');
        }

        if(order === HashMapObject.SORT_ORDER_ASCENDING){

            return this._data.sort(methodFlag);
        }

        if(order === HashMapObject.SORT_ORDER_DESCENDING){

            // TODO return krsort($this->_data, $methodFlag);
        }

        throw new Error('HashMapObject->sortByKey: Unknown sort order');
    }
    
    
    /**
     * Sort the key/value pairs inside the HashMapObject by their values.
     * Note that applying a sort method on values with different types than the expected by the sort method will give unexpected results.
     *
     * @param method Defines sort mode: HashMapObject.SORT_STRING or HashMapObject.SORT_NUMERIC
     * @param order Defines the order for the sorted elements: HashMapObject.SORT_ORDER_ASCENDING (default) or HashMapObject.SORT_ORDER_DESCENDING
     *
     * @throws Error
     * @return True if sort was successful
     */
    sortByValue(method = HashMapObject.SORT_METHOD_NUMERIC, order = HashMapObject.SORT_ORDER_ASCENDING){

        let methodFlag = null;

        if(method === HashMapObject.SORT_METHOD_STRING){

            methodFlag = "SORT_STRING";
        }

        if(method === HashMapObject.SORT_METHOD_NUMERIC){

            methodFlag = "SORT_NUMERIC";
        }

        if(methodFlag === null){

            throw new Error('HashMapObject->sortByValue: Unknown sort method');
        }

        if(order === HashMapObject.SORT_ORDER_ASCENDING){

            // TODO return asort($this->_data, $methodFlag);
        }

        if(order === HashMapObject.SORT_ORDER_DESCENDING){

            // TODO return arsort($this->_data, $methodFlag);
        }

        throw new Error('HashMapObject->sortByValue: Unknown sort order');
    }
    
    
    /**
     * Remove and get the first element value from the HashMapObject sorted list
     *
     * @throws Error If the HashMapObject is empty
     * @return The value on the first element of the list
     */
    shift(){

        if(this._length <= 0){

            throw new Error('HashMapObject->shift: No elements');
        }

        this._length -= 1;

        var result = this._data[this.getKeys()[0]];
        
        delete this._data[this.getKeys()[0]];
        
        return result;
        
     // TODO - el ordre en objects no esta garantit. caldra guardar array amb keys i treballar-hi
    }


    /**
     * Remove and get the last element value from the HashMapObject sorted list
     *
     * @throws Error If the HashMapObject is empty
     * @return The value on the last element of the list
     */
    pop(){

        if(this._length <= 0){

            throw new Error('HashMapObject->pop: No elements');
        }

        this._length -= 1;

        var result = this._data[this.getKeys()[this._length]];
        
        delete this._data[this.getKeys()[this._length]];
        
        return result;
        
        // TODO - el ordre en objects no esta garantit. caldra guardar array amb keys i treballar-hi
    }


    /**
     * Reverse the order of the HashMapObject elements
     *
     * @return void
     */
    reverse(){

     // TODO - aixo no funcionara this._data = array_reverse($this->_data);

        return true;
    }


    /**
     * Checks that specified key value has a valid format (Non empty string)
     *
     * @param key The key value to test
     *
     * @throws Error
     *
     * @return void
     */
    _validateKeyFormat(key: any){
        
        // Check if key is a non empty string.
        // We use the same logic as StringUtils.isEmpty but with some simplification for better performance
        if(!StringUtils.isString(key) || key.replace(/ |\n|\r|\t/gi, '') == ''){

            throw new Error('HashMapObject: key must be a non empty string');
        }
    }
}