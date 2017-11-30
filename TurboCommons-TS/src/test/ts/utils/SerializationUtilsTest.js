"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("SerializationUtilsTest", {
    
    before : function(){

        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.SerializationUtils = org_turbocommons.SerializationUtils;  
        
        // Following classes are defined to be used on several tests.
        // The have been created by using the typescript playground online tool
        // that automatically generates the javascript code from a typescript given 
        // one.
        
        // A class with a single simple property
        window.SingleProp = (function () {
            function SingleProp() {
                this.foo = '';
            }
            return SingleProp;
        }());
        
        // A class with a single property that contains a SingleProp class instance
        window.SingleObjProp = (function () {
            function SingleObjProp() {
                this.obj = new SingleProp();
            }
            return SingleObjProp;
        }());
        
        // A class with a single property that contains an array with a SingleObjProp class instance
        window.SingleArrayProp = (function () {
            function SingleArrayProp() {
                this.arr = [new SingleObjProp()];
            }
            return SingleArrayProp;
        }());        
    },
    
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;     
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
    },
    
    after : function(){

        delete window.ObjectUtils;
        delete window.SerializationUtils;
        delete window.SingleProp;
        delete window.SingleObjProp;
        delete window.SingleArrayProp;
    }
});


/**
 * hashMapObjectToClass
 */
QUnit.todo("hashMapObjectToClass", function(assert){

});


/**
 * javaPropertiesObjectToString
 */
QUnit.todo("javaPropertiesObjectToString", function(assert){

});


/**
 * jsonToClass
 */
QUnit.test("jsonToClass", function(assert){

    // Test empty values
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass('{}', {}, false), {}));
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass('{}', {}, true), {}));
    
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(emptyValues[i], {}, true);
        });
        
        if(!ObjectUtils.isObject(emptyValues[i])){
            
            assert.throws(function() {
                SerializationUtils.jsonToClass('{}', emptyValues[i], true);
            });
        }
        
        assert.throws(function() {
            SerializationUtils.jsonToClass('{}', {}, emptyValues[i]);
        });
    }

    // Test ok values either with strict set as true and false
    for(var boolValue of [true, false]){
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"foo":"value"}',
                    new SingleProp(),
                    boolValue), 
                {foo:'value'}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"obj":{"foo":"value"}}',
                    new SingleObjProp(), 
                    boolValue),
                {obj:{foo:'value'}}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"arr":[{"obj":{"foo":"value"}}]}',
                new SingleArrayProp(), 
                boolValue),
                {"arr":[{"obj":{"foo":"value"}}]}));
    }
    
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    assert.throws(function() {
        SerializationUtils.jsonToClass('hello', {}, true);
    });
    
    assert.throws(function() {
        SerializationUtils.jsonToClass('{}', [1,2,3], true);
    });
    
    assert.throws(function() {
        SerializationUtils.jsonToClass('{}', {}, 'hello');
    });
});


/**
 * objectToClass
 */
QUnit.todo("objectToClass", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * stringToJavaPropertiesObject
 */
QUnit.todo("stringToJavaPropertiesObject", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * stringToXmlObject
 */
QUnit.todo("stringToXmlObject", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * xmlObjectToString
 */
QUnit.todo("xmlObjectToString", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});
