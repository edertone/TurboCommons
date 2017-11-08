"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("StringUtilsTest", {
    beforeEach : function() {

        window.StringUtils = org_turbocommons.StringUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
    },

    afterEach : function() {

        delete window.StringUtils;
        delete window.ArrayUtils;
    }
});


/**
 * isString
 */
QUnit.test("isString", function(assert) {

    assert.ok(StringUtils.isString(''));
    assert.ok(StringUtils.isString('      '));
    assert.ok(StringUtils.isString('1'));
    assert.ok(StringUtils.isString('a'));
    assert.ok(StringUtils.isString('hello'));
    assert.ok(StringUtils.isString("hello\n\nguys"));

    assert.ok(!StringUtils.isString(undefined));
    assert.ok(!StringUtils.isString(null));
    assert.ok(!StringUtils.isString(0));
    assert.ok(!StringUtils.isString(15));
    assert.ok(!StringUtils.isString([]));
    assert.ok(!StringUtils.isString([1]));
    assert.ok(!StringUtils.isString(['a', 'cd']));
    assert.ok(!StringUtils.isString({}));
    assert.ok(!StringUtils.isString(new Error()));
});


/**
 * isEmpty
 */
QUnit.test("isEmpty", function(assert) {

    assert.ok(StringUtils.isEmpty(undefined));
    assert.ok(StringUtils.isEmpty(null));
    assert.ok(StringUtils.isEmpty(''));
    assert.ok(StringUtils.isEmpty([]));
    assert.ok(StringUtils.isEmpty('      '));
    assert.ok(StringUtils.isEmpty("\n\n  \n"));
    assert.ok(StringUtils.isEmpty("\t   \n     \r\r"));
    assert.ok(StringUtils.isEmpty('EMPTY', ['EMPTY']));
    assert.ok(StringUtils.isEmpty('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

    assert.ok(!StringUtils.isEmpty('adsadf'));
    assert.ok(!StringUtils.isEmpty('    sdfasdsf'));
    assert.ok(!StringUtils.isEmpty('EMPTY'));
    assert.ok(!StringUtils.isEmpty('EMPTY test', ['EMPTY']));
    assert.ok(!StringUtils.isEmpty('EMPTY       void   hole    XX', ['EMPTY', 'void', 'hole']));

    // Test non string value gives exception
    assert.throws(function() {

        StringUtils.isEmpty(123);
    });
});


/**
 * isCamelCase
 */
QUnit.test("isCamelCase", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * isSnakeCase
 */
QUnit.test("isSnakeCase", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * countStringOccurences
 */
QUnit.test("countStringOccurences", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * countCapitalLetters
 */
QUnit.test("countCapitalLetters", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * countWords
 */
QUnit.test("countWords", function(assert) {

    assert.ok(StringUtils.countWords(null) == 0);
    assert.ok(StringUtils.countWords('') == 0);
    assert.ok(StringUtils.countWords('  ') == 0);
    assert.ok(StringUtils.countWords('       ') == 0);
    assert.ok(StringUtils.countWords('hello') == 1);
    assert.ok(StringUtils.countWords('hello baby') == 2);
    assert.ok(StringUtils.countWords('Üèó ï á étwer') == 4);
    assert.ok(StringUtils.countWords("try\nto\r\n\t\ngo\r\nup") == 4);
    assert.ok(StringUtils.countWords("     \n      \r\n") == 0);
    assert.ok(StringUtils.countWords("     \n   1   \r\n") == 1);
    assert.ok(StringUtils.countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
    assert.ok(StringUtils.countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
});


/**
 * limitLen
 */
QUnit.test("limitLen", function(assert) {

    assert.ok(StringUtils.limitLen(null, 10) === '');
    assert.ok(StringUtils.limitLen('', 10) === '');
    assert.ok(StringUtils.limitLen('hello dear how are you', 1) === ' ');
    assert.ok(StringUtils.limitLen('hello dear how are you', 2) === ' .');
    assert.ok(StringUtils.limitLen('hello dear how are you', 3) === ' ..');
    assert.ok(StringUtils.limitLen('hello dear how are you', 4) === ' ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 5) === 'h ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 18) === 'hello dear how ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 19) === 'hello dear how  ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 20) === 'hello dear how a ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 22) === 'hello dear how are you');
    assert.ok(StringUtils.limitLen('hello dear how are you', 50) === 'hello dear how are you');

    // Test invalid values give exception
    assert.throws(function() {

        StringUtils.limitLen('', 0);
    });

    assert.throws(function() {

        StringUtils.limitLen('hello', [1, 2]);
    });

    assert.throws(function() {

        StringUtils.limitLen('hello', null);
    });
});


/**
 * getDomainFromUrl
 */
QUnit.test("getDomainFromUrl", function(assert) {

    assert.ok(StringUtils.getDomainFromUrl(null) === '');
    assert.ok(StringUtils.getDomainFromUrl(undefined) === '');
    assert.ok(StringUtils.getDomainFromUrl('') === '');
    assert.ok(StringUtils.getDomainFromUrl("aa....aa..") === '');
    assert.ok(StringUtils.getDomainFromUrl('google.com') === '');
    assert.ok(StringUtils.getDomainFromUrl('http://google.com') === 'google.com');
    assert.ok(StringUtils.getDomainFromUrl('http://www.google.com') === 'google.com');
    assert.ok(StringUtils.getDomainFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'youtube.com');
    assert.ok(StringUtils.getDomainFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'chrome.com');
    assert.ok(StringUtils.getDomainFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'abc.es');
    assert.ok(StringUtils.getDomainFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
    assert.ok(StringUtils.getDomainFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
    assert.ok(StringUtils.getDomainFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
    assert.ok(StringUtils.getDomainFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'funet.fi');
    assert.ok(StringUtils.getDomainFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'xyz.com');
    assert.ok(StringUtils.getDomainFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'xyz.com');
});


/**
 * getHostNameFromUrl
 */
QUnit.test("getHostNameFromUrl", function(assert) {

    assert.ok(StringUtils.getHostNameFromUrl(null) === '');
    assert.ok(StringUtils.getHostNameFromUrl(undefined) === '');
    assert.ok(StringUtils.getHostNameFromUrl('') === '');
    assert.ok(StringUtils.getHostNameFromUrl("aa....aa..") === '');
    assert.ok(StringUtils.getHostNameFromUrl('google.com') === '');
    assert.ok(StringUtils.getHostNameFromUrl('http://x.ye') === 'x.ye');
    assert.ok(StringUtils.getHostNameFromUrl('http://google.com') === 'google.com');
    assert.ok(StringUtils.getHostNameFromUrl('http://www.google.com') === 'www.google.com');
    assert.ok(StringUtils.getHostNameFromUrl('|%$)"·/%') === '');
    assert.ok(StringUtils.getHostNameFromUrl('http://|%$)"·/%') === '');
    assert.ok(StringUtils.getHostNameFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'www.youtube.com');
    assert.ok(StringUtils.getHostNameFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'developer.chrome.com');
    assert.ok(StringUtils.getHostNameFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'www.abc.es');
    assert.ok(StringUtils.getHostNameFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
    assert.ok(StringUtils.getHostNameFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
    assert.ok(StringUtils.getHostNameFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'ftp.funet.fi');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
});


/**
 * getLines
 */
QUnit.test("getLines", function(assert) {

    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines(null), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines(''), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('          '), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('single line'), ['single line']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\nline2\nline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n        \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n\n\n\t\r       \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n 1  \nline2"), ['line1', ' 1  ', 'line2']));

    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('          ', []), ['          ']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n   \nline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2", []), ['line1', '   ', 'line2']));
});


/**
 * getKeyWords
 */
QUnit.test("getKeyWords", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * getFileNameWithExtension
 */
QUnit.test("getFileNameWithExtension", function(assert) {

    assert.ok(StringUtils.getFileNameWithExtension(null) === '');
    assert.ok(StringUtils.getFileNameWithExtension('') === '');
    assert.ok(StringUtils.getFileNameWithExtension('       ') === '');
    assert.ok(StringUtils.getFileNameWithExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64.exe');
    assert.ok(StringUtils.getFileNameWithExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64.exe');
    assert.ok(StringUtils.getFileNameWithExtension('//folder/folder2/folder3/file.txt') === 'file.txt');
    assert.ok(StringUtils.getFileNameWithExtension('CCleaner64.exe') === 'CCleaner64.exe');
    assert.ok(StringUtils.getFileNameWithExtension('\\\\\\CCleaner64.exe') === 'CCleaner64.exe');
    assert.ok(StringUtils.getFileNameWithExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64.exe');
    assert.ok(StringUtils.getFileNameWithExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64.exe');
});


/**
 * getFileNameWithoutExtension
 */
QUnit.test("getFileNameWithoutExtension", function(assert) {

    assert.ok(StringUtils.getFileNameWithoutExtension(null) === '');
    assert.ok(StringUtils.getFileNameWithoutExtension('') === '');
    assert.ok(StringUtils.getFileNameWithoutExtension('       ') === '');
    assert.ok(StringUtils.getFileNameWithoutExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'CCleaner64');
    assert.ok(StringUtils.getFileNameWithoutExtension('\\Files/CCleaner/CCleaner64.exe') === 'CCleaner64');
    assert.ok(StringUtils.getFileNameWithoutExtension('//folder/folder2/folder3/file.txt') === 'file');
    assert.ok(StringUtils.getFileNameWithoutExtension('CCleaner64.exe') === 'CCleaner64');
    assert.ok(StringUtils.getFileNameWithoutExtension('\\\\\\CCleaner64.exe') === 'CCleaner64');
    assert.ok(StringUtils.getFileNameWithoutExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'CCleaner64');
    assert.ok(StringUtils.getFileNameWithoutExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'CCleaner64');
});


/**
 * getFileExtension
 */
QUnit.test("getFileExtension", function(assert) {

    assert.ok(StringUtils.getFileExtension(null) === '');
    assert.ok(StringUtils.getFileExtension('') === '');
    assert.ok(StringUtils.getFileExtension('       ') === '');
    assert.ok(StringUtils.getFileExtension('C:\\Program Files\\CCleaner\\CCleaner64.exe') === 'exe');
    assert.ok(StringUtils.getFileExtension('\\Files/CCleaner/CCleaner64.exe') === 'exe');
    assert.ok(StringUtils.getFileExtension('//folder/folder2/folder3/file.txt') === 'txt');
    assert.ok(StringUtils.getFileExtension('CCleaner64.exe') === 'exe');
    assert.ok(StringUtils.getFileExtension('\\\\\\CCleaner64.exe') === 'exe');
    assert.ok(StringUtils.getFileExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') === 'exe');
    assert.ok(StringUtils.getFileExtension('CCleaner64.EXE') === 'EXE');
    assert.ok(StringUtils.getFileExtension('\\\\\\CCleaner64.eXEfile') === 'eXEfile');
    assert.ok(StringUtils.getFileExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") === 'exe');
});


/**
 * getSchemeFromUrl
 */
QUnit.test("getSchemeFromUrl", function(assert) {

    // Invalid urls
    assert.ok(StringUtils.getSchemeFromUrl(null) === '');
    assert.ok(StringUtils.getSchemeFromUrl(undefined) === '');
    assert.ok(StringUtils.getSchemeFromUrl('') === '');
    assert.ok(StringUtils.getSchemeFromUrl('adfadsf') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http://') === '');
    assert.ok(StringUtils.getSchemeFromUrl('||@#~@#~·$%') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http://|@##~€#~€') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http:/youtube.org') === '');
    assert.ok(StringUtils.getSchemeFromUrl('x.ye') === '');
    assert.ok(StringUtils.getSchemeFromUrl('google.com') === '');

    // Valid urls
    assert.ok(StringUtils.getSchemeFromUrl('http://youtube.org') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('https://yahoo.es') === 'https');
    assert.ok(StringUtils.getSchemeFromUrl('ftp://user:password@host.com:8080/path') === 'ftp');
    assert.ok(StringUtils.getSchemeFromUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('http://➡.ws/䨹') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('https://223.255.255.254') === 'https');

});


/**
 * formatCase
 */
QUnit.test("formatCase", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * formatPath
 */
QUnit.test("formatPath", function(assert) {

    var osSeparator = '/';

    assert.ok(StringUtils.formatPath(null) === '');
    assert.ok(StringUtils.formatPath('') === '');
    assert.ok(StringUtils.formatPath('       ') === '       ');
    assert.ok(StringUtils.formatPath('test//test/') === 'test' + osSeparator + 'test');
    assert.ok(StringUtils.formatPath('////test//////test////') === osSeparator + 'test' + osSeparator + 'test');
    assert.ok(StringUtils.formatPath('\\\\////test//test/') === osSeparator + 'test' + osSeparator + 'test');
    assert.ok(StringUtils.formatPath('test\\test/hello\\\\') === 'test' + osSeparator + 'test' + osSeparator + 'hello');

    // Test non string paths throw exception
    assert.throws(function() {

        StringUtils.formatPath(['1']);
    });

    assert.throws(function() {

        StringUtils.formatPath(1);
    });
});


/**
 * formatUrl
 */
QUnit.test("formatUrl", function(assert) {

    assert.ok(StringUtils.formatUrl(null) === '');
    assert.ok(StringUtils.formatUrl(undefined) === '');
    assert.ok(StringUtils.formatUrl('') === '');

    // Format correct urls
    assert.ok(StringUtils.formatUrl('google.com') === 'http://google.com');
    assert.ok(StringUtils.formatUrl('www.google.com') === 'http://www.google.com');
    assert.ok(StringUtils.formatUrl('   google.es') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('google.es   ') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('   google.es   ') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('http://www.example.com:8800') === 'http://www.example.com:8800');
    assert.ok(StringUtils.formatUrl('www.example.com:8800') === 'http://www.example.com:8800');
    assert.ok(StringUtils.formatUrl('foo.com/blah_blah/') === 'http://foo.com/blah_blah/');
    assert.ok(StringUtils.formatUrl('ftp://test.com   ') === 'ftp://test.com');
    assert.ok(StringUtils.formatUrl('http:\\\\test.com') === 'http://test.com');

    // Format incorrect urls
    assert.ok(StringUtils.formatUrl('        ') === '        ');
    assert.ok(StringUtils.formatUrl('123f56ccaca') === '123f56ccaca');
    assert.ok(StringUtils.formatUrl('!$%&ERTdg4547') === '!$%&ERTdg4547');
    assert.ok(StringUtils.formatUrl('http://.32') === 'http://.32');
    assert.ok(StringUtils.formatUrl('http://10.1.1.255') === 'http://10.1.1.255');
    assert.ok(StringUtils.formatUrl('ftp:/google.es') === 'ftp:/google.es');
});


/**
 * formatForFullTextSearch
 */
QUnit.test("formatForFullTextSearch", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * generateRandomPassword
 */
QUnit.test("generateRandomPassword", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeAccents
 */
QUnit.test("removeAccents", function(assert) {

    assert.ok(StringUtils.removeAccents(null) === '');
    assert.ok(StringUtils.removeAccents('') === '');
    assert.ok(StringUtils.removeAccents('        ') === '        ');
    assert.ok(StringUtils.removeAccents('Fó Bår') === 'Fo Bar');
    assert.ok(StringUtils.removeAccents("|!€%'''") === "|!€%'''");
    assert.ok(StringUtils.removeAccents('hiweury asb fsuyr weqr') === 'hiweury asb fsuyr weqr');
    assert.ok(StringUtils.removeAccents('!iYgh65541tGY%$$73267yt') === '!iYgh65541tGY%$$73267yt');
    assert.ok(StringUtils.removeAccents('hello 12786,.123123') === 'hello 12786,.123123');
    assert.ok(StringUtils.removeAccents('check this `^+*´--_{}[]') === 'check this `^+*´--_{}[]');
    assert.ok(StringUtils.removeAccents('hellóóóóóí´ 12786,.123123"') === 'helloooooi´ 12786,.123123"');
    assert.ok(StringUtils.removeAccents("hello\nbaby\r\ntest it well !!!!!") === "hello\nbaby\r\ntest it well !!!!!");
    assert.ok(StringUtils.removeAccents('óíéàùú hello') === 'oieauu hello');
    assert.ok(StringUtils.removeAccents("óóó èèè\núùúùioler    \r\noughúíééanh hello") === "ooo eee\nuuuuioler    \r\noughuieeanh hello");
    assert.ok(StringUtils.removeAccents('öïüíúóèà go!!.;') === 'oiuiuoea go!!.;');

});


/**
 * removeWordsShorterThan
 */
QUnit.test("removeWordsShorterThan", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeWordsLongerThan
 */
QUnit.test("removeWordsLongerThan", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeUrls
 */
QUnit.test("removeUrls", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeEmails
 */
QUnit.test("removeEmails", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeHtmlCode
 */
QUnit.test("removeHtmlCode", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});


/**
 * removeMultipleSpaces
 */
QUnit.test("removeMultipleSpaces", function(assert) {

    // TODO: copy tests from PHP
    assert.ok(true);
});