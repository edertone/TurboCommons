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
        
        window.emptyValues = [undefined, null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function() {

        delete window.StringUtils;
        delete window.ArrayUtils;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
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
 * isUrl
 */
QUnit.test("isUrl", function(assert) {

    // Wrong url cases
    assert.notOk(StringUtils.isUrl(''));
    assert.notOk(StringUtils.isUrl(null));
    assert.notOk(StringUtils.isUrl([]));
    assert.notOk(StringUtils.isUrl('    '));
    assert.notOk(StringUtils.isUrl('123f56ccaca'));
    assert.notOk(StringUtils.isUrl('8/%$144///(!(/"'));
    assert.notOk(StringUtils.isUrl('http'));
    assert.notOk(StringUtils.isUrl('x.y'));
    assert.notOk(StringUtils.isUrl('http://x.y'));
    assert.notOk(StringUtils.isUrl('google.com-'));
    assert.notOk(StringUtils.isUrl("\n   \t\n"));
    assert.notOk(StringUtils.isUrl('./test/file.js'));
    assert.notOk(StringUtils.isUrl('http:\\google.com'));
    assert.notOk(StringUtils.isUrl('_http://google.com'));
    assert.notOk(StringUtils.isUrl('http://www.example..com'));
    assert.notOk(StringUtils.isUrl('http://.com'));
    assert.notOk(StringUtils.isUrl('http://www.example.'));
    assert.notOk(StringUtils.isUrl('http:/www.example.com'));
    assert.notOk(StringUtils.isUrl('http://'));
    assert.notOk(StringUtils.isUrl('http://.'));
    assert.notOk(StringUtils.isUrl('http://??/'));
    assert.notOk(StringUtils.isUrl('http://foo.bar?q=Spaces should be encoded'));
    assert.notOk(StringUtils.isUrl('rdar://1234'));
    assert.notOk(StringUtils.isUrl('http://foo.bar/foo(bar)baz quux'));
    assert.notOk(StringUtils.isUrl('http://10.1.1.255'));
    assert.notOk(StringUtils.isUrl('http://.www.foo.bar./'));
    assert.notOk(StringUtils.isUrl('http://.www.foo.bar/'));
    assert.notOk(StringUtils.isUrl('ftp://user:password@host:port/path'));
    assert.notOk(StringUtils.isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
    assert.notOk(StringUtils.isUrl('C:\\Program Files (x86)'));
    assert.notOk(StringUtils.isUrl('http://www.google.com\\test.html'));

    // good url cases
    assert.ok(StringUtils.isUrl('http://x.ye'));
    assert.ok(StringUtils.isUrl('http://google.com'));
    assert.ok(StringUtils.isUrl('ftp://mydomain.com'));
    assert.ok(StringUtils.isUrl('http://www.example.com:8800'));
    assert.ok(StringUtils.isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
    assert.ok(StringUtils.isUrl('http://www.test.com/do.html#A'));
    assert.ok(StringUtils.isUrl('https://subdomain.test.com/'));
    assert.ok(StringUtils.isUrl('https://test.com'));
    assert.ok(StringUtils.isUrl('http://foo.com/blah_blah/'));
    assert.ok(StringUtils.isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
    assert.ok(StringUtils.isUrl('http://userid@example.com:8080'));
    assert.ok(StringUtils.isUrl('http://➡.ws/䨹'));
    assert.ok(StringUtils.isUrl('http://⌘.ws/'));
    assert.ok(StringUtils.isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
    assert.ok(StringUtils.isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
    assert.ok(StringUtils.isUrl('http://223.255.255.254'));
    assert.ok(StringUtils.isUrl('ftp://user:password@host.com:8080/path'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2?c=3'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2?????'));
    assert.ok(StringUtils.isUrl('http://www.test.com?pageid=123&testid=1524'));
    
    // Test non string values throw exceptions
    assert.throws(function() {

        StringUtils.isUrl([12341]);
    });

    assert.throws(function() {

        StringUtils.isUrl(12341);
    });
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
QUnit.todo("isCamelCase", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * isSnakeCase
 */
QUnit.todo("isSnakeCase", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * replace
 */
QUnit.test("replace", function(assert) {

    // Test empty values
    for(var i = 0; i < emptyValuesCount; i++){
        
        if(StringUtils.isString(emptyValues[i])){
            
            assert.strictEqual(StringUtils.replace(emptyValues[i], "a", "b"), emptyValues[i]);
            assert.strictEqual(StringUtils.replace("string", emptyValues[i], "b"), "string");
            assert.strictEqual(StringUtils.replace("string", "a", emptyValues[i]), "string");
            
            assert.throws(function() {
                StringUtils.replace("string", "a", "b", emptyValues[i]);
            }, /count must be an integer/);
            
        }else{
            
            assert.throws(function() {
                StringUtils.replace(emptyValues[i], "a", "b");
            }, /string is not valid/);
            
            if(ArrayUtils.isArray(emptyValues[i])){
                
                assert.strictEqual(StringUtils.replace("string", emptyValues[i], "b"), "string");
                assert.strictEqual(StringUtils.replace("string", "a", emptyValues[i]), "string");
                
            }else{
                
                assert.throws(function() {
                    StringUtils.replace("string", emptyValues[i], "b");
                }, /search is not a string or array/);
                
                assert.throws(function() {
                    StringUtils.replace("string", "a", emptyValues[i]);
                }, /replacement is not a string or array/);
            }            
        }
    }

    // Test ok values
    assert.strictEqual(StringUtils.replace("x", "", "xyz"), "x");
    assert.strictEqual(StringUtils.replace("x", "x", "xyz"), "xyz");
    assert.strictEqual(StringUtils.replace("x", "x", "$"), "$");
    assert.strictEqual(StringUtils.replace("x", "x", "$$"), "$$");
    assert.strictEqual(StringUtils.replace("string", "str", ""), "ing");
    assert.strictEqual(StringUtils.replace("string", "s", "b"), "btring");
    assert.strictEqual(StringUtils.replace("string", "st", "ab"), "abring");
    assert.strictEqual(StringUtils.replace("string", "string", ""), "");
    assert.strictEqual(StringUtils.replace("abababAb", "a", "X"), "XbXbXbAb");
    assert.strictEqual(StringUtils.replace("abababAb", "aba", "r"), "rbabAb");
    assert.strictEqual(StringUtils.replace("+$-/\\_", "$", "Q"), "+Q-/\\_");
    
    assert.strictEqual(StringUtils.replace("string", ["s"], "b"), "btring");
    assert.strictEqual(StringUtils.replace("string", ["s", "i", "g"], "b"), "btrbnb");
    assert.strictEqual(StringUtils.replace("string", ["s", "i"], ["b", " "]), "btr ng");
    assert.strictEqual(StringUtils.replace("Hello???", ["H", "E", "?"], ["h", "X", "!"]), "hello!!!");
    // TODO - add lot more tests

    // Test ok values with limited count
    // TODO
    
    // Test wrong values
    // TODO

    // Test exceptions
    assert.throws(function() {
        StringUtils.replace("string", ["a"], ["b", "c"]);
    }, /search and replacement arrays must have the same length/);
    
    assert.throws(function() {
        StringUtils.replace("string", ["a", "b", "c"], ["b", "c"]);
    }, /search and replacement arrays must have the same length/);
    // TODO add more
});


/**
 * trim
 */
QUnit.test("trim", function(assert){

    // Test empty values
    assert.throws(function() {
        StringUtils.trim(undefined);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(null);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(0);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim([]);
    }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trim(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trim("   "), "");
    assert.strictEqual(StringUtils.trim("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trim("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trim("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trim("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trim("hello"), "hello");
    assert.strictEqual(StringUtils.trim("hello\n"), "hello");
    assert.strictEqual(StringUtils.trim("hello\r\n"), "hello");
    assert.strictEqual(StringUtils.trim("\nhello\r\n"), "hello");
    assert.strictEqual(StringUtils.trim("   hello"), "hello");
    assert.strictEqual(StringUtils.trim("hello   "), "hello");
    assert.strictEqual(StringUtils.trim("  hello  "), "hello");
    
    assert.strictEqual(StringUtils.trim("helloxax", "xa"), "hello");
    assert.strictEqual(StringUtils.trim("XXXhello", "xlX"), "hello");
    assert.strictEqual(StringUtils.trim("XXXhelloxxx", "xX"), "hello");
    assert.strictEqual(StringUtils.trim("1|2", "123"), "|");
    assert.strictEqual(StringUtils.trim("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trim("1|2\n", "1"), "|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.trim([1, 2, 3, 4]);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(new Error());
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(123466);
    }, /value is not a string/);
});


/**
 * trimLeft
 */
QUnit.test("trimLeft", function(assert){

    // Test empty values
    assert.throws(function() {
        StringUtils.trimLeft(undefined);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimLeft(null);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimLeft(0);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimLeft([]);
    }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trimLeft(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trimLeft("   "), "");
    assert.strictEqual(StringUtils.trimLeft("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimLeft("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimLeft("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimLeft("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimLeft("hello"), "hello");
    assert.strictEqual(StringUtils.trimLeft("hello\n"), "hello\n");
    assert.strictEqual(StringUtils.trimLeft("hello\r\n"), "hello\r\n");
    assert.strictEqual(StringUtils.trimLeft("\nhello\r\n"), "hello\r\n");
    assert.strictEqual(StringUtils.trimLeft("   hello"), "hello");
    assert.strictEqual(StringUtils.trimLeft("hello   "), "hello   ");
    assert.strictEqual(StringUtils.trimLeft("  hello  "), "hello  ");
    
    assert.strictEqual(StringUtils.trimLeft("helloxax", "xa"), "helloxax");
    assert.strictEqual(StringUtils.trimLeft("XXXhello", "xlX"), "hello");
    assert.strictEqual(StringUtils.trimLeft("XXXhelloxxx", "xX"), "helloxxx");
    assert.strictEqual(StringUtils.trimLeft("1|2", "123"), "|2");
    assert.strictEqual(StringUtils.trimLeft("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trimLeft("1|2\n", "1"), "|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.trimLeft([1, 2, 3, 4]);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimLeft(new Error());
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimLeft(123466);
    }, /value is not a string/);
});


/**
 * trimRight
 */
QUnit.test("trimRight", function(assert){

    // Test empty values
    assert.throws(function() {
        StringUtils.trimRight(undefined);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(null);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(0);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight([]);
    }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trimRight(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trimRight("   "), "");
    assert.strictEqual(StringUtils.trimRight("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimRight("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimRight("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimRight("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimRight("hello"), "hello");
    assert.strictEqual(StringUtils.trimRight("hello\n"), "hello");
    assert.strictEqual(StringUtils.trimRight("hello\r\n"), "hello");
    assert.strictEqual(StringUtils.trimRight("\nhello\r\n"), "\nhello");
    assert.strictEqual(StringUtils.trimRight("   hello"), "   hello");
    assert.strictEqual(StringUtils.trimRight("hello   "), "hello");
    assert.strictEqual(StringUtils.trimRight("  hello  "), "  hello");
    
    assert.strictEqual(StringUtils.trimRight("helloxax", "xa"), "hello");
    assert.strictEqual(StringUtils.trimRight("XXXhello", "xlX"), "XXXhello");
    assert.strictEqual(StringUtils.trimRight("XXXhelloxxx", "xX"), "XXXhello");
    assert.strictEqual(StringUtils.trimRight("1|2", "123"), "1|");
    assert.strictEqual(StringUtils.trimRight("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trimRight("1|2\n", "1"), "1|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.trimRight([1, 2, 3, 4]);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(new Error());
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(123466);
    }, /value is not a string/);
});


/**
 * countStringOccurences
 */
QUnit.test("countStringOccurences", function(assert) {

    assert.strictEqual(StringUtils.countStringOccurences('       ', ' '), 7);
    assert.strictEqual(StringUtils.countStringOccurences('hello', 'o'), 1);
    assert.strictEqual(StringUtils.countStringOccurences('hello baby', 'b'), 2);
    assert.strictEqual(StringUtils.countStringOccurences('hello baby', 'B'), 0);
    assert.strictEqual(StringUtils.countStringOccurences("tRy\nto\r\n\t\ngo\r\nUP", 'o'), 2);
    assert.strictEqual(StringUtils.countStringOccurences("     \n      \r\n", 'a'), 0);
    assert.strictEqual(StringUtils.countStringOccurences(" AEÉÜ    \n   1   \r\nÍË", 'É'), 1);
    assert.strictEqual(StringUtils.countStringOccurences("heLLo Baby\nhellÓ àgaiN and go\n\n\r\nUp!", 'a'), 3);
    assert.strictEqual(StringUtils.countStringOccurences("helló bàbÝ\n   whats Up Todäy? are you feeling better? GOOD!", 'T'), 1);

    // Test exceptions
    assert.throws(function() {
        StringUtils.countStringOccurences(null, null);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.countStringOccurences('', '');
    }, /cannot count occurences for an empty string/);

    assert.throws(function() {
        StringUtils.countStringOccurences('  ', '');
    }, /cannot count occurences for an empty string/);
});


/**
 * countCapitalLetters
 */
QUnit.todo("countCapitalLetters", function(assert) {

    // TODO: copy tests from PHP
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
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\rline2\rline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\nline2\r\nline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n        \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n\n\n\t\r       \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n 1  \nline2"), ['line1', ' 1  ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n 1  \n\r\r\nline2"), ['line1', ' 1  ', 'line2']));

    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('          ', []), ['          ']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r   \rline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n   \nline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n\n\n\t\r       \nline2", []), ['line1', "\t", '       ', 'line2']));
});


/**
 * getKeyWords
 */
QUnit.todo("getKeyWords", function(assert) {

    // TODO: copy tests from PHP
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
QUnit.todo("formatCase", function(assert) {

    // TODO: copy tests from PHP
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
    assert.ok(StringUtils.formatUrl('https://angular.io/guide/http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io\\guide/http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io\\guide/////http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io/api/common/http/HttpErrorResponse') === 'https://angular.io/api/common/http/HttpErrorResponse');
    assert.ok(StringUtils.formatUrl('https://www.youtube.com/watch?v=dp5hsDgENLk&feature=youtu.be') === 'https://www.youtube.com/watch?v=dp5hsDgENLk&feature=youtu.be');
    assert.ok(StringUtils.formatUrl('https://www.ovh.com/auth/?action=gotomanager&from=https://www.ovh.es/') === 'https://www.ovh.com/auth/?action=gotomanager&from=https://www.ovh.es/');
    assert.ok(StringUtils.formatUrl('https://stackoverflow.com/questions/10161177/url-with-multiple-forward-slashes-does-it-break-anything') === 'https://stackoverflow.com/questions/10161177/url-with-multiple-forward-slashes-does-it-break-anything');
        
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
QUnit.todo("formatForFullTextSearch", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * generateRandomPassword
 */
QUnit.test("generateRandomPassword", function(assert) {

    // Test empty values
    assert.throws(function() {

        StringUtils.generateRandomPassword(null, null, null);
    });

    // Test ok values
    assert.ok(StringUtils.generateRandomPassword(0, true, true) === '');
    assert.ok(StringUtils.generateRandomPassword(0, false, false) === '');

    for (var i = 1; i < 50; i++) {

        assert.ok(StringUtils.generateRandomPassword(i, true, true).length === i);
        assert.ok(StringUtils.generateRandomPassword(i, false, false).length === i);
        assert.ok(StringUtils.generateRandomPassword(i, true, false).length === i);
        assert.ok(StringUtils.generateRandomPassword(i, false, true).length === i);
    }

    // Test wrong values
    // not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.generateRandomPassword(-1);
    });

    assert.throws(function() {
        StringUtils.generateRandomPassword('some string');
    });
});


/**
 * testRemoveNewLineCharacters
 */
QUnit.todo("testRemoveNewLineCharacters", function(assert) {

    // TODO: copy tests from PHP
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
QUnit.todo("removeWordsShorterThan", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeWordsLongerThan
 */
QUnit.todo("removeWordsLongerThan", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeUrls
 */
QUnit.todo("removeUrls", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeHtmlCode
 */
QUnit.todo("removeHtmlCode", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeMultipleSpaces
 */
QUnit.todo("removeMultipleSpaces", function(assert) {

    // TODO: copy tests from PHP
});