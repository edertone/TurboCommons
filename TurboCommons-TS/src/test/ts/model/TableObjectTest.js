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


/**
 * testSetColumnNames
 */
QUnit.test("testSetColumnNames", function(assert){
    
    // Test empty values
    var test = new TableObject(10, 10);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.setColumnNames(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(5, 3);
    assert.ok(ArrayUtils.isEqualTo(['', '  ', "\n\n\n"], test.setColumnNames(['', '  ', "\n\n\n"])));
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '  ', "\n\n\n"]));

    for (var i = 1; i < 50; i+=10) {

        for (var j = 1; j < 50; j+=10) {

            var test = new TableObject(i, j);

            var columns = [];

            for (var k = 0; k < j; k++) {

                columns.push( 'column' + k);
            }

            assert.ok(ArrayUtils.isEqualTo(columns, test.setColumnNames(columns)));
            assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), columns));
        }
    }

    // Test wrong values
    var test = new TableObject(0, 0);

    assert.throws(function() {
        test.setColumnNames(['column1']);
    });

    var test = new TableObject(2, 2);

    assert.throws(function() {
        test.setColumnNames(['column', 'column']);
    });

    assert.throws(function() {
        test.setColumnNames(['column', 1]);
    });

    // Test setting wrong number of column names
    for (var i = 1; i < 10; i++) {

        for (var j = 1; j < 10; j++) {

            var test = new TableObject(i, j);

            var columns = [];

            for (var k = 0; k < (j * 2); k++) {

                columns.push('column' + k);
            }

            assert.throws(function() {
                test.setColumnNames(columns);
            });
        }
    }

    // Test exceptions
    // Tested with wrong values    
});


/**
 * testGetColumnNames
 */
QUnit.test("testGetColumnNames", function(assert){

    // Test empty values
    test = new TableObject();
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), []));

    // Test ok values
    test = new TableObject(1, 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['']));
    test.setColumnNames(['column1']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1']));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnNames(['column1', 'column2', 'column3', 'column4']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1', 'column2', 'column3', 'column4']));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(0, 'col0');
    assert.ok(ArrayUtils.isEqualTo(['col0', '', '', ''], test.getColumnNames()));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(2, 'col2');
    assert.ok(ArrayUtils.isEqualTo(['', '', 'col2', ''], test.getColumnNames()));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(3, 'col3');
    assert.ok(ArrayUtils.isEqualTo(['', '', '', 'col3'], test.getColumnNames()));

    test = new TableObject(1, 8);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '', '', '', '', '']));
    test.setColumnNames(['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']));

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Already tested at constructor test
});


/**
 * testGetColumnName
 */
QUnit.test("testGetColumnName", function(assert){

    // Test empty values
    test = new TableObject();

    assert.throws(function() {
        test.getColumnName(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumnName(window.emptyValues[i]);
        });
    }

    // Test ok values
    test = new TableObject(10, 10);
    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(5) === '');
    assert.ok(test.getColumnName(7) === '');

    assert.ok(test.setColumnName(1, ''));
    assert.ok(test.setColumnName(2, '   '));
    assert.ok(test.setColumnName(3, "\n\n"));
    assert.ok(test.setColumnName(4, 'name 1'));
    assert.ok(test.setColumnName(5, '  name 2'));

    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(2) === '   ');
    assert.ok(test.getColumnName(3) === "\n\n");
    assert.ok(test.getColumnName(4) === 'name 1');
    assert.ok(test.getColumnName(5) === '  name 2');

    assert.ok(test.setColumnName(1, 'newName'));
    assert.ok(test.setColumnName('   ', 'noEmpty'));
    assert.ok(test.setColumnName(3, 'newName2'));
    assert.ok(test.setColumnName('name 1', 'newName 4'));
    assert.ok(test.setColumnName(5, 'newName3'));

    assert.ok(test.getColumnName(1) === 'newName');
    assert.ok(test.getColumnName(2) === 'noEmpty');
    assert.ok(test.getColumnName(3) === 'newName2');
    assert.ok(test.getColumnName(4) === 'newName 4');
    assert.ok(test.getColumnName(5) === 'newName3');

    // Test wrong values
    assert.throws(function() {
        test.getColumnName(-1);
    });

    assert.throws(function() {
        test.getColumnName(111);
    });

    assert.throws(function() {
        test.getColumnName('nonexistantkey');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetColumnIndex
 */
QUnit.test("testGetColumnIndex", function(assert){
    
    // Test empty values
    var test = new TableObject();

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumnIndex(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(10, 10);

    assert.ok(test.setColumnName(2, '   '));
    assert.ok(test.setColumnName(3, "\n\n"));
    assert.ok(test.setColumnName(4, 'name 1'));
    assert.ok(test.setColumnName(5, '  name 2'));

    assert.ok(test.getColumnIndex('   ') === 2);
    assert.ok(test.getColumnIndex("\n\n") === 3);
    assert.ok(test.getColumnIndex('name 1') === 4);
    assert.ok(test.getColumnIndex('  name 2') === 5);

    // Test wrong values
    assert.throws(function() {
        test.getColumnIndex(123);
    });

    assert.throws(function() {
        test.getColumnIndex('non existant key');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetColumn
 */
QUnit.test("testGetColumn", function(assert){
    
 // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.getColumn(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumn(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null]));

    var test = new TableObject(5, 5);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null, null, null, null, null]));
    test.setColumn(2, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(2), [1, 2, 3, 4, 5]));
    test.setColumn(4, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(3), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(4), [1, 2, 3, 4, 5]));

    assert.ok(test.setColumnName(2, 'column2'));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn('column2'), [1, 2, 3, 4, 5]));

    // Test wrong values
    assert.throws(function() {
        test.getColumn(-1);
    });

    assert.throws(function() {
        test.getColumn(11);
    });

    assert.throws(function() {
        test.getColumn('non existant');
    });

    // Test exceptions
    // Already tested
});


