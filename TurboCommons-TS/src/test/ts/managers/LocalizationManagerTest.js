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
 * test-json
 */
QUnit.test("test-json", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    sut.locales = ['en_US', 'es_ES'];
    sut.paths = ['./resources/managers/localizationManager/test-json/$locale/$bundle.json'];

    var done = assert.async();

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
        
        done();
    });

    // Test wrong values
    // TODO

    // Test exceptions
    sut.loadBundle('nonexistant', function(){
        
        assert.ok(false);
        
    }, function(){
        
        assert.ok(true);        
    }, 0);
    // TODO
});


/**
 * todo
 */
QUnit.todo("todo", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});
