"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("ModelHistoryManagerTest", {
    beforeEach : function(){

        window.sut = new org_turbocommons.ModelHistoryManager();
        window.ModelHistoryManager = org_turbocommons.ModelHistoryManager;
        
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function(){

        delete window.sut;
        delete window.ModelHistoryManager;

        delete window.ArrayUtils;
        delete window.ObjectUtils;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
    }
});


/**
 * constructor
 */
QUnit.test("constructor", function(assert){

    // Nothing to test
    assert.ok(true);
});


/**
 * setInitialState
 */
QUnit.test("setInitialState", function(assert){

    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        if(ObjectUtils.isObject(window.emptyValues[i]) && 
                ObjectUtils.isEqualTo(window.emptyValues[i], {})){
            
            sut.setInitialState(window.emptyValues[i]);
            
            assert.ok(ObjectUtils.isEqualTo(sut.get, {}));
                
        }else{
        
            assert.throws(function() {
                sut.setInitialState(window.emptyValues[i]);
            }, /Invalid instance value/);
        }
        
    }

    // Test ok values
    sut.setInitialState({a:1, b:2});
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 2;
    sut.get.b = 3;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:2, b:3}));

    sut.setInitialState({c:3, d:4});
    assert.ok(ObjectUtils.isEqualTo(sut.get, {c:3, d:4}));
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Tested by empty values
});


/**
 * get
 */
QUnit.test("get", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    sut.setInitialState({a:1, b:2});
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 3;
    sut.get.b = 4;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.saveSnapshot();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.get.a = 5;
    sut.get.b = 6;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:5, b:6}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    sut = new ModelHistoryManager();
    
    assert.throws(function() {
        sut.get;
    }, /Undefined initial state/);
});


/**
 * snapshots
 */
QUnit.test("snapshots", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    sut.setInitialState({a:1, b:2});
    
    sut.saveSnapshot();
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 3;
    assert.ok(sut.snapshots.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 1);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    
    sut.get.a = 4;
    assert.ok(sut.snapshots.length === 1);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    sut.saveSnapshot();
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 2);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    
    sut.get.b = 5;
    assert.ok(sut.snapshots.length === 2);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 3);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 3);
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    assert.ok(sut.snapshots.length === 1);
    
    sut.get.b = 6;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    sut.saveSnapshot();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    assert.ok(sut.snapshots.length === 2);
    
    sut.undoAll();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    assert.ok(sut.snapshots.length === 0);

    // Test wrong values
    // Not necessary

    // Test exceptions
    sut = new ModelHistoryManager();
    
    assert.ok(ArrayUtils.isEqualTo(sut.snapshots, []));
});


/**
 * getSnapshotsByTag
 */
QUnit.test("getSnapshotsByTag", function(assert){

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
 * saveSnapShot
 */
QUnit.test("saveSnapShot", function(assert){

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
 * isUndoPossible
 */
QUnit.test("isUndoPossible", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(!sut.isUndoPossible);
    
    sut.setInitialState({a:1, b:2});
    
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(!sut.isUndoPossible);

    sut.get.a = 3;
    assert.ok(sut.isUndoPossible);
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);

    sut.get.a = 4;
    assert.ok(sut.isUndoPossible);
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    assert.ok(sut.snapshots.length === 2);
    
    sut.undoAll();
    assert.ok(!sut.isUndoPossible);
    assert.ok(sut.snapshots.length === 0);
    
    sut.saveSnapshot();
    assert.ok(!sut.isUndoPossible);
    
    sut.get.b = 3;
    assert.ok(sut.isUndoPossible);
    
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    
    sut.undo();
    assert.ok(!sut.isUndoPossible);
    
    sut.get.b = 5;
    assert.ok(sut.isUndoPossible);
    
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * undo
 */
QUnit.test("undo", function(assert){

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
 * undoAll
 */
QUnit.test("undoAll", function(assert){

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
 * redo
 */
QUnit.todo("redo", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});