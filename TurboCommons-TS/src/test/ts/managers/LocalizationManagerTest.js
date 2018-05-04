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
        
        window.basePath = './resources/managers/localizationManager';

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.sut = new org_turbocommons.LocalizationManager();
    },

    afterEach : function(){

        delete window.basePath;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;

        delete window.ArrayUtils;
        delete window.sut;
    }
});


/**
 * initialize
 */
QUnit.test("initialize-empty-values", function(assert){

    // Test empty values
    assert.strictEqual(sut.locales().length, 0);
    
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.initialize(emptyValues[i], [{path: 'p', bundles: ['b']}]);
        }, /no locales defined/);
        
        assert.throws(function() {
            sut.initialize(['es_ES'], emptyValues[i]);
        }, /bundles must be an array of objects/);
    } 

    assert.strictEqual(sut.locales().length, 0);
});


/**
 * initialize
 */
QUnit.test("initialize-secondth-time-resets-state", function(assert){

    var done = assert.async(1);
    var completedUrlsCount = 0;
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
        
    sut.initialize(['es_ES'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        assert.strictEqual(completedUrlsCount, 1);
        
        var bundles = [{
          path: window.basePath + '/test-json/$locale/$bundle.json',
          bundles: ['Locales']
        }];
      
        completedUrlsCount = 0;
          
        sut.initialize(['es_ES', 'en_US'], bundles, function(errors){

            assert.strictEqual(errors.length, 0);
            assert.strictEqual(sut.locales().length, 2);
            assert.strictEqual(completedUrlsCount, 2);
            done();
          
        }, function(completedUrl, totalUrls){
          
            completedUrlsCount ++;
            assert.strictEqual(totalUrls, 2);
        });
        
        assert.strictEqual(sut.locales().length, 0);
        
    }, function(completedUrl, totalUrls){
        
        completedUrlsCount ++;        
        assert.strictEqual(totalUrls, 1);
    });
});


/**
 * initialize
 */
QUnit.test("initialize-wrong-values", function(assert){

    assert.throws(function() {
        sut.initialize("Locales");
    }, /no locales defined/);
    
    assert.throws(function() {
        sut.initialize(['es_ES'], 123);
    }, /bundles must be an array of objects/);
});


/**
 * initialize
 */
QUnit.test("initialize-exceptions", function(assert){

    // Test exceptions    
    assert.throws(function() {
        sut.initialize([1,2,3,4]);
    }, /bundles must be an array of objects/);
    
    assert.throws(function() {
        sut.initialize(150);
    }, /no locales defined/);
});


/**
 * initialize
 */
QUnit.test("initialize-non-existing-bundle", function(assert){

    // Test ok values
    var done = assert.async(1);
    
    // We load a non existing bundle and expect the errorCallback to be fired
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['nonexistingbundle']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 1);
        assert.strictEqual(sut.locales().length, 1);
        done();    
    });
});


/**
 * loadLocales
 */
QUnit.test("loadLocales-empty-values", function(assert){

    var done = assert.async(1);
    
    assert.throws(function() {
        sut.loadLocales(['en_US']);
    }, /LocalizationManager not initialized/);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        assert.strictEqual(sut.locales()[0], 'en_US');
        
        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.loadLocales(emptyValues[i]);
            }, /no locales defined/);
        }
        
        done();
    }); 
});


/**
 * loadLocales
 */
QUnit.test("loadLocales-ok-values", function(assert){
   
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        
        // Test ok values
        sut.loadLocales(['es_ES'], function(errors){

            assert.strictEqual(errors.length, 0);
            assert.strictEqual(sut.locales().length, 2);
            assert.strictEqual(sut.locales()[0], 'en_US');
            assert.strictEqual(sut.locales()[1], 'es_ES');
            
            done();
        });
    }); 
});


/**
 * loadLocales
 */
QUnit.test("loadLocales-wrong-values", function(assert){
   
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        
        // Test missing locale
        sut.loadLocales(['fr_FR'], function(errors){

            assert.strictEqual(errors.length, 1);            
            assert.strictEqual(sut.locales().length, 2);
            done();
        });
    }); 
});


/**
 * loadLocales
 */
