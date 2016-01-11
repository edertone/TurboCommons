<?php


namespace com\edertone\turboCommons\src\main\php\utils;


require_once __DIR__.'/StringUtils.php';


/**
 * This class contains a collection of methods that are related to the most common http operations.
 */
class HTTPUtils{


	/**
	 * Process the value for the given GET or POST variable by formatting it depending on if it is set or not and the requested data type.
	 *
	 * @param string $key The GET or POST variable name that we want to process
	 * @param mixed $unSetValue The value that will be returned if the requested variable is not set. The type of this parameter will also determine the type of the function result when the value is set.
	 * @param string $method POST by default. The http method we are using: GET or POST. There's a special value ANY that will try to get the value from POST or GET if not exists.
	 *
	 * @return mixed The value for the given variable. If not set, the 'notSetValue' will be given. If set, the value will be also converted to the type of the notSetValue variable.
	 */
	public static function formatVAR($key, $unSetValue = '', $method = 'POST'){

		// Check if the specified variable is set or not
		$isset = false;

		switch($method){

			case 'POST':
				$isset = isset($_POST[$key]);
				break;

			case 'GET':
				$isset = isset($_GET[$key]);
				break;

			case 'ANY':
				$isset = (isset($_POST[$key]) | isset($_GET[$key]));
				break;

			default:
				$isset = false;
				break;
		}

		if($isset){

			switch($method){

				case 'POST':
					$value = $_POST[$key];
					break;

				case 'GET':
					$value = $_GET[$key];
					break;

				case 'ANY':
					$value = isset($_POST[$key]) ? $_POST[$key] : isset($_GET[$key]) ? $_GET[$key] : '';
					break;
			}

			if(is_bool($unSetValue)){

				return ($value == '0' || $value == 'false') ? false : true;

			}else{

				return $value;
			}

		}else{

			return $unSetValue;
		}
	}


	/**
	 * Get the full filename for the current php script that is being executed. Result will include the file extension but not the path where the file resides.
	 * Example: Home.php
	 *
	 * @return string
	 */
	public static function getCurrentFileName(){

		return basename($_SERVER['PHP_SELF']);

	}


	/**
	 * Get the current page domain, without the htt:// string. For example, if we are on http://test1.domain.com/home/1232, we will get: test1.domain.com.
	 *
	 * @return string
	 */
	public static function getCurrentDomain(){

		return $_SERVER['HTTP_HOST'];

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
	 * WARNING IMPORTANT! - The flag "allow_url_fopen" must be set to TRUE on php.ini for this method to work, otherwise it will FUCKING FAIL
	 *
	 * @param string $url		The url to get it's contents. It must be an absolute path like http://www.xxx.com/yyy/file.php
	 * @param string $params	Array with parameters that will be passed to the url. example: array('a' => 1, 'b' => 2)
	 * @param string $method	POST or GET depending on the script needs. By default is POST
	 *
	 * @return string containing the specified url output
	 */
	public static function getUrlContents($url, $params, $method = 'POST'){

		// build the POST string that will be used to send the parameters to the url.
		//Note that we force the & sepparator to avoid failures on some php systems.
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
   	 * This method will get an object representing the output of the given URL, and expecting that the url outputs XML data.
   	 * We can send POST or GET parameters to the url
   	 *
   	 * @param string $url		The url to get it's contents. It must be an absolute path like http://www.xxx.com/yyy/file.php
   	 * @param string $params	Array with parameters that will be passed to the url with the specified method
   	 * @param string $method	POST or GET depending on the script needs. By default is POST
   	 *
   	 * @return An object containing the converted XML output given by the URL.
   	 */
	public static function getUrlContentsXML($url, $params, $method = 'POST'){

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
		if(!($fp = fsockopen ($server, 43, $errnr, $errstr, 20)))
			die(1);

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