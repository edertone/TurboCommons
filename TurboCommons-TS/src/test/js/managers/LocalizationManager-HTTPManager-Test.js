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
        delete window.HTTPManager;
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
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(sut.isLocaleLoaded('en_US'));
        assert.ok(sut.isLocaleLoaded('es_ES'));
        assert.ok(sut.isLocaleLoaded('fr_FR'));
        done();
    });
});


/**
 * isLanguageLoaded
 */
QUnit.test("isLanguageLoaded", function(assert){
    
    // Test invalid values
    assert.throws(function() {
        sut.isLanguageLoaded('en_US');
    }, /language must be a valid 2 digit value/);
    
    assert.throws(function() {
        sut.isLanguageLoaded('s');
    }, /language must be a valid 2 digit value/);

    assert.throws(function() {
        sut.isLanguageLoaded('somestring');
    }, /language must be a valid 2 digit value/);

    assert.notOk(sut.isLanguageLoaded('en'));
    assert.notOk(sut.isLanguageLoaded('es'));
    assert.notOk(sut.isLanguageLoaded('fr'));
    assert.notOk(sut.isLanguageLoaded('en'));

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];

    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(sut.isLanguageLoaded('en'));
        assert.ok(sut.isLanguageLoaded('es'));
        assert.ok(sut.isLanguageLoaded('fr'));
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
            sut.initialize(new HTTPManager(), emptyValues[i], [{label: 'a', path: 'p', bundles: ['b']}]);
        }, /no locales defined/);
        
        assert.throws(function() {
            sut.initialize(new HTTPManager(), ['es_ES'], emptyValues[i]);
        }, /Locations must be an array of objects/);
    } 

    assert.strictEqual(sut.locales().length, 0);
});


/**
 * initialize-without-bundles
 */
QUnit.test("initialize-without-bundles", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: []
    }];

    assert.strictEqual(sut.isInitialized(), false);

    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){
        
        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 3);
        assert.strictEqual(sut.languages().length, 3);
        
        assert.strictEqual(sut.isInitialized(), true);
        
        done();
    });
});


/**
 * initialize-without-finish-callback
 */
QUnit.test("initialize-without-finish-callback", function(assert){

    // This test is not necesssary loading async urls, but we still write it here
    // to match the sync version structure of tests.
    assert.ok(true);
});


/**
 * initialize
 */
QUnit.test("initialize-secondth-time-resets-state", function(assert){

    var done = assert.async(1);
    var completedUrlsCount = 0;
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    assert.strictEqual(sut.isInitialized(), false);
        
    sut.initialize(new HTTPManager(), ['es_ES'], locations, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        assert.strictEqual(completedUrlsCount, 1);
        
        var locations = [{
            label: 'test-json',
            path: window.basePath + '/test-json/$locale/$bundle.json',
            bundles: ['Locales']
        }];
      
        completedUrlsCount = 0;
          
        sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], locations, function(errors){

            assert.strictEqual(errors.length, 0);
            assert.strictEqual(sut.locales().length, 2);
            assert.strictEqual(completedUrlsCount, 2);
            done();
          
        }, function(completedUrl, totalUrls){
          
            completedUrlsCount ++;
            assert.strictEqual(totalUrls, 2);
        });
        
        assert.strictEqual(sut.locales().length, 2);
        
        assert.strictEqual(sut.isInitialized(), true);
        
    }, function(completedUrl, totalUrls){
        
        assert.strictEqual(sut.isInitialized(), false);
        
        completedUrlsCount ++;        
        assert.strictEqual(totalUrls, 1);
    });
});


/**
 * initialize
 */
QUnit.test("initialize-wrong-values", function(assert){

    assert.throws(function() {
        sut.initialize(new HTTPManager(), "Locales", [{label: 'a', path: 'b', bundles: ['c']}]);
    }, /no locales defined/);
    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), ['es_ES'], 123);
    }, /Locations must be an array of objects/);
});


/**
 * initialize
 */
QUnit.test("initialize-exceptions", function(assert){

    // Test exceptions    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), [1,2,3,4]);
    }, /Locations must be an array of objects/);
    
    assert.throws(function() {
        sut.initialize(new HTTPManager(), 150, [{label: 'a', path: 'b', bundles: ['c']}]);
    }, /no locales defined/);
});


/**
 * initialize
 */
QUnit.test("initialize-non-existing-bundle", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['nonexistingbundle']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        assert.strictEqual(errors.length, 1);
        assert.strictEqual(sut.locales().length, 1);
        done();    
    });
});


