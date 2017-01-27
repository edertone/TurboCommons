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


QUnit.module("ObjectUtilsTest");


/**
 * getKeys
 */
QUnit.test("getKeys", function(assert){

	assert.ok(utils.ArrayUtils.isEqualTo(utils.ObjectUtils.getKeys({}), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ObjectUtils.getKeys({
		1 : 1
	}), ['1']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ObjectUtils.getKeys({
		a : 1
	}), ['a']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ObjectUtils.getKeys({
		a : 1,
		b : 2,
		c : 3
	}), ['a', 'b', 'c']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ObjectUtils.getKeys({
		a : 1,
		b : {
			a : 9,
			x : 0
		},
		c : 3
	}), ['a', 'b', 'c']));

	// Test exceptions
	assert.throws(function(){

		utils.ObjectUtils.getKeys(undefined);
	});

	assert.throws(function(){

		utils.ObjectUtils.getKeys(null);
	});


	assert.throws(function(){

		utils.ObjectUtils.getKeys([]);
	});

	assert.throws(function(){

		utils.ObjectUtils.getKeys([1, 2, 3]);
	});
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert){

	// Test identic values
	assert.ok(utils.ObjectUtils.isEqualTo({}, {}));
	assert.ok(utils.ObjectUtils.isEqualTo({
		hello : 'home'
	}, {
		hello : 'home'
	}));
	assert.ok(utils.ObjectUtils.isEqualTo({
		1 : 1
	}, {
		1 : 1
	}));
	assert.ok(utils.ObjectUtils.isEqualTo({
		hello : 'home',
		number : 1
	}, {
		hello : 'home',
		number : 1
	}));
	assert.ok(utils.ObjectUtils.isEqualTo({
		hello : 'home',
		number : 1,
		array : [1, 2, 3]
	}, {
		hello : 'home',
		number : 1,
		array : [1, 2, 3]
	}));
	assert.ok(utils.ObjectUtils.isEqualTo({
		hello : 'home',
		array : {
			hello : 'home',
			number : 1
		}
	}, {
		hello : 'home',
		array : {
			hello : 'home',
			number : 1
		}
	}));

	// Test different values	
	assert.ok(!utils.ObjectUtils.isEqualTo({}, {
		1 : 1
	}));
	assert.ok(!utils.ObjectUtils.isEqualTo({
		1 : 1
	}, {
		1 : 2
	}));
	assert.ok(!utils.ObjectUtils.isEqualTo({
		hello : 'guys'
	}, {
		1 : 2
	}));
	assert.ok(!utils.ObjectUtils.isEqualTo({
		hello : 'guys'
	}, {
		hell : 'guys'
	}));
	assert.ok(!utils.ObjectUtils.isEqualTo({
		hello : 'home',
		number : 1,
		array : [1, 3]
	}, {
		hello : 'home',
		number : 1,
		array : [1, 2, 3]
	}));

	// Test exceptions with non objects
	assert.throws(function(){

		utils.ObjectUtils.isEqualTo(undefined, undefined);
	});

	assert.throws(function(){

		utils.ObjectUtils.isEqualTo(null, null);
	});

	assert.throws(function(){

		utils.ObjectUtils.isEqualTo([], []);
	});

	assert.throws(function(){

		utils.ObjectUtils.isEqualTo("hello", "hello");
	});
});