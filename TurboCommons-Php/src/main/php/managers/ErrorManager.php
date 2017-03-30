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

use org\turbocommons\src\main\php\model\BaseSingletonClass;
use org\turbocommons\src\main\php\utils\BrowserUtils;
use org\turbocommons\src\main\php\model\ErrorData;


/**
 * A singleton class that is used to encapsulate all the global error management.
 * It will give total control over the code exceptions, the way they are handled and notified.
 */
class ErrorManager extends BaseSingletonClass{


	/**
	 * Flag that tells the class to show or hide all the browser html error output.
	 * This is normally set to false cause html errors will give lots of information to malicious users,
	 * so setting it to true will generate an email warning notification if email error notifications are enabled
	 */
	public $errorsToBrowser = false;


	/**
	 * Enable or disable the email error notifications. If set to empty '' string, no mail notifications will happen.
	 * If an email address is specified, any error or warning that happens on the application will be sent to the specified address with all the detailed information.
	 * Defaults to: tecnic@edertone.com
	 */
	public $errorsToMail = '';


	/**
	 * List with error types to ignore when the current browser is detected as a bot or url crawler.
	 * When the browser is detected as a bot or crawler and an error happens, if the type of the error matches one of this list, no browser output or email alerts will be launched.
	 * Possible values for the list can be : ErrorData::PHP_WARNING, ErrorData::PHP_FATAL, ErrorData::JS, ...
	 */
	public $ignoreBrowserBots = [];


	/**
	 * List with error types to ignore so they will never generate any email alert or browser output.
	 * Note that once ignored, there will be no way to know if an error happened for these specified types.
	 * Possible values for the list can be : ErrorData::PHP_WARNING, ErrorData::PHP_FATAL, ErrorData::JS, ...
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
	 * Returns the global singleton instance.
	 *
	 * @return ErrorManager The singleton instance.
	 */
	public static function getInstance(){

		// This method is overriden from the singleton one simply to get correct
		// autocomplete annotations when returning the instance
		 $instance = parent::getInstance();

		 return $instance;
	}


	/**
	 * Use this method to initialize the error management class.
	 * The ErrorManager will not be doing anything till this method is called. Once intialized, the custom error handlers will take care of
	 * all the exceptions and errors that happen.
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
	 * Get the detailed backtrace to the current execution point.
	 *
	 * @return string The detailed execution trace to the point this method is called.
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
	 * If the php errors are setup to be output to the browser html code, this method will generate an alert that
	 * will be displayed as any other error message that is handled by this class.
	 * This verification is very important, cause showing the errors on browser is very dangerous as gives a lot of information to malicious users.
	 *
	 * @return void
	 */
	private function _checkBrowserOutputIsDisabled(){

		$message = '';

		if($this->errorsToBrowser){

			$message = 'ErrorManager::getInstance()->errorsToBrowser Are enabled.';
		}

		$displayErrors = false;

		switch (ini_get('display_errors')) {

			case '1':
			case 'On':
			case 'true':
				$displayErrors = true;
				break;

			default:
		}

		if($displayErrors){

			$message = 'PHP Errors are globally enabled (display_errors).';
		}

		if($message != ''){

			$errorData = new ErrorData();
			$errorData->type = ErrorData::PHP_WARNING;
			$errorData->fileName = __FILE__;
			$errorData->line = '';
			$errorData->message = $message.' Malicious users will get lots of information, please disable all php error browser output.';

			// Check if error needs to be sent by email
			if($this->errorsToMail != ''){

				$this->_sendErrorToMail($errorData);
			}

			// Check if error needs to be sent to browser output
			if($this->errorsToBrowser){

				$this->_sendErrorToBrowser($errorData);
			}
		}
	}