/**
 * initialize-non-existing-path
 */
QUnit.test("initialize-non-existing-path", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'thispathdoesnotexist',
        path: window.basePath + '/thispathdoesnotexist/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], locations, function(errors){

        assert.strictEqual(errors.length, 2);
        assert.strictEqual(sut.locales().length, 2);
        assert.strictEqual(sut.languages().length, 2);
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
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
QUnit.test("loadLocales-without-finished-callback", function(assert){
   
    // This test is not necesssary loading async urls, but we still write it here
    // to match the sync version structure of tests.
    assert.ok(true);
});


/**
 * loadLocales
 */
QUnit.test("loadLocales-wrong-values", function(assert){
   
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        assert.strictEqual(errors.length, 0);
        assert.strictEqual(sut.locales().length, 1);
        
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
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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

    assert.throws(function() {
        sut.loadBundles('somelocation', []);
    }, /no bundles specified to load on somelocation location/);
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    assert.throws(function() {
        sut.loadBundles('test-loadBundles', locations[0].bundles);
    }, /LocalizationManager not initialized/);
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        sut.loadBundles('test-loadBundles', ['MoreLocales'], function(errors){

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
QUnit.test("loadBundles-without-finished-callback", function(assert){
    
    // This test is not necesssary loading async urls, but we still write it here
    // to match the sync version structure of tests.
    assert.ok(true);
});


/**
 * loadBundles
 */
QUnit.test("loadBundles-nonexistant-bundles-or-locations", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        sut.loadBundles('test-loadBundles', ['nonexistant'], function(errors){

            assert.strictEqual(errors.length, 1);
            done();
        });
        
        assert.throws(function() {
            
            sut.loadBundles('nonexistant', ['MoreLocales'], function(errors){});
            
        }, /Undefined location: nonexistant/);
    }); 
});


/**
 * locales
 */
QUnit.test("locales", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        
        sut.setLocalesOrder(['en_US', 'fr_FR', 'es_ES']);
        
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'fr_FR', 'es_ES']));
        
        done();
    });
});


/**
 * languages
 */
QUnit.test("languages", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));
        
        sut.setLocalesOrder(['en_US', 'fr_FR', 'es_ES']);
        
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'fr', 'es']));
        
        done();
    });
});


/**
 * activeBundle
 */
QUnit.test("activeBundle", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales', 'MoreLocales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        assert.strictEqual(sut.activeBundle(), 'MoreLocales');
        
        sut.setActiveBundle('Locales');
        assert.strictEqual(sut.activeBundle(), 'Locales');
        
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
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.strictEqual(sut.primaryLocale(), 'es_ES');
        
        sut.setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
        
        assert.strictEqual(sut.primaryLocale(), 'en_US');
        
        done();
    });
});


/**
 * primaryLanguage
 */
QUnit.test("primaryLanguage", function(assert){
    
    assert.throws(function() {
        sut.primaryLanguage();
    }, /LocalizationManager not initialized/);

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.strictEqual(sut.primaryLanguage(), 'es');
        
        sut.setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
        
        assert.strictEqual(sut.primaryLanguage(), 'en');
        
        done();
    });
});


/**
 * setActiveBundle
 */
QUnit.test("setActiveBundle", function(assert){
    
    // Test empty values
    for(var i=0; i < emptyValuesCount; i++){

        assert.throws(function() {
            sut.setActiveBundle(emptyValues[i]);
        }, /bundle not loaded/);
    }

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales', 'MoreLocales']
    }];

    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        // Test ok values
        assert.strictEqual(sut.activeBundle(), 'MoreLocales');
        assert.strictEqual(sut.get('SOME_LOCALE'), 'Some locale');

        sut.setActiveBundle('Locales');
        assert.strictEqual(sut.activeBundle(), 'Locales');
        assert.strictEqual(sut.get('LOGIN'), 'Login');

        // Test wrong values
        assert.throws(function() {
            sut.setActiveBundle('nonexisting');
        }, /nonexisting bundle not loaded/);
        
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
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));
        
        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'fr']));
        
        sut.setPrimaryLocale('fr_FR');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['fr', 'en', 'es']));

        sut.setPrimaryLocale('es_ES');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'fr_FR', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'fr', 'en']));

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
 * setPrimaryLocales
 */
