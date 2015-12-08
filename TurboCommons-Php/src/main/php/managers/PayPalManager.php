<?php

namespace com\edertone\libTurboPhp\src\main\php\managers;


/**
 * Class to manage paypal transactions
 */
class PayPalManager extends BaseStrictClass {


	/**
	 * Execute the paypal IPN validation. The process will connect with paypal and send
	 * the received IPN headers so we can get a valid or invalid response for the transaction from the paypal servers.
	 *
	 * Note that this method uses CURL, that must be enabled in php for the connection to work.
	 *
	 * @param string $enableSandbox Set it to true when debugging the payment process
	 *
	 * @return string Empty string if the validation goes ok, and a string containing the type of error if the ipn validation fails
	 */
	public function validateIPN($enableSandbox = false){

		if(count($_POST) <= 0){
			return 'POST_DATA_EMPTY';
		}

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd='.urlencode('_notify-validate');

		foreach ($_POST as $key => $value){
			$req .= '&'.$key.'='.urlencode(stripslashes($value));
		}

		// Detect parameters depending on sandbox enabled or not
		$payPalUrl = ($enableSandbox) ? 'www.sandbox.paypal.com' : 'www.paypal.com';

		if(!function_exists('curl_init')){
			return 'CURL_NOT_AVAILABLE';
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://'.$payPalUrl.'/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: '.$payPalUrl));
		$res = curl_exec($ch);
		curl_close($ch);

		if (strcmp ($res, 'VERIFIED') == 0) {

			// Everything ok, we return an empty string
			return '';

		}else if (strcmp ($res, 'INVALID') == 0) {

			// Invalid response means the payment request was wrong
			return 'INVALID';
		}

		// Reaching here means an unknown error
		return 'UNKNOWN_ERROR';

	}


	/**
	 * Validate an IPN payment by completed and by minimim amount
	 *
	 * @param string $minimumAmount Minimun value that is allowed for the received money. This is a security validation to abort invalid prices
	 *
	 * @return string Empty string if the validation goes ok, and a string containing the type of error if the ipn validation fails
	 */
	public function validateIPNPayment($minimumAmount){

		// Payment status must be COMPLETED, so the money is in our pocket.
		if(strtolower($_POST['payment_status']) != 'completed'){
			return 'PAYMENT_INCOMPLETE';
		}

		// TODO: check that txn_id has not been previously processed

		// TODO: check that receiver_email is your Primary PayPal email

		// Check that the money amount is between specified limits
		if($minimumAmount != '' && $_POST['mc_gross'] < $minimumAmount){
			return 'MINIMUM_AMOUNT_ERROR: '.$_POST['mc_gross'];
		}

		return '';

	}


	/**
	 * Validate a subscription sign up
	 *
	 * @return string Empty string if the validation goes ok, and a string containing the type of error if the ipn validation fails
	 */
	public function validateIPNSubscriptionSignUp(){

		if(strtolower($_POST['txn_type']) != 'subscr_signup'){
			return 'TRANSACTION IS NOT A SUBSCRIPTION SIGN UP';
		}

		return '';

	}


	/**
	 * Validate a subscription cancellation
	 *
	 * @return string Empty string if the validation goes ok, and a string containing the type of error if the ipn validation fails
	 */
	public function validateIPNSubscriptionCancel(){

		if(strtolower($_POST['txn_type']) != 'subscr_cancel'){
			return 'TRANSACTION IS NOT A SUBSCRIPTION CANCELLATION';
		}

		return '';

	}


	/**
	 * Generates a string resume for the IPN Headers that are stored on global $_POST variable. Normally used to store the received info with a human readable value
	 *
	 * @return string
	 */
	public function getIPNHeadersResume(){

		$ipnHeaders = '';

		// Generate a text with all the post values for email informative purposes
		foreach ($_POST as $key => $value)
			$ipnHeaders .= $key . ' = ' .$value ."\n\n";

		return $ipnHeaders;
	}
}

?>