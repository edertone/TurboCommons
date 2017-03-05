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

	assert.ok(utils.StringUtils.isEmpty(undefined));
	assert.ok(utils.StringUtils.isEmpty(null));
	assert.ok(utils.StringUtils.isEmpty(''));
	assert.ok(utils.StringUtils.isEmpty([]));
	assert.ok(utils.StringUtils.isEmpty('      '));
	assert.ok(utils.StringUtils.isEmpty("\n\n  \n"));
	assert.ok(utils.StringUtils.isEmpty("\t   \n     \r\r"));
	assert.ok(utils.StringUtils.isEmpty('EMPTY', ['EMPTY']));
	assert.ok(utils.StringUtils.isEmpty('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

	assert.ok(!utils.StringUtils.isEmpty('adsadf'));
	assert.ok(!utils.StringUtils.isEmpty('    sdfasdsf'));
	assert.ok(!utils.StringUtils.isEmpty('EMPTY'));
	assert.ok(!utils.StringUtils.isEmpty('EMPTY test', ['EMPTY']));
	assert.ok(!utils.StringUtils.isEmpty('EMPTY       void   hole    XX', ['EMPTY', 'void', 'hole']));

	// Test non string value gives exception
	assert.throws(function(){

		utils.StringUtils.isEmpty(123);
	});
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
 * getDomainFromUrl
 */
QUnit.test("getDomainFromUrl", function(assert){

	assert.ok(utils.StringUtils.getDomainFromUrl(null) === '');
	assert.ok(utils.StringUtils.getDomainFromUrl(undefined) === '');
	assert.ok(utils.StringUtils.getDomainFromUrl('') === '');
	assert.ok(utils.StringUtils.getDomainFromUrl("aa....aa..") === '');
	assert.ok(utils.StringUtils.getDomainFromUrl('google.com') === '');
	assert.ok(utils.StringUtils.getDomainFromUrl('http://google.com') === 'google.com');
	assert.ok(utils.StringUtils.getDomainFromUrl('http://www.google.com') === 'google.com');
	assert.ok(utils.StringUtils.getDomainFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'youtube.com');
	assert.ok(utils.StringUtils.getDomainFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'chrome.com');
	assert.ok(utils.StringUtils.getDomainFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'abc.es');
	assert.ok(utils.StringUtils.getDomainFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
	assert.ok(utils.StringUtils.getDomainFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
	assert.ok(utils.StringUtils.getDomainFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
	assert.ok(utils.StringUtils.getDomainFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'funet.fi');
	assert.ok(utils.StringUtils.getDomainFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'xyz.com');
	assert.ok(utils.StringUtils.getDomainFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'xyz.com');
});


/**
 * getHostNameFromUrl
 */
QUnit.test("getHostNameFromUrl", function(assert){

	assert.ok(utils.StringUtils.getHostNameFromUrl(null) === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl(undefined) === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl("aa....aa..") === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('google.com') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('http://x.ye') === 'x.ye');
	assert.ok(utils.StringUtils.getHostNameFromUrl('http://google.com') === 'google.com');
	assert.ok(utils.StringUtils.getHostNameFromUrl('http://www.google.com') === 'www.google.com');
	assert.ok(utils.StringUtils.getHostNameFromUrl('|%$)"·/%') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('http://|%$)"·/%') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'www.youtube.com');
	assert.ok(utils.StringUtils.getHostNameFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'developer.chrome.com');
	assert.ok(utils.StringUtils.getHostNameFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'www.abc.es');
	assert.ok(utils.StringUtils.getHostNameFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
	assert.ok(utils.StringUtils.getHostNameFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'ftp.funet.fi');
	assert.ok(utils.StringUtils.getHostNameFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
	assert.ok(utils.StringUtils.getHostNameFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
});


/**
 * getLines
 */
QUnit.test("getLines", function(assert){

	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines(null), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines(''), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines('          '), []));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines('single line'), ['single line']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\nline2\nline3"), ['line1', 'line2', 'line3']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\n        \nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\n\n\n\t\r       \nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\r\n   \r\nline2"), ['line1', 'line2']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\n 1  \nline2"), ['line1', ' 1  ', 'line2']));

	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines('          ', []), ['          ']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\n   \nline2", []), ['line1', '   ', 'line2']));
	assert.ok(utils.ArrayUtils.isEqualTo(utils.StringUtils.getLines("line1\r\n   \r\nline2", []), ['line1', '   ', 'line2']));
});


/**
 * getKeyWords
 */
QUnit.test("getKeyWords", function(assert){

	// TODO: copy tests from PHP
	assert.ok(true);
});


/**
 * getFileNameWithExtension
 */
QUnit.test("getFileNameWithExtension", function(assert){

	assert.ok(utils.StringUtils.getFileNameWithExtension(null) === '');
	assert.ok(utils.StringUtils.getFileNameWithExtension('') === '');
	assert.ok(utils.StringUtils.getFileNameWithExtension('       ') === '');
	assert.ok(utils.StringUtils.getFileNameWithExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.getFileNameWithExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.getFileNameWithExtension('//folder/folder2/folder3/file.txt') === 'file.txt');
	assert.ok(utils.StringUtils.getFileNameWithExtension('CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.getFileNameWithExtension('\\\\\\CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.getFileNameWithExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64.exe');
	assert.ok(utils.StringUtils.getFileNameWithExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64.exe');
});


/**
 * getFileNameWithoutExtension
 */
QUnit.test("getFileNameWithoutExtension", function(assert){

	assert.ok(utils.StringUtils.getFileNameWithoutExtension(null) === '');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('') === '');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('       ') === '');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('//folder/folder2/folder3/file.txt') === 'file');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('\\\\\\CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64');
	assert.ok(utils.StringUtils.getFileNameWithoutExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64');
});


/**
 * getFileExtension
 */
QUnit.test("getFileExtension", function(assert){

	assert.ok(utils.StringUtils.getFileExtension(null) === '');
	assert.ok(utils.StringUtils.getFileExtension('') === '');
	assert.ok(utils.StringUtils.getFileExtension('       ') === '');
	assert.ok(utils.StringUtils.getFileExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.getFileExtension('\\Files/CCleaner/CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.getFileExtension('//folder/folder2/folder3/file.txt') === 'txt');
	assert.ok(utils.StringUtils.getFileExtension('CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.getFileExtension('\\\\\\CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.getFileExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'exe');
	assert.ok(utils.StringUtils.getFileExtension('CCleaner64.EXE') === 'EXE');
	assert.ok(utils.StringUtils.getFileExtension('\\\\\\CCleaner64.eXEfile') === 'eXEfile');
	assert.ok(utils.StringUtils.getFileExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'exe');
});


/**
 * getSchemeFromUrl
 */
QUnit.test("getSchemeFromUrl", function(assert){

	// Invalid urls
	assert.ok(utils.StringUtils.getSchemeFromUrl(null) === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl(undefined) === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('adfadsf') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('||@#~@#~·$%') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://|@##~€#~€') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http:/youtube.org') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('x.ye') === '');
	assert.ok(utils.StringUtils.getSchemeFromUrl('google.com') === '');

	// Valid urls
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://youtube.org') === 'http');
	assert.ok(utils.StringUtils.getSchemeFromUrl('https://yahoo.es') === 'https');
	assert.ok(utils.StringUtils.getSchemeFromUrl('ftp://user:password@host.com:8080/path') === 'ftp');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html') === 'http');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://➡.ws/䨹') === 'http');
	assert.ok(utils.StringUtils.getSchemeFromUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com') === 'http');
	assert.ok(utils.StringUtils.getSchemeFromUrl('https://223.255.255.254') === 'https');

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

	assert.throws(function(){

		utils.StringUtils.formatPath(1);
	});
});


/**
 * formatUrl
 */
QUnit.test("formatUrl", function(assert){

	assert.ok(utils.StringUtils.formatUrl(null) === '');
	assert.ok(utils.StringUtils.formatUrl(undefined) === '');
	assert.ok(utils.StringUtils.formatUrl('') === '');

	// Format correct urls
	assert.ok(utils.StringUtils.formatUrl('google.com') === 'http://google.com');
	assert.ok(utils.StringUtils.formatUrl('www.google.com') === 'http://www.google.com');
	assert.ok(utils.StringUtils.formatUrl('   google.es') === 'http://google.es');
	assert.ok(utils.StringUtils.formatUrl('google.es   ') === 'http://google.es');
	assert.ok(utils.StringUtils.formatUrl('   google.es   ') === 'http://google.es');
	assert.ok(utils.StringUtils.formatUrl('http://www.example.com:8800') === 'http://www.example.com:8800');
	assert.ok(utils.StringUtils.formatUrl('www.example.com:8800') === 'http://www.example.com:8800');
	assert.ok(utils.StringUtils.formatUrl('foo.com/blah_blah/') === 'http://foo.com/blah_blah/');
	assert.ok(utils.StringUtils.formatUrl('ftp://test.com   ') === 'ftp://test.com');
	assert.ok(utils.StringUtils.formatUrl('http:\\\\test.com') === 'http://test.com');

	// Format incorrect urls
	assert.ok(utils.StringUtils.formatUrl('        ') === '        ');
	assert.ok(utils.StringUtils.formatUrl('123f56ccaca') === '123f56ccaca');
	assert.ok(utils.StringUtils.formatUrl('!$%&ERTdg4547') === '!$%&ERTdg4547');
	assert.ok(utils.StringUtils.formatUrl('http://.32') === 'http://.32');
	assert.ok(utils.StringUtils.formatUrl('http://10.1.1.255') === 'http://10.1.1.255');
	assert.ok(utils.StringUtils.formatUrl('ftp:/google.es') === 'ftp:/google.es');
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