QUnit.test("setPrimaryLocales", function(assert){
    
    assert.throws(function() {
        sut.setPrimaryLocales(["en_US"]);
    }, /en_US not loaded/);
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));
        
        sut.setPrimaryLocales(['en_US']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'fr']));
        
        sut.setPrimaryLocales(['fr_FR']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['fr', 'en', 'es']));

        sut.setPrimaryLocales(['es_ES']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'fr_FR', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'fr', 'en']));

        sut.setPrimaryLocales(['en_US', 'fr_FR']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'fr_FR', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'fr', 'es']));

        sut.setPrimaryLocales(['es_ES', 'en_US', 'fr_FR']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));

        // Test exceptions
        assert.throws(function() {
            sut.setPrimaryLocales([]);
        }, /locales must be non empty string array with no duplicate elements/);
        
        assert.throws(function() {
            sut.setPrimaryLocales([1]);
        }, /Invalid locale value/);
        
        assert.throws(function() {
            sut.setPrimaryLocales(123);
        }, /locales must be non empty string array with no duplicate elements/);
        
        assert.throws(function() {
            sut.setPrimaryLocales(["LOGIN"]);
        }, /LOGIN not loaded/);
        
        assert.throws(function() {
            sut.setPrimaryLocales({});
        }, /locales must be non empty string array with no duplicate elements/);
        
        assert.throws(function() {
            sut.setPrimaryLocales(['es_ES', 'nothing']);
        }, /nothing not loaded/);
        
        assert.throws(function() {
            sut.setPrimaryLocales(['es_ES', 'es_ES']);
        }, /locales must be non empty string array with no duplicate elements/);
        
        done();
    });
});


/**
 * setPrimaryLanguage
 */
QUnit.test("setPrimaryLanguage", function(assert){
    
    assert.throws(function() {
        sut.setPrimaryLanguage('en');
    }, /en not loaded/);

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];

    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.strictEqual(errors.length, 0); 
        
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));

        sut.setPrimaryLanguage('en');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'fr']));

        sut.setPrimaryLanguage('fr');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['fr', 'en', 'es']));

        sut.setPrimaryLanguage('es');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'fr_FR', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'fr', 'en']));

        // Test exceptions
        assert.throws(function() {
            sut.setPrimaryLanguage(123);
        }, /123 not loaded/);

        assert.throws(function() {
            sut.setPrimaryLanguage(["LOGIN"]);
        }, /LOGIN not loaded/);

        assert.throws(function() {
            sut.setPrimaryLanguage({});
        }, /not loaded/);
        
        done();
    });
});


/**
 * setPrimaryLanguage_repeated_languages
 */
QUnit.test("setPrimaryLanguage-repeated-languages", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-duplicate-languages',
        path: window.basePath + '/test-duplicate-languages/$locale/$bundle.json',
        bundles: ['Locales']
    }];

    sut.initialize(new HTTPManager(), ['es_ES', 'en_GB', 'en_US'], locations, function(errors){

        assert.strictEqual(errors.length, 0); 
        
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_GB', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'en']));
        assert.strictEqual(sut.get('LOGIN'), 'acceder');

        sut.setPrimaryLanguage('en');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_GB', 'es_ES', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'en']));
        assert.strictEqual(sut.get('LOGIN'), 'login GB');
        
        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'en_GB', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'en', 'es']));
        assert.strictEqual(sut.get('LOGIN'), 'login US');
        
        sut.setPrimaryLanguage('es');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'en_GB']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'en']));
        assert.strictEqual(sut.get('LOGIN'), 'acceder');
        
        done();
    });
});


/**
 * setPrimaryLanguages
 */
QUnit.test("setPrimaryLanguages", function(assert){
    
    assert.throws(function() {
        sut.setPrimaryLanguages(["en"]);
    }, /en not loaded/);
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));
        
        sut.setPrimaryLanguages(['en']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'fr']));
        
        sut.setPrimaryLanguages(['fr']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['fr', 'en', 'es']));

        sut.setPrimaryLanguages(['es']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'fr_FR', 'en_US']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'fr', 'en']));

        sut.setPrimaryLanguages(['en', 'fr']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'fr_FR', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'fr', 'es']));

        sut.setPrimaryLanguages(['es', 'en', 'fr']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));

        // Test exceptions
        assert.throws(function() {
            sut.setPrimaryLanguages([]);
        }, /languages must be non empty string array with no duplicate elements/);
        
        assert.throws(function() {
            sut.setPrimaryLanguages([1]);
        }, /1 not loaded/);
        
        assert.throws(function() {
            sut.setPrimaryLanguages(123);
        }, /languages must be non empty string array with no duplicate elements/);
        
        assert.throws(function() {
            sut.setPrimaryLanguages(["LOGIN"]);
        }, /LOGIN not loaded/);
        
        assert.throws(function() {
            sut.setPrimaryLanguages({});
        }, /languages must be non empty string array with no duplicate elements/);
                
        assert.throws(function() {
            sut.setPrimaryLanguages(['es', 'nothing']);
        }, /nothing not loaded/);
        
        assert.throws(function() {
            sut.setPrimaryLanguages(['es', 'es']);
        }, /languages must be non empty string array with no duplicate elements/);
        
        done();
    });
});


