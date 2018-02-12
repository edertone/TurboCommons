"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("NumericUtilsTest", {
    beforeEach : function(){

        window.NumericUtils = org_turbocommons.NumericUtils;
    },

    afterEach : function(){

        delete window.NumericUtils;
    }
});


/**
 * isNumeric
 */
QUnit.test("isNumeric", function(assert){

    // Test empty values
    assert.notOk(NumericUtils.isNumeric(null));
    assert.notOk(NumericUtils.isNumeric(undefined));
    assert.notOk(NumericUtils.isNumeric(''));
    assert.notOk(NumericUtils.isNumeric([]));
    assert.notOk(NumericUtils.isNumeric({}));
    assert.ok(NumericUtils.isNumeric(0));
    assert.ok(NumericUtils.isNumeric(-0));

    // Test ok values
    assert.ok(NumericUtils.isNumeric(1));
    assert.ok(NumericUtils.isNumeric(-1));
    assert.ok(NumericUtils.isNumeric(.1));
    assert.ok(NumericUtils.isNumeric(-.1));
    assert.ok(NumericUtils.isNumeric(0.1));
    assert.ok(NumericUtils.isNumeric(-0.1));
    assert.ok(NumericUtils.isNumeric(1560));
    assert.ok(NumericUtils.isNumeric(-1560));
    assert.ok(NumericUtils.isNumeric(456.987));
    assert.ok(NumericUtils.isNumeric(-456.987));
    assert.ok(NumericUtils.isNumeric(0.00001));
    assert.ok(NumericUtils.isNumeric(-0.00001));
    assert.ok(NumericUtils.isNumeric(1560345346456));
    assert.ok(NumericUtils.isNumeric(-1560345346456));
    assert.ok(NumericUtils.isNumeric('1'));
    assert.ok(NumericUtils.isNumeric('-1'));
    assert.ok(NumericUtils.isNumeric('.1'));
    assert.ok(NumericUtils.isNumeric('-.1'));
    assert.ok(NumericUtils.isNumeric('0.1'));
    assert.ok(NumericUtils.isNumeric('-0.1'));
    assert.ok(NumericUtils.isNumeric('1560'));
    assert.ok(NumericUtils.isNumeric('-1560'));
    assert.ok(NumericUtils.isNumeric('456.987'));
    assert.ok(NumericUtils.isNumeric('-456.987'));
    assert.ok(NumericUtils.isNumeric('0.00001'));
    assert.ok(NumericUtils.isNumeric('-0.00001'));
    assert.ok(NumericUtils.isNumeric('1560345346456'));
    assert.ok(NumericUtils.isNumeric('-1560345346456'));
    assert.ok(NumericUtils.isNumeric(' 1'));
    assert.ok(NumericUtils.isNumeric('1 '));
    assert.ok(NumericUtils.isNumeric(' 1 '));
    assert.ok(NumericUtils.isNumeric('    1     '));
    assert.ok(NumericUtils.isNumeric("1     \n"));

    // Test wrong values
    assert.notOk(NumericUtils.isNumeric('abc'));
    assert.notOk(NumericUtils.isNumeric('1-'));
    assert.notOk(NumericUtils.isNumeric('1,1'));
    assert.notOk(NumericUtils.isNumeric(' '));
    assert.notOk(NumericUtils.isNumeric('!.1'));
    assert.notOk(NumericUtils.isNumeric([1, 2, 3]));
    assert.notOk(NumericUtils.isNumeric(['hello']));
    assert.notOk(NumericUtils.isNumeric(new Error()));
    assert.notOk(NumericUtils.isNumeric({
        a : 1
    }));
});


/**
 * isInteger
 */
QUnit.test("isInteger", function(assert){

    // Test empty values
    assert.notOk(NumericUtils.isInteger(undefined));
    assert.notOk(NumericUtils.isInteger(null));
    assert.notOk(NumericUtils.isInteger(''));
    assert.notOk(NumericUtils.isInteger([]));
    assert.notOk(NumericUtils.isInteger({}));
    assert.ok(NumericUtils.isInteger(0));
    assert.ok(NumericUtils.isInteger(-0));

    // Test ok values
    assert.ok(NumericUtils.isInteger(1));
    assert.ok(NumericUtils.isInteger(-1));
    assert.ok(NumericUtils.isInteger(1560));
    assert.ok(NumericUtils.isInteger(-1560));
    assert.ok(NumericUtils.isInteger(1560345346456));
    assert.ok(NumericUtils.isInteger(-1560345346456));
    assert.ok(NumericUtils.isInteger('1'));
    assert.ok(NumericUtils.isInteger('-1'));
    assert.ok(NumericUtils.isInteger('1560'));
    assert.ok(NumericUtils.isInteger('-1560'));
    assert.ok(NumericUtils.isInteger('1560345346456'));
    assert.ok(NumericUtils.isInteger('-1560345346456'));
    assert.ok(NumericUtils.isInteger('1560345346456356456246235456'));
    assert.ok(NumericUtils.isInteger('-15603453464564525123524565476546'));
    assert.ok(NumericUtils.isInteger(' 1'));
    assert.ok(NumericUtils.isInteger('1 '));
    assert.ok(NumericUtils.isInteger(' 1 '));
    assert.ok(NumericUtils.isInteger('    1     '));
    assert.ok(NumericUtils.isInteger("1     \n"));

    // Test wrong values
    assert.notOk(NumericUtils.isInteger('.1'));
    assert.notOk(NumericUtils.isInteger('-.1'));
    assert.notOk(NumericUtils.isInteger('0.1'));
    assert.notOk(NumericUtils.isInteger('-0.1'));
    assert.notOk(NumericUtils.isInteger('456.987'));
    assert.notOk(NumericUtils.isInteger('-456.987'));
    assert.notOk(NumericUtils.isInteger('0.00001'));
    assert.notOk(NumericUtils.isInteger('-0.00001'));
    assert.notOk(NumericUtils.isInteger('abc'));
    assert.notOk(NumericUtils.isInteger('1-'));
    assert.notOk(NumericUtils.isInteger('1,1'));
    assert.notOk(NumericUtils.isInteger(' '));
    assert.notOk(NumericUtils.isInteger('!.1'));
    assert.notOk(NumericUtils.isInteger([1, 2, 3]));
    assert.notOk(NumericUtils.isInteger(['hello']));
    assert.notOk(NumericUtils.isInteger(new Error()));
    assert.notOk(NumericUtils.isInteger({
        a : 1
    }));
});


