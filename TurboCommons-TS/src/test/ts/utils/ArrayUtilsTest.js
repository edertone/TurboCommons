"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ArrayUtilsTest", {
    beforeEach : function() {

        window.ArrayUtils = org_turbocommons.ArrayUtils;
    },

    afterEach : function() {

        delete window.ArrayUtils;
    }
});


/**
 * isArray
 */
QUnit.test("isArray", function(assert) {

    // Test empty values
    assert.ok(!ArrayUtils.isArray(undefined));
    assert.ok(!ArrayUtils.isArray(null));
    assert.ok(ArrayUtils.isArray([]));
    assert.ok(!ArrayUtils.isArray(0));

    // Test correct values
    assert.ok(ArrayUtils.isArray([1]));
    assert.ok(ArrayUtils.isArray(["2"]));
    assert.ok(ArrayUtils.isArray(["q"]));
    assert.ok(ArrayUtils.isArray([true, false]));
    assert.ok(ArrayUtils.isArray([1, 4, "a"]));
    assert.ok(ArrayUtils.isArray([new Error(), 67]));

    // Test wrong values
    assert.ok(!ArrayUtils.isArray(1));
    assert.ok(!ArrayUtils.isArray("a"));
    assert.ok(!ArrayUtils.isArray(false));
    assert.ok(!ArrayUtils.isArray(new Error()));
    assert.ok(!ArrayUtils.isArray({
            a : 1
        }));
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert) {

    // Test non array values must launch exception
    assert.throws(function() {

        ArrayUtils.isEqualTo(null, null);
    });

    assert.throws(function() {

        ArrayUtils.isEqualTo(1, 1);
    });

    assert.throws(function() {

        ArrayUtils.isEqualTo("asfasf1", "345345");
    });

    // Test identic arrays
    assert.ok(ArrayUtils.isEqualTo([null], [null]));
    assert.ok(ArrayUtils.isEqualTo([], []));
    assert.ok(ArrayUtils.isEqualTo([[]], [[]]));
    assert.ok(ArrayUtils.isEqualTo([[1]], [[1]]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], [1, 2, 3]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 1, 2], [1, 2, 1, 2]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 4]]));
    assert.ok(ArrayUtils.isEqualTo(["hello world"], ["hello world"]));

    // Test different arrays
    assert.ok(!ArrayUtils.isEqualTo([null], []));
    assert.ok(!ArrayUtils.isEqualTo([1], ["1"]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, 3], [1, 3, 2]));
    assert.ok(!ArrayUtils.isEqualTo([1, "2,3"], [1, 2, 3]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ["4"]]]));
    assert.ok(!ArrayUtils.isEqualTo(["hello world"], ["hello worl1d"]));

    // Test identic objects
    assert.ok(ArrayUtils.isEqualTo([{
        a : 1
    }], [{
        a : 1
    }]));

    assert.ok(ArrayUtils.isEqualTo([{
        a : 1,
        b : [1, 2, 3],
        c : 'hello'
    }], [{
        a : 1,
        b : [1, 2, 3],
        c : 'hello'
    }]));

    // Test different objects
    assert.ok(!ArrayUtils.isEqualTo([{
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
QUnit.test("removeElement", function(assert) {

    // Test non array values must launch exception
    assert.throws(function() {

        ArrayUtils.removeElement(null, null);
    });

    assert.throws(function() {

        ArrayUtils.removeElement(1, 1);
    });

    assert.throws(function() {

        ArrayUtils.removeElement("asfasf1", "345345");
    });

    // Test several arrays
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([], null), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([], 1), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1], 1), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["1"], 1), ["1"]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["1"], "1"), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1, 2, 3, 4], 1), [2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1, 2, 3, 4], 8), [1, 2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", "guys"], "guys"), ["hello"]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test"]], 1), ["hello", ["test"]]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test"]], ["test"]), ["hello", 1]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test", "array"], ["test"]], ["test", "array"]), ["hello", 1, ["test"]]));


});