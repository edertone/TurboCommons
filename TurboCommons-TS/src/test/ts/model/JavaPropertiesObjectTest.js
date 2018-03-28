"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("JavaPropertiesObjectTest", {
    
    before: function(assert) {

        window.StringUtils = org_turbocommons.StringUtils;
        window.basePath = './resources/model/javaPropertiesObject/';

        var httpManager = new org_turbocommons.HTTPManager();
        
        // Load all the properties files
        var done = assert.async();
        
        httpManager.loadAllResourcesFromList(basePath + '_folder-list.txt', basePath,
            function(resourcesList, resourcesData){
        
                window.propertiesFiles = resourcesList;
                window.propertiesFilesData = resourcesData;
                done();
                
            }, function(errorUrl, errorMsg, errorCode){
                
                assert.ok(false, 'Error loading file ' + errorUrl);
                done();
            });
    },
    
    beforeEach : function(){

        window.JavaPropertiesObject = org_turbocommons.JavaPropertiesObject;

        window.wrongValues = [null, [], 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, {}];
        window.wrongValuesCount = window.wrongValues.length; 
    },

    afterEach : function(){

        delete window.JavaPropertiesObject;

        delete window.wrongValues;
        delete window.wrongValuesCount;
    },
    
    after : function(){

        delete window.StringUtils;
        delete window.basePath;
        
        delete window.propertiesFiles;
        delete window.propertiesFilesData;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new JavaPropertiesObject();
    assert.ok(test.length() === 0);

    test = new JavaPropertiesObject('');
    assert.ok(test.length() === 0);

    assert.throws(function() {
        new JavaPropertiesObject('       ');
    }, /invalid properties format/);

    assert.throws(function() {
        new JavaPropertiesObject("\n\n\n");
    }, /invalid properties format/);
    
    // Test ok values
    test = new JavaPropertiesObject('name=Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name = Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name    =    Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('      name = Stephen');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen');

    test = new JavaPropertiesObject('name=Stephen      ');
    assert.ok(test.length() === 1);
    assert.ok(test.get('name') === 'Stephen      ');

    test = new JavaPropertiesObject('path=c:\\\\docs\\\\doc1');
    assert.ok(test.length() === 1);
    assert.ok(test.get('path') === 'c:\\docs\\doc1');
    
    for (var file of propertiesFiles) {
    
        var fileData = propertiesFilesData[propertiesFiles.indexOf(file)];
        var test = new JavaPropertiesObject(fileData);

        switch (StringUtils.getFileNameWithExtension(file)) {

            case '1KeyWithValue.properties':
                assert.ok(test.length() === 1);
                assert.ok(test.get('keyname') === 'value');
                break;

            case '2KeysWithValue.properties':
                assert.ok(test.length() === 2);
                assert.ok(test.get('keyname') === 'value');
                assert.ok(test.get('keyname2') === 'value2');
                break;

            case 'CommentsSlashesAndSpecialChars.properties':
                assert.ok(test.length() === 5);
                assert.ok(test.get('website') === 'http://en.wikipedia.org/');
                assert.ok(test.get('language') === 'English');
                assert.ok(test.get('message') === 'Welcome to Wikipedia!');
                assert.ok(test.get('key with spaces') === 'This is the value that could be looked up with the key "key with spaces".');
                assert.ok(test.get('tab') === "\t");
                break;

            case 'LotsOfEmptySpacesEveryWhere.properties':
                assert.strictEqual(12, test.length());
                assert.ok(test.get('k1') === '');
                assert.ok(test.get('k2') === ' ');
                assert.ok(test.get('k3') === '   ');
                assert.ok(test.get('k4') === '   test');
                assert.ok(test.get('k5') === '    test   ');
                assert.ok(test.get('k6') === "   test  \r\ngo");
                assert.ok(test.get('k7') === '  ');
                assert.ok(test.get(' k8') === '8');
                assert.ok(test.get('  k9') === '9');
                assert.ok(test.get('  k10 ') === '10');
                assert.ok(test.get('  k11  ') === '11');
                assert.ok(test.get('  k12  ') === '12');
                break;

            case 'LotsOfLatinKeysAndValues.properties':
                assert.ok(test.length() === 45);
                assert.ok(test.get('period.maintenance.InMillis') === '86400000');
                assert.ok(test.get('decontamination.frequency.inDays') === '30');
                assert.ok(test.get('decontamination.warningBeforehand.inDays') === '1');
                assert.ok(test.get('technicalServiceInspection.frequency.inMonths') === '12');
                assert.ok(test.get('technicalServiceInspection.warningBeforehand.inDays') === '7');
                assert.ok(test.get('instrument.restartFrequency.inDays') === '7');
                assert.ok(test.get('start.purgeFinishedTestsPriorToTheLast.inDays') === '7');
                assert.ok(test.get('max.error.count') === '-1');
                assert.ok(test.get('error.delimeter') === '(!)');
                assert.ok(test.get('log.stdout') === 'N');
                assert.ok(test.get('portalrolemembership.bb.controlled.fields') === '');
                assert.ok(test.get('membership.datasource.key') === '');
                assert.ok(test.get('reconcile') === 'Y');
                break;

            case 'LotsOfScapedCharacters.properties':
                assert.ok(test.length() === 6);
                assert.ok(test.get('escaped key!=:# is __good') === 'And must work as "escaped key!=:# is __good"');
                assert.ok(test.get('key with spaces') === "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
                assert.ok(test.get('key\\with\\slashes') === 'value');
                assert.ok(test.get('multiline.values') === "line 1\nline 2\nline 3\nline 4\\");
                assert.ok(test.get('multiplebackslashes') === '\\\\\\value\\\\');
                assert.ok(test.get('multiline.backslashes') === "value\n\n\\value");
                break;

            case 'MidSizeInternationalizedFile7KeysLotsOfText.properties':
                assert.ok(test.length() === 7);
                assert.ok(test.get('featureName') === 'Spring Dashboard (optional)');
                assert.ok(test.get('providerName') === 'Pivotal Software, Inc.');
                assert.ok(test.get('updateSiteName') === 'Eclipse Integration Commons Updates');
                assert.ok(test.get('description') === 'This feature provides the STS dashboard for displaying RSS feeds and the extensions page');
                assert.ok(test.get('copyright') === 'Copyright (c) 2015, 2016 Pivotal Software, Inc.');
                assert.ok(test.get('licenseUrl') === 'open_source_licenses.txt');
                break;

            case 'MultipleKeysWithDifferentSpaces.properties':
                assert.ok(test.length() === 4);
                assert.ok(test.get('keyname') === 'value');
                assert.ok(test.get('keyname2') === 'value2');
                assert.ok(test.get('key3') === 'value3');
                assert.ok(test.get('key4') === 'value4');
                break;

            case 'VietnameseAndJapaneseCharacters.properties':
                assert.ok(test.length() === 11);
                assert.ok(test.get('Currency_Converter') === 'Chuyen doi tien te  ');
                assert.ok(test.get('Enter_Amount') === 'Nhập vào số lượng  ');
                assert.ok(test.get('Target_Currency') === 'Đơn vị chuyển  ');
                assert.ok(test.get('Alert_Mess') === 'Vui lòng nhập một số hợp lệ  ');
                assert.ok(test.get('Alert_Title') === 'Thong bao ');
                assert.ok(test.get('SOME_CHINESE_TEXT') === '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
                assert.ok(test.get('SOME_JAPANESE_TEXT') === '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
                assert.ok(test.get('SOME_JAPANESE_TEXT_WITH_MULTILINES') === "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");
                break;

            case 'BigFile-5000Lines.properties':
                assert.ok(test.length() === 5000);
                assert.ok(test.get('0') === 'value-0');
                assert.ok(test.get('789') === 'value-789');
                assert.ok(test.get('1240') === 'value-1240');
                assert.ok(test.get('3450') === 'value-3450');
                assert.ok(test.get('4999') === 'value-4999');
                break;

            case 'BigFile-15000Lines.properties':
                assert.ok(test.length() === 15000);
                assert.ok(test.get('0') === 'value-0');
                assert.ok(test.get('1789') === 'value-1789');
                assert.ok(test.get('5240') === 'value-5240');
                assert.ok(test.get('10450') === 'value-10450');
                assert.ok(test.get('14999') === 'value-14999');
                break;

            default:
                assert.ok(false, file + ' Was not tested');
                break;
        }
    }

    // Test exceptions
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            new JavaPropertiesObject(wrongValues[i]);
        }, /invalid properties format|value must be a string/g);
    }
});


