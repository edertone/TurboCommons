/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { NumericUtils } from './NumericUtils';
import { ValidationManager } from "../managers/ValidationManager";
import { HashMapObject } from '../model/HashMapObject';
import { StringUtils } from '../utils/StringUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { ObjectUtils } from '../utils/ObjectUtils';

    
/**
 * Contains methods that allow us to convert data from one complex data structure 
 * format to another complex data structure format
 */  
export class SerializationUtils {
    
    
    /**
     * Copy data from a HashMapObject instance to an arbitrary class instance which contains
     * the same properties as the hashmap keys. Class property values will be set to the same value of the hash map key 
     *
     * @param hashMap An object that contains data which is organized as a hash map. For example: An associative array or an object with key / value pairs
     * @param classInstance A class instance that will be filled with all the values that are found on the hashmap (the instance is modified by this method and all values erased).
     * @param strictMode If set to true, all keys that are found on the hashmap instance must exist on the class instance, otherwise an exception will be thrown
     *
     * @return The provided class instance with all its properties filled with the corresponding hashmap values
     */
    public static hashMapObjectToClass(hashMap:HashMapObject, classInstance:any, strictMode = true){

        // TODO - implement this and translate it to PHP
//        let keys = hashMap.getKeys(); 
//        
//        for (let key of keys) {
//
//            if(property_exists($classInstance, $key)){
//
//                $classInstance->{$key} = $value;
//            }
//        }
//
//        return $classInstance;
    }
        

    // TODO - review from PHP
    public static javaPropertiesObjectToString(){

        // TODO - implement this translating from PHP
    }
    
    
    /**
     * Copy data from a json string to a class instance. All class properties will be filled with their values on the json string
     * For more information on how the conversion is performed, see this class objectToClass method
     * 
     * @see SerializationUtils.objectToClass
     * 
     * @param string A string containing valid json data
     * @param classInstance A class instance that will be filled with all the json data (the instance is modified by this method and all values erased).
     * @param strictMode If set to true, all keys that are found on the json data must exist on the class instance, otherwise an exception will be thrown
     *
     * @return The provided class instance with all its properties filled with the corresponding json values
     */
    public static jsonToClass(string:string, classInstance:any, strictMode = true){

        return SerializationUtils.objectToClass(JSON.parse(string), classInstance, strictMode);
    }
    
    
    /**
     * TODO 
     *
     * @param object TODO
     * @param classInstance TODO
     * @param strictMode TODO
     *
     * @return TODO
     */
    public static objectToClass(object:Object, classInstance:any, strictMode = true){

        if(typeof strictMode !== 'boolean'){
            
            throw new Error("strictMode must be boolean");
        }
        
        let objectKeys = ObjectUtils.getKeys(object);
        let classInstanceName = (classInstance.constructor as any).name;
        let classInstanceKeys = ObjectUtils.getKeys(classInstance);
        
        // On strict mode, verify that both objects have the same keys
        if(strictMode && objectKeys.length !== classInstanceKeys.length){
                
            throw new Error("(strict mode): [" + objectKeys.join(',') + "] keys not match " + classInstanceName + " props: [" + classInstanceKeys.join(',') + "]");
        }
        
        // Loop all the received object keys and store each value on the respective class property
        for(let key of objectKeys){
            
            // Check if key exists on class instance
            if(!classInstance.hasOwnProperty(key)){
                
                if(strictMode){
                    
                    throw new Error("(strict mode): <" + key + "> not found in " + classInstanceName);
                }
                
                continue;
            }
            
            let value = (object as any)[key];
            
            // A null key value will leave the property value untouched
            if(value === null){
                
                continue;
            }
            
            // If property has an explicit null or undefined default value, any type is allowed.
            if(classInstance[key] !== null && classInstance[key] !== undefined){
                
                let typeErrorMessage = '<' + classInstanceName + '.' + key + '> was ' + (typeof value) + ' but expected to be ';
                
                if (ArrayUtils.isArray(classInstance[key])){
                    
                    if(!ArrayUtils.isArray(value)){
                        
                        throw new Error(typeErrorMessage + 'array');                    
                    }
                        
                    if(classInstance[key].length > 0){
                        
                        if(classInstance[key].length !== 1){
                        
                            throw new Error('To define a typed list, ' + classInstanceName + '.' + key + ' must contain only 1 default typed element');
                        }
                        
                        let defaultElement = classInstance[key][0];
                        let isDefaultElementAClass = (ObjectUtils.isObject(defaultElement) && defaultElement.constructor.name !== 'Object');
                                                  
                        classInstance[key] = [];
                        
                        for(let o of value){
                            
                            if(isDefaultElementAClass){
                                
                                o = SerializationUtils.objectToClass(o, ObjectUtils.clone(defaultElement), strictMode);
                                
                            }else{
                                
                                // Type of array elements must match the default value
                                if(typeof o !== typeof defaultElement){
                                    
                                    throw new Error('<' + classInstanceName + '.' + key + '> is defined as array of ' + (typeof defaultElement) + ' but received array of ' + typeof o);
                                }                            
                            }

                            classInstance[key].push(o);
                        }
                        
                        continue;
                    }
                }

                if (ObjectUtils.isObject(classInstance[key])){
                    
                    if(!ObjectUtils.isObject(value)){
                        
                        throw new Error(typeErrorMessage + classInstance[key].constructor.name);
                    }
                        
                    if(classInstance[key].constructor.name !== 'Object'){
                        
                        value = SerializationUtils.objectToClass(value, classInstance[key], strictMode);
                    }
                }
                
                // Type of both object key and class property must match
                if(typeof classInstance[key] !== typeof value){
                    
                    throw new Error(typeErrorMessage + typeof classInstance[key]);
                }
            }
            
            classInstance[key] = value;
        }
        
        return classInstance;
    }
    
    
    // TODO - review from PHP
    public static stringToJavaPropertiesObject(string: string){

        // TODO - review from PHP
    }
    
    
    /**
     * Convert a string containing a well formed XML structure to an XMLObject instance
     *
     * @param string A string containing xml data
     *
     * @return The representation of the given string as an XmlObject instance
     */
    public static stringToXmlObject(string : string){

        if(StringUtils.isEmpty(string)){

            throw new Error('Empty string is not a valid xml value');
        }
        
     // TODO - implement this and translate it to PHP
//        return new XMLObject($string);
    }


    /**
     * Convert an XMLObject to its string representation
     *
     * @param xml An XMLObject instance
     *
     * @return The textual valid representation of the given XMLObject
     */
    public static xmlObjectToString(xml: any){

        // TODO - force XMLObject type to the method parameter
        return xml.toString();
    }
}