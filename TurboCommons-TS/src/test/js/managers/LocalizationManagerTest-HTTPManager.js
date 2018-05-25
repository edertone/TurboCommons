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
        window.HTTPManager = org_turbocommons.HTTPManager;
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
 * isLocaleLoaded
 */
QUnit.test("isLocaleLoaded", function(assert){

    assert.notOk(sut.isLocaleLoaded('en_US'));
    assert.notOk(sut.isLocaleLoaded('es_ES'));
    assert.notOk(sut.isLocaleLoaded('fr_FR'));
    assert.notOk(sut.isLocaleLoaded('en_GB'));
    
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], bundles, function(errors){

        assert.ok(sut.isLocaleLoaded('en_US'));
        assert.ok(sut.isLocaleLoaded('es_ES'));
        assert.ok(sut.isLocaleLoaded('fr_FR'));
        done();
    });
});


/**
 * initialize
 */
QUnit.test("initialize-empty-values", function(assert){

    // Test empty values
    assert.strictEqual(sut.locales().length, 0);
    
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.initialize(new HTTPManager(), emptyValues[i], [{path: 'p', bundles: ['b']}]);
        }, /no locales defined/);
        
        assert.throws(function() {
            sut.initialize(new HTTPManager(), ['es_ES'], emptyValues[i]);
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
        
    sut.initialize(new HTTPManager(), ['es_ES'], bundles, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        assert.strictEqual(completedUrlsCount, 1);
        
        var bundles = [{
          path: window.basePath + '/test-json/$locale/$bundle.json',
          bundles: ['Locales']
        }];
      
        completedUrlsCount = 0;
          
        sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], bundles, function(errors){

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
        sut.initialize(new HTTPManager(), "Locales");
    }, /no locales defined/);
    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), ['es_ES'], 123);
    }, /bundles must be an array of objects/);
});


/**
 * initialize
 */
QUnit.test("initialize-exceptions", function(assert){

    // Test exceptions    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), [1,2,3,4]);
    }, /bundles must be an array of objects/);
    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), 150);
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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('USER'), 'User');
        
        var bundles = [{
            path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
            bundles: ['Locales', 'MoreLocales']
        }];
        
        sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

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
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], bundles, function(errors){

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
    
    var bundles = [{
        path: window.basePath + '/test-multiple-paths/path-1/$locale/$bundle.properties',
        bundles: ['bundle1']
    },{
        path: window.basePath + '/test-multiple-paths/path-2/$locale/$bundle.properties',
        bundles: ['bundle1']
    },{
        path: window.basePath + '/test-multiple-paths/path-3/$locale/$bundle.properties',
        bundles: ['bundle1']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], bundles, function(errors){

        assert.strictEqual(sut.get('PATH_NAME'), 'ruta3');
        assert.strictEqual(sut.get('PATH_NAME', 'bundle1'), 'ruta3');
        assert.strictEqual(sut.get('PATH_NAME', '', window.basePath + '/test-multiple-paths/path-2/$locale/$bundle.properties'), 'ruta2');
        assert.strictEqual(sut.get('PATH_NAME', 'bundle1', window.basePath + '/test-multiple-paths/path-2/$locale/$bundle.properties'), 'ruta2');
        assert.strictEqual(sut.get('PATH_NAME'), 'ruta2');

        assert.strictEqual(sut.get('NOT_ON_ES'), 'not on es 2');
        assert.strictEqual(sut.get('NOT_ON_ES', 'bundle1'), 'not on es 2');
        assert.strictEqual(sut.get('NOT_ON_ES', '', window.basePath + '/test-multiple-paths/path-1/$locale/$bundle.properties'), 'not on es 1');
        assert.strictEqual(sut.get('NOT_ON_ES', 'bundle1'), 'not on es 1');
        done();
    });
});


/**
 * locales
 */
QUnit.test("locales", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], bundles, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        
        sut.setLocalesOrder(['en_US', 'fr_FR', 'es_ES']);
        
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'fr_FR', 'es_ES']));
        
        done();
    });
});


/**
 * primaryLocale
 */