	/**
	 * Output the given error to browser with a pretty html format
	 *
	 * @param ErrorData $errorData An ErrorData entity instance containing the information of an exception to send.
	 *
	 * @return void
	 */
	private function _sendErrorToBrowser(ErrorData $errorData){

		// If php errors are ignored, we will skip the error
		if(in_array($errorData->type, $this->ignoreErrorTypes)){

			return;
		}

		// If the current browser is detected as a bot and we want to ignore bots and crawlers, we will skip the error
		if(in_array($errorData->type, $this->ignoreBrowserBots) && BrowserUtils::isABot()){

			return;
		}

		echo '<br><b>'.$errorData->message.'</b> -- '.$errorData->fileName;

		if(isset($errorData->line) && $errorData->line != ''){

			echo ' line '.$errorData->line;
		}

		echo '<br><br>';
	}


	/**
	 * Send a notification email with the specified error data. It also sends the following data:<br>
	 * <i>- Browser:</i> The browser info.<br>
	 * <i>- Cookies:</i> The current cookies state when the error occurred.<br><br>
	 *
	 * @param ErrorData $errorData see ErrorManager::_sendErrorToBrowser
	 *
	 * @see ErrorManager::_sendErrorToBrowser
	 *
	 * @return void
	 */
	private function _sendErrorToMail(ErrorData $errorData){

		// No error type means nothing to do
		if($errorData->type == '' || $errorData->fileName == ''){

			return;
		}

		// If php errors are ignored, we will skip the error
		if(in_array($errorData->type, $this->ignoreErrorTypes)){

			return;
		}

		// If the current browser is detected as a bot and we want to ignore bots and crawlers, we will skip the error
		if(in_array($errorData->type, $this->ignoreBrowserBots) && BrowserUtils::isABot()){

			return;
		}

		// If the error type is Javascript, we will skip non useful errors
		// TODO: aixo caldra tractar-ho al errormanager de javascript, aqui no te sentit
		if(strtolower($errorData->type) == 'javascript'){

			// Full js file path must contain the current project domain. Otherwise the error is being raised by an external js file and we won't care.
			if(strpos($errorData->fileName, $_SERVER['HTTP_HOST']) === false){

				return;
			}
		}

		// If the current number of sent messages exceeds 5, we will send no more
		if(count($this->_messagesSent) >= 5){

			return;
		}

		// We will split the filename from its path to send them sepparated via mail
		if(isset($errorData->fileName)){

			$name = str_replace('\\', '/', $errorData->fileName);

			if(strpos($name, '/') !== false){
				$name = substr(strrchr($name, '/'), 1);
			}
			$errorData->filePath = $errorData->fileName;
			$errorData->fileName = $name;
		}

		// Define the full URL
		$fullUrl = isset($errorData->fullUrl) ? $errorData->fullUrl : 'Unknown';

		// Define the referer url if exists
		$refererUrl = isset($errorData->referer) ? $errorData->referer : '';

		// Define the email subject
		$subject  = $errorData->type.' for '.str_replace('http://www.', '', $fullUrl).' (Script: '.$errorData->fileName.') IP:'.$_SERVER['REMOTE_ADDR'];

		// Define the email message
		$errorMessage  = 'Error type: '.(isset($errorData->type) ? $errorData->type : 'Unknown')."\n\n";
		$errorMessage .= 'IP: '.$_SERVER['REMOTE_ADDR']."\n\n";
		$errorMessage .= 'Line: '.(isset($errorData->line) ? $errorData->line : 'Unknown')."\n";
		$errorMessage .= 'File name: '.(isset($errorData->fileName) ? $errorData->fileName : 'Unknown')."\n";
		$errorMessage .= 'File path: '.(isset($errorData->filePath) ? $errorData->filePath : 'Unknown')."\n";
		$errorMessage .= 'Full URL: '.$fullUrl."\n";
		$errorMessage .= 'Referer URL: '.$refererUrl."\n\n";
		$errorMessage .= 'Message: '.(isset($errorData->message) ? $errorData->message : 'Unknown')."\n\n";
		$errorMessage .= 'Browser: '.$_SERVER['HTTP_USER_AGENT']."\n\n";
		$errorMessage .= 'Cookies: '.print_r($_COOKIE, true)."\n\n";

		if(isset($errorData->getParams)){
			$errorMessage .= 'GET params: '.$errorData->getParams."\n\n";
		}

		if(isset($errorData->postParams)){
			$errorMessage .= 'POST params: '.$errorData->postParams."\n\n";
		}

		// Create a string that will be used to compare already sent messages
		$messageCompare = $this->errorsToMail.$subject.$errorMessage;

		// Add more information related to memory and app context
		if(isset($errorData->usedMemory)){
			$errorMessage .= 'Used memory: '.$errorData->usedMemory.' of '.ini_get('memory_limit')."\n\n";
		}

		// Add the error trace if available
		if(isset($errorData->trace) && $errorData->trace != ''){

			$errorMessage .= 'Trace: '.substr($errorData->trace, 0, 20000).'...'."\n\n";
		}

		if(isset($errorData->context)){
			$errorMessage .= 'Context: '.substr($errorData->context, 0, 20000).'...'."\n\n";
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
			trigger_error($errorData->message.(isset($errorData->trace) ? $errorData->trace : ''), E_USER_WARNING);
		}

		// Store the message to the list of currently sent messages
		$this->_messagesSent[] = $messageCompare;
	}


