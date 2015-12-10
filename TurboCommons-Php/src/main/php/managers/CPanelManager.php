<?php

namespace com\edertone\turboCommons\src\main\php\managers;


/**
 * A class that is used to easily execute CPANEL server operations
 */
class CPanelManager extends BaseSingletonClass{


	/** The username that will be used to authenticate the cpanel calls. Normally the server root */
	public $user = '';


	/** The password to authenticate the cpanel calls */
	public $psw = '';


	/**
	 * Cpanel user account where the operations are performed. This may be different from the account that is used for
	 * authentication: We normally want to authenticate as server root and execute the calls on a different cpanel account.
	 */
	public $account = '';


	/** Host where the cpanel server is located */
	public $host = '127.0.0.1';


	/** port to use for the calls */
	public $port = '2087';


	/** Protocol to use with the api calls (http/https) */
	public $protocol = 'https';


	/**
	 * Park a domain on top of another one. VERY IMPORTANT: If you want to park domains that do not exsit or with DNS
	 * that point to another server, you must set the following on the WHM control panel:
	 *
	 * Tweak Settings -> Domains -> Allow unregistered domains = ON
	 *
	 * @param string $domain    The domain name you wish to park
	 * @param string $topDomain Not required in most of the cases, this is the domain on top of which the parked domain will be parked. This value must point to a preexisting subdomain (e.g. topdomain.example.com). If this parameter is not specified, the primary domain is used, and this is the common case.
	 *
	 * @return boolean Operation result success or failure
	 */
	public function parkDomain($domain, $topDomain = ''){

		if($topDomain != ''){
			$apiRes = $this->_jsonApiQuery('Park', 'park', array('domain' => $domain, 'topdomain' => $topDomain));
		}else{
			$apiRes = $this->_jsonApiQuery('Park', 'park', array('domain' => $domain));
		}

		return ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

	}


	/**
	 * Get a list with all the parked domains for the specified user
	 *
	 * @return array The list of parked domains and their status
	 */
	public function getParkedDomains(){

		$apiRes = $this->_jsonApiQuery('Park', 'listparkeddomains');

		$result = array();

		foreach ($apiRes['cpanelresult']['data'] as $v){

			$a = array(
					'domain' => (string)$v['domain'],
					'status' => (string)$v['status']
					);

			array_push($result, $a);
		}

		return $result;

	}


	/**
	 * Remove a parked domain
	 *
	 * @param string $domain The domain name of the parked domain you wish to remove
	 *
	 * @return boolean Operation result success or failure
	 */
	public function unParkDomain($domain){

		$apiRes = $this->_jsonApiQuery('Park', 'unpark', array('domain' => $domain));

		return ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

	}


	/**
	 * Create a new mail address inside the current cpanel account
	 *
	 * @param string $domain Domain name for the e-mail account
	 * @param string $mail   Username part of the e-mail account (the address part before "@")
	 * @param string $psw    Password for the e-mail account
	 * @param string $quota  Positive integer defining a disk quota for the e-mail account; could be 0 for unlimited
	 *
	 * @return boolean Operation result success or failure
	 */
	public function createMail($domain, $mail, $psw, $quota){

		$apiRes = $this->_jsonApiQuery('Email', 'addpop', array('domain' => $domain, 'email' => $mail, 'password' => $psw, 'quota' => $quota));

		return ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

	}


