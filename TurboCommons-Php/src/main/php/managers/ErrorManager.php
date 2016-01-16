<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\managers;


/**
 * A class that is used to encapsulate all the error management in a singleton class
 */
class ErrorManager extends BaseSingletonClass{


	/** The javascript error type*/
	const JS = 'JS';


	/** The PHP warning error type */
	const PHP_WARNING = 'PHP_WARNING';


	/** The php fatal error type */
	const PHP_FATAL = 'PHP_FATAL';


	/**
	 * Flag that tells the project to show or hide all the browser html error output.
	 * This is normally set to false cause html errors will give lots of information to malicious users, so setting it to true will generate an email warning notification if email error notifications are enabled
	 */
	public $errorsToBrowser = false;


	/**
	 * Enable or disable the email error notifications. If set to empty '' string, no mail notifications will happen.
	 * If an email address is specified, any error or warning that happens on the application will be sent to the specified address with all the detailed information.
	 * Defaults to: tecnic@edertone.com
	 */
	public $errorsToMail = 'tecnic@edertone.com';


	/**
	 * List with error types to ignore when the current browser is detected as a bot or url crawler.
	 * When the browser is detected as a bot or crawler and an error happens, if the type of the error matches one of this list, no browser output or email alerts will be launched.
	 * Possible values for the list can be : ErrorManager::PHP_WARNING, ErrorManager::PHP_FATAL, ErrorManager::JS, ...
	 */
	public $ignoreBrowserBots = [self::JS];


	/**
	 * List with error types to ignore so they will never generate any email alert or browser output.
	 * Note that once ignored, there will be no way to know if an error happened for these specified types.
	 * Possible values for the list can be : ErrorManager::PHP_WARNING, ErrorManager::PHP_FATAL, ErrorManager::JS, ...
	 * TODO: aixo no esta implementat del tot
	 */
	public $ignoreErrorTypes = [];


	/**
	 * Tells if the initialize() method has been called and therefore the php error management is being handled by this class
	 */
	private $_initialized = false;


	/**
	 * Auxiliary private array that stores all the messages that have been sent via mail on the current execution period, to prevent sending the same notifications multiple times
	 */
	private $_messagesSent = [];


	/**
	 * Use this method to initialize the error management class.
	 * The ErrorManager will not be doing anything till this method is called. Once intialized, the custom error handlers will take care of the generated app errors.
	 * This method should be called only once. Subsequent calls will do nothing.
	 *
	 * @return void
	 */
	public function initialize(){

		if(!$this->_initialized){

			// Disable the native php browser errors output.
			// If the errorsToBrowser property is true, this class will take care of showing them via browser output.
			ini_set('display_errors', '0');

			// Initialize the handlers that will take care of the errors
			$this->_setWarningErrorHandler();

			$this->_setFatalErrorHandler();

			$this->_initialized = true;
		}
	}


	/**
	 * Get the detailed backtrace to the current execution point, normally used to check what happened before an error.
	 *
	 * @return string The detailed execution trace to the current code point
	 */
	public function getBackTrace(){

		ob_start();
		debug_print_backtrace();
		$trace = ob_get_contents();
		ob_end_clean();

		// Remove first item from backtrace as it's this function which is redundant.
		$trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1);