QUnit.test("primaryLocale", function(assert){
    
    assert.throws(function() {
        sut.primaryLocale();
    }, /LocalizationManager not initialized/);

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], bundles, function(errors){

        assert.strictEqual(sut.primaryLocale(), 'es_ES');
        
        sut.setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
        
        assert.strictEqual(sut.primaryLocale(), 'en_US');
        
        done();
    });
});


/**
 * setPrimaryLocale
 */
QUnit.test("setPrimaryLocale", function(assert){
    
    assert.throws(function() {
        sut.setPrimaryLocale('en_US');
    }, /en_US not loaded/);
    
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], bundles, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        
        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        
        sut.setPrimaryLocale('fr_FR');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));

        sut.setPrimaryLocale('es_ES');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'fr_FR', 'en_US']));

        // Test exceptions
        assert.throws(function() {
            sut.setPrimaryLocale(123);
        }, /Invalid locale value/);
        
        assert.throws(function() {
            sut.setPrimaryLocale(["LOGIN"]);
        }, /Invalid locale value/);
        
        assert.throws(function() {
            sut.setPrimaryLocale({});
        }, /Invalid locale value/);
        
        done();
    });
});


/**
 * setLocalesOrder
 */
QUnit.test("setLocalesOrder", function(assert){
    
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], bundles, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.strictEqual(sut.get('LOGIN'), 'acceder');
        
        sut.setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.strictEqual(sut.get('LOGIN'), 'Login');
        
        sut.setLocalesOrder(['fr_FR', 'en_US', 'es_ES']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.strictEqual(sut.get('LOGIN'), 'loguele');
        
        // Test exceptions
        assert.throws(function() {
            sut.setLocalesOrder(['fr_FR']);
        }, /locales must contain all the currently loaded locales/);
        
        assert.throws(function() {
            sut.setLocalesOrder(['fr_FR', 'en_US', 'es_ES', 'en_GB']);
        }, /locales must contain all the currently loaded locales/);
        
        assert.throws(function() {
            sut.setLocalesOrder(['fr_FR', 'en_US', 'en_GB']);
        }, /en_GB not loaded/);
        
        assert.throws(function() {
            sut.setLocalesOrder(123);
        }, /locales must be an array/);
        
        assert.throws(function() {
            sut.setLocalesOrder(["LOGIN"]);
        }, /locales must contain all the currently loaded locales/);
        
        assert.throws(function() {
            sut.setLocalesOrder({});
        }, /locales must be an array/);
        
        done();
    });
});


/**
 * getStartCase
 */
QUnit.test("getStartCase", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.getStartCase(emptyValues[i]);
            }, /not found on Locales/);
        }

        // Test ok values
        assert.strictEqual(sut.getStartCase('H'), 'H');
        assert.strictEqual(sut.getStartCase('HELLO'), 'Hello');
        assert.strictEqual(sut.getStartCase('HELLO_UNDER'), 'Helló. Únder Ü??');
        assert.strictEqual(sut.getStartCase('MIXED_CASE'), 'Hello People');
        assert.strictEqual(sut.getStartCase('MULTIPLE_WORDS'), 'Word1 Word2 Word3 Word4 Word5');
        assert.strictEqual(sut.getStartCase('SOME_ACCENTS'), 'Óyeà!!! Üst??');
        
        done();
    });
});


/**
 * getAllUpperCase
 */
QUnit.test("getAllUpperCase", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.getAllUpperCase(emptyValues[i]);
            }, /not found on Locales/);
        }

        // Test ok values
        assert.strictEqual(sut.getAllUpperCase('H'), 'H');
        assert.strictEqual(sut.getAllUpperCase('HELLO'), 'HELLO');
        assert.strictEqual(sut.getAllUpperCase('HELLO_UNDER'), 'HELLÓ. ÚNDER Ü??');
        assert.strictEqual(sut.getAllUpperCase('MIXED_CASE'), 'HELLO PEOPLE');
        assert.strictEqual(sut.getAllUpperCase('MULTIPLE_WORDS'), 'WORD1 WORD2 WORD3 WORD4 WORD5');
        assert.strictEqual(sut.getAllUpperCase('SOME_ACCENTS'), 'ÓYEÀ!!! ÜST??');
        
        done();
    });
});


/**
 * getAllLowerCase
 */
