<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;


/**
 * Class that acts as an interface to email sending operations
 */
class MailManager extends BaseStrictClass {


	/** Constant that defines the utf 8 character encoding */
	const UTF8 = 'UTF-8';


	/** Constant that defines the latin character encoding */
	const ISO_8859_1 = 'ISO-8859-1';


	/** Stores the number of attached files to the current email */
	private $_attachmentsLen = 0;


	/** Structure with the filenames and binary data of the files to attach to the mail */
	private $_attachments = array();


	/**
	 * Attach a file from an OS path to the email
	 *
	 * @param string $filename The name for the file as it will apear on the email
	 * @param string $path The path where the file is located on system
	 *
	 * @return void
	 */
	public function attachFile($filename, $path){

		$f['filename'] = $filename;
		$f['binary'] = chunk_split(base64_encode(file_get_contents($path)));

		$this->_attachments[] = $f;
		$this->_attachmentsLen ++;

	}


	/**
	 * Attach a file from binary data to the email
	 *
	 * @param string $filename The name for the file when sent on the email
	 * @param string $binary_data The file binary data to attach
	 *
	 * @return void
	 */
	public function attachBinary($filename, $binary_data){

		$f['filename'] = $filename;
		$f['binary'] = chunk_split(base64_encode($binary_data));

		$this->_attachments[] = $f;
		$this->_attachmentsLen ++;

	}


	/**
	 * Send the email, with the currently specified parameters. Note that if we want to attach files to the mail we must do it before calling the send method.
	 *
	 * @param string  $senderAddress The addres for the person who sends the email. If you want to set a custom sender name also than the email, use : My custom sender name <mi@email.com>
	 * @param array|string $receiverAddress	The address where the email will be sent, or an array containing a list of adresses where the mail will be sent
	 * @param string  $subject Title for the message to send
	 * @param string  $message Message body
	 * @param boolean $htmlMode Enable when sending html content. Disabled by default
	 * @param string  $encoding The charset that is defined when sending the email. MailManager::UTF-8 by default. It is VERY important to make sure that the subject and message parameters are passed with the same encoding as the one defined here, otherwise strange characters will appear on the received email.
	 * @param boolean $dispositionRequire Request notification when the email is read by the receiver. False by default
	 *
	 * @return boolean True if the mail was queued to be sent (does not mean it will reach its destination), False if the mail could not be delivered.
	 */
	public function sendMail($senderAddress, $receiverAddress, $subject, $message, $htmlMode = false, $encoding = self::UTF8, $dispositionRequire = false){

		// Sanitize the sender and receiver addresses to remove non email characters
		$senderAddress = trim(filter_var($senderAddress, FILTER_SANITIZE_EMAIL));
		$receiverAddress = trim(filter_var($receiverAddress, FILTER_SANITIZE_EMAIL));

		// Some empty values mean we wont deliver the mail
		if($senderAddress == '' || $receiverAddress == '' || $subject.$message == ''){

			return false;
		}

		// Set default charset
		if($encoding == ''){
			$encoding = self::ISO_8859_1;
		}

		// Define the character encoding for the subject and body
		if($encoding == self::UTF8){

			$encoding = 'charset="'.self::UTF8.'"';

		}else{

			$encoding = 'charset="'.self::ISO_8859_1.'"';
		}

		// Definition for the headers - using \r makes thunderbird fail!!
		$headers = "MIME-Version: 1.0\n";
		$headers .= 'From: '.$senderAddress."\n";
		$headers .= 'Return-Path: '.$senderAddress."\n";
		$headers .= 'Reply-To: '.$senderAddress."\n";

		if($dispositionRequire){

			$headers .= 'Disposition-Notification-To: '.$senderAddress."\n";
		}

		// Check if the mail is in html format
		if($htmlMode == true){

			$tmp = 'Content-type: text/html; '.$encoding;

		}else{

			$tmp = 'Content-Type: text/plain; '.$encoding;
		}

		// Check if there are attached files
		if($this->_attachmentsLen > 0){

			//create a boundary string. It must be unique so we use the MD5 algorithm to generate a random hash
			$mimeBoundary = '==Multipart_Boundary_x'.md5(time()).'x';

			// Output the multipart mixed headers
			$headers .= 'Content-Type: multipart/mixed; boundary="{'.$mimeBoundary."}\"\n";

			// output the text part for the e-mail
			$emailMessage  = "This is a multi-part message in MIME format.\n\n";
			$emailMessage .= '--{'.$mimeBoundary."}\n";
			$emailMessage .= $tmp."\n";
			$emailMessage .= "Content-Transfer-Encoding: 7bit\n\n";
			$emailMessage .= $message."\n\n";

			// Output all the different attached files
			for($i=0; $i<$this->_attachmentsLen; $i++){

				$emailMessage .= '--{'.$mimeBoundary."}\n";
				$emailMessage .= "Content-Type: application/octet-stream;\n";
				$emailMessage .= ' name="'.$this->_attachments[$i]['filename']."\"\n";
				$emailMessage .= "Content-Disposition: attachment;\n";
				$emailMessage .= ' filename="'.$this->_attachments[$i]['filename']."\"\n";
				$emailMessage .= "Content-Transfer-Encoding: base64\n\n";
				$emailMessage .= $this->_attachments[$i]['binary']."\n\n";
			}

		}else {

			$emailMessage = $message;
			$headers .= $tmp."\n";
		}

		// Send the mail
		try {

			if(!is_array($receiverAddress)){

				$receiverAddress = array($receiverAddress);
			}

			$result = true;

			foreach($receiverAddress as $receiver){

				$result = $result & mail($receiver, $subject, $emailMessage, $headers);
			}

			return $result;

		} catch (Exception $e) {

			return false;
		}
	}


