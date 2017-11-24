"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("HTTPManagerTest", {
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.HTTPManager = org_turbocommons.HTTPManager;
        window.browserManager = new org_turbocommons.BrowserManager();
        window.sut = new org_turbocommons.HTTPManager();

        window.sut.timeout = 3000;
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
        delete window.HTTPManager;
        delete window.browserManager;
        delete window.sut;
    }
});


/**
 * testConstructor
 */
QUnit.test("testConstructor", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut = new HTTPManager(emptyValues[i]);
        });
    }

    // Test ok values
    sut = new HTTPManager(true);
    assert.ok(sut.asynchronous === true);
    
    sut = new HTTPManager(false);
    assert.ok(sut.asynchronous === false);

    // Test wrong values
    // Already tested at empty values

    // Test exceptions
    // Already tested at empty values
});


/**
 * isInternetAvailable
 */
QUnit.test("isInternetAvailable", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.isInternetAvailable(emptyValues[i]);
        });
        
        assert.throws(function() {
            sut.isInternetAvailable(emptyValues[i], emptyValues[i]);
        });
    }

    // Test ok values    
    var done = assert.async();
    
    sut.isInternetAvailable(function(){
        
        assert.ok(sut.internetCheckLocations.length === 3);
        
        sut.internetCheckLocations = [browserManager.getCurrentUrl()];
        
        sut.isInternetAvailable(function(){
            
            assert.ok(sut.internetCheckLocations.length === 1);
            
            // Test exceptions
            sut.internetCheckLocations = ['hello bad url'];
            
            assert.throws(function() {
                sut.isInternetAvailable(function(){}, function(){});
            });
            
            sut.internetCheckLocations = [];
            
            assert.throws(function() {
                sut.isInternetAvailable(function(){}, function(){});
            });
            
            done();
            
        }, function(){
            
            assert.ok(false, 'Internet is not available');
            done();
        });
        
    }, function(){
        
        assert.ok(false, 'Internet is not available');
        done();
    });
    
    // Test wrong values
    // We cannot force offline state, so this cannot be tested
});


/**
 * isDomainFreeToRegister
 */
QUnit.todo("isDomainFreeToRegister", function(assert){

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
 * urlExists
 */
QUnit.test("urlExists", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.urlExists(emptyValues[i]);
        });
        
        assert.throws(function() {
            sut.isInternetAvailable(emptyValues[i], emptyValues[i]);
        });
        
        assert.throws(function() {
            sut.isInternetAvailable(emptyValues[i], emptyValues[i], emptyValues[i]);
        });
    }

    // Test ok values
//    sut.urlExists('https://www.google.com', function(){
//        
//        assert.ok(true);
//        
//    }, function(){
//        
//        assert.ok(false);
//    });
    // TODO

    // Test wrong values
//    sut.urlExists('https://www.thisurldoesnotexistsdfasdfwermv.asd', function(){
//        
//        assert.ok(false);
//        
//    }, function(){
//        
//        assert.ok(true);
//    });

    // Test exceptions
    // TODO
});


/**
 * getUrlHeaders
 */
QUnit.todo("getUrlHeaders", function(assert){

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
 * get
 */
QUnit.todo("get", function(assert){

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
 * post
 */
QUnit.todo("post", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});



