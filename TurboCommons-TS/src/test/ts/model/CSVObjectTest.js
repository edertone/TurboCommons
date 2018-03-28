"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("CSVObjectTest", {

    before : function(assert){

        window.StringUtils = org_turbocommons.StringUtils;
        window.basePath = './resources/model/csvObject/';

        var httpManager = new org_turbocommons.HTTPManager();

        // Load all the csv files
        var done = assert.async();
        
        httpManager.loadAllResourcesFromList(basePath + '_folder-list.txt', basePath,
            function(resourcesList, resourcesData){
        
                window.csvFiles = resourcesList;  
                window.csvFilesData = resourcesData;
                window.csvFilesCount = resourcesList.length;
                done();
                
            }, function(errorUrl, errorMsg, errorCode){
                
                assert.ok(false, 'Error loading file ' + errorUrl);
                done();
            });
    },

    beforeEach : function(){

        window.CSVObject = org_turbocommons.CSVObject;

        window.emptyValues = [null, [], {}, 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.wrongValues = [123, [1, 2, 3], ['asdf'], new Error()];
        window.wrongValuesCount = window.wrongValues.length;
    },

    afterEach : function(){

        delete window.CSVObject;

        delete window.emptyValues;
        delete window.emptyValuesCount;

        delete window.wrongValues;
        delete window.wrongValuesCount;
    },

    after : function(){

        delete window.StringUtils;
        delete window.basePath;

        delete window.csvFiles;
        delete window.csvFilesData;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new CSVObject();
    assert.strictEqual(0, test.countColumns());
    assert.strictEqual(0, test.countRows());

    test = new CSVObject('');
    assert.strictEqual(0, test.countColumns());
    assert.strictEqual(0, test.countRows());

    test = new CSVObject('     ');
    assert.strictEqual(0, test.countColumns());
    assert.strictEqual(0, test.countRows());

    test = new CSVObject("\n\n\n");
    assert.strictEqual(0, test.countColumns());
    assert.strictEqual(0, test.countRows());

    for (var i = 0; i < emptyValuesCount; i++) {

        assert.throws(function() {
            new CSVObject(emptyValues[i]);
        }, /constructor expects a string value/);
    }

    // Test ok values

    // Single value csv
    test = new CSVObject('value');
    assert.strictEqual('value', test.getCell(0, 0));
    assert.strictEqual(1, test.countRows());
    assert.strictEqual(1, test.countColumns());
    assert.strictEqual('value', test.getCell(0, 0));
    assert.ok(test.isEqualTo('value'));

    // Simple one row empty csv
    test = new CSVObject(',"",');
    assert.strictEqual('', test.getCell(0, 0));
    assert.strictEqual('', test.getCell(0, 1));
    assert.strictEqual('', test.getCell(0, 2));
    assert.ok(test.isEqualTo(',,'));

    // Simple one row empty csv with headers
    test = new CSVObject("c1,c2,c3\r\n,,", true);
    assert.strictEqual('', test.getCell(0, 'c1'));
    assert.strictEqual('', test.getCell(0, 'c2'));
    assert.strictEqual('', test.getCell(0, 'c3'));
    assert.strictEqual('', test.getCell(0, 0));
    assert.strictEqual('', test.getCell(0, 1));
    assert.strictEqual('', test.getCell(0, 2));
    assert.ok(test.isEqualTo("c1,\"c2\",c3\n,,"));

    // Simple one row csv without headers
    test = new CSVObject('a,b,c');
    assert.strictEqual('a', test.getCell(0, 0));
    assert.strictEqual('b', test.getCell(0, 1));
    assert.strictEqual('c', test.getCell(0, 2));
    assert.ok(test.isEqualTo('a,b,c'));

    // Simple one row csv with headers
    test = new CSVObject("c1,c2,c3\n1,2,3", true);
    assert.strictEqual('1', test.getCell(0, 'c1'));
    assert.strictEqual('2', test.getCell(0, 'c2'));
    assert.strictEqual('3', test.getCell(0, 'c3'));
    assert.strictEqual('1', test.getCell(0, 0));
    assert.strictEqual('2', test.getCell(0, 1));
    assert.strictEqual('3', test.getCell(0, 2));
    assert.ok(test.isEqualTo("c1,c2,c3\r\n1,2,3"));

    // Simple one row csv without headers and scaped fields
    test = new CSVObject('"a","b","c"');
    assert.strictEqual('a', test.getCell(0, 0));
    assert.strictEqual('b', test.getCell(0, 1));
    assert.strictEqual('c', test.getCell(0, 2));
    assert.ok(test.isEqualTo('a,b,c'));

    // Simple one row csv with headers and scaped fields
    test = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
    assert.strictEqual('a', test.getCell(0, 'c1'));
    assert.strictEqual('b', test.getCell(0, 'c2'));
    assert.strictEqual('c', test.getCell(0, 'c3'));
    assert.strictEqual('a', test.getCell(0, 0));
    assert.strictEqual('b', test.getCell(0, 1));
    assert.strictEqual('c', test.getCell(0, 2));
    assert.ok(test.isEqualTo("c1,c2,c3\na,b,c"));

    // Simple csv without headers and edge cases
    test = new CSVObject(' a ,b  ,c  ');
    assert.strictEqual(' a ', test.getCell(0, 0));
    assert.strictEqual('b  ', test.getCell(0, 1));
    assert.strictEqual('c  ', test.getCell(0, 2));
    assert.ok(test.isEqualTo(' a ,"b  ",c  '));

    // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
    test = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
    assert.strictEqual('1', test.getCell(0, 0));
    assert.strictEqual('2', test.getCell(0, 1));
    assert.strictEqual('3', test.getCell(0, 2));
    assert.strictEqual('a', test.getCell(1, 0));
    assert.strictEqual('b', test.getCell(1, 1));
    assert.strictEqual('c', test.getCell(1, 2));
    assert.strictEqual('4', test.getCell(2, 0));
    assert.strictEqual('5', test.getCell(2, 1));
    assert.strictEqual('6', test.getCell(2, 2));
    assert.ok(test.isEqualTo("1,2,3\na,b,c\r\r4,5,6\r\n"));
    assert.ok(test.countColumns() === 3);
    assert.ok(test.countRows() === 3);

    // Simple csv without headers and scaped fields and characters with edge cases
    test = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
    assert.strictEqual('"" 1', test.getCell(0, 0));
    assert.strictEqual(',,,2', test.getCell(0, 1));
    assert.strictEqual('3', test.getCell(0, 2));
    assert.strictEqual('4,', test.getCell(0, 3));
    assert.strictEqual('5 ', test.getCell(0, 4));
    assert.ok(test.isEqualTo('""""" 1",",,,2","3","4,","5 "'));

    // Simple two row csv without headers and scaped fields and characters
    test = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
    assert.strictEqual('1', test.getCell(0, 0));
    assert.strictEqual('2', test.getCell(0, 1));
    assert.strictEqual('3', test.getCell(0, 2));
    assert.strictEqual('a"a', test.getCell(1, 0));
    assert.strictEqual('b', test.getCell(1, 1));
    assert.strictEqual('c', test.getCell(1, 2));
    assert.ok(test.isEqualTo("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));

    // Simple two row csv with headers and mixed scaped and non scaped fields and characters
    test = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
    assert.strictEqual(['c1', 'c,"2', 'c3'], test.getColumnNames());
    assert.strictEqual('1', test.getCell(0, 'c1'));
    assert.strictEqual('2', test.getCell(0, 'c,"2'));
    assert.strictEqual(' 3 ', test.getCell(0, 'c3'));
    assert.strictEqual('1', test.getCell(0, 0));
    assert.strictEqual('2', test.getCell(0, 1));
    assert.strictEqual(' 3 ', test.getCell(0, 2));
    assert.strictEqual('a ",a', test.getCell(1, 'c1'));
    assert.strictEqual('b', test.getCell(1, 'c,"2'));
    assert.strictEqual('c', test.getCell(1, 'c3'));
    assert.strictEqual('a ",a', test.getCell(1, 0));
    assert.strictEqual('b', test.getCell(1, 1));
    assert.strictEqual('c', test.getCell(1, 2));
    assert.ok(test.isEqualTo("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

    // test csv files from resources
    // Expected values are expected to be stored on a properties file for each one of the csv files.
    // It must have exactly the same name but with .properties extension
    for (var file of csvFiles) {
        
        var fileData = csvFilesData[csvFiles.indexOf(file)];
        
        var test = new CSVObject(fileData, StringUtils.countStringOccurences(file, 'WithHeader') === 1);

        var resultFile = StringUtils.getFileNameWithoutExtension(file) + '.properties';
        //var resultData = new JavaPropertiesObject($this->filesManager->readFile($this->basePath.'/'.$resultFile));

        assert.strictEqual(resultData.get('rows'), test.countRows(), 'File: '  + file);
        assert.strictEqual(resultData.get('cols'), test.countColumns(), 'File: ' + file);

        for (var key of resultData.getKeys()) {

            if(key !== 'rows' && key !== 'cols'){

                //$rowCol = explode('-', $key, 2);

                //$columnFormatted = NumericUtils::isNumeric($rowCol[1]) ? (int)$rowCol[1] : $rowCol[1];

                //$expected = $resultData->get($key);
                //$value = test.getCell((int)$rowCol[0], $columnFormatted);

                //assert.strictEqual($expected, $value, 'File: '.$file.' row and col: '.$key);
            }
        }
    }

    // Test wrong values
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            new CSVObject(wrongValues[i]);
        }, /constructor expects a string value/);
    }

    // Test exceptions
    // Already tested
});