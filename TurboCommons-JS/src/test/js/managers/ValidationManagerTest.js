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
 * isBoolean
 */
QUnit.test("isBoolean", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isBoolean(true));
	assert.ok(validationManager.isBoolean(false));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isBoolean(undefined));
	assert.ok(!validationManager.isBoolean(null));
	assert.ok(!validationManager.isBoolean([]));
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
 * isUrl
 */
QUnit.test("isUrl", function(assert){

	var validationManager = new managers.ValidationManager();

	// Wrong url cases
	assert.ok(!validationManager.isUrl(''));
	assert.ok(!validationManager.isUrl(null));
	assert.ok(!validationManager.isUrl(undefined));
	assert.ok(!validationManager.isUrl([]));
	assert.ok(!validationManager.isUrl('123f56'));
	assert.ok(!validationManager.isUrl('cacacacaca'));
	assert.ok(!validationManager.isUrl('8/%$144///(!(/"'));
	assert.ok(!validationManager.isUrl('http'));
	assert.ok(!validationManager.isUrl('ftp'));
	assert.ok(!validationManager.isUrl('    '));
	assert.ok(!validationManager.isUrl('google.com-'));
	assert.ok(!validationManager.isUrl("\n   \t\n"));
	assert.ok(!validationManager.isUrl('http:\\google.com'));
	assert.ok(!validationManager.isUrl('_http://google.com'));
	assert.ok(!validationManager.isUrl('http://www.example..com'));
	assert.ok(!validationManager.isUrl('http://.com'));
	assert.ok(!validationManager.isUrl('http://.'));
	assert.ok(!validationManager.isUrl('http://www.example.'));

	// good url cases
	assert.ok(validationManager.isUrl('x.y'));
	assert.ok(validationManager.isUrl('google.com'));
	assert.ok(validationManager.isUrl('http://x.y'));
	assert.ok(validationManager.isUrl('http://google.com'));
	assert.ok(validationManager.isUrl('http:\\google.com '));
	assert.ok(validationManager.isUrl('http://www.example.com:8800'));
	assert.ok(validationManager.isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
	assert.ok(validationManager.isUrl('http://www.test.com?pageid=123&testid=1524'));
	assert.ok(validationManager.isUrl('http://www.test.com/do.html#A'));
	assert.ok(validationManager.isUrl('https://subdomain.test.com/'));
	assert.ok(validationManager.isUrl('https://test.com'));
	// TODO: add ftp related urls

	validationManager.reset();
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	// Test non string values throw exceptions
	assert.throws(function(){

		assert.ok(!validationManager.isUrl([12341]));
	});
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


/**
 * isFilledIn
 */
QUnit.test("isFilledIn", function(assert){

	var validationManager = new managers.ValidationManager();

	// Test empty strings
	assert.ok(!validationManager.isFilledIn(undefined, null, '', true));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);

	assert.ok(!validationManager.isFilledIn(null));
	assert.ok(!validationManager.isFilledIn('      '));
	assert.ok(!validationManager.isFilledIn("\n\n  \n"));
	assert.ok(!validationManager.isFilledIn("\t   \n     \r\r"));
	assert.ok(!validationManager.isFilledIn('EMPTY', ['EMPTY']));
	assert.ok(!validationManager.isFilledIn('EMPTY           ', ['EMPTY']));
	assert.ok(!validationManager.isFilledIn('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_ERROR);

	// Test non empty strings
	validationManager.reset();

	assert.ok(validationManager.isFilledIn('adsadf'));
	assert.ok(validationManager.isFilledIn('    sdfasdsf'));
	assert.ok(validationManager.isFilledIn('EMPTY'));
	assert.ok(validationManager.isFilledIn('EMPTY test', ['EMPTY']));

	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);
});


//TODO - Add all missing tests