QUnit.test("loadLocales-duplicate-locales", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.get("LOGIN"), "Login");
        
        sut.loadLocales(['en_US'], function(errors){

            assert.strictEqual(errors.length, 0);
            assert.strictEqual(sut.locales().length, 1);
            assert.strictEqual(sut.locales()[0], 'en_US');
            assert.strictEqual(sut.get("LOGIN"), "Login");
            
            sut.loadLocales(['en_US'], function(errors){

                assert.strictEqual(errors.length, 0);
                assert.strictEqual(sut.locales().length, 1);
                assert.strictEqual(sut.locales()[0], 'en_US');
                assert.strictEqual(sut.get("LOGIN"), "Login");
                
                done();
            });
        });
    }); 
});


/**
 * loadBundles
 */
QUnit.test("loadBundles-empty-values", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    assert.throws(function() {
        sut.loadBundles(bundles.path, bundles.bundles);
    }, /LocalizationManager not initialized/);
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        assert.strictEqual(sut.locales()[0], 'en_US');
        
        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.loadBundles(emptyValues[i]);
            });
        }
        
        done();
    }); 
});


/**
 * loadBundles
 */
QUnit.test("loadBundles-ok-values", function(assert){
    
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        sut.loadBundles(window.basePath + '/test-loadBundles/$locale/$bundle.json',
                ['MoreLocales'], function(errors){

            assert.strictEqual(errors.length, 0);
            assert.strictEqual(sut.locales().length, 1);
            assert.strictEqual(sut.locales()[0], 'en_US');  
            done();        
        });
    }); 
});


/**
 * loadBundles
 */
QUnit.test("loadBundles-nonexistant-bundles-or-pahts", function(assert){
    
    var done = assert.async(2);
    
    var bundles = [{
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        sut.loadBundles(window.basePath + '/test-loadBundles/$locale/$bundle.json',
                ['nonexistant'], function(errors){

            assert.strictEqual(errors.length, 1);
            done();
        });
        
        sut.loadBundles(window.basePath + '/test-nonexistant/$locale/$bundle.json',
                ['MoreLocales'], function(errors){

            assert.strictEqual(errors.length, 1);
            done();
        });  
    }); 
});


/**
 * get
 */
QUnit.test("get-non-initialized", function(assert){

    assert.strictEqual('$exception', sut.missingKeyFormat);
    
    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.get(emptyValues[i]);
        }, /LocalizationManager not initialized/);
    }
    
    assert.throws(function() {
        sut.get("KEY");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales", "Some/path");
    }, /LocalizationManager not initialized/);

    sut.missingKeyFormat = '';
    assert.throws(function() {
        sut.get("KEY");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales", "Some/path");
    }, /LocalizationManager not initialized/);
    
    sut.missingKeyFormat = '--$key--';
    assert.throws(function() {
        sut.get("KEY");
    }, /LocalizationManager not initialized/);

    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales", "Some/path");
    }, /LocalizationManager not initialized/);
    
    sut.missingKeyFormat = '<$key>';
    assert.throws(function() {
        sut.get("NON_EXISTANT");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("NON_EXISTANT", "Nonexistant");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("NON_EXISTANT", "Nonexistant", "Nonexistant/path");
    }, /LocalizationManager not initialized/);
});


/**
 * get
 */
QUnit.test("get-initialized-missing-values", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        // Test missingKeyFormat with $exception wildcard
        assert.strictEqual('$exception', sut.missingKeyFormat);
        
        assert.throws(function() {
            sut.get("MISSINGKEY");
        }, /key <MISSINGKEY> not found/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales");
        }, /key <MISSINGKEY> not found/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "Some/path");
        }, /Path <Some\/path> not loaded/);
        
        // Test empty missingKeyFormat
        sut.missingKeyFormat = '';
        assert.strictEqual(sut.get("MISSINGKEY"), '');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", window.basePath + '/test-json/$locale/$bundle.json'), '');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "Some/path");
        }, /Path <Some\/path> not loaded/);
        
        // Test missingKeyFormat with some text
        sut.missingKeyFormat = 'sometext';
        assert.strictEqual(sut.get("MISSINGKEY"), 'sometext');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), 'sometext');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", window.basePath + '/test-json/$locale/$bundle.json'), 'sometext');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "Some/path");
        }, /Path <Some\/path> not loaded/);

        // Test missingKeyFormat with $key wildcard
        sut.missingKeyFormat = '--$key--';
        assert.strictEqual(sut.get("MISSINGKEY"), '--MISSINGKEY--');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '--MISSINGKEY--');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", window.basePath + '/test-json/$locale/$bundle.json'), '--MISSINGKEY--');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "Some/path");
        }, /Path <Some\/path> not loaded/);
        
        sut.missingKeyFormat = '<$key>';
        assert.strictEqual(sut.get("MISSINGKEY"), '<MISSINGKEY>');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '<MISSINGKEY>');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", window.basePath + '/test-json/$locale/$bundle.json'), '<MISSINGKEY>');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "Some/path");
        }, /Path <Some\/path> not loaded/);
        
        done();
    }); 
});


