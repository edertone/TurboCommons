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
 * The most common string processing and modification utilities
 * 
 * @class
 */
org_turbocommons_src_main_js_utils.StringUtils = {


	/**
	 * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
	 * This method checks all these different conditions that can tell us that a string is empty.
	 * 
	 * @static
	 * 
	 * @param {string} string String to check
	 * @param {array} otherEmptyKeys Optional array containing a list of string values that will be considered as empty for the given string. This can be useful in some cases when we want to consider a string like 'NULL' as an empty string.
	 *
	 * @returns boolean false if the string is not empty, true if the string contains only spaces, newlines or any other "empty" character
	 */
	isEmpty : function(string, otherEmptyKeys){

		// Set optional parameters default values
		otherEmptyKeys = (otherEmptyKeys === undefined) ? null : otherEmptyKeys;

		var aux = '';

		// Note that we are checking emptyness every time we do a replace to improve speed, avoiding unnecessary replacements.
		if(string == null || string == "" || string === undefined){

			return true;
		}

		// Replace all empty spaces.
		if((aux = string.replace(/ /g, '')) == ''){

			return true;
		}

		// Replace all new line characters
		if((aux = aux.replace(/\n/g, '')) == ''){

			return true;
		}

		if((aux = aux.replace(/\r/g, '')) == ''){

			return true;
		}

		if((aux = aux.replace(/\t/g, '')) == ''){

			return true;
		}

		// Check if the empty keys array is specified
		if(Object.prototype.toString.call(otherEmptyKeys) === '[object Array]'){

			if(otherEmptyKeys.length > 0){

				if(otherEmptyKeys.indexOf(aux) >= 0){

					return true;
				}
			}
		}

		return false;
	},


	/**
	 * Count the number of words that exist on the given string
	 *
	 * @static
	 * 
	 * @param string The string which words will be counted
	 * @param wordSeparator ' ' by default. The character that is considered as the word sepparator
	 *
	 * @returns int The number of words (elements divided by the wordSeparator value) that are present on the string
	 */
	countWords : function(string, wordSeparator){

		// Set optional parameters default values
		wordSeparator = (wordSeparator === undefined) ? ' ' : wordSeparator;

		// Alias namespaces
		var ns = org_turbocommons_src_main_js_utils;

		var count = 0;
		var lines = ns.StringUtils.extractLines(string);

		for(var i = 0; i < lines.length; i++){

			var words = lines[i].split(wordSeparator);

			for(var j = 0; j < words.length; j++){

				if(!ns.StringUtils.isEmpty(words[j])){

					count++;
				}
			}
		}

		return count;
	},


	/**
	 * Method that limits the lenght of a string and optionally appends informative characters like ' ...'
	 * to inform that the original string was longer.
	 * 
	 * @static
	 * 
	 * @param string String to limit
	 * @param limit Max number of characters
	 * @param limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
	 *
	 * @returns string The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
	 */
	limitLen : function(string, limit, limiterString){

		// Set optional parameters default values
		limit = (limit === undefined) ? 100 : limit;
		limiterString = (limiterString === undefined) ? ' ...' : limiterString;

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		if(!validationManager.isNumeric(limit)){

			throw new Error("StringUtils.limitLen: limit must be a numeric value");
		}

		if(!validationManager.isString(string)){

			return '';
		}

		if(string.length <= limit){

			return string;
		}

		if(limiterString.length > limit){

			return limiterString.substring(0, limit);

		}else{

			return string.substring(0, limit - limiterString.length) + limiterString;
		}
	},


	/**
	 * Extracts all the lines from the given string and outputs an array with each line as an element.
	 * It does not matter which line separator's been used (\n, \r, Windows, linux...). All source lines will be correctly extracted.
	 * 
	 * @static
	 * 
	 * @param string Text containing one or more lines that will be converted to an array with each line on a different element.
	 * @param filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
	 *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
	 *
	 * @returns array A list with all the string lines sepparated as different array elements.
	 */
	extractLines : function(string, filters){

		// Set optional parameters default values
		filters = (filters === undefined) ? [/\s+/g] : filters;

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		var res = [];

		// Validate we are receiving a string
		if(!validationManager.isString(string)){

			return res;
		}

		var tmp = string.split(/\r?\n/);

		for(var i = 0; i < tmp.length; i++){

			// Apply specified filters
			if(validationManager.isString(tmp[i])){

				// TODO: this is not exactly the same behaviour as the php version.
				// In the php version, we can define an array of filters and if any of the filters matches th current line,
				// it will not be added to the result. This version only accepts the first element of the filters array, it must be fixed!
				if(tmp[i].replace(filters[0], '') != ''){

					res.push(tmp[i]);
				}
			}
		}

		return res;
	},


	/**
	 * TODO
	 */
	extractKeyWords : function(){

		// TODO: Translate from PHP method
	},


	/**
	 * TODO
	 */
	extractFileNameWithExtension : function(path){

		// TODO: Translate from PHP method
	},


	/**
	 * TODO
	 */
	extractFileNameWithoutExtension : function(path){

		// TODO: Translate from PHP method
	},


	/**
	 * TODO
	 */
	extractFileExtension : function(path){

		// TODO: Translate from PHP method
	},


	/**
	 * Given a raw string containing a file system path, this method will process it to obtain a path that
	 * is 100% format valid for the current operating system.
	 * Directory separators will be converted to the OS valid ones, no directory separator will be present
	 * at the end and duplicate separators will be removed.
	 * This method basically standarizes the given path so it does not fail for the current OS.
	 * 
	 * NOTE: This method will not check if the path is a real path on the current file system; it will only fix formatting problems
	 * 
	 * @static
	 * 
	 * @param path The path that must be formatted
	 *
	 * @returns string The correctly formatted path without any trailing directory separator
	 */
	formatPath : function(path){

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		var osSeparator = org_turbocommons_src_main_js_utils.FileSystemUtils.getDirectorySeparator();

		if(path == null){

			return '';
		}

		if(!validationManager.isString(path)){

			throw new Error("StringUtils.formatPath: Specified path must be a string");
		}

		// Replace all slashes on the path with the os default
		path = path.replace(/\//g, osSeparator);
		path = path.replace(/\\/g, osSeparator);

		// Remove duplicate path separator characters
		while(path.indexOf(osSeparator + osSeparator) >= 0){

			path = path.replace(osSeparator + osSeparator, osSeparator);
		}

		// Remove the last slash only if it exists, to prevent duplicate directory separator
		if(path.substr(path.length - 1) == osSeparator){

			path = path.substr(0, path.length - 1);
		}

		return path;
	}
};