/**
 * setLocalesOrder
 */
QUnit.test("setLocalesOrder", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-locales',
        path: window.basePath + '/test-locales/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US', 'fr_FR'], locations, function(errors){

        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['es', 'en', 'fr']));
        assert.strictEqual(sut.get('LOGIN'), 'acceder');
        
        sut.setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES', 'fr_FR']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['en', 'es', 'fr']));
        assert.strictEqual(sut.get('LOGIN'), 'Login');
        
        sut.setLocalesOrder(['fr_FR', 'en_US', 'es_ES']);
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['fr_FR', 'en_US', 'es_ES']));
        assert.ok(ArrayUtils.isEqualTo(sut.languages(), ['fr', 'en', 'es']));
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
        sut.get("KEY", "Locales", "some-location");
    }, /LocalizationManager not initialized/);

    sut.missingKeyFormat = '';
    assert.throws(function() {
        sut.get("KEY");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales", "some-location");
    }, /LocalizationManager not initialized/);
    
    sut.missingKeyFormat = '--$key--';
    assert.throws(function() {
        sut.get("KEY");
    }, /LocalizationManager not initialized/);

    assert.throws(function() {
        sut.get("KEY", "Locales");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("KEY", "Locales", "some-location");
    }, /LocalizationManager not initialized/);
    
    sut.missingKeyFormat = '<$key>';
    assert.throws(function() {
        sut.get("NON_EXISTANT");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("NON_EXISTANT", "Nonexistant");
    }, /LocalizationManager not initialized/);
    
    assert.throws(function() {
        sut.get("NON_EXISTANT", "Nonexistant", "some-location");
    }, /LocalizationManager not initialized/);
});


/**
 * get
 */
QUnit.test("get-initialized-missing-values", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
            sut.get("MISSINGKEY", "Locales", "some-location");
        }, /Location <some-location> not loaded/);
        
        // Test empty missingKeyFormat
        sut.missingKeyFormat = '';
        assert.strictEqual(sut.get("MISSINGKEY"), '');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", 'test-json'), '');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "some-location");
        }, /Location <some-location> not loaded/);
        
        // Test missingKeyFormat with some text
        sut.missingKeyFormat = 'sometext';
        assert.strictEqual(sut.get("MISSINGKEY"), 'sometext');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), 'sometext');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", 'test-json'), 'sometext');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "some-location");
        }, /Location <some-location> not loaded/);

        // Test missingKeyFormat with $key wildcard
        sut.missingKeyFormat = '--$key--';
        assert.strictEqual(sut.get("MISSINGKEY"), '--MISSINGKEY--');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '--MISSINGKEY--');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", 'test-json'), '--MISSINGKEY--');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "some-location");
        }, /Location <some-location> not loaded/);
        
        sut.missingKeyFormat = '<$key>';
        assert.strictEqual(sut.get("MISSINGKEY"), '<MISSINGKEY>');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales"), '<MISSINGKEY>');
        assert.strictEqual(sut.get("MISSINGKEY", "Locales", 'test-json'), '<MISSINGKEY>');
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "MissingBundle");
        }, /Bundle <MissingBundle> not loaded/);
        
        assert.throws(function() {
            sut.get("MISSINGKEY", "Locales", "some-location");
        }, /Location <some-location> not loaded/);
        
        done();
    }); 
});


/**
 * get
 */
QUnit.test("get-initialized-correct-values-with-single-locale-loaded", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        assert.strictEqual(sut.get('PASSWORD'), 'Password');
        assert.strictEqual(sut.get('USER'), 'User');
        
        var locations = [{
            label: 'test-loadBundles',
            path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
            bundles: ['Locales', 'MoreLocales']
        }];
        
        sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-loadBundles',
        path: window.basePath + '/test-loadBundles/$locale/$bundle.json',
        bundles: ['Locales', 'MoreLocales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], locations, function(errors){

        assert.strictEqual(sut.get('PASSWORD'), 'Contraseña');
        assert.strictEqual(sut.get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        
        done();
    });
});


