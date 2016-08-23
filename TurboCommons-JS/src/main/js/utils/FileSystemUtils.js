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
 * Class that helps with the most common file system operations
 * 
 * @class
 */
org_turbocommons_src_main_js_utils.FileSystemUtils = {


	/**
	 * Gives us the current OS directory separator character, so we can build cross platform file paths
	 * 
	 * @static
	 * 
	 * @returns string The current OS directory separator character
	 */
	getDirectorySeparator : function(){

		// NOTE: Js is not able to know the OS directory separator character,
		// so we return a universally valid one. This may be improved by detecting the
		// current OS and returning its related separator...
		return '/';
	}
};