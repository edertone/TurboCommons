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
    
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;     
        
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
                this.arr = [new SingleArrayProp()];
            }
            return MultipleComlexProps;
        }());     
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
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
 * javaPropertiesObjectToString
 */
QUnit.todo("javaPropertiesObjectToString", function(assert){

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
    
    for(var boolValue of [true, false]){
        
        // Test special case: If we provide an empty object as the classInstance or classInstance property
        // default type, no strict checking will happen. Object will be directly opdated with the source JSON data
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"obj":{"foo1": 1, "foo2": 2}}',
                new SingleObjProp(),
                boolValue),
            {obj: {foo1: 1, foo2: 2}}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"someKey":[{"someClass":{"foo":"value", "foo2": 1, "foo3": []}}]}',
                new Object(),
                boolValue),
            {someKey:[{someClass:{foo:"value", foo2: 1, foo3: []}}]}));
        
        
        // Test identical JSON and classInstance with strict true and false
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
        
        // Test complex class with many properties
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                    '{"number": 9882, "string": "hello", "obj": {"foo": "bar"},' +
                    '"someClass": {"arr":[{"someClass":{"foo":"value"}}]},' +
                    '"arr": [{"arr":[{"someClass":{"foo":"value"}}]}]}',
                    new MultipleComlexProps(), 
                    boolValue),
                {number: 9882, string: "hello", obj: {foo: "bar"},
                someClass: {arr:[{someClass:{foo:"value"}}]},
                arr: [{arr:[{someClass:{foo:"value"}}]}]}));
        
        // Test same properties and keys but with different order
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"string": "hello", "number": 9882,' +
                '"someClass": {"arr":[{"someClass":{"foo":"value"}}]},' +
                '"arr": [{"arr":[{"someClass":{"foo":"value"}}]}],' +
                '"obj": {"foo": "bar"}}',
                new MultipleComlexProps(), 
                boolValue),
            {obj: {foo: "bar"}, number: 9882, string: "hello",
            arr: [{arr:[{someClass:{foo:"value"}}]}],
            someClass: {arr:[{someClass:{foo:"value"}}]}}));
    }
    
    
    // Test class and JSON with different keys and properties with strict true and false
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo1":"value", "foo2":"value"}',
                new SingleProp(),
                true);
    });
    
    assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
            '{"foo1":"value", "foo2":"value"}',
            new SingleProp(),
            false), 
        {foo:''}));
    
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
         
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"arr":[{"obj1":{"foo":"value"}}]}',
                new SingleArrayProp(), 
                true);
    });
    
    
    // Test errors related to different types on class and JSON with strict mode
    // TODO
    
    // Test wrong values with strict mode true and keys or properties that do not exist on the other part
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo1":"value"}',
                new SingleProp(),
                true);
    });
    
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo": 1, "obj":{"foo2": 2}}',
                new SingleObjProp(),
                true);
    });
    
    assert.throws(function() {
        SerializationUtils.jsonToClass(
                '{"foo":"value", "nonexistant":"value"}',
                new SingleProp(),
                true);
    });

    // Test wrong parameters exceptions
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
