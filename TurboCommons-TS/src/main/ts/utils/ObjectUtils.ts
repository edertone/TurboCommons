/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
namespace org_turbocommons_utils {

    
    /**
     * Utilities to perform common object operations
     * 
     * @class
     */
    export class ObjectUtils {
    
    
        /**
         * Tells if the given value is an object or not
         *
         * @param {any} value A value to check
         *
         * @returns {boolean} true if the given value is an object, false otherwise
         */
        public static isObject(value:any):boolean{
        
            var ArrayUtils = org_turbocommons_utils.ArrayUtils;
        
            return !(ArrayUtils.isArray(value) || value === null || typeof value !== 'object');
        }
        
    
    	/**
    	 * Get the list of literals for a given object. Note that only 1rst depth keys are providen
    	 * 
    	 * @static
    	 * 
    	 * @param {object} object A valid object
    	 *
    	 * @returns {array} List of strings with the first level object key names in the same order as defined on the object instance
    	 */
    	public static getKeys(object:any):string[]{
    
    		var res = [];
    		
    		if(!ObjectUtils.isObject(object)){
    
    			throw new Error("ObjectUtils.getKeys: Provided parameter must be an object");
    		}
    
    		for(var key in object){
    
    			res.push(key);
    		}
    
    		return res;
    	}
    
    
    	/**
    	 * Check if two provided objects are identical
    	 * 
    	 * @static
    	 * 
    	 * @param {object} object1 First object to compare
    	 * @param {object} object2 Second object to compare
    	 *
    	 * @returns {boolean} true if objects are exactly the same, false if not
    	 */
    	public static isEqualTo(object1:any, object2:any):boolean{
    
    		var ArrayUtils = org_turbocommons_utils.ArrayUtils;
            var validationManager = new org_turbocommons_managers.ValidationManager();
    
    		// Both provided values must be objects or an exception will be launched
    		if(!ObjectUtils.isObject(object1) || !ObjectUtils.isObject(object2)){
    
    			throw new Error("ObjectUtils.isEqualTo: Provided parameters must be objects");
    		}
    
    		var keys1:string[] = ObjectUtils.getKeys(object1);
    		var keys2:string[] = ObjectUtils.getKeys(object2);
    
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
    }
}