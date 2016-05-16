<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\model;

use com\edertone\turboCommons\src\main\php\managers\ErrorManager;


/**
 * This entity is used by the ErrorManger class to encapuslate all the information of a single application exception.
 */
class ErrorData{

	/** The javascript error type */
	const JS = 'JS';


	/** The PHP warning error type */
	const PHP_WARNING = 'PHP_WARNING';


	/** The php fatal error type */
	const PHP_FATAL = 'PHP_FATAL';


	/** The error type: PHP, JAVASCRIPT, etc */
	public $type = '';


	/** The script file name where the error occurred */
	public $fileName = '';


	/** The script line where the error occurred */
	public $line = '';


	/** The error description message */
	public $message = '';


	/** The error context */
	public $context = '';


	/** The full browser URL when the error occurred */
	public $fullUrl = '';


	/**
	 * The url that created the link to the current full url. Useful to trace which url generated the one where the error happened.
	 * (It may not be always available)
	 */
	public $referer = '';


	/** The script used memory */
	public $usedMemory = '';


	/** The current PHP GET params state when the error occurred */
	public $getParams = '';


	/** The current PHP POST params state when the error occurred */
	public $postParams = '';


	/** The error trace */
	public $trace = '';


	/**
	 * Class constructor will collect all the useful data regarding the exception context and application state
	 */
	public function __construct(){

		$this->fullUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$this->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		$this->usedMemory = number_format(memory_get_usage() / 1048576, 2).'M';

		$this->getParams = print_r($_GET, true);

		$this->postParams = print_r($_POST, true);

		$this->trace = ErrorManager::getInstance()->getBackTrace();
	}
}

?>