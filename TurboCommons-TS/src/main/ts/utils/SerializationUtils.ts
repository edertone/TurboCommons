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
     * @param classInstance A class instance that will be filled with all the values that are found on the hashmap (This parameter is modified by this method).
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
        

    /**
     * Convert a JavaPropertiesObject instance to a string that is valid so it can be saved to a .properties file.
     *
     * @param javaProperties An instance of a JavaPropertiesObject
     * 
     * @return string An ISO-8859-1 string containing valid properties data, ready to be stored as a .properties java format file.
     */
    public static javaPropertiesObjectToString(javaProperties:any){

        // TODO - implement this and translate it to PHP
//        return $javaProperties->toString();
    }
    
    
    /**
     * Copy data from a json string to a class instance. All class properties will be filled with their values on the json string
     *
     * @param string A string containing valid json data
     * @param classInstance A class instance that will be filled with all the json data (This parameter is modified by this method).
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
            
            throw new Error("SerializationUtils.objectToClass: strictMode must be boolean");
        }
        
        let keys = ObjectUtils.getKeys(object);
        let className = (classInstance.constructor as any).name;

        // On strict mode, verify that both objects share the same keys
        if(strictMode){

            let classProperties = ObjectUtils.getKeys(classInstance);
            
            if(!ArrayUtils.isEqualTo(keys, classProperties)){
                
                throw new Error("SerializationUtils.objectToClass: keys do not match " + className + " properties");
            }
        }
        
        for(let key of keys){
            
            // Key must exist on provided class instance
            if(!classInstance.hasOwnProperty(key)){
                
                throw new Error("SerializationUtils.objectToClass: property " + key + " not found in " + className);
            }
            
            let value = (object as any)[key];
            
            if(ArrayUtils.isArray(value) || ArrayUtils.isArray(classInstance[key])){

                if(ArrayUtils.isArray(value) !== ArrayUtils.isArray(classInstance[key])) {
                    
                    throw new Error('SerializationUtils.objectToClass: ' + key + ' must be array in both object and ' + className + ' class');
                }
                                       
                if(strictMode){
                    
                    if(classInstance[key].length !== 1){
                        
                        throw new Error('SerializationUtils.objectToClass: ' + key + ' must contain 1 default element in ' + className + ' class');
                    }
                    
                    let elementClassName = classInstance[key][0].constructor.name;
                    
                    classInstance[key] = [];
                    
                    for(let o of value){
                        
                        classInstance[key].push(SerializationUtils.objectToClass(o, new (window as any)[elementClassName](), strictMode));     
                    }
                
                }else{
                 
                    classInstance[key] = value;
                }
              
                
            }else{
                
                if(ObjectUtils.isObject(value) || ObjectUtils.isObject(classInstance[key])){
                    
                    if(ObjectUtils.isObject(value) !== ObjectUtils.isObject(classInstance[key])){
                                                
                        throw new Error('SerializationUtils.objectToClass: ' + key + ' must be Object in both object and ' + className + ' class');
                    }
                        
                    classInstance[key] = SerializationUtils.objectToClass(value, classInstance[key], strictMode);
                    
                }else{
                 
                    classInstance[key] = value;
                }
            }
        }
        
        return classInstance;
    }
    
    
    /**
     * Convert a string containing the contents of a Java properties file to a JavaPropertiesObject instance.
     * 
     * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results may not be correct).
     *
     * @param string The contents of a .properties Java file
     *
     * @return The properties format parsed as a JavaPropertiesObject instance
     */
    public static stringToJavaPropertiesObject(string: string){

     // TODO - implement this and translate it to PHP
//        return new JavaPropertiesObject($string);
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

            throw new Error('SerializationUtils->stringToXmlObject Empty string is not a valid xml value');
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