	/**
	 * Generates an associative array that is ready to be used with the formFieldsPrettyFormat method, and checks if the specified parameters exist on the received HTTP variables.
	 *
	 * @param array $associativeMap Array containing the association that is required to generate the fields array. For example: array('Nombre' => 'name')
	 * @param string $method The method GET or POST where the values are found. Defaults to POST
	 * @param boolean $dieIfMissing Flag that tells the method to launch a die() if any of the specified http vars is missing (that would mean a hack attempt may be happening). True by default
	 *
	 * @return array The correct array to use with the formFieldsPrettyFormat method.
	 */
	public function formGetFieldsFromVars(array $associativeMap, $method = 'POST', $dieIfMissing = true){

		// Array where the result will be stored
		$res = array();

		// Store the post or get object depending on the requested method
		$vars = ($method == 'POST') ? $_POST : $_GET;

		// Store on the result array all the requested values, checking if they exist or not
		foreach ($associativeMap as $key => $value){

			if(!isset($vars[$value]) && $dieIfMissing){

				die('Security error formGetFieldsFromVars');
			}

			$res[$key] = $vars[$value];

		}

		return $res;
	}


	/**
	 * Used to automatically format the fields of a form to be viewed on a plain text email in a correctly human readable format, (without any HTML, simple plain text)
	 *
	 * @param array	 $fields  Associative array with the field names and respective values that will be formatted on the result. (example: ['Nombre' => 'Pepito', 'Telf' => '676879754'])
	 * @param string $message The message that's been sent, if any
	 * @param string $messageCaption The title that will be seen on the formatted email field for the message part
	 *
	 * @return string The formatted data, ready to be sent on an email body
	 */
	public function formFieldsPrettyFormat($fields, $message = '', $messageCaption = 'Comentario'){

		$result = '';

		// Concat all the received fields and the message. If result is empty, we will return an empty string.
		foreach ($fields as $f){
			$result .= $f;
		}

		if($result.$message == ''){
			return '';
		}

		// Generate the formatted result
		$result = '';

		foreach ($fields as $key => $f){

			$result .= $key.':   '.$f;
			$result .= "\n\n";
		}

		$result .= '-----------------------------------------------------------------------------------------';
		$result .= "\n\n";

		if($message != ''){

			$result .= $messageCaption.': ';
			$result .= "\n\n";
			$result .= $message;
		}

		return $result;

	}


	/**
	 * Method that is used to replace values on a template file (normally an HTML template), so it gets ready to be sent via mail.
	 *
	 * @param string $templatePath The full file path to the template, including the file name.
	 * @param array $valuesToReplace Associative array containing the values that must be found and replaced with the respective value on the given template.
	 * @param boolean $replaceConstants True if we want to replace the currently defined runtime constants on the template with their respective values. True by default
	 * @param string $replaceConstantsContaining Use this to filter which constants will be replaced on the template. For example, if we set this to 'LOC_' only the constants which name contains this string will be replaced on the template. Alert: To improve performance, it is recommended to define this parameter.
	 *
	 * @return string The read template with all the found values replaced and ready to be sent.
	 */
	public static function processTemplate($templatePath, array $valuesToReplace, $replaceConstants = true, $replaceConstantsContaining = ''){

		$filesManager = new FilesManager();

		// Read the template text from the specified path
		$templateText = $filesManager->readFile($templatePath);

		// We must sort all the values to replace by name lenght. Longest must be first, to prevent unwanted replacements. This is important!
		$valuesToReplaceKeys = array_keys($valuesToReplace);

		usort($valuesToReplaceKeys, function($a, $b) {

		    return strlen($b) - strlen($a);
		});

		// Replace all provided values
		foreach($valuesToReplaceKeys as $key){

			if (strpos($templateText, $key) === false) {

				trigger_error('MailManager::processTemplate Specified key <'.$key.'> not found on template', E_USER_WARNING);
			}

			$templateText = str_replace($key, $valuesToReplace[$key], $templateText);
		}

		// Replace all constant values
		if($replaceConstants){

			$constants = array_keys(get_defined_constants(false));

			// We must sort the constants by name lenght. Longest must be first, to prevent unwanted replacements. This is important!
			usort($constants, function($a, $b) {

			    return strlen($b) - strlen($a);
			});

			foreach($constants as $constantName){

				if($replaceConstantsContaining == ''){

					$templateText = str_replace($constantName, constant($constantName), $templateText);

				}else{

					if(strpos($constantName, $replaceConstantsContaining) !== false){

						$templateText = str_replace($constantName, constant($constantName), $templateText);
					}
				}
			}
		}

		return $templateText;
	}
}

?>