<?php

namespace com\edertone\libTurboPhp\src\main\php\utils;


/**
 * Methods that let us encrypt and decrypt different data sources
 */
class CryptographicUtils {


	/**
	 * Encrypts the given string so it can be securely sent as an HTTP GET or POST parameter and decrypted later.
	 * String is also encoded as base 64 so it is ready of url use.
	 * Note that the encryption method is a custom one, fairly simple but secure enough
	 *
	 * @param string $string The string we want to encryt and encode
	 * @param string $key The code that will be used for the encrypt process. Same code will be necessary later to decrypt the string and get the original value. If not specified, a default one is provided
	 *
	 * @return string The codified string, ready to be sent via http GET or POST
	 */
	public static function encryptForUrl($string, $key = 'uTYg1d4678RFCh'){

		$res = '';
		$stringLen = strlen($string);
		$revKey = strrev($key);

		// Concatenate the reversed key as many times as necessary to fill the string lenght
		$padded = $revKey;

		while(strlen($padded) < $stringLen) {

			$padded .= $revKey;
		}

		// Apply a transformation on the original string with the padded string
		for($i = 0; $i < $stringLen; $i++){

			$stringChar = substr($string, $i, 1);
			$paddedChar = substr($padded, $i, 1);

			$res .= chr(ord($stringChar) + ord($paddedChar) % 255);
		}

		// Note that + and / symbols on base64 encoded strings will give us multiple url encoding problems, so we will replace them with - and _
		$res = base64_encode($res);
		$res = str_replace('+',  '-', $res);
		$res = str_replace('/',  '_', $res);
		$res = strrev($res);

		return rawurlencode($res);
    }


    /**
     * Restores the original value for a string that's been encrypted with CryptographicUtils::encryptForUrl.
     * Note that the encryption method is a custom one, fairly simple but secure enough
     *
     * @param string $string The codified string that contains the value we want to decrypt.
     * @param string $key The code that was used to encrypt the string, so we can restore it to the original value. If not specified, a default one is provided
     *
     * @return string If we give the correct key, the original string before being encrypted. If key is wrong, random unuseful characters will be given.
     */
    public static function decryptFromUrl($string, $key = 'uTYg1d4678RFCh'){

    	$res = '';
    	$string = rawurldecode($string);
    	$string = strrev($string);
    	$string = str_replace('-', '+', $string);
    	$string = str_replace('_', '/', $string);
    	$string = base64_decode($string);

		$stringLen = strlen($string);
		$revKey = strrev($key);

		// Concatenate the reversed key as many times as necessary to fill the string lenght
    	$padded = $revKey;

		while(strlen($padded) < $stringLen) {

			$padded .= $revKey;
		}

		// Restore the original string by applying a reverse transformation.
		for($i = 0; $i < $stringLen; $i++){

			$stringChar = substr($string, $i, 1);
			$paddedChar = substr($padded, $i, 1);

			$res .= chr(ord($stringChar) - ord($paddedChar) % 255);
		}

		return $res;
    }

}

?>