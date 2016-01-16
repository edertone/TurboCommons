"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/* exported CookiesUtils */

/**
 * Static class with utilities related to cookies
 */
var CookiesUtils = {


	/**
	 * Set the value for a cookie.
	 * 
	 * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
	 * 
	 * @param key the name for the cookie we want to create
	 * @param value the value we want to set to the new cookie. If not specified or set to null, the cookie will not be created.
	 * @param expires The lifetime of the cookie. Value can be a `Number` which will be interpreted as days from time of creation or a `Date` object. If omitted or '' string, the cookie becomes a session cookie.
	 * @param path Define the path where the cookie is valid. By default it is the whole domain: '/'. A specific path can be passed (/ca/Home/) or a '' string to set it as the current site http path.
	 * @param domain Define the domain where the cookie is valid. Default: domain of page where the cookie was created.
	 * @param secure If true, the cookie transmission requires a secure protocol (https). Default: `false`.
	 * 
	 * @returns boolean Success or failure
	 */
	setCookie : function(key, value, expires, path, domain, secure){

		// TODO: Caldria verificar d'alguna manera si excedim l'espai total d'emmagatzematge de cookies que és de 4kb!

		// If no value specified, we will quit
		if(value === undefined || value == null){
			return false;
		}

		expires = (expires === undefined) ? "" : expires;
		path = (path === undefined) ? "/" : path;
		domain = (domain === undefined) ? "" : domain;
		secure = (secure === undefined) ? false : secure;

		// If the expires parameter is numeric, we will generate the correct date value
		if(typeof expires === 'number'){

			var days = expires;

			expires = new Date();
			expires.setDate(expires.getDate() + days);
		}

		// Generate and return the cookie value
		return (document.cookie = [encodeURIComponent(key), '=', encodeURIComponent(value), expires ? '; expires=' + expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
		path ? '; path=' + path : '', domain ? '; domain=' + domain : '', secure ? '; secure' : ''].join(''));

	},


	/**
	 * Get the value for an existing cookie.
	 * 
	 * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
	 * 
	 * @param key the name of the cookie we want to get
	 * 
	 * @return Cookie value or null if not set
	 */
	getCookie : function(key){

		var pluses = /\+/g;

		// Get an array with all the page cookies
		var cookies = document.cookie.split('; ');

		for(var i = 0, l = cookies.length; i < l; i++){

			var parts = cookies[i].split('=');

			if(decodeURIComponent(parts.shift().replace(pluses, ' ')) === key){
				var cookie = decodeURIComponent(parts.join('=').replace(pluses, ' '));
				return cookie;
			}
		}

		return null;

	},


	/**
	 * Deletes the specified cookie from browser. Note that the cookie will only be deleted if belongs to the same path as specified.
	 * 
	 * @param key The name of the cookie we want to delete
	 * @param path Define the path where the cookie is set. By default it is the whole domain: '/'. If the cookie is not set on this path, we must pass the cookie domain or the delete will fail.
	 * 
	 * @returns boolean Cookie value or null
	 */
	deleteCookie : function(key, path){

		if(this.getCookie(key) !== null){

			this.setCookie(key, "", -1, path);

			return true;
		}

		return false;

	}
};