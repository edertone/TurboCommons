<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use org\turbocommons\src\main\php\managers\ValidationManager;
use UnexpectedValueException;


/**
 * This class contains a collection of methods that are related to the most common http operations.
 */
class HTTPUtils{


	/**
	 * Test if the specified url exists by trying to connect to it.
	 * Note that this method freezes the execution until the response is received from the given url so use it carefully.
	 * Response will be longer for non existing urls cause it will wait till the request timeout completes.
	 *
	 * @param string $url An internet address to check
	 *
	 * @return boolean True if url exists and is accessible, false if the url could not be accessed.
	 */
	public static function urlExists($url){

		if(StringUtils::isEmpty($url)){

			return false;
		}

		$validationManager = new ValidationManager();

		// Avoid performing an http request if the url is invalid
		if(!$validationManager->isUrl($url)){

			return false;
		}

		$headers = static::getUrlHeaders($url);

        if($headers === null){

			return false;
		}

		foreach([404, 405] as $code){

			if (is_numeric($code) && strpos($headers[0], strval($code)) !== false){

				return false;
			}
		}

	    return true;
	}


	// TODO - Everything must be reviewed from here **************************************************



	/**
	 * Obtain the value for the specified GET variable
	 *
	 * @return string The GET parameter value for the specified key
	 */
	public static function get($key){

		return (isset($_GET[$key])) ? $_GET[$key] : '';
	}


	/**
	 * Obtain the value for the specified POST variable
	 *
	 * @return string The POST parameter value for the specified key
	 */
	public static function post($key){

		return (isset($_POST[$key])) ? $_POST[$key] : '';
	}


	/**
	 * Get the current page full url, including 'http://', domain and all the get parameters
	 *
	 * @return string
	 */
	public static function getCurrentFullURL(){

		return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}


	/**
	 * This method will get the output of the given URL, letting us send POST or GET parameters.
	 * WARNING IMPORTANT! - The flag "allow_url_fopen" must be set to TRUE on php.ini for this method to work, otherwise it will FAIL
	 *
	 * @param string $url		The url to get it's contents. It must be an absolute path like http://www.xxx.com/yyy/file.php
	 * @param string $params	Array with parameters that will be passed to the url. example: array('a' => 1, 'b' => 2)
	 * @param string $method	POST or GET depending on the script needs. By default is POST
	 *
	 * @return string containing the specified url output
	 */
	public static function getUrlContents($url, $params, $method = 'POST'){

		// build the POST string that will be used to send the parameters to the url.
		//Note that we force the & separator to avoid failures on some php systems.
		$params = http_build_query($params, null, '&');

		// Set the POST method and the POST parameters that will be used on the httprequest
		$context_options = array (
			'http' => array (
					'method' => $method,
					'header'=> "Content-type: application/x-www-form-urlencoded\r\n".'Content-Length: '.strlen($params)."\r\n",
					'content' => $params
			)
		);

		// Do the request and return it's result
		return file_get_contents($url, false, stream_context_create($context_options));
	}


	/**
	 * Get the Http headers for a given url.
	 *
	 * @param string $url The url that for which we want to get the http headers.
	 *
	 * @return array Url headers split by each line as an array element or null if no headers could be found
	 */
	public static function getUrlHeaders($url){

		// Check that curl is available
		if(!function_exists('curl_init')){

			throw new UnexpectedValueException('HTTPUtils::getUrlHeaders: Curl must be enabled');
		}

		$handle = curl_init($url);

		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_HEADER, true);
		curl_setopt($handle, CURLOPT_NOBODY, true);
		curl_setopt($handle, CURLOPT_USERAGENT, true);

		$headers = curl_exec($handle);

		curl_close($handle);

		if(empty($headers)){

			return null;
		}

		return explode(PHP_EOL, $headers);
	}


	/**
   	 * This method will get an object representing the output of the given URL, and expecting that the url outputs XML data.
   	 * We can send POST or GET parameters to the url
   	 *
   	 * @param string $url		The url to get it's contents. It must be an absolute path like http://www.xxx.com/yyy/file.php
   	 * @param string $params	Array with parameters that will be passed to the url with the specified method
   	 * @param string $method	POST or GET depending on the script needs. By default is POST
   	 *
   	 * @return \SimpleXMLElement An object containing the converted XML output given by the URL.
   	 */
	public static function getUrlContentsAsXML($url, $params, $method = 'POST'){

		$xml = self::getUrlContents($url, $params, $method);

		return simplexml_load_string($xml);
	}


	/**
	 * Force the browser to avoid caching the script contents. Normally used for Internet Explorer
	 *
	 * @return void
	 */
	public static function disableBrowserCaching(){

		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');  // disable IE caching
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
	}


	/**
	 * When a url receives POST data and the user reloads the browser, an annoying message appears to the user: "confirm form resubmission ?"
	 * This method destroys the post data for the current url, by reloading the current page without any POST info, and therefore fixing the problem to the user.
	 *
	 * Note that page will be loaded again, so we must take tare of any possible side effect of this condition.
	 *
	 * @return void
	 */
	public static function clearPostVariables(){

		header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		die();
	}


	/**
	 * Synchronously checks if the specified domain is free for Internet registering by calling several whois services.
	 *
	 * @param string $domain The domain to check
	 *
	 * @return boolean True if the specified domain is free can be registered, false if the domain is already registered by somebody
	 */
	public static function isDomainFreeToRegister($domain){

		$domain = strtolower($domain);

		//Remove www. sequence from the string if exists
		if (substr($domain, 0, 4) == 'www.'){

			$domain = substr($domain, 4, strlen($domain));
		}

		//Retrieve current domain extension
		$ext = explode('.', $domain);
		$ext = $ext[count($ext) - 1];

		//Choose whois server & pattern depending the domain extension
		switch($ext){

			case 'biz':
				$server = 'whois.neulevel.biz';
				$pattern = 'Not found: ';
				break;

			case 'info':
				$server = 'whois.afilias.info';
				$pattern = 'NOT FOUND';
				break;

			case 'cat':
				$server = 'whois.cat';
				$pattern = 'NOT FOUND';
				break;

			default:
				$server = 'whois.crsnic.net';
				$pattern = 'No match for ';
				break;
		}

		// we normally use the "$errno: $errstr" to show if something's happened instead of "1". But in this case, we must return a 1 value if
		// no communication is obtained, so our users can continue using the app
		if(!($fp = fsockopen ($server, 43, $errnr, $errstr, 20))){

			die(1);
		}

		fputs($fp, $domain."\n");

		while (!feof($fp)){

			$serverReturn = fgets($fp, 2048);

			if(substr_count($serverReturn, $pattern) > 0){

				fclose($fp);
				return true;
			}
		}

		fclose($fp);

		return false;
	}
}

?>