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

	assert.ok(!validationManager.isTrue(false, 'false error'));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();

	assert.ok(!validationManager.isTrue(false, 'false error', true));
	assert.ok(validationManager.lastMessage === 'false error');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isTrue(false, 'false error 2'));
	assert.ok(validationManager.lastMessage === 'false error 2');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	// Check validation reset works as expected
	// TODO: call here the reset test code. We want to reuse it
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
	assert.ok(validationManager.isNumeric("1"));
	assert.ok(validationManager.isNumeric("1.4545645"));
	assert.ok(validationManager.isNumeric("-1.345"));
	assert.ok(validationManager.isNumeric("-345341"));
	assert.ok(validationManager.isNumeric("1.4564564563456"));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isNumeric("hello", 'numeric error'));
	assert.ok(!validationManager.isNumeric("1,4356", 'numeric error'));
	assert.ok(!validationManager.isNumeric("1,4.4545", 'numeric error'));
	assert.ok(!validationManager.isNumeric("--345", 'numeric error'));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	validationManager.reset();

	assert.ok(!validationManager.isNumeric("hello", 'numeric error', true));
	assert.ok(validationManager.lastMessage === 'numeric error');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isNumeric("hello", 'numeric error 2'));
	assert.ok(validationManager.lastMessage === 'numeric error 2');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	// Check validation reset works as expected
	// TODO: call here the reset test code. We want to reuse it
});


/**
 * reset
 */
QUnit.test("reset", function(assert){

	// TODO: this code must be reused on all the other tests

	var validationManager = new managers.ValidationManager();

	// Check validation reset works as expected
	validationManager.reset();
	assert.ok(validationManager.isArray(validationManager.failedElementsList));
	assert.ok(validationManager.failedElementsList.length === 0);
	assert.ok(validationManager.isArray(validationManager.failedMessagesList));
	assert.ok(validationManager.failedMessagesList.length === 0);
	assert.ok(validationManager.isArray(validationManager.failedStatusList));
	assert.ok(validationManager.failedStatusList.length === 0);
	assert.ok(validationManager.lastMessage === '');
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

});

// TODO - tots els testos que falten