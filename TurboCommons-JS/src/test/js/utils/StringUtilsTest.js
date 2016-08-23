"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

// Import namespaces
var utils = org_turbocommons_src_main_js_utils;


QUnit.module("StringUtilsTest");

/**
 * isEmpty
 */
QUnit.test("isEmpty", function(assert){

	assert.ok(utils.StringUtils.isEmpty(null));
	assert.ok(utils.StringUtils.isEmpty(''));
	assert.ok(utils.StringUtils.isEmpty('      '));
	assert.ok(utils.StringUtils.isEmpty("\n\n  \n"));
	assert.ok(utils.StringUtils.isEmpty("\t   \n     \r\r"));
	assert.ok(!utils.StringUtils.isEmpty('adsadf'));
	assert.ok(!utils.StringUtils.isEmpty('    sdfasdsf'));
	assert.ok(!utils.StringUtils.isEmpty('EMPTY'));
	assert.ok(!utils.StringUtils.isEmpty('EMPTY test', ['EMPTY']));
	assert.ok(utils.StringUtils.isEmpty('EMPTY', ['EMPTY']));
});


/**
 * countWords
 */
QUnit.test("countWords", function(assert){

	assert.ok(utils.StringUtils.countWords(null) == 0);
	assert.ok(utils.StringUtils.countWords('') == 0);
	assert.ok(utils.StringUtils.countWords('  ') == 0);
	assert.ok(utils.StringUtils.countWords('       ') == 0);
	assert.ok(utils.StringUtils.countWords('hello') == 1);
	assert.ok(utils.StringUtils.countWords('hello baby') == 2);
	assert.ok(utils.StringUtils.countWords("try\nto\r\n\t\ngo\r\nup") == 4);
	assert.ok(utils.StringUtils.countWords("     \n      \r\n") == 0);
	assert.ok(utils.StringUtils.countWords("     \n   1   \r\n") == 1);
	assert.ok(utils.StringUtils.countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
	assert.ok(utils.StringUtils.countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
});


/**
 * limitLen
 */
QUnit.test("limitLen", function(assert){

	assert.ok(utils.StringUtils.limitLen(null, 0) === '');
	assert.ok(utils.StringUtils.limitLen(null, 10) === '');
	assert.ok(utils.StringUtils.limitLen('', 0) === '');
	assert.ok(utils.StringUtils.limitLen('', 10) === '');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 1) === ' ');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 2) === ' .');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 3) === ' ..');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 4) === ' ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 5) === 'h ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 18) === 'hello dear how ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 19) === 'hello dear how  ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 20) === 'hello dear how a ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 22) === 'hello dear how are you');
	assert.ok(utils.StringUtils.limitLen('hello dear how are you', 50) === 'hello dear how are you');

	// Test non numeric limit value gives exception
	assert.throws(function(){

		assert.ok(utils.StringUtils.limitLen('hello', null) === '');
	});
});


/**
 * extractLines
 */
QUnit.test("extractLines", function(assert){

	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines(null), []));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines(''), []));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines('          '), []));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines('single line'), ['single line']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\nline2\nline3"), ['line1', 'line2', 'line3']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\n        \nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\n\n\n\t\r       \nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\r\n   \r\nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\n 1  \nline2"), ['line1', ' 1  ', 'line2']));

	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines('          ', []), ['          ']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\n   \nline2", []), ['line1', '   ', 'line2']));
	assert.ok(utils.ArrayUtils.isEqual(utils.StringUtils.extractLines("line1\r\n   \r\nline2", []), ['line1', '   ', 'line2']));
});


/**
 * extractKeyWords
 */
QUnit.test("extractKeyWords", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * extractFileNameWithExtension
 */
QUnit.test("extractFileNameWithExtension", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * extractFileNameWithoutExtension
 */
QUnit.test("extractFileNameWithoutExtension", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * extractFileExtension
 */
QUnit.test("extractFileExtension", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * formatPath
 */
QUnit.test("formatPath", function(assert){

	var osSeparator = utils.FileSystemUtils.getDirectorySeparator();

	assert.ok(utils.StringUtils.formatPath(null) === '');
	assert.ok(utils.StringUtils.formatPath('') === '');
	assert.ok(utils.StringUtils.formatPath('       ') === '       ');
	assert.ok(utils.StringUtils.formatPath('test//test/') === 'test' + osSeparator + 'test');
	assert.ok(utils.StringUtils.formatPath('////test//////test////') === osSeparator + 'test' + osSeparator + 'test');
	assert.ok(utils.StringUtils.formatPath('\\\\////test//test/') === osSeparator + 'test' + osSeparator + 'test');
	assert.ok(utils.StringUtils.formatPath('test\\test/hello\\\\') === 'test' + osSeparator + 'test' + osSeparator + 'hello');

	// Test non string paths throw exception
	assert.throws(function(){

		utils.StringUtils.formatPath(['1']);
	});
});