/**
 * testIsJavaProperties
 */
QUnit.test("testIsJavaProperties", function(assert){

    assert.notOk(JavaPropertiesObject.isJavaProperties(null));
    assert.ok(JavaPropertiesObject.isJavaProperties(''));
    assert.notOk(JavaPropertiesObject.isJavaProperties([]));
    assert.notOk(JavaPropertiesObject.isJavaProperties({}));
    assert.notOk(JavaPropertiesObject.isJavaProperties('     '));
    assert.notOk(JavaPropertiesObject.isJavaProperties("\n\n\n"));
    assert.notOk(JavaPropertiesObject.isJavaProperties(0));

    assert.ok(JavaPropertiesObject.isJavaProperties(new JavaPropertiesObject()));
    assert.ok(JavaPropertiesObject.isJavaProperties(new JavaPropertiesObject('')));

    // Test ok values
    assert.ok(JavaPropertiesObject.isJavaProperties('key='));
    assert.ok(JavaPropertiesObject.isJavaProperties('key:'));
    assert.ok(JavaPropertiesObject.isJavaProperties('key=value'));
    assert.ok(JavaPropertiesObject.isJavaProperties('key:value'));

    for (var file of propertiesFiles) {

        var fileData = propertiesFilesData[propertiesFiles.indexOf(file)];
        var test = new JavaPropertiesObject(fileData);
        
        assert.ok(JavaPropertiesObject.isJavaProperties(fileData));
        assert.ok(JavaPropertiesObject.isJavaProperties(test));
    }

    // Test wrong values
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.notOk(JavaPropertiesObject.isJavaProperties(wrongValues[i]));
    }

    // Test exceptions
    // Already tested at wrong values
});


