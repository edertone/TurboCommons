"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

/** @namespace */
var org_turbocommons_src_main_js_managers = org_turbocommons_src_main_js_managers || {};


/**
 * Class that allows us to manage validation in an encapsulated way.
 * We can create as many instances as we want, and each instance will store the validation history and global validation state,
 * so we can use this class to validate complex forms or multiple elements globally
 * 
 * @class
 */
org_turbocommons_src_main_js_managers.ValidationManager = function(){


	/** Stores the current state for the applied validations (VALIDATION_OK / VALIDATION_WARNING / VALIDATION_ERROR) */
	this.validationStatus = 0;


	/** Stores the list of generated warning or error messages, in the same order as happened. */
	this.failedMessagesList = [];


	/** Stores the list of failure status codes, in the same order as happened. */
	this.failedStatusList = [];


	/** Stores the last error message generated by a validation error / warning or empty string if no validation errors happened */
	this.lastMessage = '';
};


/** 
 * Constant that defines the correct validation status
 * 
 * @constant {int}
 */
org_turbocommons_src_main_js_managers.ValidationManager.VALIDATION_OK = 0;


/** 
 * Constant that defines the warning validation status
 *
 * @constant {int}
 */
org_turbocommons_src_main_js_managers.ValidationManager.VALIDATION_WARNING = 1;


/** 
 * Constant that defines the error validation status
 * 
 * @constant {int}
 */
org_turbocommons_src_main_js_managers.ValidationManager.VALIDATION_ERROR = 2;


