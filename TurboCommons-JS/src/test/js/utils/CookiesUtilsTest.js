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


QUnit.module("CookiesUtilsTest");

/**
 * setCookie
 */
QUnit.test("setCookie", function(assert){

	// Test several empty value combinations
	assert.throws(function(){

		utils.CookiesUtils.setCookie(undefined, 'value');
	});

	assert.throws(function(){

		utils.CookiesUtils.setCookie(null, 'value');
	});

	assert.throws(function(){

		utils.CookiesUtils.setCookie('', 'value');
	});

	assert.throws(function(){

		utils.CookiesUtils.setCookie('user1', []);
	});

	assert.throws(function(){

		utils.CookiesUtils.setCookie('user1', utils.CookiesUtils);
	});

	// Verify cookies do not exist
	assert.ok(!utils.CookiesUtils.isCookie('user0'));
	assert.ok(!utils.CookiesUtils.isCookie('user1'));
	assert.ok(!utils.CookiesUtils.isCookie('user2'));
	assert.ok(!utils.CookiesUtils.isCookie('user3'));

	// Define some cookies
	assert.ok(utils.CookiesUtils.setCookie('user1'));
	assert.ok(utils.CookiesUtils.setCookie('user1', undefined));
	assert.ok(utils.CookiesUtils.setCookie('user1', null));
	assert.ok(utils.CookiesUtils.setCookie('user2', '       ', 200));
	assert.ok(utils.CookiesUtils.setCookie('user3', 'value 3'));

	// Get values
	assert.ok(utils.CookiesUtils.getCookie('user0') === undefined);
	assert.ok(utils.CookiesUtils.getCookie('user1') === '');
	assert.ok(utils.CookiesUtils.getCookie('user2') === '       ');
	assert.ok(utils.CookiesUtils.getCookie('user3') === 'value 3');

	// Verify expiration is correct


	// Delete created cookies
	assert.ok(utils.CookiesUtils.deleteCookie('user0') === false);
	assert.ok(utils.CookiesUtils.deleteCookie('user1'));
	assert.ok(utils.CookiesUtils.deleteCookie('user2'));
	assert.ok(utils.CookiesUtils.deleteCookie('user3'));

	// Verify cookies do not exist
	assert.ok(utils.CookiesUtils.getCookie('user0') === undefined);
	assert.ok(utils.CookiesUtils.getCookie('user1') === undefined);
	assert.ok(utils.CookiesUtils.getCookie('user2') === undefined);
	assert.ok(utils.CookiesUtils.getCookie('user3') === undefined);
});


/**
 * getCookie
 */
QUnit.test("getCookie", function(assert){

	// Test several empty value combinations
	assert.throws(function(){

		utils.CookiesUtils.getCookie(null);
	});

	assert.throws(function(){

		utils.CookiesUtils.getCookie(undefined);
	});

	assert.throws(function(){

		utils.CookiesUtils.getCookie('');
	});

	// Verify cookies do not exist
	assert.ok(!utils.CookiesUtils.isCookie('user1'));
	assert.ok(!utils.CookiesUtils.isCookie('user2'));
	assert.ok(!utils.CookiesUtils.isCookie('user3'));

	// Define some cookies
	assert.ok(utils.CookiesUtils.setCookie('user1', null));
	assert.ok(utils.CookiesUtils.setCookie('user2', ''));
	assert.ok(utils.CookiesUtils.setCookie('user3', 'value 3'));

	// Get values
	assert.ok(utils.CookiesUtils.getCookie('user1') === '');
	assert.ok(utils.CookiesUtils.getCookie('user2') === '');
	assert.ok(utils.CookiesUtils.getCookie('user3') === 'value 3');

	// Modify value for a cookie and test that it has changed
	assert.ok(utils.CookiesUtils.setCookie('user3', 'new value now'));
	assert.ok(utils.CookiesUtils.getCookie('user3') === 'new value now');

	// Delete created cookies
	assert.ok(utils.CookiesUtils.deleteCookie('user1'));
	assert.ok(utils.CookiesUtils.deleteCookie('user2'));
	assert.ok(utils.CookiesUtils.deleteCookie('user3'));

	// Verify cookies do not exist
	assert.ok(!utils.CookiesUtils.isCookie('user1'));
	assert.ok(!utils.CookiesUtils.isCookie('user2'));
	assert.ok(!utils.CookiesUtils.isCookie('user3'));
});


/**
 * deleteCookie
 */
QUnit.test("deleteCookie", function(assert){

	// Test several empty value combinations
	assert.throws(function(){

		utils.CookiesUtils.deleteCookie(null);
	});

	assert.throws(function(){

		utils.CookiesUtils.deleteCookie(undefined);
	});

	assert.throws(function(){

		utils.CookiesUtils.deleteCookie('');
	});

	// Verify cookie do not exist
	assert.ok(!utils.CookiesUtils.isCookie('user1'));

	// Define a cookie
	assert.ok(utils.CookiesUtils.setCookie('user1', 'go to the cookies'));

	// Verify cookie exists
	assert.ok(utils.CookiesUtils.isCookie('user1'));

	// Delete cookie
	assert.ok(utils.CookiesUtils.deleteCookie('user1'));

	// Verify deleted
	assert.ok(utils.CookiesUtils.getCookie('user1') === undefined);
});