/**
 * testAddColumns
 */
QUnit.test("testAddColumns", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.addColumns(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.addColumns(window.emptyValues[i]);
        });

        assert.throws(function() {
            test.addColumns(1, ['col'], window.emptyValues[i]);
        });

        if(!ArrayUtils.isArray(window.emptyValues[i])){

            assert.throws(function() {
                test.addColumns(1, window.emptyValues[i]);
            });
        }
    }

    // Test ok values
    var test = new TableObject();
    assert.ok(test.addColumns(1));
    assert.ok(test.countColumns() === 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['']));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), []));

    test = new TableObject(4, 4);
    assert.ok(test.addColumns(2, ['col1', 'col2']));
    assert.ok(test.countColumns() === 6);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', '', 'col1', 'col2'], test.getColumnNames()));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(3), [null, null, null, null]));

    assert.ok(test.addColumns(1, ['col3']));
    assert.ok(test.countColumns() === 7);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    assert.ok(test.addColumns(1, ['col4'], 2));
    assert.ok(test.countColumns() === 8);
    assert.ok(ArrayUtils.isEqualTo(['', '', 'col4', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    assert.ok(test.addColumns(1, [], 0));
    assert.ok(test.countColumns() === 9);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', 'col4', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    var test = new TableObject(3, 1);
    test.setColumn(0, [1, 2, 3]);
    assert.ok(1 === test.countColumns());

    assert.ok(test.addColumns(1, [], 0));
    assert.ok(2 === test.countColumns());
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(1)));
    assert.ok(test.addColumns(1, [], 0));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(2)));
    assert.ok(3, test.countColumns());

    assert.ok(test.addColumns(1, ['col'], 2));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(3)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn('col')));
    assert.ok(test.countColumns() === 4);

    var test = new TableObject();
    test.addRows(3);
    test.addColumns(3, ['c0', 'c1', 'c2']);
    test.setColumn(0, [1, 2, 3]);
    test.setColumn(1, [4, 5, 6]);
    test.setColumn(2, [7, 8, 9]);
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 3);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn('c0')));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn('c1')));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn('c2')));
    test.addColumns(1, ['letters'], 2);
    test.setColumn(2, ['x', 'y', 'z']);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn('c0')));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn('c1')));
    assert.ok(ArrayUtils.isEqualTo(['x', 'y', 'z'], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo(['x', 'y', 'z'], test.getColumn('letters')));
    assert.ok(test.getColumnIndex('letters') === 2);
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn(3)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn('c2')));
    assert.ok(test.getColumnIndex('c2') === 3);
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 4);

    // Test wrong values
    var test = new TableObject(3, 3);

    window.wrongValues = [0, -1, 1.1, [1, 2, 3, 4]];
    window.wrongValuesCount = window.wrongValues.length;

    for (var i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.addColumns(window.wrongValues[i]);
        });
    }

    assert.throws(function() {
        test.addColumns(1, [], 4);
    });

    assert.throws(function() {
        test.addColumns(1, ['a', 'b']);
    });

    // Test exceptions
    // Already tested
});

// TODO - add missing tests