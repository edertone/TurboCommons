"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ObjectUtilsTest", {
    beforeEach : function() {

        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
    },

    afterEach : function() {

        delete window.ArrayUtils;
        delete window.ObjectUtils;
    }
});


/**
 * isObject
 */
QUnit.test("isObject", function(assert) {

    // test empty values
    assert.ok(!ObjectUtils.isObject(null));
    assert.ok(!ObjectUtils.isObject(undefined));
    assert.ok(!ObjectUtils.isObject(''));
    assert.ok(!ObjectUtils.isObject([]));
    assert.ok(!ObjectUtils.isObject(0));
    assert.ok(ObjectUtils.isObject({}));

    // Test valid values
    assert.ok(ObjectUtils.isObject(new Error()));
    assert.ok(ObjectUtils.isObject({
        1 : 1
    }));
    assert.ok(ObjectUtils.isObject({
        a : 'hello'
    }));
    assert.ok(ObjectUtils.isObject({
        a : 1,
        b : 2,
        c : 3
    }));

    // Test invalid values
    assert.ok(!ObjectUtils.isObject(874));
    assert.ok(!ObjectUtils.isObject('hello'));
    assert.ok(!ObjectUtils.isObject([123]));
    assert.ok(!ObjectUtils.isObject([1, 'aaa']));
});


/**
 * getKeys
 */
QUnit.test("getKeys", function(assert) {

    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({}), []));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        1 : 1
    }), ['1']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1
    }), ['a']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1,
        b : 2,
        c : 3
    }), ['a', 'b', 'c']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1,
        b : {
            a : 9,
            x : 0
        },
        c : 3
    }), ['a', 'b', 'c']));

    // Test exceptions
    assert.throws(function() {

        ObjectUtils.getKeys(undefined);
    });

    assert.throws(function() {

        ObjectUtils.getKeys(null);
    });


    assert.throws(function() {

        ObjectUtils.getKeys([]);
    });

    assert.throws(function() {

        ObjectUtils.getKeys([1, 2, 3]);
    });
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert) {

    // Test identic values
    assert.ok(ObjectUtils.isEqualTo({}, {}));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home'
    }, {
        hello : 'home'
    }));
    assert.ok(ObjectUtils.isEqualTo({
        1 : 1
    }, {
        1 : 1
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        number : 1
    }, {
        hello : 'home',
        number : 1
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        number : 1,
        array : [1, 2, 3]
    }, {
        hello : 'home',
        number : 1,
        array : [1, 2, 3]
    }));
    assert.ok(ObjectUtils.isEqualTo({
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
    assert.ok(!ObjectUtils.isEqualTo({}, {
            1 : 1
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            1 : 1
        }, {
            1 : 2
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'guys'
        }, {
            1 : 2
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'guys'
        }, {
            hell : 'guys'
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'home',
            number : 1,
            array : [1, 3]
        }, {
            hello : 'home',
            number : 1,
            array : [1, 2, 3]
        }));

    // Test exceptions with non objects
    assert.throws(function() {

        ObjectUtils.isEqualTo(undefined, undefined);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo(null, null);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo([], []);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo("hello", "hello");
    });
});