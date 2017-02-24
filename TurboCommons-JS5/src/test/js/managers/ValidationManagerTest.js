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
	assert.ok(!validationManager.isUrl('    '));
	assert.ok(!validationManager.isUrl('123f56ccaca'));
	assert.ok(!validationManager.isUrl('8/%$144///(!(/"'));
	assert.ok(!validationManager.isUrl('http'));
	assert.ok(!validationManager.isUrl('x.y'));
	assert.ok(!validationManager.isUrl('http://x.y'));
	assert.ok(!validationManager.isUrl('google.com-'));
	assert.ok(!validationManager.isUrl("\n   \t\n"));
	assert.ok(!validationManager.isUrl('http:\\google.com'));
	assert.ok(!validationManager.isUrl('_http://google.com'));
	assert.ok(!validationManager.isUrl('http://www.example..com'));
	assert.ok(!validationManager.isUrl('http://.com'));
	assert.ok(!validationManager.isUrl('http://www.example.'));
	assert.ok(!validationManager.isUrl('http:/www.example.com'));
	assert.ok(!validationManager.isUrl('http://'));
	assert.ok(!validationManager.isUrl('http://.'));
	assert.ok(!validationManager.isUrl('http://??/'));
	assert.ok(!validationManager.isUrl('http://foo.bar?q=Spaces should be encoded'));
	assert.ok(!validationManager.isUrl('rdar://1234'));
	assert.ok(!validationManager.isUrl('http://foo.bar/foo(bar)baz quux'));
	assert.ok(!validationManager.isUrl('http://10.1.1.255'));
	assert.ok(!validationManager.isUrl('http://.www.foo.bar./'));
	assert.ok(!validationManager.isUrl('http://.www.foo.bar/'));
	assert.ok(!validationManager.isUrl('ftp://user:password@host:port/path'));
	assert.ok(!validationManager.isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
	assert.ok(!validationManager.isUrl('C:\\Program Files (x86)'));

	// good url cases
	assert.ok(validationManager.isUrl('http://x.ye'));
	assert.ok(validationManager.isUrl('http://google.com'));
	assert.ok(validationManager.isUrl('ftp://mydomain.com'));
	assert.ok(validationManager.isUrl('http://www.example.com:8800'));
	assert.ok(validationManager.isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
	assert.ok(validationManager.isUrl('http://www.test.com?pageid=123&testid=1524'));
	assert.ok(validationManager.isUrl('http://www.test.com/do.html#A'));
	assert.ok(validationManager.isUrl('https://subdomain.test.com/'));
	assert.ok(validationManager.isUrl('https://test.com'));
	assert.ok(validationManager.isUrl('http://foo.com/blah_blah/'));
	assert.ok(validationManager.isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
	assert.ok(validationManager.isUrl('http://userid@example.com:8080'));
	assert.ok(validationManager.isUrl('http://➡.ws/䨹'));
	assert.ok(validationManager.isUrl('http://⌘.ws/'));
	assert.ok(validationManager.isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
	assert.ok(validationManager.isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
	assert.ok(validationManager.isUrl('http://223.255.255.254'));
	assert.ok(validationManager.isUrl('ftp://user:password@host.com:8080/path'));

	validationManager.reset();
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	// Test non string values throw exceptions
	assert.throws(function(){

		validationManager.isUrl([12341]);
	});

	assert.throws(function(){

		validationManager.isUrl(12341);
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
 * isObject
 */
QUnit.test("isObject", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isObject({}));

	assert.ok(validationManager.isObject({
		1 : 1
	}));

	assert.ok(validationManager.isObject({
		1 : '1'
	}));

	assert.ok(validationManager.isObject({
		1 : '1',
		5 : 5,
		array : []
	}));

	assert.ok(validationManager.isObject({
		novalue : null
	}));

	assert.ok(validationManager.isObject(new managers.ValidationManager()));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_OK);

	assert.ok(!validationManager.isObject(null, '', true));
	assert.ok(!validationManager.isObject(undefined, '', true));
	assert.ok(!validationManager.isObject([], '', true));
	assert.ok(validationManager.validationStatus === managers.ValidationManager.VALIDATION_WARNING);
	assert.ok(!validationManager.isObject(1));
	assert.ok(!validationManager.isObject(''));
	assert.ok(!validationManager.isObject('hello'));
	assert.ok(!validationManager.isObject([1, 4, 5]));
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
	assert.ok(!validationManager.isFilledIn(undefined, [], '', true));
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


/**
 * isDate
 */
QUnit.test("isDate", function(assert){

	assert.ok(true);

	// TODO
});


/**
 * isMail
 */
QUnit.test("isMail", function(assert){

	assert.ok(true);

	// TODO
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert){

	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isEqualTo(null, null));
	assert.ok(validationManager.isEqualTo(undefined, undefined));
	assert.ok(validationManager.isEqualTo('', ''));
	assert.ok(validationManager.isEqualTo(123, 123));
	assert.ok(validationManager.isEqualTo(1.56, 1.56));
	assert.ok(validationManager.isEqualTo([], []));
	assert.ok(validationManager.isEqualTo('hello', 'hello'));
	assert.ok(validationManager.isEqualTo(new managers.ValidationManager(), new managers.ValidationManager()));
	assert.ok(validationManager.isEqualTo([1, 6, 8, 4], [1, 6, 8, 4]));

	assert.ok(!validationManager.isEqualTo(null, undefined));
	assert.ok(!validationManager.isEqualTo('', 'hello'));
	assert.ok(!validationManager.isEqualTo(124, 12454));
	assert.ok(!validationManager.isEqualTo(1.45, 1));
	assert.ok(!validationManager.isEqualTo([], {}));
	assert.ok(!validationManager.isEqualTo('gobaby', 'hello'));
	assert.ok(!validationManager.isEqualTo('hello', new managers.ValidationManager()));
	assert.ok(!validationManager.isEqualTo([5, 2, 8, 5], [1, 6, 9, 5]));
	assert.ok(!validationManager.isEqualTo({
		a : 1,
		b : 2
	}, {
		c : 1,
		b : 3
	}));
});

//TODO - Add all missing tests
