"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("NumericUtilsTest", {
    beforeEach : function(){

        window.NumericUtils = org_turbocommons.NumericUtils;
        
        window.emptyValues = [undefined, null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function(){

        delete window.NumericUtils;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
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
    
    var objectThatMustNotBeAltered = {value: " 15  "};    
    assert.ok(NumericUtils.isNumeric(objectThatMustNotBeAltered.value));
    assert.strictEqual(objectThatMustNotBeAltered.value, " 15  ");
    
    // Test wrong values
    assert.notOk(NumericUtils.isNumeric('abc'));
    assert.notOk(NumericUtils.isNumeric('1-'));
    assert.notOk(NumericUtils.isNumeric('1,1'));
    assert.notOk(NumericUtils.isNumeric(' '));
    assert.notOk(NumericUtils.isNumeric('!.1'));
    assert.notOk(NumericUtils.isNumeric([1, 2, 3]));
    assert.notOk(NumericUtils.isNumeric(['hello']));
    assert.notOk(NumericUtils.isNumeric(new Error()));
    assert.notOk(NumericUtils.isNumeric({ a : 1 }));
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
    assert.notOk(NumericUtils.isInteger({ a : 1 }));
});


/** test */
QUnit.test("forceNumeric", function(assert){

    // Test empty values
    NumericUtils.forceNumeric(0);
    assert.throws(function() { NumericUtils.forceNumeric(null, 'somenull'); }, '/somenull must be numeric/');
    assert.throws(function() { NumericUtils.forceNumeric([], 'somearray'); }, '/somearray must be numeric/');
    assert.throws(function() { NumericUtils.forceNumeric('', 'somestring', 'some error message'); }, '/somestring some error message/');

    // Test ok values
    NumericUtils.forceNumeric(12123);
    NumericUtils.forceNumeric(-123123);
    NumericUtils.forceNumeric('123123');
    NumericUtils.forceNumeric('123.11');
    NumericUtils.forceNumeric('-123123');

    // Test wrong values
    // Test exceptions
    assert.throws(function() { NumericUtils.forceNumeric('asdf'); }, '/must be numeric/');
    assert.throws(function() { NumericUtils.forceNumeric([1,2,3,4]); }, '/must be numeric/');
    assert.throws(function() { NumericUtils.forceNumeric(new stdClass()); }, '/must be numeric/');
});


/** test */
QUnit.test("forcePositiveInteger", function(assert){

    // Test empty values
    assert.throws(function() { NumericUtils.forcePositiveInteger(0); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(null, 'somenull'); }, '/somenull must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger([], 'somearray'); }, '/somearray must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger('', 'somestring', 'some error message'); }, '/somestring some error message/');

    // Test ok values
    NumericUtils.forcePositiveInteger(1);
    NumericUtils.forcePositiveInteger(10);
    NumericUtils.forcePositiveInteger(1000);
    NumericUtils.forcePositiveInteger(12341234);
    NumericUtils.forcePositiveInteger(13453452345);
    NumericUtils.forcePositiveInteger('1');
    NumericUtils.forcePositiveInteger('123');

    // Test wrong values
    // Test exceptions
    assert.throws(function() { NumericUtils.forcePositiveInteger([1,2,3,4]); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(new stdClass()); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger('erterwt'); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(-100); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(-10000); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(10.56); }, '/must be a positive integer/');
    assert.throws(function() { NumericUtils.forcePositiveInteger(-10.56); }, '/must be a positive integer/');
});


/**
 * getNumeric
 */
