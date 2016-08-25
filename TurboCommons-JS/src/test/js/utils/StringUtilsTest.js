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

	assert.ok(utils.StringUtils.extractFileNameWithExtension(null) === '');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('') === '');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('       ') === '');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('//folder/folder2/folder3/file.txt') === 'file.txt');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('\\\\\\CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.extractFileNameWithExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.extractFileNameWithExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64.exe');
});


/**
 * extractFileNameWithoutExtension
 */
QUnit.test("extractFileNameWithoutExtension", function(assert){

	assert.ok(utils.StringUtils.extractFileNameWithoutExtension(null) === '');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('') === '');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('       ') === '');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('//folder/folder2/folder3/file.txt') === 'file');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('\\\\\\CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.extractFileNameWithoutExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64');
});


/**
 * extractFileExtension
 */
QUnit.test("extractFileExtension", function(assert){

	assert.ok(utils.StringUtils.extractFileExtension(null) === '');
	assert.ok(utils.StringUtils.extractFileExtension('') === '');
	assert.ok(utils.StringUtils.extractFileExtension('       ') === '');
	assert.ok(utils.StringUtils.extractFileExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.extractFileExtension('\\Files/CCleaner/CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.extractFileExtension('//folder/folder2/folder3/file.txt') === 'txt');
	assert.ok(utils.StringUtils.extractFileExtension('CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.extractFileExtension('\\\\\\CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.extractFileExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.extractFileExtension('CCleaner64.EXE') === 'EXE');
	assert.ok(utils.StringUtils.extractFileExtension('\\\\\\CCleaner64.eXEfile') === 'eXEfile');
	assert.ok(utils.StringUtils.extractFileExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'exe');
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


/**
 * formatForFullTextSearch
 */
QUnit.test("formatForFullTextSearch", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * generateRandomPassword
 */
QUnit.test("generateRandomPassword", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * removeAccents
 */
QUnit.test("removeAccents", function(assert){

	assert.ok(utils.StringUtils.removeAccents(null) === '');
	assert.ok(utils.StringUtils.removeAccents('') === '');
	assert.ok(utils.StringUtils.removeAccents('        ') === '        ');
	assert.ok(utils.StringUtils.removeAccents('Fó Bår') === 'Fo Bar');
	assert.ok(utils.StringUtils.removeAccents("|!€%'''") === "|!€%'''");
	assert.ok(utils.StringUtils.removeAccents('hiweury asb fsuyr weqr') === 'hiweury asb fsuyr weqr');
	assert.ok(utils.StringUtils.removeAccents('!iYgh65541tGY%$$73267yt') === '!iYgh65541tGY%$$73267yt');
	assert.ok(utils.StringUtils.removeAccents('hello 12786,.123123') === 'hello 12786,.123123');
	assert.ok(utils.StringUtils.removeAccents('check this `^+*´--_{}[]') === 'check this `^+*´--_{}[]');
	assert.ok(utils.StringUtils.removeAccents('hellóóóóóí´ 12786,.123123"') === 'helloooooi´ 12786,.123123"');
	assert.ok(utils.StringUtils.removeAccents("hello\nbaby\r\ntest it well !!!!!") === "hello\nbaby\r\ntest it well !!!!!");
	assert.ok(utils.StringUtils.removeAccents('óíéàùú hello') === 'oieauu hello');
	assert.ok(utils.StringUtils.removeAccents("óóó èèè\núùúùioler    \r\noughúíééanh hello") === "ooo eee\nuuuuioler    \r\noughuieeanh hello");
	assert.ok(utils.StringUtils.removeAccents('öïüíúóèà go!!.;') === 'oiuiuoea go!!.;');

});