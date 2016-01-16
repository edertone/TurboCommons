"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/**
 * Static Class with the most common string processing and modification utilities
 * 
 * import path: 'js/libs/libEdertoneJS/utils/StringUtils.js'
 */
var StringUtils = {


	/** 
	 * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
	 * This method checks all these different conditions that can tell us that a string is empty. 
	 * 
	 * @param s The string to check
	 * 
	 * @returns boolean false if the string is not empty, true if the string contains only spaces, newlines or any other "empty" character
	 */
	isEmpty : function(s){

		var aux = '';

		// Note that we are checking emptyness every time we do a replace to improve speed, avoiding unnecessary replacements.
		if(s == null || s == "" || s === undefined){

			return true;
		}

		// Replace all empty spaces.
		if((aux = s.replace(/ /g, '')) == ''){

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

		return false;
	},


	/**
	* Method that gives the file extension for a given path (The dot symbol is NOT included)
	*
	* @param path The path of the file
	* @param lowercase Specify if the return value will be converted to lower case or not
	*
	* @returns file extension WITHOUT dot character. For example: jpg, png, js, exe ...
	*/
	getFileExtension : function(path, lowercase){

		// Set optional parameters default values
		lowercase = (lowercase === undefined) ? false : lowercase;

		// Find the extension by getting the last position of the dot character
		var extension = path.substring(path.lastIndexOf('.') + 1);

		return lowercase ? extension.toLoweCase() : extension;
	},


	/**
	 * Count the number of words existing on the given string
	 * 
	 * @param s	The string to count
	 * @param wordSepparator ' ' by default. The character that is considered as the word sepparator
	 * 
	 * @returns int	The number of words in the string
	 */
	countWords : function(s, wordSepparator){

		// Set optional parameters default values
		wordSepparator = (wordSepparator === undefined) ? ' ' : wordSepparator;

		var count = 0;
		var words = s.split(wordSepparator);

		for(var i = 0; i < words.length; i++){

			if(!StringUtils.isEmpty(words[i])){

				count++;
			}
		}

		return count;
	},


	/**
	 * URL-encode according to RFC 3986
	 * 
	 * @param s	The input url
	 * 
	 * @returns string	The encoded string
	 */
	urlEncode : function(s){

		s = (s + '').toString();
		return encodeURIComponent(s).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');
	}
};