/**
 * get
 */
QUnit.test("get-initialized-keys-from-multiple-locations-bundles-and-locales", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'path-1',
        path: window.basePath + '/test-multiple-paths/path-1/$locale/$bundle.properties',
        bundles: ['bundle1']
    },{
        label: 'path-2',
        path: window.basePath + '/test-multiple-paths/path-2/$locale/$bundle.properties',
        bundles: ['bundle1']
    },{
        label: 'path-3',
        path: window.basePath + '/test-multiple-paths/path-3/$locale/$bundle.properties',
        bundles: ['bundle1']
    }];
    
    sut.initialize(new HTTPManager(), ['es_ES', 'en_US'], locations, function(errors){

        assert.strictEqual(sut.get('PATH_NAME'), 'ruta3');
        assert.strictEqual(sut.get('PATH_NAME', 'bundle1'), 'ruta3');
        assert.strictEqual(sut.get('PATH_NAME', '', 'path-2'), 'ruta2');
        assert.strictEqual(sut.get('PATH_NAME', 'bundle1', 'path-2'), 'ruta2');
        assert.strictEqual(sut.get('PATH_NAME'), 'ruta2');

        assert.strictEqual(sut.get('NOT_ON_ES'), 'not on es 2');
        assert.strictEqual(sut.get('NOT_ON_ES', 'bundle1'), 'not on es 2');
        assert.strictEqual(sut.get('NOT_ON_ES', '', 'path-1'), 'not on es 1');
        assert.strictEqual(sut.get('NOT_ON_ES', 'bundle1'), 'not on es 1');
        done();
    });
});


/**
 * getStartCase
 */
QUnit.test("getStartCase", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-cases',
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-cases',
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-cases',
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-cases',
        path: window.basePath + '/test-cases/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US'], locations, function(errors){

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
    
    var locations = [{
        label: 'test-json',
        path: window.basePath + '/test-json/$locale/$bundle.json',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], locations, function(errors){

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
            sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-json');
        }, /key <NOT_TO_BE_FOUND> not found on Locales - test-json/);
        
        sut.missingKeyFormat = '--$key--';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-json'), '--NOT_TO_BE_FOUND--');
        
        sut.missingKeyFormat = '';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-json'), '');
        
        done();
    });
});


/**
 * test-properties
 */
QUnit.test("test-properties", function(assert){

    var done = assert.async(1);
    
    var locations = [{
        label: 'test-properties',
        path: window.basePath + '/test-properties/$locale/$bundle.properties',
        bundles: ['Locales']
    }];
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], locations, function(errors){

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
            sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-properties');
        }, /key <NOT_TO_BE_FOUND> not found on Locales - test-properties/);
        
        sut.missingKeyFormat = '--$key--';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-properties'), '--NOT_TO_BE_FOUND--');
        
        sut.missingKeyFormat = '';        
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales'), '');
        assert.strictEqual(sut.get('NOT_TO_BE_FOUND', 'Locales', 'test-properties'), '');
        
        done();
    });
});


/**
 * test-get-with-wildcards
 */