/**
 * getNumeric
 */
QUnit.test("getNumeric", function(assert){

    // Test empty values
    assert.ok(NumericUtils.getNumeric(0) == 0);
    assert.ok(NumericUtils.getNumeric('0') == 0);

    assert.throws(function() {

        NumericUtils.getNumeric(undefined);
    });
    
    assert.throws(function() {

        NumericUtils.getNumeric(null);
    });
    
    assert.throws(function() {

        NumericUtils.getNumeric('');
    });
    
    assert.throws(function() {

        NumericUtils.getNumeric([]);
    });

    // Test ok values
    assert.ok(NumericUtils.getNumeric(1) == 1);
    assert.ok(NumericUtils.getNumeric(10) == 10);
    assert.ok(NumericUtils.getNumeric(1123134) == 1123134);
    assert.ok(NumericUtils.getNumeric(1.1) == 1.1);
    assert.ok(NumericUtils.getNumeric(.1) == .1);
    assert.ok(NumericUtils.getNumeric(0.00001) == 0.00001);
    assert.ok(NumericUtils.getNumeric(1.000001) == 1.000001);
    assert.ok(NumericUtils.getNumeric(-1) == -1);
    assert.ok(NumericUtils.getNumeric(-10) == -10);
    assert.ok(NumericUtils.getNumeric(-1123134) == -1123134);
    assert.ok(NumericUtils.getNumeric(-1.1) == -1.1);
    assert.ok(NumericUtils.getNumeric(-.1) == -.1);
    assert.ok(NumericUtils.getNumeric(-0.00001) == -0.00001);
    assert.ok(NumericUtils.getNumeric(-1.000001) == -1.000001);
    assert.ok(NumericUtils.getNumeric('1') == 1);
    assert.ok(NumericUtils.getNumeric('10') == 10);
    assert.ok(NumericUtils.getNumeric('1123134') == 1123134);
    assert.ok(NumericUtils.getNumeric('1.1') == 1.1);
    assert.ok(NumericUtils.getNumeric('.1') == .1);
    assert.ok(NumericUtils.getNumeric('0.00001') == 0.00001);
    assert.ok(NumericUtils.getNumeric('1.000001') == 1.000001);
    assert.ok(NumericUtils.getNumeric('-1') == -1);
    assert.ok(NumericUtils.getNumeric('-10') == -10);
    assert.ok(NumericUtils.getNumeric('-1123134') == -1123134);
    assert.ok(NumericUtils.getNumeric('-1.1') == -1.1);
    assert.ok(NumericUtils.getNumeric('-.1') == -.1);
    assert.ok(NumericUtils.getNumeric('-0.00001') == -0.00001);
    assert.ok(NumericUtils.getNumeric('-1.000001') == -1.000001);
    assert.ok(NumericUtils.getNumeric('  1 ') == 1);
    assert.ok(NumericUtils.getNumeric('  .1 ') == 0.1);
    assert.ok(NumericUtils.getNumeric('  -1 ') == -1);

    // Test wrong values
    assert.throws(function() {

        NumericUtils.getNumeric('abc');
    });

    assert.throws(function() {

        NumericUtils.getNumeric('1-');
    });
    
    assert.throws(function() {

        NumericUtils.getNumeric('1,1');
    });
    
    assert.throws(function() {

        NumericUtils.getNumeric('hello');
    });
});


/**
 * generateRandomInteger
 */
QUnit.test("generateRandomInteger", function(assert){

    // Test ok values
    for (var i = 0; i < 1000; i+=100) {

        var min = i;
        var max = i * 2 + 1;

        var val = NumericUtils.generateRandomInteger(max, min);
        assert.ok(val >= min && val <= max);
        assert.ok(NumericUtils.isInteger(val));

        min = NumericUtils.generateRandomInteger(max, min);
        max = min + NumericUtils.generateRandomInteger(i * 10 + 2, i + 1);

        val = NumericUtils.generateRandomInteger(max, min);
        assert.ok(val >= min && val <= max);
        assert.ok(NumericUtils.isInteger(val));
    }

    // Test exceptions
    var exceptionValues = [null, '', [], new Error(), 'hello', -1, .1, 1.1, [1, 2, 3, 4]];

    for (i = 0; i < exceptionValues.length; i++) {

        for (var j = 0; j < exceptionValues.length; j++) {

            assert.throws(function() {

                NumericUtils.getNumeric(NumericUtils.generateRandomInteger(exceptionValues[i], exceptionValues[j]));
            });
        }
    }
});