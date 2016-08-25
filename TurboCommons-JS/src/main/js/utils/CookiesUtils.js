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
 * Utilities related with saving, reading and operating with cookies
 * 
 * <pre><code> 
 * This is a static class, so no instance needs to be created.
 * Usage example:
 * 
 * var ns = org_turbocommons_src_main_js_utils;
 * 
 * var cookie = ns.CookiesUtils.getCookie('key');
 * ...
 * </code></pre>
 * 
 * @class
 */
org_turbocommons_src_main_js_utils.CookiesUtils = {


	/**
	 * Check if the specified cookie exists
	 * 
	 * @static
	 * 
	 * @param {string} key the name for the cookie we want to find
	 * 
	 * @returns {boolean} True if cookie with specified name exists, false otherwise
	 */
	isCookie : function(key){

		var ns = org_turbocommons_src_main_js_utils;

		return (ns.CookiesUtils.getCookie(key) !== undefined);
	},


	/**
	 * Set the value for a cookie or create it if not exist
	 * 
	 * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
	 * 
	 * @static
	 * 
	 * @param {string} key the name for the cookie we want to create
	 * @param {string} value the value we want to set to the new cookie.
	 * @param {string} expires The lifetime of the cookie. Value can be a `Number` which will be interpreted as days from time of creation or a `Date` object. If omitted or '' string, the cookie becomes a session cookie.
	 * @param {string} path Define the path where the cookie is valid. By default it is the whole domain: '/'. A specific path can be passed (/ca/Home/) or a '' string to set it as the current site http path.
	 * @param {string} domain Define the domain where the cookie is valid. Default: domain of page where the cookie was created.
	 * @param {boolean} secure If true, the cookie transmission requires a secure protocol (https). Default: false.
	 * 
	 * @returns {boolean} True if cookie was created, false otherwise. An exception may be thrown if invalid parameters are specified
	 */
	setCookie : function(key, value, expires, path, domain, secure){

		// Set optional parameters default values
		expires = (expires === undefined || expires === null) ? '' : expires;
		path = (path === undefined) ? "/" : path;
		domain = (domain === undefined) ? "" : domain;
		secure = (secure === undefined) ? false : secure;

		// TODO: Should be interesting to detect if we are going to exceed the total available space for 
		// cookies storage before storing the data, to prevent it from silently failing 	

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Empty key means an exception
		if(!validationManager.isString(key) || !validationManager.isRequired(key)){

			throw new Error("CookiesUtils.setCookie: key must be defined");
		}

		// Empty values mean cookie will be created empty
		if(value === undefined || value === null){

			value = '';
		}

		// Reaching here, non string value means an exception
		if(!validationManager.isString(value)){

			throw new Error("CookiesUtils.setCookie: value must be a string");
		}

		// If the expires parameter is numeric, we will generate the correct date value
		if(validationManager.isNumeric(expires)){

			var days = expires;

			expires = new Date();
			expires.setDate(expires.getDate() + days);
		}

		// Generate the cookie value
		var res = encodeURIComponent(key) + '=' + encodeURIComponent(value);
		res += expires ? '; expires=' + expires.toUTCString() : '';
		res += path ? '; path=' + path : '';
		res += domain ? '; domain=' + domain : '';
		res += secure ? '; secure' : '';

		document.cookie = res;

		return true;
	},


	/**
	 * Get the value for an existing cookie.
	 * 
	 * @static
	 * 
	 * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
	 * 
	 * @param {string} key the name of the cookie we want to get
	 * 
	 * @returns {string} Cookie value or undefined if cookie does not exist
	 */
	getCookie : function(key){

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Empty key means an exception
		if(!validationManager.isString(key) || !validationManager.isRequired(key)){

			throw new Error("CookiesUtils.getCookie: key must be defined");
		}

		// Get an array with all the page cookies
		var cookies = document.cookie.split('; ');

		var pluses = /\+/g;

		for(var i = 0, l = cookies.length; i < l; i++){

			var parts = cookies[i].split('=');

			if(decodeURIComponent(parts.shift().replace(pluses, ' ')) === key){

				return decodeURIComponent(parts.join('=').replace(pluses, ' '));
			}
		}

		return undefined;
	},


	/**
	 * Deletes the specified cookie from browser. Note that the cookie will only be deleted if belongs to the same path as specified.
	 * 
	 * @static
	 * 
	 * @param {string} key The name of the cookie we want to delete
	 * @param {string} path Define the path where the cookie is set. By default it is the whole domain: '/'. If the cookie is not set on this path, we must pass the cookie domain or the delete will fail.
	 * 
	 * @returns {boolean} True if cookie was deleted or false if cookie could not be deleted or was not found.
	 */
	deleteCookie : function(key, path){

		var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

		// Empty key means an exception
		if(!validationManager.isString(key) || !validationManager.isRequired(key)){

			throw new Error("CookiesUtils.deleteCookie: key must be defined");
		}

		if(this.getCookie(key) !== undefined){

			this.setCookie(key, '', -1, path);

			return true;
		}

		return false;
	}
};