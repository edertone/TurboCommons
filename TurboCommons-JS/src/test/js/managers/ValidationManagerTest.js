"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

//Import namespaces
var managers = org_turbocommons_src_main_js_managers;


QUnit.module("ValidationManagerTest");

/**
 * isTrue
 */
QUnit.test("isTrue", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isTrue(true));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isTrue(false));
	assert.ok(!validationManager.isTrue(null));
	assert.ok(!validationManager.isTrue([]));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();

	assert.ok(!validationManager.isTrue(false, 'false error', true));
	assert.ok(validationManager.lastMessage === 'false error');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isTrue(false, 'false error 2'));
	assert.ok(validationManager.lastMessage === 'false error 2');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);
});


/**
 * isNumeric
 */
QUnit.test("isNumeric", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isNumeric(1));
	assert.ok(validationManager.isNumeric(145646));
	assert.ok(validationManager.isNumeric(-1));
	assert.ok(validationManager.isNumeric(-1.56567));
	assert.ok(validationManager.isNumeric(1.34435));
	assert.ok(validationManager.isNumeric(-3453451));
	assert.ok(validationManager.isNumeric('1'));
	assert.ok(validationManager.isNumeric('1.4545645'));
	assert.ok(validationManager.isNumeric('-1.345'));
	assert.ok(validationManager.isNumeric('-345341'));
	assert.ok(validationManager.isNumeric('1.4564564563456'));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isNumeric([]));
	assert.ok(!validationManager.isNumeric(new managers.ValidationManager()));
	assert.ok(!validationManager.isNumeric('hello', 'numeric error'));
	assert.ok(!validationManager.isNumeric('1,4356', 'numeric error'));
	assert.ok(!validationManager.isNumeric('1,4.4545', 'numeric error'));
	assert.ok(!validationManager.isNumeric('--345', 'numeric error'));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();

	assert.ok(!validationManager.isNumeric('hello', 'numeric error', true));
	assert.ok(validationManager.lastMessage === 'numeric error');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isNumeric('hello', 'numeric error 2'));
	assert.ok(validationManager.lastMessage === 'numeric error 2');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);
});


/**
 * isString
 */
QUnit.test("isString", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isString(''));
	assert.ok(validationManager.isString('sfadf'));
	assert.ok(validationManager.isString('3453515 532'));
	assert.ok(validationManager.isString("\n\n$!"));
	assert.ok(validationManager.isString('hello baby how are you'));
	assert.ok(validationManager.isString("hello\n\nbably\r\ntest"));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isString(null, '', true));
	assert.ok(!validationManager.isString(123, '', true));
	assert.ok(!validationManager.isString(4.879, '', true));
	assert.ok(!validationManager.isString(new managers.ValidationManager(), '', true));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isString([]));
	assert.ok(!validationManager.isString(-978));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);
});


/**
 * isArray
 */
QUnit.test("isArray", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isArray([]));
	assert.ok(validationManager.isArray([1]));
	assert.ok(validationManager.isArray(['1']));
	assert.ok(validationManager.isArray(['1', 5, []]));
	assert.ok(validationManager.isArray([null]));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isArray(null, '', true));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isArray(1));
	assert.ok(!validationManager.isArray(''));
	assert.ok(!validationManager.isArray(new managers.ValidationManager()));
	assert.ok(!validationManager.isArray('hello'));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);
});


// TODO - Add all missing tests