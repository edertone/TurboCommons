"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ValidationManagerTest", {
    beforeEach : function() {

        window.ValidationManager = org_turbocommons.ValidationManager;
        window.validationManager = new org_turbocommons.ValidationManager();
    },

    afterEach : function() {

        delete window.ValidationManager;
        delete window.validationManager;
    }
});


/**
 * getStatus
 */
QUnit.todo("getStatus", function(assert) {

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
 * ok
 */
QUnit.todo("ok", function(assert) {

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
 * getFirstMessage
 */
QUnit.todo("getFirstMessage", function(assert) {

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
 * getLastMessage
 */
QUnit.todo("getLastMessage", function(assert) {

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
 * isTrue
 */
QUnit.test("isTrue", function(assert) {

    // Test empty values
    assert.ok(!validationManager.isTrue(undefined));
    assert.ok(!validationManager.isTrue(null));
    assert.ok(!validationManager.isTrue([]));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();

    // Test ok values
    assert.ok(validationManager.isTrue(true));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test wrong values
    assert.ok(!validationManager.isTrue(false));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
    assert.ok(!validationManager.isTrue('121212'));
    assert.ok(!validationManager.isTrue([1, 78]));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test mixed ok and wrong
    assert.ok(!validationManager.isTrue(false, 'false error', '', true));
    assert.ok(validationManager.getLastMessage() === 'false error');
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.ok(validationManager.isTrue(true, 'no error'));
    assert.ok(!validationManager.isTrue(false, 'false error 2'));
    assert.ok(validationManager.getLastMessage() === 'false error 2');
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
});


/**
 * isBoolean
 */
QUnit.test("isBoolean", function(assert) {

    // Test empty values
    assert.ok(!validationManager.isBoolean(undefined));
    assert.ok(!validationManager.isBoolean(null));
    assert.ok(!validationManager.isBoolean(''));
    assert.ok(!validationManager.isBoolean([]));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getLastMessage() === '');
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test ok values
    assert.ok(validationManager.isBoolean(true));
    assert.ok(validationManager.isBoolean(false));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test wrong values
    assert.ok(!validationManager.isBoolean('hello'));
    assert.ok(!validationManager.isBoolean(['go', 12]));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
    assert.ok(!validationManager.isBoolean(45));
    assert.ok(!validationManager.isBoolean(new Error(), 'custom error'));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
    assert.ok(validationManager.getLastMessage() === 'custom error');

    validationManager.reset();

    // Test mixed ok and wrong values
    assert.ok(!validationManager.isBoolean([12], 'error', '', true));
    assert.ok(validationManager.getLastMessage() === 'error');
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.ok(validationManager.isBoolean(true, 'no error'));
    assert.ok(!validationManager.isBoolean('asdf', 'error 2'));
    assert.ok(validationManager.getLastMessage() === 'error 2');
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
});


/**
 * isNumeric
 */
QUnit.test("isNumeric", function(assert) {

    // Test empty values
    assert.notOk(validationManager.isNumeric(null));
    assert.notOk(validationManager.isNumeric(undefined));
    assert.notOk(validationManager.isNumeric(''));
    assert.notOk(validationManager.isNumeric([]));
    assert.ok(validationManager.isNumeric(0));

    validationManager.reset();
    assert.ok(validationManager.getLastMessage() === '');
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test ok values
    assert.ok(validationManager.isNumeric(1));
    assert.ok(validationManager.isNumeric(-1));
    assert.ok(validationManager.isNumeric(145646));
    assert.ok(validationManager.isNumeric(-3453451));
    assert.ok(validationManager.isNumeric(1.34435));
    assert.ok(validationManager.isNumeric(-1.56567));
    assert.ok(validationManager.isNumeric('1'));
    assert.ok(validationManager.isNumeric('-1'));
    assert.ok(validationManager.isNumeric('1.4545645'));
    assert.ok(validationManager.isNumeric('-1.345'));
    assert.ok(validationManager.isNumeric('345341'));
    assert.ok(validationManager.isNumeric('-345341'));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test wrong values
    assert.notOk(validationManager.isNumeric([12, 'b']));
    assert.notOk(validationManager.isNumeric(new ValidationManager()));
    assert.ok(validationManager.getLastMessage() === 'value is not a number');
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
    assert.notOk(validationManager.isNumeric('hello', 'numeric error'));
    assert.notOk(validationManager.isNumeric('1,4356', 'numeric error'));
    assert.ok(validationManager.getLastMessage() === 'numeric error');
    assert.notOk(validationManager.isNumeric('1,4.4545', 'numeric error'));
    assert.notOk(validationManager.isNumeric('--345', 'numeric error'));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getLastMessage() === '');
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test mixed ok and wrong values
    assert.notOk(validationManager.isNumeric('hello', 'numeric error', '', true));
    assert.ok(validationManager.getLastMessage() === 'numeric error');
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.notOk(validationManager.isNumeric('hello', 'numeric error 2'));
    assert.ok(validationManager.getLastMessage() === 'numeric error 2');
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
});


/**
 * isNumericBetween
 */
QUnit.todo("isNumericBetween", function(assert) {

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
 * isString
 */
QUnit.test("isString", function(assert) {

    assert.ok(validationManager.isString(''));
    assert.ok(validationManager.isString('sfadf'));
    assert.ok(validationManager.isString('3453515 532'));
    assert.ok(validationManager.isString("\n\n$!"));
    assert.ok(validationManager.isString('hello baby how are you'));
    assert.ok(validationManager.isString("hello\n\nbably\r\ntest"));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    assert.ok(!validationManager.isString(null, '', '', true));
    assert.ok(!validationManager.isString(123, '', '', true));
    assert.ok(!validationManager.isString(4.879, '', '', true));
    assert.ok(!validationManager.isString(new ValidationManager(), '', '', true));
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.ok(!validationManager.isString([]));
    assert.ok(!validationManager.isString(-978));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getStatus() === ValidationManager.OK);
});


/**
 * isUrl
 */
QUnit.test("isUrl", function(assert) {

    // Wrong url cases
    assert.ok(!validationManager.isUrl(''));
    assert.ok(!validationManager.isUrl(null));
    assert.ok(!validationManager.isUrl([]));
    assert.ok(!validationManager.isUrl('    '));
    assert.ok(!validationManager.isUrl("\n   \t\n"));
    assert.ok(!validationManager.isUrl('ftp://user:password@host:port/path'));
    assert.ok(!validationManager.isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
    assert.ok(!validationManager.isUrl('C:\\Program Files (x86)'));

    // good url cases
    assert.ok(validationManager.isUrl('http://x.ye'));
    assert.ok(validationManager.isUrl('http://google.com'));
    assert.ok(validationManager.isUrl('ftp://mydomain.com'));
    assert.ok(validationManager.isUrl('http://www.example.com:8800'));
    assert.ok(validationManager.isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
    assert.ok(validationManager.isUrl('ftp://user:password@host.com:8080/path'));

    validationManager.reset();
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test non string values throw exceptions
    assert.throws(function() {

        validationManager.isUrl([12341]);
    });

    assert.throws(function() {

        validationManager.isUrl(12341);
    });
});


/**
 * isArray
 */
QUnit.test("isArray", function(assert) {

    assert.ok(validationManager.isArray([]));
    assert.ok(validationManager.isArray([1]));
    assert.ok(validationManager.isArray(['1']));
    assert.ok(validationManager.isArray(['1', 5, []]));
    assert.ok(validationManager.isArray([null]));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    assert.ok(!validationManager.isArray(undefined, '', '', true));
    assert.ok(!validationManager.isArray(null, '', '', true));
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.ok(!validationManager.isArray(1));
    assert.ok(!validationManager.isArray(''));
    assert.ok(!validationManager.isArray(new ValidationManager()));
    assert.ok(!validationManager.isArray('hello'));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getStatus() === ValidationManager.OK);
});


/**
 * isObject
 */
QUnit.test("isObject", function(assert) {

    assert.ok(validationManager.isObject({}));

    assert.ok(validationManager.isObject({
        1 : 1
    }));

    assert.ok(validationManager.isObject({
        1 : '1'
    }));

    assert.ok(validationManager.isObject({
        1 : '1',
        5 : 5,
        array : []
    }));

    assert.ok(validationManager.isObject({
        novalue : null
    }));

    assert.ok(validationManager.isObject(new ValidationManager()));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    assert.ok(!validationManager.isObject(null, '', '', true));
    assert.ok(!validationManager.isObject(undefined, '', '', true));
    assert.ok(!validationManager.isObject([], '', '', true));
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    
    assert.ok(!validationManager.isObject(1));
    assert.ok(!validationManager.isObject(''));
    assert.ok(!validationManager.isObject('hello'));
    assert.ok(!validationManager.isObject([1, 4, 5]));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();
    assert.ok(validationManager.getStatus() === ValidationManager.OK);
});


/**
 * isFilledIn
 */
QUnit.test("isFilledIn", function(assert) {

    // Test empty values
    assert.notOk(validationManager.isFilledIn(null));
    assert.notOk(validationManager.isFilledIn(''));
    assert.notOk(validationManager.isFilledIn([]));
    assert.ok(!validationManager.isFilledIn(null, [], '', '', true));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    validationManager.reset();

    // Test ok values
    assert.ok(validationManager.isFilledIn('adsadf'));
    assert.ok(validationManager.isFilledIn('    sdfasdsf'));
    assert.ok(validationManager.isFilledIn('EMPTY'));
    assert.ok(validationManager.isFilledIn('EMPTY test', ['EMPTY']));
    assert.ok(validationManager.getStatus() === ValidationManager.OK);

    // Test wrong values
    assert.notOk(validationManager.isFilledIn('      ', [], '', '', true));
    assert.ok(validationManager.getStatus() === ValidationManager.WARNING);
    assert.notOk(validationManager.isFilledIn("\n\n  \n"));
    assert.notOk(validationManager.isFilledIn("\t   \n     \r\r"));
    assert.notOk(validationManager.isFilledIn('EMPTY', ['EMPTY']));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);
    assert.notOk(validationManager.isFilledIn('EMPTY           ', ['EMPTY']));
    assert.notOk(validationManager.isFilledIn('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));
    assert.ok(validationManager.getStatus() === ValidationManager.ERROR);

    // Test non string value gives exception
    assert.throws(function() {

        validationManager.isFilledIn(125);
    });

    assert.throws(function() {

        validationManager.isFilledIn([125]);
    });

    assert.throws(function() {

        validationManager.isFilledIn(new Error());
    });
});


/**
 * isDate
 */
QUnit.todo("isDate", function(assert) {

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
 * isMail
 */
QUnit.todo("isMail", function(assert) {

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
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert) {

    assert.ok(validationManager.isEqualTo(null, null));
    assert.ok(validationManager.isEqualTo(undefined, undefined));
    assert.ok(validationManager.isEqualTo('', ''));
    assert.ok(validationManager.isEqualTo(123, 123));
    assert.ok(validationManager.isEqualTo(1.56, 1.56));
    assert.ok(validationManager.isEqualTo([], []));
    assert.ok(validationManager.isEqualTo('hello', 'hello'));
    assert.ok(validationManager.isEqualTo(new ValidationManager(), new ValidationManager()));
    assert.ok(validationManager.isEqualTo([1, 6, 8, 4], [1, 6, 8, 4]));

    assert.ok(!validationManager.isEqualTo(null, undefined));
    assert.ok(!validationManager.isEqualTo('', 'hello'));
    assert.ok(!validationManager.isEqualTo(124, 12454));
    assert.ok(!validationManager.isEqualTo(1.45, 1));
    assert.ok(!validationManager.isEqualTo([], {}));
    assert.ok(!validationManager.isEqualTo('gobaby', 'hello'));
    assert.ok(!validationManager.isEqualTo('hello', new ValidationManager()));
    assert.ok(!validationManager.isEqualTo([5, 2, 8, 5], [1, 6, 9, 5]));
    assert.ok(!validationManager.isEqualTo({
            a : 1,
            b : 2
        }, {
            c : 1,
            b : 3
        }));
});

//TODO - Add all missing tests