	/**
	 * Read information form an error data received via POST, and convert it to an associative array
	 *
	 * @return array Associative array containing the error information that is found on the $_POST php object
	 */
	private function _getErrorDataFromPost(){

		$errorData = array();

		if(isset($_POST['type'])){
			$errorData->type = $_POST['type'];
		}

		if(isset($_POST['fullUrl'])){
			$errorData->fullUrl = $_POST['fullUrl'];
		}

		if(isset($_POST['referer'])){
			$errorData->referer = $_POST['referer'];
		}

		if(isset($_POST['fileName'])){
			$errorData->fileName = $_POST['fileName'];
		}

		if(isset($_POST['line'])){
			$errorData->line = $_POST['line'];
		}

		if(isset($_POST['message'])){
			$errorData->message = $_POST['message'];
		}

		if(isset($_POST['type'])){
			$errorData->type = $_POST['type'];
		}

		if(isset($_POST['getParams'])){
			$errorData->getParams = $_POST['getParams'];
		}

		if(isset($_POST['postParams'])){
			$errorData->postParams = $_POST['postParams'];
		}

		if(isset($_POST['trace'])){
			$errorData->trace = $_POST['trace'];
		}

		if(isset($_POST['context'])){
			$errorData->context = $_POST['context'];
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

				default:
			}

			$errorData = new ErrorData();
			$errorData->type = ErrorData::PHP_WARNING;
			$errorData->fileName = $errorFile;
			$errorData->line = $errorLine;
			$errorData->message = $type.': '.$errorMessage;
			$errorData->context = print_r($errorContext, true);

			// Check if error needs to be sent by email
			if(ErrorManager::getInstance()->errorsToMail != ''){

				ErrorManager::getInstance()->_sendErrorToMail($errorData);
			}

			// Check if error needs to be sent to browser output
			if(ErrorManager::getInstance()->errorsToBrowser){

				ErrorManager::getInstance()->_sendErrorToBrowser($errorData);
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

				$errorData = new ErrorData();
				$errorData->type = ErrorData::PHP_FATAL;
				$errorData->fileName = $error['file'];
				$errorData->line = $error['line'];
				$errorData->message = $error['message'];

				// Check if error needs to be sent by email
				if(ErrorManager::getInstance()->errorsToMail != ''){

					ErrorManager::getInstance()->_sendErrorToMail($errorData);
				}

				// Check if error needs to be sent to browser output
				if(ErrorManager::getInstance()->errorsToBrowser){

					ErrorManager::getInstance()->_sendErrorToBrowser($errorData);
				}
			}

			// This is called here to perform this verification at the end of the current script, and launch a warning if necessary
			ErrorManager::getInstance()->_checkBrowserOutputIsDisabled();
		});
	}
}

?>