/**
 * get
 */
QUnit.test("get-initialized-correct-values-with-single-locale-loaded", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('USER'), 'User');
        
        var bundles = [{
            path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
            bundles: ['Locales', 'MoreLocales']
        }];
        
        sut.initialize(['en_US'], bundles, function(errors){

            assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');
            assert.strictEqual(sut.get('PASSWORD'), 'Password');
            assert.strictEqual(sut.get('USER'), 'User');
            
            assert.strictEqual(sut.get('SOME_LOCALE', 'MoreLocales'), 'Some locale');
            assert.strictEqual(sut.get('SOME_OTHER'), 'Some other');

            done();
        }); 
    }); 
});


/**
 * get
 */
QUnit.test("get-initialized-keys-from-another-bundle-fail", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales', 'MoreLocales']
    }];
    
    sut.initialize(['en_US'], bundles, function(errors){

        assert.throws(function() {
            sut.get("LOGIN", "MoreLocales");
        }, /key <LOGIN> not found on MoreLocales/);
        
        assert.throws(function() {
            sut.get("SOME_OTHER", "Locales");
        }, /key <SOME_OTHER> not found on Locales/);
        done();
    });
});


/**
 * get
 */
QUnit.test("get-initialized-values-for-multiple-locales", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(['es_ES', 'en_US'], bundles, function(errors){

        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        
        done();
    });
});


/**
 * get
 */
QUnit.test("get-initialized-keys-from-multiple-paths-bundles-and-locales", function(assert){
    
var done = assert.async(1);
    // TODO - un bon pollastre
    var bundles = [{
        path: window.basePath + '/test-getStartCase/$locale/$bundle.json',
        bundles: ['Locales']
    },{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    },{
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales', 'MoreLocales']
    }];
    
    sut.initialize(['es_ES', 'en_US'], bundles, function(errors){

        assert.strictEqual(sut.get('LOGIN'), 'Login');
        
        done();
    });
 // TODO
});


/**
 * locales
 */
QUnit.test("locales", function(assert){

});


/**
 * getStartCase
 */
QUnit.test("getStartCase", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
    
//    assert.ok(sut.getStartCase('h', StringUtils::FORMAT_START_CASE) === 'H');
//    assert.ok(StringUtils::formatCase('HI', StringUtils::FORMAT_START_CASE) === 'Hi');
//    assert.ok(StringUtils::formatCase('hello', StringUtils::FORMAT_START_CASE) === 'Hello');
//    assert.ok(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_START_CASE) === 'Helló. Únder Ü??');
//    assert.ok(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_START_CASE) === 'Óyeà!!! Üst??');
//    assert.ok(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_START_CASE) === 'Hello People');
//    assert.ok(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_START_CASE) === "Över! Còmpléx.   \n\n\n\t\t   Ís Test!is?for!?!? You.!  ");

});


/**
 * getAllUpperCase
 */
QUnit.test("getAllUpperCase", function(assert){

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
QUnit.test("getAllLowerCase", function(assert){

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
 * getFirstUpperRestLower
 */
QUnit.test("getFirstUpperRestLower", function(assert){

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
    sut.paths = [window.basePath + '/test-json/$locale/$bundle.json'];

    var done = assert.async(2);

    sut.loadBundle('Locales', function(){

        // Test EN_US
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('MISSING_TAG'), 'Missing tag');
        assert.strictEqual(sut.get('USER', 'Locales'), 'User');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales, ['en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.paths, [window.basePath + '/test-json/$locale/$bundle.json']));

        // Test ES_ES
        sut.locales = ['es_ES', 'en_US'];

        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('USER'), 'Usuario');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Test tag that is missing on es_ES but found on en_US
        assert.strictEqual(sut.get('MISSING_TAG'), 'Missing tag');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales, ['es_ES', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.paths, [window.basePath + '/test-json/$locale/$bundle.json']));

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
        
    }, function(path){
        
        assert.strictEqual(path, window.basePath + '/test-json/en_US/nonexistant.json'); 
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
QUnit.test("test-properties", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});