/**
 * testIsEqualTo
 */
QUnit.test("testIsEqualTo", function(assert){

    // Test empty values
    var properties = new JavaPropertiesObject();

    assert.ok(properties.isEqualTo(''));
    assert.ok(properties.isEqualTo(new JavaPropertiesObject()));

    assert.throws(function() {
        properties.isEqualTo(null);
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        properties.isEqualTo([]);
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        properties.isEqualTo({});
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        properties.isEqualTo(0);
    }, /properties does not contain valid java properties data/);

    // Test ok values
    for (var file of propertiesFiles) {

        var fileData = propertiesFilesData[propertiesFiles.indexOf(file)];
        properties = new JavaPropertiesObject(fileData);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(properties.length() < 1000){

            assert.ok(properties.isEqualTo(fileData));
            assert.ok(properties.isEqualTo(properties));
        }
    }

    // Test wrong values
    properties = new JavaPropertiesObject();

    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            properties.isEqualTo(wrongValues[i]);
        }, /properties does not contain valid java properties data/);
    }

    properties = new JavaPropertiesObject('key1=v1');
    assert.notOk(properties.isEqualTo('key2=v1'));

    properties = new JavaPropertiesObject('key1=v1');
    assert.notOk(properties.isEqualTo('key1=v2'));

    properties = new JavaPropertiesObject('key1=v1');
    assert.notOk(properties.isEqualTo("key1=v1\nkey2=v2"));

    // Test exceptions
    // Already tested at wrong values
});


/**
 * testToString
 */
QUnit.test("testToString", function(assert){

    // Test empty values
    var test = new JavaPropertiesObject();
    assert.ok(test.toString() === '');

    test = new JavaPropertiesObject('');
    assert.ok(test.toString() === '');

    // Test ok values
    for (var file of propertiesFiles) {

        var fileData = propertiesFilesData[propertiesFiles.indexOf(file)];
        
        test = new JavaPropertiesObject(fileData);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(test.length() < 1000){
            
            assert.ok(test.isEqualTo(test.toString(), true));
        }
    }

    // Test wrong values
    // Already tested at constructor test

    // Test exceptions
    // Already tested at constructor test
});