		// Renumber backtrace items.
		$trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace);

		return $trace;
	}


	/**
	 * Generates an associative array containing all the detailed information for an error.
	 *
	 * @param string $type The type of error we are creating. Possible values can be : ErrorManager::PHP_WARNING, ErrorManager::PHP_FATAL, ErrorManager::JS, ...
	 * @param string $fileName The filename where the error happened
	 * @param string $line The line of code where the error happened
	 * @param string $message The error message
	 * @param string $context The context for the error. Not always available.
	 *
	 * @return multitype:string unknown mixed Ambigous <string, unknown>
	 */
	public function createErrorData($type, $fileName, $line, $message, $context = ''){

		$errorData = array();

		$errorData['type'] = $type;

		$errorData['fileName'] = $fileName;

		$errorData['line'] = $line;

		$errorData['message'] = $message;

		$errorData['context'] = $context;

		$errorData['fullUrl'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$errorData['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		$errorData['usedMemory'] = number_format(memory_get_usage() / 1048576, 2).'M';

		$errorData['getParams'] = print_r($_GET, true);

		$errorData['postParams'] = print_r($_POST, true);

		$errorData['trace'] = $this->getBackTrace();

		return $errorData;
	}


	/**
	 * If the php errors are setup to be output to the browser html code, this method will generate an alert that will be displayed as any other error message that is handled by this class.
	 * This verification is very important, cause showing the errors on browser is very dangerous as gives a lot of information to malicious users.
	 *
	 * @return void
	 */
	public function checkBrowserOutputIsDisabled(){

		$message = '';

		if($this->errorsToBrowser){

			$message = 'ErrorManager::getInstance()->errorsToBrowser Are enabled.';
		}

		$displayErrors = false;

		switch (ini_get('display_errors')) {

			case '1':
				$displayErrors = true;
				break;

			case 'On':
				$displayErrors = true;
				break;

			case 'true':
				$displayErrors = true;
				break;
		}

		if($displayErrors){

			$message = 'PHP Errors are globally enabled (display_errors).';
		}

		if($message != ''){

			$errorData = $this->createErrorData(self::PHP_WARNING, '', '', $message.' Malicious users will get lots of information, please disable all php error browser output.');

			// Check if error needs to be sent by email
			if($this->errorsToMail != ''){

				$this->sendErrorToMail($errorData);
			}

			// Check if error needs to be sent to browser output
			if($this->errorsToBrowser){

				$this->sendErrorToBrowser($errorData);
			}
		}
	}


	/**
	 * Output the given error to browser with a pretty html format
	 *
	 * @param array $errorData Associative array containing the error data.<br><br>
	 * Data that should be defined:<br>
	 * <i>- type:</i> The error type: PHP, JAVASCRIPT, etc<br>
	 * <i>- fileName:</i> The script file name where the error occurred<br>
	 * <i>- line:</i> The script line where the error occurred<br>
	 * <i>- fullUrl:</i> The full browser URL when the error occurred<br>
	 * <i>- referer:</i> The url that created the link to the current full url. Useful to trace which url generated this one. (It may not be always available)
	 * <i>- message:</i> The error description message<br><br>
	 *
	 * Extra error data (not mandatory):<br>
	 * <i>- usedMemory:</i> The script used memory<br>
	 * <i>- getParams:</i> The current PHP GET params state when the error occurred<br>
	 * <i>- postParams:</i> The current PHP POST params state when the error occurred<br>
	 * <i>- trace:</i> The error trace<br>
	 * <i>- context:</i> The error context<br><br>

	 * @return void
	 */
	public function sendErrorToBrowser(array $errorData){

		// If php errors are ignored, we will skip the error
		if(in_array($errorData['type'], $this->ignoreErrorTypes)){

			return;
		}

		// If the current browser is detected as a bot and we want to ignore bots and crawlers, we will skip the error
		if(in_array($errorData['type'], $this->ignoreBrowserBots) && BrowserUtils::isABot()){

			return;
		}

		echo '<br><b>'.$errorData['message'].'</b> '.$errorData['fileName'];

		if(isset($errorData['line'])){

			if($errorData['line'] != ''){

				echo ' line '.$errorData['line'];
			}
		}

		echo '<br><br>';
	}


	/**
	 * Send a notification email with the specified error data. It also sends the following data:<br>
	 * <i>- Browser:</i> The browser info.<br>
	 * <i>- Cookies:</i> The current cookies state when the error occurred.<br><br>
	 *
	 * @param array $errorData see ErrorManager::sendErrorToBrowser
	 *
	 * @see ErrorManager::sendErrorToBrowser
	 *
	 * @return void
	 */
	public function sendErrorToMail(array $errorData){

		// No error type means nothing to do
		if(!isset($errorData['type']) || !isset($errorData['fileName'])){

			return;
		}

		// If php errors are ignored, we will skip the error
		if(in_array($errorData['type'], $this->ignoreErrorTypes)){

			return;
		}

		// If the current browser is detected as a bot and we want to ignore bots and crawlers, we will skip the error
		if(in_array($errorData['type'], $this->ignoreBrowserBots) && BrowserUtils::isABot()){

			return;
		}

		// If the error type is Javascript, we will skip non useful errors
		// TODO: aixo caldra tractar-ho al errormanager de javascript, aqui no te sentit
		if(strtolower($errorData['type']) == 'javascript'){

			// Full js file path must contain the current project domain. Otherwise the error is being raised by an external js file and we won't care.
			if(strpos($errorData['fileName'], $_SERVER['HTTP_HOST']) === false){

				return;
			}
		}

		// If the current number of sent messages exceeds 5, we will send no more
		if(count($this->_messagesSent) >= 5){

			return;
		}

		// We will split the filename from its path to send them sepparated via mail
		if(isset($errorData['fileName'])){

			$name = str_replace('\\', '/', $errorData['fileName']);

			if(strpos($name, '/') !== false){
				$name = substr(strrchr($name, '/'), 1);
			}
			$errorData['filePath'] = $errorData['fileName'];
			$errorData['fileName'] = $name;
		}

		// Define the full URL
		$fullUrl = isset($errorData['fullUrl']) ? $errorData['fullUrl'] : 'Unknown';

		// Define the referer url if exists
		$refererUrl = isset($errorData['referer']) ? $errorData['referer'] : '';

		// Define the email subject
		$subject  = $errorData['type'].' for '.str_replace('http://www.', '', $fullUrl).' (Script: '.$errorData['fileName'].') IP:'.$_SERVER['REMOTE_ADDR'];

		// Define the email message
		$errorMessage  = 'Error type: '.(isset($errorData['type']) ? $errorData['type'] : 'Unknown')."\n\n";
		$errorMessage .= 'IP: '.$_SERVER['REMOTE_ADDR']."\n\n";
		$errorMessage .= 'Line: '.(isset($errorData['line']) ? $errorData['line'] : 'Unknown')."\n";
		$errorMessage .= 'File name: '.(isset($errorData['fileName']) ? $errorData['fileName'] : 'Unknown')."\n";
		$errorMessage .= 'File path: '.(isset($errorData['filePath']) ? $errorData['filePath'] : 'Unknown')."\n";
		$errorMessage .= 'Full URL: '.$fullUrl."\n";
		$errorMessage .= 'Referer URL: '.$refererUrl."\n\n";
		$errorMessage .= 'Message: '.(isset($errorData['message']) ? $errorData['message'] : 'Unknown')."\n\n";
		$errorMessage .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\n\n";
		$errorMessage .= 'Cookies: '.print_r($_COOKIE, true)."\n\n";

		if(isset($errorData['getParams'])){
			$errorMessage .= 'GET params: '.$errorData['getParams']."\n\n";
		}

		if(isset($errorData['postParams'])){
			$errorMessage .= 'POST params: '.$errorData['postParams']."\n\n";
		}

		// Create a string that will be used to compare already sent messages
		$messageCompare = $this->errorsToMail.$subject.$errorMessage;

		// Add more information related to memory and app context
		if(isset($errorData['usedMemory'])){
			$errorMessage .= 'Used memory: '.$errorData['usedMemory'].' of '.ini_get('memory_limit')."\n\n";
		}

		// Add the error trace if available
		if(isset($errorData['trace'])){

			if($errorData['trace'] != ''){

				$errorMessage .= 'Trace: '.substr($errorData['trace'], 0, 20000).'...'."\n\n";
			}
		}

		if(isset($errorData['context'])){
			$errorMessage .= 'Context: '.substr($errorData['context'], 0, 20000).'...'."\n\n";
		}

		// If this same error message has already been sent, we wont send it again.
		foreach ($this->_messagesSent as $m){

			if($m == $messageCompare){

				return;
			}
		}

		// If mail can't be queued, or we are in a localhost enviroment without email cappabilities,
		// we will launch a warning with the error information, so it does not get lost and goes to the php error logs.
		// @codingStandardsIgnoreStart
		if(!@mail($this->errorsToMail, $subject, $errorMessage) || $_SERVER['HTTP_HOST'] == 'localhost'){

			// @codingStandardsIgnoreEnd
			trigger_error($errorData['message'].(isset($errorData['trace']) ? $errorData['trace'] : ''), E_USER_WARNING);
		}

		// Store the message to the list of currently sent messages
		array_push($this->_messagesSent, $messageCompare);
	}


	/**
	 * Read information form an error data received via POST, and convert it to an associative array
	 *
	 * @return array Associative array containing the error information that is found on the $_POST php object
	 */
	public function getErrorDataFromPost(){

		$errorData = array();

		if(isset($_POST['type'])){
			$errorData['type'] = $_POST['type'];
		}

		if(isset($_POST['fullUrl'])){
			$errorData['fullUrl'] = $_POST['fullUrl'];
		}

		if(isset($_POST['referer'])){
			$errorData['referer'] = $_POST['referer'];
		}

		if(isset($_POST['fileName'])){
			$errorData['fileName'] = $_POST['fileName'];
		}

		if(isset($_POST['line'])){
			$errorData['line'] = $_POST['line'];
		}

		if(isset($_POST['message'])){
			$errorData['message'] = $_POST['message'];
		}

		if(isset($_POST['type'])){
			$errorData['type'] = $_POST['type'];
		}

		if(isset($_POST['getParams'])){
			$errorData['getParams'] = $_POST['getParams'];
		}

		if(isset($_POST['postParams'])){
			$errorData['postParams'] = $_POST['postParams'];
		}

		if(isset($_POST['trace'])){
			$errorData['trace'] = $_POST['trace'];
		}

		if(isset($_POST['context'])){
			$errorData['context'] = $_POST['context'];
		}

		return $errorData;
	}


	/**
	 * Set the error handler to manage non fatal php errors. This overrides the standard php error handler
	 *
	 * @return void
	 */
	private function _setWarningErrorHandler(){

		set_error_handler(function ($errorType, $errorMessage, $errorFile, $errorLine, $errorContext){

			$type = 'PHP ';

			switch($errorType){

				case E_WARNING:
					$type .= 'E_WARNING ';
					break;

				case E_NOTICE:
					$type .= 'E_NOTICE ';
					break;

				case E_USER_ERROR:
					$type .= 'E_USER_ERROR';
					break;

				case E_USER_WARNING:
					$type .= 'E_USER_WARNING';
					break;

				case E_USER_NOTICE:
					$type .= 'E_USER_NOTICE';
					break;

				case E_RECOVERABLE_ERROR:
					$type .= 'E_RECOVERABLE_ERROR';
					break;

				case E_DEPRECATED:
					$type .= 'E_DEPRECATED';
					break;

				case E_USER_DEPRECATED:
					$type .= 'E_USER_DEPRECATED';
					break;

				case E_ALL:
					$type .= 'E_ALL ';
					break;
			}

			$errorData = ErrorManager::getInstance()->createErrorData(ErrorManager::PHP_WARNING, $errorFile, $errorLine, $type.': '.$errorMessage, print_r($errorContext, true));

			// Check if error needs to be sent by email
			if(ErrorManager::getInstance()->errorsToMail != ''){

				ErrorManager::getInstance()->sendErrorToMail($errorData);
			}

			// Check if error needs to be sent to browser output
			if(ErrorManager::getInstance()->errorsToBrowser){

				ErrorManager::getInstance()->sendErrorToBrowser($errorData);
			}
		});
	}


	/**
	 * Set an error handler to manage fatal php errors.
	 *
	 * @return void
	 */
	private function _setFatalErrorHandler(){

		register_shutdown_function(function () {

			$error = error_get_last();

			if($error['type'] == E_ERROR || $error['type'] == E_USER_ERROR || $error['type'] == E_CORE_ERROR ||
					$error['type'] == E_COMPILE_ERROR || $error['type'] == E_RECOVERABLE_ERROR || $error['type'] == E_PARSE){

				$errorData = ErrorManager::getInstance()->createErrorData(ErrorManager::PHP_FATAL, $error['file'], $error['line'], $error['message']);

				// Check if error needs to be sent by email
				if(ErrorManager::getInstance()->errorsToMail != ''){

					ErrorManager::getInstance()->sendErrorToMail($errorData);
				}

				// Check if error needs to be sent to browser output
				if(ErrorManager::getInstance()->errorsToBrowser){

					ErrorManager::getInstance()->sendErrorToBrowser($errorData);
				}
			}

			// This is called here to perform this verification at the end of the current script, and launch a warning if necessary
			ErrorManager::getInstance()->checkBrowserOutputIsDisabled();
		});
	}
}

?>