QUnit.test("test-get-with-wildcards", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-get-with-wildcards',
        path: window.basePath + '/test-get-with-wildcards/$locale/$bundle.properties',
        bundles: ['Locales']
    }];

    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], locations, function(errors){

        assert.strictEqual(sut.get('TAG_1'), 'this has no wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', []), 'this has no wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', ['test']), 'this has no wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', 'noreplacethis'), 'this has no wildcards');

        sut.setPrimaryLocale('es_ES');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US'])); 

        assert.strictEqual(sut.get('TAG_1'), 'ésta no tiene wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', []), 'ésta no tiene wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', ['test']), 'ésta no tiene wildcards');
        assert.strictEqual(sut.get('TAG_1', '', '', 'noreplacethis'), 'ésta no tiene wildcards');

        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES']));

        assert.strictEqual(sut.get('TAG_2'), 'this has {0}');
        assert.strictEqual(sut.get('TAG_2', '', '', []), 'this has {0}');
        assert.strictEqual(sut.get('TAG_2', '', '', ['replace']), 'this has replace');
        assert.strictEqual(sut.get('TAG_2', '', '', ['1', '2', '3']), 'this has 1');

        sut.setPrimaryLocale('es_ES');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US']));

        assert.strictEqual(sut.get('TAG_2'), 'ésta tiene {0}');
        assert.strictEqual(sut.get('TAG_2', '', '', []), 'ésta tiene {0}');
        assert.strictEqual(sut.get('TAG_2', '', '', ['replace']), 'ésta tiene replace');
        assert.strictEqual(sut.get('TAG_2', '', '', ['1', '2', '3']), 'ésta tiene 1');

        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES']));

        assert.strictEqual(sut.get('TAG_3'), 'this has {0} {1} {2}');
        assert.strictEqual(sut.get('TAG_3', '', '', []), 'this has {0} {1} {2}');
        assert.strictEqual(sut.get('TAG_3', '', '', ['replace']), 'this has replace {1} {2}');
        assert.strictEqual(sut.get('TAG_3', '', '', ['replace', 'replace']), 'this has replace replace {2}');
        assert.strictEqual(sut.get('TAG_3', '', '', ['1', '2', '3']), 'this has 1 2 3');
        assert.strictEqual(sut.get('TAG_3', '', '', ['1', '2', '3', '4']), 'this has 1 2 3');
        assert.strictEqual(sut.get('TAG_3', '', '', ['1', '', '3']), 'this has 1  3');

        assert.strictEqual(sut.get('TAG_4'), 'some $2 custom $0 format $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['1', '2', '3']), 'some $2 custom $0 format $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['1', '2', '3', '4']), 'some $2 custom $0 format $1');

        sut.wildCardsFormat = '$N';
        assert.strictEqual(sut.get('TAG_4'), 'some $2 custom $0 format $1');
        assert.strictEqual(sut.get('TAG_4', '', '', []), 'some $2 custom $0 format $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a']), 'some $2 custom a format $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b']), 'some $2 custom a format b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b', 'c']), 'some c custom a format b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b', 'c', 'd']), 'some c custom a format b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', '', 'c']), 'some c custom a format ');

        sut.setPrimaryLocale('es_ES');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['es_ES', 'en_US']));

        assert.strictEqual(sut.get('TAG_4'), 'algun $2 personalizado $0 formato $1');
        assert.strictEqual(sut.get('TAG_4', '', '', []), 'algun $2 personalizado $0 formato $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a']), 'algun $2 personalizado a formato $1');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b']), 'algun $2 personalizado a formato b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b', 'c']), 'algun c personalizado a formato b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', 'b', 'c', 'd']), 'algun c personalizado a formato b');
        assert.strictEqual(sut.get('TAG_4', '', '', ['a', '', 'c']), 'algun c personalizado a formato ');

        sut.setPrimaryLocale('en_US');
        assert.ok(ArrayUtils.isEqualTo(sut.locales(), ['en_US', 'es_ES']));

        assert.strictEqual(sut.get('TAG_5'), 'missing _1_ wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['1', '2', '3']), 'missing _1_ wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['1', '2', '3', '4']), 'missing _1_ wildcard _3_ indices _5_');

        sut.wildCardsFormat = '_N_';
        assert.strictEqual(sut.get('TAG_5'), 'missing _1_ wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', []), 'missing _1_ wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['a']), 'missing _1_ wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['a', 'b']), 'missing b wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['a', 'b', 'c']), 'missing b wildcard _3_ indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['a', 'b', 'c', 'd']), 'missing b wildcard d indices _5_');
        assert.strictEqual(sut.get('TAG_5', '', '', ['a', '', 'c', 'd', 'e', 'f', 'g']), 'missing  wildcard d indices f');
        
        done();
    });
});


/**
 * test-get-with-isBundleMandatory
 */
QUnit.test("test-get-with-isBundleMandatory", function(assert){
    
    var done = assert.async(1);
    
    var locations = [{
        label: 'test-get-with-wildcards',
        path: window.basePath + '/test-get-with-wildcards/$locale/$bundle.properties',
        bundles: ['Locales']
    }];

    sut.isBundleMandatory = true;
    
    sut.initialize(new HTTPManager(), ['en_US', 'es_ES'], locations, function(errors){

        assert.throws(function() {
            sut.get('TAG_1');
        }, /bundle is mandatory for key TAG_1/);
        
        sut.isBundleMandatory = false;
        
        assert.strictEqual(sut.get('TAG_1'), 'this has no wildcards');
        
        done();
    });
});

