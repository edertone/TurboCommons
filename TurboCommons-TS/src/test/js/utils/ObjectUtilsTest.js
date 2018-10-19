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
    
    // Test same values but with different key order
    assert.ok(ObjectUtils.isEqualTo({
        number : 1,
        hello : 'home',
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
        array : {
            number : 1,
            hello : 'home'
        },
        hello : 'home'
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


/**
 * clone
 */
QUnit.test("clone", function(assert) {
    
    // Test empty values
    assert.strictEqual(ObjectUtils.clone(null), null);
    assert.strictEqual(ObjectUtils.clone(undefined), undefined);
    assert.strictEqual(ObjectUtils.clone(0), 0);
    assert.strictEqual(ObjectUtils.clone(''), '');
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.clone([]), []));
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.clone({}), {}));
    assert.strictEqual(ObjectUtils.clone('    '), '    ');

    // Test ok values. Verify modified clones do not affect original one
    var value = -145;    
    var clonedValue = ObjectUtils.clone(value);    
    assert.strictEqual(clonedValue, value);
    clonedValue = clonedValue + 100;
    assert.strictEqual(clonedValue, -45);
    assert.strictEqual(value, -145);
    
    var value = 'hello';    
    var clonedValue = ObjectUtils.clone(value);    
    assert.strictEqual(clonedValue, value);
    clonedValue = clonedValue + 'test';
    assert.strictEqual(value, 'hello');
    
    var value = [1,2,3,4,5];    
    var clonedValue = ObjectUtils.clone(value);
    assert.ok(ArrayUtils.isEqualTo(clonedValue, value));
    clonedValue.push(6);
    assert.ok(ArrayUtils.isEqualTo(value, [1,2,3,4,5]));
    
    var value = [1,2,3,{a:1, b:2, c:{d:1}},5];
    var clonedValue = ObjectUtils.clone(value);
    assert.ok(ArrayUtils.isEqualTo(clonedValue, value));
    clonedValue[3].a = 5;
    clonedValue[3].c.d = 6;
    assert.ok(ArrayUtils.isEqualTo(clonedValue, [1,2,3,{a:5, b:2, c:{d:6}},5]));
    assert.ok(ArrayUtils.isEqualTo(value, [1,2,3,{a:1, b:2, c:{d:1}},5]));
    
    var value = {a:1, b:2, c:[3,4,5,{d:6,e:{f:7}}]};
    var clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    clonedValue.a = 5;
    clonedValue.c[0] = 9;
    clonedValue.c[3].e = null;
    assert.ok(ObjectUtils.isEqualTo(clonedValue, {a:5, b:2, c:[9,4,5,{d:6,e:null}]}));
    assert.ok(ObjectUtils.isEqualTo(value, {a:1, b:2, c:[3,4,5,{d:6,e:{f:7}}]}));
    
    // Test an object containing references to other objects
    var reference = {ref:1};
    var value = {a:1, b:reference};
    var clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    reference.ref = 2;
    assert.ok(ObjectUtils.isEqualTo(clonedValue, {a:1, b:{ref:1}}));
    assert.ok(ObjectUtils.isEqualTo(value, {a:1, b:{ref:2}}));
    
    // Test an object containing a function
    var value = {a:1, b:function(a) { return a + 2 }};
    var clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    
    assert.strictEqual(value.a, 1);
    assert.strictEqual(value.b(4), 6);
    assert.strictEqual(clonedValue.a, 1);
    assert.strictEqual(clonedValue.b(4), 6);
    assert.strictEqual(clonedValue.b(6), 8);
    
    // Test wrong values
    // not necessary

    // Test exceptions
    // no exceptions are thrown by this method
});