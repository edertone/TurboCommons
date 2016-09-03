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
 * Utilities to perform common array operations
 * 
 * @class
 */
org_turbocommons_src_main_js_utils.ArrayUtils = {


	/**
	 * Check if two provided arrays are identical
	 * 
	 * @static
	 * 
	 * @param {array} array1 First array to compare
	 * @param {array} array2 Second array to compare
	 *
	 * @returns boolean true if arrays are exactly the same, false if not
	 */
	isEqual : function(array1, array2){

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Both provided values must be arrays or an exception will be launched
		if(!validationManager.isArray(array1) || !validationManager.isArray(array2)){

			throw new Error("ArrayUtils.isEqual: Provided parameters must be arrays");
		}

		// Compare lengths,can save a lot of time 
		if(array1.length != array2.length){

			return false;
		}

		for(var i = 0, l = array1.length; i < l; i++){

			// Check if we have nested arrays
			if(array1[i] instanceof Array && array2[i] instanceof Array){

				// recurse into the nested arrays
				if(!this.isEqual(array1[i], array2[i])){

					return false;
				}

			}else if(array1[i] !== array2[i]){

				// Warning! Two different object instances will never be equal: {x:20} != {x:20}
				return false;
			}
		}

		return true;
	},


	/**
	 * Remove the specified item from an array
	 * 
	 * @static
	 * 
	 * @param {array} array An array (it will not be modified by this method)
	 * @param {object} element The element that must be removed from the given array
	 *
	 * @returns {array} The provided array but without the specified element (if found). Note that originally received array is not modified by this method
	 */
	removeElement : function(array, element){

		// Alias namespaces
		var ut = org_turbocommons_src_main_js_utils;

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Provided array must be an array
		if(!validationManager.isArray(array)){

			throw new Error("ArrayUtils.removeElement: Provided parameter must be an array");
		}

		var res = [];

		if(validationManager.isArray(element)){

			for(var i = 0; i < array.length; i++){

				if(!validationManager.isArray(array[i])){

					res.push(array[i]);

				}else{

					if(!ut.ArrayUtils.isEqual(element, array[i])){

						res.push(array[i]);
					}
				}
			}

		}else{

			for(var j = 0; j < array.length; j++){

				if(element !== array[j]){

					res.push(array[j]);
				}
			}
		}

		return res;
	}
};