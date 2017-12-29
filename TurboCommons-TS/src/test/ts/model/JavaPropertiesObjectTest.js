"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("JavaPropertiesObjectTest", {
    beforeEach : function(){

        window.basePath = './resources/model/javaPropertiesObject';

        window.JavaPropertiesObject = org_turbocommons.JavaPropertiesObject;

        window.wrongValues = [null, [], 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, {}];
        window.wrongValuesCount = window.wrongValues.length;
    },

    afterEach : function(){

        delete window.basePath;

        delete window.JavaPropertiesObject;

        delete window.wrongValues;
        delete window.wrongValuesCount;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new JavaPropertiesObject();
    assert.ok(test.length() === 0);

    test = new JavaPropertiesObject('');
    assert.ok(test.length() === 0);

    assert.throws(function() {
        new JavaPropertiesObject('       ');
    }, /invalid properties format/);

    assert.throws(function() {
        new JavaPropertiesObject("\n\n\n");
    }, /invalid properties format/);
    
    // Test ok values
    test = new JavaPropertiesObject('name=Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name = Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name    =    Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('      name = Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name=Stephen      ');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen      ');

    test = new JavaPropertiesObject('path=c:\\\\docs\\\\doc1');
    assert.ok(test.length() === 1);
    assert.ok(test.get('path') === 'c:\\docs\\doc1');
    
 // TODO - Add all missing tests from PHP version
    
});