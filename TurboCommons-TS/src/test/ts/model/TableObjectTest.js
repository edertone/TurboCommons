"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("TableObjectTest", {
    beforeEach : function(){

        window.NumericUtils = org_turbocommons.NumericUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.TableObject = org_turbocommons.TableObject;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n"];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function(){

        delete window.NumericUtils;
        delete window.TableObject;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new TableObject();
    assert.ok(test.countCells() === 0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);

    var test = new TableObject(0, 0);
    assert.ok(test.countCells() === 0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < window.emptyValuesCount; j++) {

            assert.throws(function() {
                new TableObject(window.emptyValues[i], window.emptyValues[j]);
            });
        }
    }

    // Test ok values
    for (var i = 1; i < 5000; i+=100) {

        for (var j = 1; j < 5000; j+=100) {

            var test = new TableObject(i, j);
            assert.ok(i * j === test.countCells());
            assert.ok(i === test.countRows());
            assert.ok(j === test.countColumns());
        }
    }

    var test = new TableObject(2, ['c1', 'c2', 'c3']);
    assert.ok(test.countCells() === 6);
    assert.ok(test.countRows() === 2);
    assert.ok(test.countColumns() === 3);

    // Test wrong values
    assert.throws(function() {
        new TableObject(0, NumericUtils.generateRandomInteger(10000000));
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000), 0);
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000) * -1, NumericUtils.generateRandomInteger(10000000) * -1);
    });

    assert.throws(function() {
        new TableObject('hello', 'hello');
    });

    assert.throws(function() {
        new TableObject([], []);
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000) * -1, NumericUtils.generateRandomInteger(10000000));
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000), NumericUtils.generateRandomInteger(10000000) * -1);
    });

    // Test exceptions
    // Tested with wrong values
});


/**
 * testSetColumnName
 */
QUnit.test("testSetColumnName", function(assert){
    
    // Test empty values
    var test = new TableObject(10, 10);

    assert.ok(test.setColumnName(0, ''));
    assert.ok(test.setColumnName(0, '    '));
    assert.ok(test.setColumnName(0, "\n\n\n"));

    window.emptyValues = [null, [], {}];
    window.emptyValuesCount = window.emptyValues.length;

    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < 10; j++) {

            assert.throws(function() {
                test.setColumnName(j, window.emptyValues[i]);
            });
        }
    }

    // Test ok values
    var test = new TableObject(1, 1);
    assert.ok(test.setColumnName(0, 'column1'));
    assert.ok(ArrayUtils.isEqualTo([null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), test.getColumn('column1')));
    assert.ok(test.getColumnName(0) === 'column1');
    assert.ok(test.getColumnIndex('column1') === 0);

    var test = new TableObject(20, 20);
    assert.ok(test.setColumnName(11, 'column11'));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(11), test.getColumn('column11')));
    assert.ok(test.getColumnName(11) === 'column11');
    assert.ok(test.getColumnIndex('column11') === 11);

    assert.ok(test.setColumnName(12, 'column12'));
    assert.ok(test.setColumnName(11, 'renamed'));
    assert.ok(test.getColumnName(12) === 'column12');
    assert.ok(test.getColumnName(11) === 'renamed');

    assert.ok(test.setColumnName('renamed', 're-renamed'));
    assert.ok(test.getColumnName(11) === 're-renamed');

    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(19) === '');
    assert.ok(test.setColumnName(11, ''));
    assert.ok(test.setColumnName(12, '   '));
    assert.ok(test.setColumnName(13, "\n\n"));
    assert.ok(test.getColumnName(11) === '');
    assert.ok(test.getColumnName(12) === '   ');
    assert.ok(test.getColumnName(13) === "\n\n");

    // Test wrong values
    window.wrongValues = [null, [], {}, -1, 50];
    window.wrongValuesCount = window.wrongValues.length;

    for (i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.setColumnName(window.wrongValues[i], 'name');
        });
    }

    for (i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.setColumnName(1, window.wrongValues[i]);
        });
    }

    assert.throws(function() {
        test.setColumnName(40, 'name');
    });

    // Test exceptions
    // Already tested
});