QUnit.test("getAllLowerCase", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.getAllLowerCase(emptyValues[i]);
            }, /not found on Locales/);
        }

        // Test ok values
        assert.strictEqual(sut.getAllLowerCase('H'), 'h');
        assert.strictEqual(sut.getAllLowerCase('HELLO'), 'hello');
        assert.strictEqual(sut.getAllLowerCase('HELLO_UNDER'), 'helló. únder ü??');
        assert.strictEqual(sut.getAllLowerCase('MIXED_CASE'), 'hello people');
        assert.strictEqual(sut.getAllLowerCase('MULTIPLE_WORDS'), 'word1 word2 word3 word4 word5');
        assert.strictEqual(sut.getAllLowerCase('SOME_ACCENTS'), 'óyeà!!! üst??');
        
        done();
    });
});


/**
 * getFirstUpperRestLower
 */
QUnit.test("getFirstUpperRestLower", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], bundles, function(errors){

        // Test empty values
        for (var i = 0; i < emptyValuesCount; i++) {
            
            assert.throws(function() {
                sut.getFirstUpperRestLower(emptyValues[i]);
            }, /not found on Locales/);
        }

        // Test ok values
        assert.strictEqual(sut.getFirstUpperRestLower('H'), 'H');
        assert.strictEqual(sut.getFirstUpperRestLower('HELLO'), 'Hello');
        assert.strictEqual(sut.getFirstUpperRestLower('HELLO_UNDER'), 'Helló. únder ü??');
        assert.strictEqual(sut.getFirstUpperRestLower('MIXED_CASE'), 'Hello people');
        assert.strictEqual(sut.getFirstUpperRestLower('MULTIPLE_WORDS'), 'Word1 word2 word3 word4 word5');
        assert.strictEqual(sut.getFirstUpperRestLower('SOME_ACCENTS'), 'Óyeà!!! üst??');
        
        done();
    });
});


/**
 * test-json
 */
QUnit.test("test-json", function(assert){
    
    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], bundles, function(errors){

        // Test EN_US
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        assert.strictEqual(sut.get('USER', 'Locales'), 'User');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES']));
        
        // Test ES_ES
        sut.setLocalesOrder(['es_ES', 'en_US']);
        
        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('USER'), 'Usuario');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Test tag that is missing on es_ES but found on en_US
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        
        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US']));  

        // Test tag that is missing everywhere
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND');
        }, /key <NOT_TO_BE_FOUND> not found on Locales/);
        
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND', 'Locales');
        }, /key <NOT_TO_BE_FOUND> not found on Locales/);
        
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-json/$locale/$bundle.json');
        }, /key <NOT_TO_BE_FOUND> not found on Locales - /);
        
        sut.missingKeyFormat = '--$key--';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-json/$locale/$bundle.json'), '--NOT_TO_BE_FOUND--');
        
        sut.missingKeyFormat = '';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-json/$locale/$bundle.json'), '');
        
        done();
    });
});


/**
 * test-properties
 */
QUnit.test("test-properties", function(assert){

    var done = assert.async(1);
    
    var bundles = [{
        path: window.basePath + '/test-properties/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], bundles, function(errors){

        // Test EN_US
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        assert.strictEqual(sut.get('USER', 'Locales'), 'User');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES']));
        
        // Test ES_ES
        sut.setLocalesOrder(['es_ES', 'en_US']);
        
        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('USER'), 'Usuario');
        assert.strictEqual(sut.get('LOGIN', 'Locales'), 'Login');

        // Test tag that is missing on es_ES but found on en_US
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        
        // Verify defined attributes are still the same
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US']));  

        // Test tag that is missing everywhere
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND');
        }, /key <NOT_TO_BE_FOUND> not found on Locales/);
        
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND', 'Locales');
        }, /key <NOT_TO_BE_FOUND> not found on Locales/);
        
        assert.throws(function() {
            sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-properties/$locale/$bundle.properties');
        }, /key <NOT_TO_BE_FOUND> not found on Locales - /);
        
        sut.missingKeyFormat = '--$key--';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-properties/$locale/$bundle.properties'), '--NOT_TO_BE_FOUND--');
        
        sut.missingKeyFormat = '';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', window.basePath + '/test-properties/$locale/$bundle.properties'), '');
        
        done();
    });
});
