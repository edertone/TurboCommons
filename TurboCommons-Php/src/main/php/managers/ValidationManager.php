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

use org\turbocommons\src\main\php\model\BaseStrictClass;


/**
 * Class that allows us to manage validation in an encapsulated way.
 * We can create as many instances as we want, and each instance will store the validation history and global validation state,
 * so we can use this class to validate complex forms or multiple elements globally
 */
class ValidationManager extends BaseStrictClass{


	/**
	 * Constant that defines the correct validation status
	 */
	const VALIDATION_OK = 0;


	/**
	 * Constant that defines the warning validation status
	 */
	const VALIDATION_WARNING = 1;


	/**
	 * Constant that defines the error validation status
	 */
	const VALIDATION_ERROR = 2;


	/** Stores the current state for the applied validations (VALIDATION_OK / VALIDATION_WARNING / VALIDATION_ERROR) */
	public $validationStatus = 0;


	/** Stores the list of jquery elements that have generated a warning or error message, in the same order as happened. */
	public $failedElementsList = [];


	/** Stores the list of generated warning or error messages, in the same order as happened. */
	public $failedMessagesList = [];


	/** Stores the list of failure status codes, in the same order as happened. */
	public $failedStatusList = [];


	/** Stores the last error message generated by a validation error / warning or empty string if no validation errors happened */
	public $lastMessage = '';


	/**
	 * Validation will fail if specified value is not a true boolean value
	 *
	 * @param boolean $value A boolean expression to validate
	 * @param string $errorMessage The error message that will be generated if validation fails
	 * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
	 *
	 * @return boolean False in case the validation fails or true if validation succeeds.
	 */
	public function isTrue($value, $errorMessage = '', $isWarning = false){

		// Set optional parameters default values
		$errorMessage = ($errorMessage === '') ? 'value is not true' : $errorMessage;

		$res = (!$value) ? $errorMessage : '';

		$this->_updateValidationStatus($res, $isWarning);

		return ($res == '');
	}


	/**
	 * Validation will fail if specified value is not numeric
	 *
	 * @param Number $value The number to validate
	 * @param string $errorMessage The error message that will be generated if validation fails
	 * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
	 *
	 * @return boolean False in case the validation fails or true if validation succeeds.
	 */
	public function isNumeric($value, $errorMessage = '', $isWarning = false){

		// Set optional parameters default values
		$errorMessage = ($errorMessage === '') ? 'value is not a number' : $errorMessage;

		$res = (!is_numeric($value)) ? $errorMessage : '';

		$this->_updateValidationStatus($res, $isWarning);

		return ($res == '');
	}


	/**
	 * Validation will fail if specified value is not a string
	 *
	 * @param string $value The element to validate
	 * @param string $errorMessage The error message that will be generated if validation fails
	 * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
	 *
	 * @returns boolean False in case the validation fails or true if validation succeeds.
	 */
	public function isString($value, $errorMessage = '', $isWarning = false){

		// Set optional parameters default values
		$errorMessage = ($errorMessage === '') ? 'value is not a string' : $errorMessage;

		$res = (!is_string($value)) ? $errorMessage : '';

		$this->_updateValidationStatus($res, $isWarning);

		return ($res == '');
	}


	/**
	 * Validation will fail if specified value is not an array
	 *
	 * @param array value The array to validate
	 * @param string errorMessage The error message that will be generated if validation fails
	 * @param boolean isWarning Tells if the validation fail will be processed as a validation error or a validation warning
	 *
	 * @return boolean False in case the validation fails or true if validation succeeds.
	 */
	public function isArray($value, $errorMessage = '', $isWarning = false){

		// Set optional parameters default values
		$errorMessage = ($errorMessage === '') ? 'value is not an array' : $errorMessage;

		$res = (!is_array($value)) ? $errorMessage : '';

		$this->_updateValidationStatus($res, $isWarning);

		return ($res == '');
	}


	/**
	 * TODO - translate from JS
	 */
	public function isRequired(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isDate(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isMail(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isEqualToValue(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isMinimumWords(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isNIF(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isMinimumLength(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isPostalCode(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isPhone(){

		// TODO - translate from JS
	}


	/**
	 * TODO - translate from JS
	 */
	public function isHtmlFormValid(){

		// TODO - translate from JS
	}


	/**
	 * Reinitialize the validation status for this class
	 *
	 * @return void
	 */
	public function reset(){

		$this->validationStatus = self::VALIDATION_OK;
		$this->failedElementsList = [];
		$this->failedMessagesList = [];
		$this->failedStatusList = [];
		$this->lastMessage = '';
	}


	/**
	 * Update the class validation Status depending on the provided error message.
	 *
	 * @param string $errorMessage The error message that's been generated from a previously executed validation method
	 * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
	 *
	 * @return void
	 */
	private function _updateValidationStatus($errorMessage, $isWarning){

		// If we are currently in an error state, nothing to do
		if($this->validationStatus == self::VALIDATION_ERROR){

			return;
		}

		// If the validation fails, we must change the validation status
		if($errorMessage != ""){

			array_push($this->failedElementsList, null);
			array_push($this->failedMessagesList, $errorMessage);

			if($isWarning){

				array_push($this->failedStatusList, self::VALIDATION_WARNING);
				$this->lastMessage = $errorMessage;

			}else{

				array_push($this->failedStatusList, self::VALIDATION_ERROR);
				$this->lastMessage = $errorMessage;
			}

			if($isWarning && $this->validationStatus != self::VALIDATION_ERROR){

				$this->validationStatus = self::VALIDATION_WARNING;

			}else{

				$this->validationStatus = self::VALIDATION_ERROR;
			}
		}
	}

}

?>