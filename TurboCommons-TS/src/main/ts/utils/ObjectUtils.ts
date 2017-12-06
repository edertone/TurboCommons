/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { ArrayUtils } from './ArrayUtils';
import { ValidationManager } from '../managers/ValidationManager';


/**
 * Utilities to perform common object operations
 * 
 * @class
 */
export class ObjectUtils {


    /**
     * Tells if the given value is an object or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is an object, false otherwise
     */
    public static isObject(value:any):boolean{
    
        return !(ArrayUtils.isArray(value) || value === null || typeof value !== 'object');
    }
    

	/**
	 * Get the list of literals for a given object. Note that only 1rst depth keys are providen
	 * 
	 * @param object A valid object
	 *
	 * @returns List of strings with the first level object key names in the same order as defined on the object instance
	 */
	public static getKeys(object:any):string[]{

		var res = [];
		
		if(!ObjectUtils.isObject(object)){

			throw new Error("ObjectUtils.getKeys: parameter must be an object");
		}

		return Object.keys(object);
	}


	/**
	 * Check if two provided objects are identical.
	 * Note that properties order does not alter the comparison. So if two objects 
	 * have the same properties with exactly the same values, but they appear in a different
	 * order on both objects, this method will consider them as equal.
	 * 
	 * @param object1 First object to compare
	 * @param object2 Second object to compare
	 *
	 * @returns true if objects are exactly the same, false if not
	 */
	public static isEqualTo(object1:any, object2:any):boolean{

		 var validationManager = new ValidationManager();

		// Both provided values must be objects or an exception will be launched
		if(!ObjectUtils.isObject(object1) || !ObjectUtils.isObject(object2)){

			throw new Error("ObjectUtils.isEqualTo: parameters must be objects");
		}

		var keys1:string[] = ObjectUtils.getKeys(object1).sort();
		var keys2:string[] = ObjectUtils.getKeys(object2).sort();

		// Compare keys can save a lot of time 
		if(!ArrayUtils.isEqualTo(keys1, keys2)){

			return false;
		}

		// Loop all the keys and verify values are identical
		for(var i:number = 0; i < keys1.length; i++){

			if(!validationManager.isEqualTo(object1[keys1[i]], object2[keys2[i]])){

				return false;
			}
		}

		return true;
	}
	
	
	/**
	 * Perform a deep copy of the given object.
	 * 
	 * @see https://stackoverflow.com/questions/4459928/how-to-deep-clone-in-javascript
	 * 
	 * @param object Any language instance like nubmers, strings, arrays, objects, etc.. that we want to duplicate.
	 * 
	 * @returns An exact independent copy of the received object, without any shared reference.
	 */
	public static clone(object:any) {
	    
	    if(object == null || typeof(object) != 'object') {
	    
	        return object;
	    }

	    let result = new object.constructor();

	    for(var key in object) {
	        
	        if (object.hasOwnProperty(key)) {
	          
	            result[key] = ObjectUtils.clone(object[key]);
	        }
	    }

	    return result;
	  }	    
}