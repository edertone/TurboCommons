<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Utilities for the browser cookies
 */
class CookiesUtils{


	/**
	 * Set a cookie on the current browser
	 *
	 * @param string $key The cookie name
	 * @param string $value The value to store on the cookie
	 * @param string $expires The expiration time in days
	 *
	 * @return boolean	True if the cookie was sucessfully created (Doesn't mean the user accepted it).
	 */
	public static function setCookie($key, $value, $expires = null){

		// TODO: Caldria verificar d'alguna manera si excedim l'espai total d'emmagatzematge de cookies que és de 4kb!

		// This is a trick to make sure the cookie value is inmediately available. When setting a cookie, we won't be able to read it till the next page reload, so
		// if we want to get its defined value later on the current script we need to directly set it on the $_COOKIE global data.
		$_COOKIE[$key] = $value;

		return setcookie($key, $value, $expires, '/');
	}


	/**
	 * Get an stored cookie. If the cookie is not defined, it will return an empty string
	 *
	 * @param string $key	The cookie name
	 *
	 * @return string
	 */
	public static function getCookie($key){

		return isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';

	}


	/**
	 * Deletes the specified cookie from browser. Note that the cookie will only be deleted if belongs to the same path as specified.
	 *
	 * @param string $key The name of the cookie we want to delete
	 * @param string $path Define the path where the cookie is set. By default it is the whole domain: '/'. If the cookie is not set on this path, we must pass the cookie domain or the delete will fail.
	 *
	 * @return boolean True on success or false if cookie did not exist
	 */
	public static function deleteCookie($key, $path = '/'){

		if(isset($_COOKIE[$key])){

			setcookie($key, '', null, $path);
			return true;
		}

		return false;

	}
}

?>