QUnit.test("getNumeric", function(assert){

    // Test empty values
    assert.strictEqual(NumericUtils.getNumeric(0), 0);
    assert.strictEqual(NumericUtils.getNumeric('0'), 0);

    assert.throws(function() { NumericUtils.getNumeric(undefined); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric(null); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric(''); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric([]); }, '/value is not numeric/');

    // Test ok values
    assert.strictEqual(NumericUtils.getNumeric(1), 1);
    assert.strictEqual(NumericUtils.getNumeric(10), 10);
    assert.strictEqual(NumericUtils.getNumeric(1123134), 1123134);
    assert.strictEqual(NumericUtils.getNumeric(1.1), 1.1);
    assert.strictEqual(NumericUtils.getNumeric(.1), .1);
    assert.strictEqual(NumericUtils.getNumeric(0.00001), 0.00001);
    assert.strictEqual(NumericUtils.getNumeric(1.000001), 1.000001);
    assert.strictEqual(NumericUtils.getNumeric(-1), -1);
    assert.strictEqual(NumericUtils.getNumeric(-10), -10);
    assert.strictEqual(NumericUtils.getNumeric(-1123134), -1123134);
    assert.strictEqual(NumericUtils.getNumeric(-1.1), -1.1);
    assert.strictEqual(NumericUtils.getNumeric(-.1), -.1);
    assert.strictEqual(NumericUtils.getNumeric(-0.00001), -0.00001);
    assert.strictEqual(NumericUtils.getNumeric(-1.000001), -1.000001);
    assert.strictEqual(NumericUtils.getNumeric('1'), 1);
    assert.strictEqual(NumericUtils.getNumeric('10'), 10);
    assert.strictEqual(NumericUtils.getNumeric('1123134'), 1123134);
    assert.strictEqual(NumericUtils.getNumeric('1.1'), 1.1);
    assert.strictEqual(NumericUtils.getNumeric('.1'), .1);
    assert.strictEqual(NumericUtils.getNumeric('0.00001'), 0.00001);
    assert.strictEqual(NumericUtils.getNumeric('1.000001'), 1.000001);
    assert.strictEqual(NumericUtils.getNumeric('-1'), -1);
    assert.strictEqual(NumericUtils.getNumeric('-10'), -10);
    assert.strictEqual(NumericUtils.getNumeric('-1123134'), -1123134);
    assert.strictEqual(NumericUtils.getNumeric('-1.1'), -1.1);
    assert.strictEqual(NumericUtils.getNumeric('-.1'), -.1);
    assert.strictEqual(NumericUtils.getNumeric('-0.00001'), -0.00001);
    assert.strictEqual(NumericUtils.getNumeric('-1.000001'), -1.000001);
    assert.strictEqual(NumericUtils.getNumeric('  1 '), 1);
    assert.strictEqual(NumericUtils.getNumeric('  .1 '), 0.1);
    assert.strictEqual(NumericUtils.getNumeric('  -1 '), -1);

    // Test wrong values
    assert.throws(function() { NumericUtils.getNumeric('abc'); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric('1-'); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric('1,1'); }, '/value is not numeric/');
    assert.throws(function() { NumericUtils.getNumeric('hello'); }, '/value is not numeric/');
});


/**
 * generateRandomInteger
 */
QUnit.test("generateRandomInteger", function(assert){

    // Test empty values
    assert.throws(function() { NumericUtils.generateRandomInteger(0, 0); }, /max must be higher than min/);
    
    for(var i = 0; i < emptyValuesCount; i++){
        
        for(var j = 0; j < emptyValuesCount; j++){
        
            if(emptyValues[i] !== 0 || emptyValues[j] !== 0){
            
                assert.throws(function() {                    
                    NumericUtils.generateRandomInteger(emptyValues[i], emptyValues[j]);
                }, /max and min must be integers/);
            }            
        }
    }

    // Test ok values
    for (var i = 0; i < 1000; i+=100) {

        // Both positive
        var min = i;
        var max = i * 2 + 1;

        var val = NumericUtils.generateRandomInteger(min, max);
        assert.ok(val >= min && val <= max);
        assert.ok(NumericUtils.isInteger(val));

        // Both negative
        min = - NumericUtils.generateRandomInteger(min, max);
        max = min + NumericUtils.generateRandomInteger(i + 1, i * 10 + 2);

        val = NumericUtils.generateRandomInteger(min, max);
        assert.ok(val >= min && val <= max);
        assert.ok(NumericUtils.isInteger(val));
        
        // Negative min, positive max
        var min = -i - 1;
        var max = i * 2 + 1;

        var val = NumericUtils.generateRandomInteger(min, max);
        assert.ok(val >= min && val <= max);
        assert.ok(NumericUtils.isInteger(val));
    }

    // Test wrong values
    assert.throws(function() { NumericUtils.generateRandomInteger(10, 0); }, /max must be higher than min/);
    assert.throws(function() { NumericUtils.generateRandomInteger(10, 10); }, /max must be higher than min/);
    assert.throws(function() { NumericUtils.generateRandomInteger(-10, -20); }, /max must be higher than min/);

    // Test exceptions
    var exceptionValues = [new Error(), 'hello', .1, 1.1, [1, 2, 3, 4]];

    for (i = 0; i < exceptionValues.length; i++) {

        for (var j = 0; j < exceptionValues.length; j++) {

            assert.throws(function() {
                NumericUtils.getNumeric(NumericUtils.generateRandomInteger(exceptionValues[i], exceptionValues[j]));
            }, /max and min must be integers/);
        }
    }    
});