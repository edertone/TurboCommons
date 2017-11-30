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
        // They have been created by using the typescript playground online tool
        // that automatically generates the javascript code from a typescript given 
        // one. Original typescript source that generates these classes is found at:
        // test/ts/resources/utils/serializationUtils/Typescript-source-test-classes.txt
        
        window.SingleProp = /** @class */ (function () {
            function SingleProp() {
                this.foo = '';
            }
            return SingleProp;
        }());
        window.SingleObjProp = /** @class */ (function () {
            function SingleObjProp() {
                this.obj = new Object();
            }
            return SingleObjProp;
        }());
        window.SingleClassProp = /** @class */ (function () {
            function SingleClassProp() {
                this.someClass = new SingleProp();
            }
            return SingleClassProp;
        }());
        window.SingleArrayProp = /** @class */ (function () {
            function SingleArrayProp() {
                this.arr = [new SingleClassProp()];
            }
            return SingleArrayProp;
        }());
        window.MultipleComlexProps = /** @class */ (function () {
            function MultipleComlexProps() {
                this.number = 0;
                this.string = '';
                this.obj = new Object();
                this.someClass = new SingleArrayProp();
                this.arr = [new SingleArrayProp(), 0, new Object()];
            }
            return MultipleComlexProps;
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
        delete window.SingleClassProp;
        delete window.SingleArrayProp;
        delete window.MultipleComlexProps;
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

    // Test ok values with identical json and class either with strict set as true and false
    for(var boolValue of [true, false]){
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"foo":"value"}',
                    new SingleProp(),
                    boolValue), 
                {foo:'value'}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"obj":{"foo":"value", "foo2":"value2"}}',
                    new SingleObjProp(), 
                    boolValue),
                {obj:{foo:"value", foo2:"value2"}}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"someClass":{"foo":"value"}}',
                    new SingleClassProp(), 
                    boolValue),
                {someClass:{foo:'value'}}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"arr":[{"someClass":{"foo":"value"}}]}',
                new SingleArrayProp(), 
                boolValue),
                {arr:[{someClass:{foo:"value"}}]}));
    }
    
    // Test ok values with strict mode false and keys that exist on the class but not on the json
    // TODO
    
    // Test ok values with strict mode false and keys that exist on the json but not on the class
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
            '{"foo1":"value"}',
            new SingleProp(),
            false), 
        {foo:''}));
    
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
            '{"foo":"value", "nonexistant":"value"}',
            new SingleProp(),
            false), 
        {foo:'value'}));
    
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
            '{"arr1":[{"obj":{"foo":"value"}}]}',
            new SingleArrayProp(), 
            false),
            {arr:[{someClass:{foo:''}}]}));
   
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
            '{"arr":[{"obj1":{"foo":"value"}}]}',
            new SingleArrayProp(), 
            false),
            {arr:[{someClass:{foo:''}}]}));
         
    // Test more ok values
    // TODO
    
    // Test wrong values with strict mode true and keys that exist in the json but not on the class
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo1":"value"}',
                new SingleProp(),
                true);
    });
    
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo":"value", "nonexistant":"value"}',
                new SingleProp(),
                true);
    });
    // TODO - more
    
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