	/**
	 * Update an existing email account for the current cpanel account
	 *
	 * @param string $domain The domain corresponding to the email address you wish to change. (This value should consist of the text after the 'at' (@) sign. For example, example.com if the address you wished to remove was user@example.com)
	 * @param string $mail   The username corresponding to the email address you wish to change. (This value should consist of the text before the 'at' (@) sign. For example, user if the address you wished to remove was user@example.com)
	 * @param string $psw    The new password for the account
	 * @param string $quota  A positive integer indicating the new disk quota value in megabytes. Enter 0 for an unlimited quota
	 *
	 * @return boolean Operation result success or failure
	 */
	public function updateMail($domain, $mail, $psw = '', $quota = ''){

		$apiRes1 = true;
		$apiRes2 = true;

		if($psw != ''){

			$apiRes = $this->_jsonApiQuery('Email', 'passwdpop', array('domain' => $domain, 'email' => $mail, 'password' => $psw));

			$apiRes1 = ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

		}

		if($quota != ''){

			$apiRes = $this->_jsonApiQuery('Email', 'editquota', array('domain' => $domain, 'email' => $mail, 'quota' => $quota));

			$apiRes2 = ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

		}

		return ($apiRes1 & $apiRes2);

	}


	/**
	 * Get a list with all the email addresses that exist inside the current cpanel account.
	 * The data result will include the email full address, the disk quota and the disk quota used in MB
	 *
	 * @param string $domain To get only the email accounts for a specific domain of this account
	 *
	 * @return array The list of emails and disk usage info
	 */
	public function getMails($domain = ''){

		if($domain != ''){
			$apiRes = $this->_jsonApiQuery('Email', 'listpopswithdisk', array('domain' => $domain));
		}else{
			$apiRes = $this->_jsonApiQuery('Email', 'listpopswithdisk');
		}

		$result = array();

		foreach ($apiRes['cpanelresult']['data'] as $v){

			$a = array(
					'email' => (string)$v['email'],
					'diskquota' => (string)$v['diskquota'],
					'diskused' => (string)$v['diskused']
					);

			array_push($result, $a);
		}

		return $result;

	}


	/**
	 * Delete an existing e-mail account
	 *
	 * @param string $domain The domain corresponding to the email account you wish to remove. (This value should consist of the text after the 'at' (@) sign. For example, example.com if the address you wished to remove was user@example.com).
	 * @param string $mail   The username corresponding to the email account you wish to remove. (This value should consist of the text before the 'at' (@) sign. For example, user if the address you wished to remove was user@example.com)
	 *
	 * @return boolean Operation result success or failure
	 */
	public function deleteMail($domain, $mail){

		$apiRes = $this->_jsonApiQuery('Email', 'delpop', array('domain' => $domain, 'email' => $mail));

		return ($apiRes['cpanelresult']['data'][0]['result'] == '1') ? true : false;

	}


	/**
	 * This function will perform a JSON-API call and return the output as a PHP object.
	 * The method uses only CPANEL API 2 calls, as it is the modern cpanel API.
	 *
	 * @param string $module 	The module of the API2 call to use
	 * @param string $function 	The function of the API2 call to execute
	 * @param array  $args      An associative array of the parameters to be passed to the JSON-API Calls
	 *
	 * @return array The result of the api call
	 */
	private function _jsonApiQuery($module, $function, $args = array()) {

		if($this->user == '' || $this->psw == '' || $this->account == ''){

			trigger_error('CPanelManager->_jsonApiQuery Missing user credentials. user, psw and account properties must be defined to connect to cpanel', E_USER_ERROR);

			return array();
		}

		$args['cpanel_jsonapi_user'] = $this->account;
		$args['cpanel_jsonapi_module'] = $module;
		$args['cpanel_jsonapi_func'] = $function;
		$args['cpanel_jsonapi_apiversion'] = '2';

		// Format the received parameters as an http query
		$postData = http_build_query($args, '', '&');

		// Define the curl header
		$header  = 'Authorization: Basic '.base64_encode($this->user.':'.$this->psw)."\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= 'Content-Length: '.strlen($postData)."\r\n\r\n";
		$header .= $postData;

		// Execute the curl query call
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_URL, $this->protocol.'://'.$this->host.':'.$this->port.'/json-api/cpanel');
		curl_setopt($curl, CURLOPT_BUFFERSIZE, 131072);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array($header));
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		return json_decode($response, true);
	}
}

?>