/**
 * Validation will fail if specified value is not a true boolean value
 *
 * @param {boolean} value A boolean expression to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isTrue = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not true' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = (value !== true) ? errorMessage : '';

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not a boolean type
 *
 * @param {boolean} value The bool to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isBoolean = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a boolean' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = (typeof (value) !== "boolean") ? errorMessage : '';

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not numeric
 *
 * @param {Number} value The element to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isNumeric = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a number' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = (!(!isNaN(parseFloat(value)) && isFinite(value))) ? errorMessage : '';

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not a string
 *
 * @param {string} value The element to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isString = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a string' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = (!(typeof value === 'string' || value instanceof String)) ? errorMessage : '';

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not a correct internet url
 *
 * @param {string} value The element to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isUrl = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not an URL' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

	if(!validationManager.isFilledIn(value) || !validationManager.isString(value)){

		res = errorMessage;

	}else{

		var res = !value.match(/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:/~+#-]*[\w@?^=%&amp;/~+#-])?/) ? errorMessage : '';
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not an array
 *
 * @param {array} value The array to validate
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isArray = function(value, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not an array' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = (Object.prototype.toString.call(value) !== '[object Array]') ? errorMessage : '';

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified text is empty.<br>
 * See Stringutils.isEmpty to understand what is considered as an empty text
 * 
 * @see Stringutils.isEmpty
 *
 * @param {string} value A text that must not be empty.
 * @param {array} otherEmptyKeys Optional array containing a list of string values that will be considered as empty for the given string. This can be useful in some cases when we want to consider a string like 'NULL' as an empty string.	 
 * @param {string} errorMessage The error message that will be generated if validation fails
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns {boolean} False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isFilledIn = function(value, otherEmptyKeys, errorMessage, isWarning){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	otherEmptyKeys = (otherEmptyKeys === undefined) ? null : otherEmptyKeys;
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is required' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;

	var res = '';

	if(ns.StringUtils.isEmpty(value, otherEmptyKeys)){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * TODO
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isDate = function(value, required, inputFormat, errorMessage, isWarning){

	// TODO - review all of this method. May be necessary to change it all

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a date' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;
	inputFormat = (inputFormat === undefined) ? "dd/mm/yyyy" : inputFormat;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(value, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(value)){

			return true;
		}
	}

	var res = '';

	return (res == '');
};


/**
 * Validation will fail if specified value is not a valid email
 *
 * @param text The text to validate
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isMail = function(text, errorMessage, isWarning, required){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not an email' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	// Test string for a valid email
	var testExp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

	if(!testExp.test(text)){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified value is not the same as the specified original one
 *
 * @param text The text to validate that must be equal to the original one
 * @param originalText The original source text to compare
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isEqualToValue = function(text, originalText, errorMessage, isWarning, required){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'values are not equal' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	// Check text and original text
	if(text != originalText){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified text does not contain a minimum of N words.
 *
 * @param text The text to validate
 * @param minWords The minimum number of words that must be present on the text to validate
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 * @param wordSeparator The character that is considered as the words separator
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isMinimumWords = function(text, minWords, errorMessage, isWarning, required, wordSeparator){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value does not have the minimum words' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;
	wordSeparator = (wordSeparator === undefined) ? ' ' : wordSeparator;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	if(StringUtils.countWords(text, wordSeparator) < minWords){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified text does not match with a valid Spanish identification fiscal number
 *
 * @param text The text to validate
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isNIF = function(text, errorMessage, isWarning, required){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a NIF' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	var isNif = false;
	var number;
	var l;
	var letter;
	var regExp = /^[XYZ]?\d{5,8}[A-Z]$/;
	var nif = text.toUpperCase();

	if(regExp.test(nif) === true){

		number = nif.substr(0, nif.length - 1);
		number = number.replace('X', 0);
		number = number.replace('Y', 1);
		number = number.replace('Z', 2);

		l = nif.substr(nif.length - 1, 1);

		number = number % 23;

		letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
		letter = letter.substring(number, number + 1);

		isNif = (letter == l);
	}

	if(!isNif){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified text does not has a minimum length
 *
 * @param text The text to validate
 * @param minLen The minimum length for the specified text
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isMinimumLength = function(text, minLen, errorMessage, isWarning, required){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value does not meet minimum length' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	if(text.length < minLen){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validation will fail if specified text does not contain a valid postal code
 *
 * @param text The text to validate
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isPostalCode = function(text, errorMessage, isWarning, required){

	// TODO: This is really tough
};


/**
 * Validation will fail if specified value is not a correct phone number
 *
 * @param text The text to validate
 * @param errorMessage The error message that will be generated if validation fails
 * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 * @param required True means the value is required
 *
 * @returns False in case the validation fails or true if validation succeeds.
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isPhone = function(text, errorMessage, isWarning, required){

	// Alias namespace
	var ns = org_turbocommons_src_main_js_utils;

	// Set optional parameters default values
	errorMessage = (ns.StringUtils.isEmpty(errorMessage)) ? 'value is not a phone' : errorMessage;
	isWarning = (isWarning === undefined) ? false : isWarning;
	required = (required === undefined) ? true : required;

	// Deferr required validation to the isRequired method
	if(required){

		if(!this.isRequired(text, errorMessage, isWarning)){

			return false;
		}

	}else{

		if(StringUtils.isEmpty(text)){

			return true;
		}
	}

	var res = '';

	var phoneValid = true;

	// Phone numeric digits must be 5 at least
	var digitsCount = text.replace(/[^0-9]/g, "").length;

	if(digitsCount < 6 || digitsCount > 15){

		phoneValid = false;
	}

	// Check that there are only allowed characters
	var allowedChars = "+- 1234567890()";

	for(var i = 0; i < text.length; i++){

		if(allowedChars.indexOf(text.charAt(i)) < 0){

			phoneValid = false;
		}
	}

	if(!phoneValid){

		res = errorMessage;
	}

	this._updateValidationStatus(res, isWarning);

	return (res == '');
};


/**
 * Validates the specified form using the different parameters specified as "data-" attributes which can be placed on the form itself or any of the elements to validate. Note that attributes defined on the elements take precedence over the attributes defined on the form element.
 * Following attributes can be used:<br><br>
 * - data-validationType: Specifies the type of validation applied (multiple types can be specified sepparated with spaces). Possible values are:<br>
 * &emsp;&emsp;required: The value is mandatory<br>
 * &emsp;&emsp;mail: Value must be an email address<br>
 * &emsp;&emsp;equalTo-selector: Value must be equal to the element (or elements) defined by the jquery 'selector'<br>
 * &emsp;&emsp;minWords-n: Value must contain at least n words<br>
 * &emsp;&emsp;nif: Value must be a valid spanish fiscal number<br>
 * &emsp;&emsp;minLen-n: Value string length must be at least n characters<br> 
 * &emsp;&emsp;postalCode: Value must be a valid postal code<br>
 * &emsp;&emsp;phone: Value must be a valid phone number<br><br>
 * - data-validationError: The error that will be generated once a validation for the specified element fails. We can define custom errors for each validation type by adding -validationType to the attribute.
 * For example: data-validationError-required="error message .." will apply only to the required validation type.  
 * 
 * @param form A jquery object representing the form to validate. We can pass an htm form element, or also a div containing inputs, buttons, and so.
 * @param throwAlert True by default. If enabled, a javascript alert will be thrown with the validation error when any validation fails.
 * @param invalidElementClass '' by default. Css class that will be applied to the elements that fail validation, so we can style them the way we want.
 * 
 * @returns {array} Empty array if validation was OK or an array containing the list of the different objects that have generated a warning or error message, in the same order as they happened
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.isHtmlFormValid = function(form, throwAlert, invalidElementClass){

	// TODO - This method must be tested intensively

	// Set optional parameters default values
	throwAlert = (throwAlert === undefined) ? true : throwAlert;
	invalidElementClass = (invalidElementClass === undefined) ? '' : invalidElementClass;

	var validationManager = this;

	// As this method performs multiple validations, this validation manager object is reset at the beginning.
	validationManager.reset();

	var res = true;
	var failedElementsList = [];

	var errorMessage = StringUtils.isEmpty(form.attr("data-validationError")) ? 'Invalid form' : form.attr("data-validationError");

	// Loop all the form elements
	form.find(':input,textarea').each(function(){

		var validationTypes = $(this).attr("data-validationType");

		if(!StringUtils.isEmpty(validationTypes)){

			// Get the validation error message if it exists
			var validationError = $(this).attr("data-validationError");

			if(StringUtils.isEmpty(validationError)){

				validationError = errorMessage;
			}

			// Split the validation type in case there's more than one specified
			validationTypes = validationTypes.split(' ');

			// Get the element value to validate
			var elementValue = $(this).val();

			if($(this).is(':checkbox')){

				elementValue = $(this).is(":checked") ? 'true' : '';
			}

			// Loop all the validation types specified for this element
			for(var i = 0; i < validationTypes.length; i++){

				if(invalidElementClass != ''){

					$(this).removeClass(invalidElementClass);
				}

				// Split the validation type as it may contain type-value for some of the types like 'equalto-id'
				var validationType = validationTypes[i].split('-');

				// Check if a custom error for this validation type is specified
				if(!StringUtils.isEmpty($(this).attr("data-validationError-" + validationType[0]))){

					validationError = $(this).attr("data-validationError-" + validationType[0]);
				}

				switch(validationType[0]){

					case 'required':
						res = validationManager.isRequired(elementValue, validationError);
						break;

					case 'mail':
						res = validationManager.isMail(elementValue, validationError, false, false);
						break;

					case 'equalTo':
						res = validationManager.isEqualToValue(elementValue, $(validationType[1]).val(), validationError, false, false);
						break;

					case 'minWords':
						res = validationManager.isMinimumWords(elementValue, validationType[1], validationError, false, false, ' ');
						break;

					case 'nif':
						res = validationManager.isNIF(elementValue, validationError, false, false);
						break;

					case 'minLen':
						res = validationManager.isMinimumLength(elementValue, validationType[1], validationError, false, false);
						break;

					case 'postalCode':
						res = validationManager.isPostalCode(elementValue, validationError, false, false);
						break;

					case 'phone':
						res = validationManager.isPhone(elementValue, validationError, false, false);
						break;

					default:
						throw new Error("ValidationManager.isHtmlFormValid - Unknown validation type: " + validationType[0]);
				}

				if(!res){

					if(invalidElementClass != ''){

						$(this).addClass(invalidElementClass);
					}

					if(throwAlert){

						alert(validationManager.lastMessage);
					}

					failedElementsList[failedElementsList.length - 1] = $(this);
				}
			}
		}
	});

	return failedElementsList;
};


/** 
 * Reinitialize the validation status for this class
 * 
 * @returns void
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype.reset = function(){

	// alias namespace
	var ns = org_turbocommons_src_main_js_managers;

	this.validationStatus = ns.ValidationManager.VALIDATION_OK;
	this.failedMessagesList = [];
	this.failedStatusList = [];
	this.lastMessage = '';
};


/**
 * Update the class validation Status depending on the provided error message.
 *
 * @param {string} errorMessage The error message that's been generated from a previously executed validation method
 * @param {boolean} isWarning Tells if the validation fail will be processed as a validation error or a validation warning
 *
 * @returns void
 */
org_turbocommons_src_main_js_managers.ValidationManager.prototype._updateValidationStatus = function(errorMessage, isWarning){

	// alias namespace
	var ns = org_turbocommons_src_main_js_managers;

	// If we are currently in an error state, nothing to do
	if(this.validationStatus == ns.ValidationManager.VALIDATION_ERROR){

		return;
	}

	// If the validation fails, we must change the validation status
	if(errorMessage != ""){

		this.failedMessagesList.push(errorMessage);

		if(isWarning){

			this.failedStatusList.push(ns.ValidationManager.VALIDATION_WARNING);
			this.lastMessage = errorMessage;

		}else{

			this.failedStatusList.push(ns.ValidationManager.VALIDATION_ERROR);
			this.lastMessage = errorMessage;
		}

		if(isWarning && this.validationStatus != ns.ValidationManager.VALIDATION_ERROR){

			this.validationStatus = ns.ValidationManager.VALIDATION_WARNING;

		}else{

			this.validationStatus = ns.ValidationManager.VALIDATION_ERROR;
		}
	}
};