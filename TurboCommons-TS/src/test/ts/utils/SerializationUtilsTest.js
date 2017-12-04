"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */



// *******************************************************************************************
// Following classes are defined to be used on several tests.
// They have been created by using the typescript playground online tool
// that automatically generates the javascript code from a typescript given 
// one. Original typescript source that generates these classes is found at:
// test/ts/resources/utils/serializationUtils/Typescript-source-test-classes.txt

// TODO - all these classes shoud be included in a sepparated file and added to index.html as resources

var org_turboCommons_test_serializationUtils;
(function (org_turboCommons_test_serializationUtils) {
    var SingleProp = /** @class */ (function () {
        function SingleProp() {
            this.oneProp = "hello";
        }
        return SingleProp;
    }());
    org_turboCommons_test_serializationUtils.SingleProp = SingleProp;
    var NonTypedProps = /** @class */ (function () {
        function NonTypedProps() {
            this.nullProp = null;
            this.undefinedProp = undefined;
        }
        return NonTypedProps;
    }());
    org_turboCommons_test_serializationUtils.NonTypedProps = NonTypedProps;
    var BasicTypeProps = /** @class */ (function () {
        function BasicTypeProps() {
            this.boolean = false;
            this.number = 0;
            this.string = '';
            this.obj = { a: 1 };
            this.someClass = new NonTypedProps();
            this.arr = [];
        }
        return BasicTypeProps;
    }());
    org_turboCommons_test_serializationUtils.BasicTypeProps = BasicTypeProps;
    var TypedArrayProps = /** @class */ (function () {
        function TypedArrayProps() {
            this.boolArray = [false];
            this.numberArray = [0];
            this.stringArray = [""];
            this.objectArray = [{}];
            this.classArray = [new NonTypedProps()];
            this.arrayArray = [[]];
        }
        return TypedArrayProps;
    }());
    org_turboCommons_test_serializationUtils.TypedArrayProps = TypedArrayProps;
})(org_turboCommons_test_serializationUtils || (org_turboCommons_test_serializationUtils = {}));

//*******************************************************************************************



QUnit.module("SerializationUtilsTest", {
    
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;     
        
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.SerializationUtils = org_turbocommons.SerializationUtils; 
        
        window.SingleProp = org_turboCommons_test_serializationUtils.SingleProp;
        window.NonTypedProps = org_turboCommons_test_serializationUtils.NonTypedProps;
        window.BasicTypeProps = org_turboCommons_test_serializationUtils.BasicTypeProps;
        window.TypedArrayProps = org_turboCommons_test_serializationUtils.TypedArrayProps;
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
        
        delete window.ObjectUtils;
        delete window.SerializationUtils;
        
        delete window.SingleProp;
        delete window.NonTypedProps;
        delete window.BasicTypeProps;
        delete window.TypedArrayProps;
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

    // Test empty values on method parameters
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
    
    for(var strictValue of [true, false]){
    
        // Test that non typed properties accept being defined with any value
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"undefinedProp": false}',
                new NonTypedProps(),
                false),
                {nullProp: null, undefinedProp: false}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"nullProp": 2, "undefinedProp": "hello"}',
                new NonTypedProps(),
                strictValue),
                {nullProp: 2, undefinedProp: "hello"}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"nullProp": [1, 2, 3], "undefinedProp": {"a": 1, "b": 2}}',
                new NonTypedProps(),
                strictValue),
                {"nullProp": [1, 2, 3], "undefinedProp": {a: 1, b: 2}}));
          
        // Test that typed properties accept only values of their own type
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"boolean": true, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' +
                '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                new BasicTypeProps(),
                strictValue),
                {boolean: true, number: 1230.1, string: "hello", obj: {"b": 2},
                    someClass: {nullProp: 1, undefinedProp: 2}, arr: [1,2,3,4]}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"boolean": false, "number": 25, "string": "h", "obj": {}, ' +
                '"someClass": {"noProp": 1}, "arr": ["a"]}',
                new BasicTypeProps(),
                false),
                {boolean: false, number: 25, string: "h", obj: {},
                    someClass: {nullProp: null, undefinedProp: undefined}, arr: ["a"]}));
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": 1, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be boolean/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": true, "string": "hello", "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be number/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": -10, "string": 1, "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be string/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": true, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be Object/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                    '"someClass": 1, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be NonTypedProps/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                    '"someClass": {"prop": true}, "arr": [1,2,3,4]}',
                    new BasicTypeProps(),
                    true);
        }, /keys not match NonTypedProps/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                    '"someClass": {"nullProp": true, "undefinedProp": "a"}, "arr": "er"}',
                    new BasicTypeProps(),
                    strictValue);
        }, /expected to be array/);
        
        // Test class and JSON with different keys and properties
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"foo1":"value", "oneProp":"value"}',
                    new SingleProp(),
                    true);
        }, /keys not match/);
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"boolean":true, "string":"hello"}',
                    new BasicTypeProps(),
                    true);
        }, /keys not match/);
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"foo1":"value", "foo2":"value"}',
                new SingleProp(),
                false), 
            {oneProp:'hello'}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"foo1":"value"}',
                new SingleProp(),
                false), 
            {oneProp:'hello'}));
        
        assert.ok(ObjectUtils.isEqualTo(SerializationUtils.jsonToClass(
                '{"oneProp":"value", "nonexistant":"value"}',
                new SingleProp(),
                false), 
            {oneProp:'value'}));
        
        assert.throws(function() {
            SerializationUtils.jsonToClass(
                    '{"foo1":"value"}',
                    new SingleProp(),
                    true);
        }, /<foo1> not found/);
        
        // Test properties with typed and non typed array values
        // TODO
    }

    // Test exceptions caused by wrong type parameters
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
