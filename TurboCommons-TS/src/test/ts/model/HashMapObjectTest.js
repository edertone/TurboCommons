"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("HashMapObjectTest", {
    beforeEach : function(){
        
        window.HashMapObject = org_turbocommons.HashMapObject;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
        
        window.populatedHashMap = new HashMapObject();
        window.populatedHashMap.set('a', 1);
        window.populatedHashMap.set('b', 2);
        window.populatedHashMap.set('c', 3);
        window.populatedHashMap.set('d', 4);
        window.populatedHashMap.set('e', 5);
        window.populatedHashMap.set('f', 6);
        window.populatedHashMap.set('g', 7);
        window.populatedHashMap.set('string', 'myValue');
        window.populatedHashMap.set('array', [1, 2, 3, 4]);
    },

    afterEach : function(){
        
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new HashMapObject();
    assert.ok(test.length() === 0);
    
    test = new HashMapObject(null);
    assert.ok(test.length() === 0);
    
    test = new HashMapObject([]);
    assert.ok(test.length() === 0);
    
    var exceptionEmptyValues = ['', '     ', "\n\n\n", 0];
    var exceptionEmptyValuesCount = exceptionEmptyValues.length;
    
    for (var i = 0; i < exceptionEmptyValuesCount; i++) {
    
        assert.throws(function() {
            test = new HashMapObject(exceptionEmptyValues[i]);
        });
    }
    
    // Test ok values
    test = new HashMapObject(['a']);
    assert.ok(test.length() === 1);
    assert.ok(test.get('0') === 'a');
    
    test = new HashMapObject({a: 1});
    assert.ok(test.length() === 1);
    assert.ok(test.get('a') === 1);
    
    test = new HashMapObject([1, 2, 3]);
    assert.ok(test.length() === 3);
    assert.ok(test.get('0') === 1);
    assert.ok(test.get('2') === 3);
    
    test = new HashMapObject(['a', 'b', 'c', 'd', 'e', 'f']);
    assert.ok(test.length() === 6);
    assert.ok(test.get('0') === 'a');
    assert.ok(test.get('3') === 'd');
    assert.ok(test.get('5') === 'f');
    
    test = new HashMapObject({1: 'a', 2: 'b', 3: 'c', 4: 'd', 5: 'e', 6: 'f'});
    assert.ok(test.length() === 6);
    assert.ok(test.get('1') === 'a');
    assert.ok(test.get('4') === 'd');
    assert.ok(test.get('6') === 'f');
    
    test = new HashMapObject({1: 'a', 2: 'b', 3: 'c', 4: 'd', 5: 'e', 6: 'f'});
    assert.ok(test.length() === 6);
    assert.ok(test.get('1') === 'a');
    assert.ok(test.get('4') === 'd');
    assert.ok(test.get('6') === 'f');
    
    // Test wrong values
    // Tested with exceptions
    
    // Test exceptions
    var exceptionValues = [123, 'hello', -1, 0.23];
    var exceptionValuesCount = exceptionValues.length;
    
    for (i = 0; i < exceptionValuesCount; i++) {
    
        assert.throws(function() {
            test = new HashMapObject(exceptionValues[i]);
        });
    }
});


// TODO - add missing tests