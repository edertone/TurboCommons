"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("LocalizationManagerTest", {
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.sut = new org_turbocommons.LocalizationManager();
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;

        delete window.ArrayUtils;
        delete window.sut;
    }
});


/**
 * loadBundle
 */
QUnit.test("loadBundle", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.loadBundle(emptyValues[i]);
        }, /bundle must be a non empty string/);
    } 

    // Test ok values
    // Tested on other tests

    // Test wrong values
    // Tested on other tests

    // Test exceptions
    assert.throws(function() {
        sut.loadBundle([1,2,3,4]);
    }, /must be a non empty string/);
    
    assert.throws(function() {
        sut.loadBundle(150);
    }, /bundle must be a non empty string/);
});


/**
 * get
 */
QUnit.test("get", function(assert){

    // Test non initialized
    assert.throws(function() {
        sut.get("KEY");
    }, /Bundle <> does not exist/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /Bundle <Locales> does not exist/);

    // Test ok values
    sut.locales = ['en_US'];
    sut.paths = ['./resources/managers/localizationManager/test-json/$locale/$bundle.json'];

    var done = assert.async();

    sut.loadBundle('Locales', function(){

        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.get(emptyValues[i]);
            }, /not found/);
        } 

        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });

    // Test wrong values
    // Already tested

    // Test exceptions
    // Already tested
});


/**
 * getAllUpperCase
 */
QUnit.todo("getAllUpperCase", function(assert){

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
 * getAllLowerCase
 */
QUnit.todo("getAllLowerCase", function(assert){

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
 * test-json
 */
QUnit.test("test-json", function(assert){

    // Test ok values
    sut.locales = ['en_US', 'es_ES'];
    sut.paths = ['./resources/managers/localizationManager/test-json/$locale/$bundle.json'];

    var done = assert.async(2);

    sut.loadBundle('Locales', function(){

        // Test EN_US
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('MISSING_TAG'), 'Missing tag');
        assert.strictEqual(sut.get('USER', 'Locales'), 'User');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales, ['en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.paths, ['./resources/managers/localizationManager/test-json/$locale/$bundle.json']));

        // Test ES_ES
        sut.locales = ['es_ES', 'en_US'];

        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('USER'), 'Usuario');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Test tag that is missing on es_ES but found on en_US
        assert.strictEqual(sut.get('MISSING_TAG'), 'Missing tag');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales, ['es_ES', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.paths, ['./resources/managers/localizationManager/test-json/$locale/$bundle.json']));

        // Test tag that is missing everywhere
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND', 'Locales');
        }, /key <NOT_TO_BE_FOUND> not found/);
        
        sut.missingKeyFormat = '--$key--';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
        
        sut.missingKeyFormat = '';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '');
        
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });

    // Test wrong values
    // Already tested

    // Test exceptions
    sut.loadBundle('nonexistant', function(){
        
        assert.ok(false);
        done();
        
    }, function(){
        
        assert.ok(true); 
        done();
    }, 0);
    
    assert.throws(function() {
        sut.loadBundle('Locales', null, null, 2);
    }, /invalid pathIndex/);
    
    assert.throws(function() {
        sut.loadBundle('Locales', null, null, 10);
    }, /invalid pathIndex/);
});


/**
 * test-properties
 */
QUnit.todo("test-properties", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});
