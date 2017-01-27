"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

// Import namespaces
var utils = org_turbocommons_src_main_js_utils;


QUnit.module("ConversionUtilsTest");

/**
 * stringToBase64
 */
QUnit.test("stringToBase64", function(assert){

	assert.ok(utils.ConversionUtils.stringToBase64(undefined) === '');
	assert.ok(utils.ConversionUtils.stringToBase64(null) === '');
	assert.ok(utils.ConversionUtils.stringToBase64('') === '');

	// Try correct values
	assert.ok(utils.ConversionUtils.stringToBase64('f') === 'Zg==');
	assert.ok(utils.ConversionUtils.stringToBase64('fo') === 'Zm8=');
	assert.ok(utils.ConversionUtils.stringToBase64('foo') === 'Zm9v');
	assert.ok(utils.ConversionUtils.stringToBase64('foob') === 'Zm9vYg==');
	assert.ok(utils.ConversionUtils.stringToBase64('fooba') === 'Zm9vYmE=');
	assert.ok(utils.ConversionUtils.stringToBase64('foobar') === 'Zm9vYmFy');
	assert.ok(utils.ConversionUtils.stringToBase64("line1\nline2\nline3") === 'bGluZTEKbGluZTIKbGluZTM=');
	assert.ok(utils.ConversionUtils.stringToBase64('{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 }') === 'eyAwLCAxLCAyLCAzLCA0LCA1LCA2LCA3LCA4LCA5IH0=');
	assert.ok(utils.ConversionUtils.stringToBase64('AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz') === 'QWFCYkNjRGRFZUZmR2dIaElpSmpLa0xsTW1Obk9vUHBRcVJyU3NUdFV1VnZXd1h4WXlaeg==');

	// Try some wrong values
	assert.throws(function(){

		utils.ConversionUtils.stringToBase64([]);
	});

	assert.throws(function(){

		utils.ConversionUtils.stringToBase64(98345);
	});

	assert.throws(function(){

		utils.ConversionUtils.stringToBase64(Qunit);
	});
});


/**
 * base64ToString
 */
QUnit.test("base64ToString", function(assert){

	assert.ok(utils.ConversionUtils.base64ToString(undefined) === '');
	assert.ok(utils.ConversionUtils.base64ToString(null) === '');
	assert.ok(utils.ConversionUtils.base64ToString('') === '');

	// Try correct values
	assert.ok(utils.ConversionUtils.base64ToString('Zg==') === 'f');
	assert.ok(utils.ConversionUtils.base64ToString('Zm8=') === 'fo');
	assert.ok(utils.ConversionUtils.base64ToString('Zm9v') === 'foo');
	assert.ok(utils.ConversionUtils.base64ToString('Zm9vYg==') === 'foob');
	assert.ok(utils.ConversionUtils.base64ToString('Zm9vYmE=') === 'fooba');
	assert.ok(utils.ConversionUtils.base64ToString('Zm9vYmFy') === 'foobar');

	// Try some random values encoded with stringToBase64
	for(var i = 0; i < 50; i++){

		var s = Math.random().toString(36).substring(20);

		assert.ok(utils.ConversionUtils.base64ToString(utils.ConversionUtils.stringToBase64(s)) === s);
	}

	// Try some wrong values
	assert.throws(function(){

		utils.ConversionUtils.base64ToString([]);
	});

	assert.throws(function(){

		utils.ConversionUtils.base64ToString(98345);
	});

	assert.throws(function(){

		utils.ConversionUtils.base64ToString(Qunit);
	});
});