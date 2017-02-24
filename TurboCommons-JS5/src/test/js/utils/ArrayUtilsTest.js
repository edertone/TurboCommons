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


QUnit.module("ArrayUtilsTest");


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert){

	// Test non array values must launch exception
	assert.throws(function(){

		utils.ArrayUtils.isEqualTo(null, null);
	});

	assert.throws(function(){

		utils.ArrayUtils.isEqualTo(1, 1);
	});

	assert.throws(function(){

		utils.ArrayUtils.isEqualTo("asfasf1", "345345");
	});

	// Test identic arrays
	assert.ok(utils.ArrayUtils.isEqualTo([null], [null]));
	assert.ok(utils.ArrayUtils.isEqualTo([], []));
	assert.ok(utils.ArrayUtils.isEqualTo([[]], [[]]));
	assert.ok(utils.ArrayUtils.isEqualTo([[1]], [[1]]));
	assert.ok(utils.ArrayUtils.isEqualTo([1, 2, 3], [1, 2, 3]));
	assert.ok(utils.ArrayUtils.isEqualTo([1, 2, 1, 2], [1, 2, 1, 2]));
	assert.ok(utils.ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 4]]));
	assert.ok(utils.ArrayUtils.isEqualTo(["hello world"], ["hello world"]));

	// Test different arrays
	assert.ok(!utils.ArrayUtils.isEqualTo([null], []));
	assert.ok(!utils.ArrayUtils.isEqualTo([1], ["1"]));
	assert.ok(!utils.ArrayUtils.isEqualTo([1, 2, 3], [1, 3, 2]));
	assert.ok(!utils.ArrayUtils.isEqualTo([1, "2,3"], [1, 2, 3]));
	assert.ok(!utils.ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
	assert.ok(!utils.ArrayUtils.isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ["4"]]]));
	assert.ok(!utils.ArrayUtils.isEqualTo(["hello world"], ["hello worl1d"]));

	// Test identic objects
	assert.ok(utils.ArrayUtils.isEqualTo([{
		a : 1
	}], [{
		a : 1
	}]));

	assert.ok(utils.ArrayUtils.isEqualTo([{
		a : 1,
		b : [1, 2, 3],
		c : 'hello'
	}], [{
		a : 1,
		b : [1, 2, 3],
		c : 'hello'
	}]));

	// Test different objects
	assert.ok(!utils.ArrayUtils.isEqualTo([{
		a : 1,
		b : [1, 4, 3],
		c : 'hello'
	}], [{
		a : 1,
		b : [1, 2, 3],
		c : 'hello'
	}]));
});


/**
 * removeElement
 */
QUnit.test("removeElement", function(assert){

	// Test non array values must launch exception
	assert.throws(function(){

		utils.ArrayUtils.removeElement(null, null);
	});

	assert.throws(function(){

		utils.ArrayUtils.removeElement(1, 1);
	});

	assert.throws(function(){

		utils.ArrayUtils.removeElement("asfasf1", "345345");
	});

	// Test several arrays
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement([], null), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement([], 1), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement([1], 1), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["1"], 1), ["1"]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["1"], "1"), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement([1, 2, 3, 4], 1), [2, 3, 4]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement([1, 2, 3, 4], 8), [1, 2, 3, 4]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["hello", "guys"], "guys"), ["hello"]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["hello", 1, ["test"]], 1), ["hello", ["test"]]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["hello", 1, ["test"]], ["test"]), ["hello", 1]));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.ArrayUtils.removeElement(["hello", 1, ["test", "array"], ["test"]], ["test", "array"]), ["hello", 1, ["test"]]));


});