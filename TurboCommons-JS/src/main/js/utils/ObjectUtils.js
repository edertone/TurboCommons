"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

/** @namespace */
var org_turbocommons_src_main_js_utils = org_turbocommons_src_main_js_utils || {};


/**
 * Utilities to perform common object operations
 * 
 * @class
 */
org_turbocommons_src_main_js_utils.ObjectUtils = {


	/**
	 * Get the list of literals for a given object. Note that only 1rst depth keys are providen
	 * 
	 * @static
	 * 
	 * @param {object} object A valid object
	 *
	 * @returns {array} List of strings with the first level object key names in the same order as defined on the object instance
	 */
	getKeys : function(object){

		var res = [];
		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		if(!validationManager.isObject(object)){

			throw new Error("ObjectUtils.getKeys: Provided parameter must be an object");
		}

		for( var key in object){

			res.push(key);
		}

		return res;
	},


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
	isEqualTo : function(object1, object2){

		// Alias namespaces
		var ut = org_turbocommons_src_main_js_utils;

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Both provided values must be objects or an exception will be launched
		if(!validationManager.isObject(object1) || !validationManager.isObject(object2)){

			throw new Error("ObjectUtils.isEqualTo: Provided parameters must be objects");
		}

		var keys1 = this.getKeys(object1);
		var keys2 = this.getKeys(object2);

		// Compare keys can save a lot of time 
		if(!ut.ArrayUtils.isEqualTo(keys1, keys2)){

			return false;
		}

		// Loop all the keys and verify values are identical
		for(var i = 0; i < keys1.length; i++){

			if(!validationManager.isEqualTo(object1[keys1[i]], object2[keys2[i]])){

				return false;
			}
		}

		return true;
	}
};