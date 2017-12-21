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

        window.StringUtils = org_turbocommons.StringUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.HashMapObject = org_turbocommons.HashMapObject;
        window.HTTPManager = org_turbocommons.HTTPManager;
        window.browserManager = new org_turbocommons.BrowserManager();
        window.sut = new org_turbocommons.HTTPManager();

        window.sut.timeout = 3000;
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
        
        delete window.StringUtils;
        delete window.ObjectUtils;
        delete window.HashMapObject;
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
 * urlExists
 */
QUnit.test("urlExists", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.urlExists(emptyValues[i]);
        });
        
        assert.throws(function() {
            sut.urlExists('https://www.google.com', emptyValues[i]);
        });
        
        assert.throws(function() {
            sut.urlExists('https://www.google.com', function(){}, emptyValues[i]);
        });
    }

    // Test ok values
    var done = assert.async(2);
    
    sut.urlExists(browserManager.getCurrentUrl(), function(){
        
        assert.ok(true);
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });

    // Test wrong values
    sut.urlExists('https://www.google.com', function(){
        
        assert.ok(false);
        done();
        
    }, function(){
        
        // Google triggers the noCallback due to CORS restrictions 
        assert.ok(true);
        done();
    });

    // Test exceptions
    // Already tested by empty values
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
 * generateUrlQueryString
 */
QUnit.test("generateUrlQueryString", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        if(ObjectUtils.isObject(emptyValues[i])){
            
            assert.strictEqual(sut.generateUrlQueryString(emptyValues[i]), '');
            
        }else{
            
            assert.throws(function() {
                sut.generateUrlQueryString(emptyValues[i]);
            });
        }
    }

    // Test ok values with objects
    assert.strictEqual(sut.generateUrlQueryString({a:1}), 'a=1');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:2}), 'a=1&b=2');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:2,c:3}), 'a=1&b=2&c=3');
    assert.strictEqual(sut.generateUrlQueryString({a:"h&b",b:"-_.*="}), 'a=h%26b&b=-_.*%3D');
    assert.strictEqual(sut.generateUrlQueryString({"/&%$·#&=":"1"}), '%2F%26%25%24%C2%B7%23%26%3D=1');
    assert.strictEqual(sut.generateUrlQueryString({"%":"%"}), '%25=%25');
    
    // Test ok values with HashMapObjects
    var hashMapObject = new HashMapObject();
    hashMapObject.set('/&%$·#&=', 1);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1');
    
    hashMapObject.set('b', 2);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2');
    
    hashMapObject.set('c', 3);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3');
    
    hashMapObject.set('d', 'he/&%$·#&=llo');
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3&d=he%2F%26%25%24%C2%B7%23%26%3Dllo');
    
    // Test wrong values
    // Tested with exceptions

    // Test exceptions
    assert.throws(function() {
        sut.generateUrlQueryString("hello");
    });
    
    assert.throws(function() {
        sut.generateUrlQueryString([1,2,3,4]);
    });
    
    assert.throws(function() {
        sut.generateUrlQueryString(new Error());
    });
    
    assert.throws(function() {
        sut.generateUrlQueryString(10);
    });
    
    assert.throws(function() {
        sut.generateUrlQueryString(true);
    });
});


/**
 * get
 */
QUnit.test("get", function(assert){
    
    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.get(emptyValues[i]);
        }, /must be a non empty string/);
    } 
    
    // Test ok values
    var done = assert.async(2);
    
    sut.get(browserManager.getCurrentUrl(), function(result){
            
        assert.ok(!StringUtils.isEmpty(result));
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });
    
    sut.get('https://www.iuyyt7ct6u8289gduf823439ryhhgfxhjwer234.casdfase43', function(result){
        
        assert.ok(false);
        done();
        
    }, function(){
        
        assert.ok(true);
        done();
    });

    // Test wrong values
    // This test is considered innecessary and skiped

    // Test exceptions
    // This test is considered innecessary and skiped
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



