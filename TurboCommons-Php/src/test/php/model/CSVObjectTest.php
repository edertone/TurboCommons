<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use Exception;
use PHPUnit_Framework_TestCase;
use stdClass;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\model\CSVObject;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * CSVObjectTest
 *
 * @return void
 */
class CSVObjectTest extends PHPUnit_Framework_TestCase {


    /**
     * @see PHPUnit_Framework_TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see PHPUnit_Framework_TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';

        $this->emptyValues = [null, [], new stdClass(), 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->wrongValues = [123, [1, 2, 3], ['asdf'], new Exception()];
        $this->wrongValuesCount = count($this->wrongValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/csvObject';

        $this->csvFiles = [];
        $files = $this->filesManager->getDirectoryList($this->basePath);

        foreach ($files as $file) {

            if(StringUtils::getFileExtension($file) === 'csv'){

                $this->csvFiles[] = $file;
            }
        }

        $this->csvFilesCount = count($this->csvFiles);
    }


    /**
     * @see PHPUnit_Framework_TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
    }


    /**
     * @see PHPUnit_Framework_TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


	/**
	 * testConstruct
	 *
	 * @return void
	 */
	public function testConstruct(){

	    // Test empty values
	    $test = new CSVObject();
        $this->assertEquals(0, $test->countColumns());
        $this->assertEquals(0, $test->countRows());

        $test = new CSVObject('');
        $this->assertEquals(0, $test->countColumns());
        $this->assertEquals(0, $test->countRows());

        $test = new CSVObject('     ');
        $this->assertEquals(0, $test->countColumns());
        $this->assertEquals(0, $test->countRows());

        $test = new CSVObject("\n\n\n");
        $this->assertEquals(0, $test->countColumns());
        $this->assertEquals(0, $test->countRows());

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                new CSVObject($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Exception $e) {
                // We expect an exception to happen
            }
	    }

	    // Test ok values

	    // Single value csv
	    $test = new CSVObject('value');
	    $this->assertEquals('value', $test->getCell(0, 0));
	    $this->assertEquals(1, $test->countRows());
	    $this->assertEquals(1, $test->countColumns());
	    $this->assertEquals('value', $test->getCell(0, 0));
	    $this->assertTrue($test->isEqualTo('value'));

	    // Simple one row empty csv
	    $test = new CSVObject(',"",');
	    $this->assertEquals('', $test->getCell(0, 0));
	    $this->assertEquals('', $test->getCell(0, 1));
	    $this->assertEquals('', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo(',,'));

	    // Simple one row empty csv with headers
	    $test = new CSVObject("c1,c2,c3\r\n,,", true);
	    $this->assertEquals('', $test->getCell(0, 'c1'));
	    $this->assertEquals('', $test->getCell(0, 'c2'));
	    $this->assertEquals('', $test->getCell(0, 'c3'));
	    $this->assertEquals('', $test->getCell(0, 0));
	    $this->assertEquals('', $test->getCell(0, 1));
	    $this->assertEquals('', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo("c1,\"c2\",c3\n,,"));

	    // Simple one row csv without headers
	    $test = new CSVObject('a,b,c');
	    $this->assertEquals('a', $test->getCell(0, 0));
	    $this->assertEquals('b', $test->getCell(0, 1));
	    $this->assertEquals('c', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo('a,b,c'));

	    // Simple one row csv with headers
	    $test = new CSVObject("c1,c2,c3\n1,2,3", true);
	    $this->assertEquals('1', $test->getCell(0, 'c1'));
	    $this->assertEquals('2', $test->getCell(0, 'c2'));
	    $this->assertEquals('3', $test->getCell(0, 'c3'));
	    $this->assertEquals('1', $test->getCell(0, 0));
	    $this->assertEquals('2', $test->getCell(0, 1));
	    $this->assertEquals('3', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo("c1,c2,c3\r\n1,2,3"));

	    // Simple one row csv without headers and scaped fields
	    $test = new CSVObject('"a","b","c"');
	    $this->assertEquals('a', $test->getCell(0, 0));
	    $this->assertEquals('b', $test->getCell(0, 1));
	    $this->assertEquals('c', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo('a,b,c'));

	    // Simple one row csv with headers and scaped fields
	    $test = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
	    $this->assertEquals('a', $test->getCell(0, 'c1'));
	    $this->assertEquals('b', $test->getCell(0, 'c2'));
	    $this->assertEquals('c', $test->getCell(0, 'c3'));
	    $this->assertEquals('a', $test->getCell(0, 0));
	    $this->assertEquals('b', $test->getCell(0, 1));
	    $this->assertEquals('c', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo("c1,c2,c3\na,b,c"));

	    // Simple csv without headers and edge cases
	    $test = new CSVObject(' a ,b  ,c  ');
	    $this->assertEquals(' a ', $test->getCell(0, 0));
	    $this->assertEquals('b  ', $test->getCell(0, 1));
	    $this->assertEquals('c  ', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo(' a ,"b  ",c  '));

	    // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
	    $test = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
	    $this->assertEquals('1', $test->getCell(0, 0));
	    $this->assertEquals('2', $test->getCell(0, 1));
	    $this->assertEquals('3', $test->getCell(0, 2));
	    $this->assertEquals('a', $test->getCell(1, 0));
	    $this->assertEquals('b', $test->getCell(1, 1));
	    $this->assertEquals('c', $test->getCell(1, 2));
	    $this->assertEquals('4', $test->getCell(2, 0));
	    $this->assertEquals('5', $test->getCell(2, 1));
	    $this->assertEquals('6', $test->getCell(2, 2));
	    $this->assertTrue($test->isEqualTo("1,2,3\na,b,c\r\r4,5,6\r\n"));
	    $this->assertTrue($test->countColumns() === 3);
	    $this->assertTrue($test->countRows() === 3);

	    // Simple csv without headers and scaped fields and characters with edge cases
	    $test = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
	    $this->assertEquals('"" 1', $test->getCell(0, 0));
	    $this->assertEquals(',,,2', $test->getCell(0, 1));
	    $this->assertEquals('3', $test->getCell(0, 2));
	    $this->assertEquals('4,', $test->getCell(0, 3));
	    $this->assertEquals('5 ', $test->getCell(0, 4));
	    $this->assertTrue($test->isEqualTo('""""" 1",",,,2","3","4,","5 "'));

	    // Simple two row csv without headers and scaped fields and characters
	    $test = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
	    $this->assertEquals('1', $test->getCell(0, 0));
	    $this->assertEquals('2', $test->getCell(0, 1));
	    $this->assertEquals('3', $test->getCell(0, 2));
	    $this->assertEquals('a"a', $test->getCell(1, 0));
	    $this->assertEquals('b', $test->getCell(1, 1));
	    $this->assertEquals('c', $test->getCell(1, 2));
	    $this->assertTrue($test->isEqualTo("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));

	    // Simple two row csv with headers and mixed scaped and non scaped fields and characters
	    $test = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
	    $this->assertEquals(['c1', 'c,"2', 'c3'], $test->getColumnNames());
	    $this->assertEquals('1', $test->getCell(0, 'c1'));
	    $this->assertEquals('2', $test->getCell(0, 'c,"2'));
	    $this->assertEquals(' 3 ', $test->getCell(0, 'c3'));
	    $this->assertEquals('1', $test->getCell(0, 0));
	    $this->assertEquals('2', $test->getCell(0, 1));
	    $this->assertEquals(' 3 ', $test->getCell(0, 2));
	    $this->assertEquals('a ",a', $test->getCell(1, 'c1'));
	    $this->assertEquals('b', $test->getCell(1, 'c,"2'));
	    $this->assertEquals('c', $test->getCell(1, 'c3'));
	    $this->assertEquals('a ",a', $test->getCell(1, 0));
	    $this->assertEquals('b', $test->getCell(1, 1));
	    $this->assertEquals('c', $test->getCell(1, 2));
	    $this->assertTrue($test->isEqualTo("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

	    // test csv files from resources
	    // Expected values are expected to be stored on a properties file for each one of the csv files.
	    // It must have exactly the same name but with .properties extension
	    foreach ($this->csvFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);

            $test = new CSVObject($fileData, StringUtils::countStringOccurences($file, 'WithHeader') === 1);

            $resultFile = StringUtils::getFileNameWithoutExtension($file).'.properties';
            $resultData = new JavaPropertiesObject($this->filesManager->readFile($this->basePath.'/'.$resultFile));

            $this->assertEquals($resultData->get('rows'), $test->countRows(), 'File: '.$file);
            $this->assertEquals($resultData->get('cols'), $test->countColumns(), 'File: '.$file);

            foreach ($resultData->getKeys() as $key) {

                if($key !== 'rows' && $key !== 'cols'){

                    $rowCol = explode('-', $key, 2);

                    $columnFormatted = NumericUtils::isNumeric($rowCol[1]) ? (int)$rowCol[1] : $rowCol[1];

                    $expected = $resultData->get($key);
                    $value = $test->getCell((int)$rowCol[0], $columnFormatted);

                    $this->assertEquals($expected, $value, 'File: '.$file.' row and col: '.$key);
                }
            }
	    }

	    // Test wrong values
	    for ($i = 0; $i < $this->wrongValuesCount; $i++) {

	        try {
	            new CSVObject($this->wrongValues[$i]);
	            $this->exceptionMessage = $this->wrongValues[$i].' wrong value did not cause exception';
	        } catch (Exception $e) {
	            // We expect an exception to happen
	        }
	    }

	    // Test exceptions
	    // Already tested
	}


	/**
	 * testSetCell
	 *
	 * @return void
	 */
	public function testSetCell(){

	    // Test empty values
	    $test = new CSVObject();
	    $test->addColumns(5);
	    $test->addRows(5);

	    $this->assertTrue($test->getCell(0, 0) === null);
	    $this->assertTrue($test->setCell(0, 0, '') === '');
	    $this->assertTrue($test->getCell(0, 0) === '');

	    for ($i = 0; $i < $this->emptyValuesCount; $i++) {

	        try {
	            $test->setCell(0, 0, $this->emptyValues[$i]);
	            $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
	        } catch (Exception $e) {
	            // We expect an exception to happen
	        }
	    }

	    // Test ok values
	    $this->assertTrue($test->getCell(0, 2) === null);
	    $this->assertTrue($test->setCell(0, 2, 'somevalue') === 'somevalue');
	    $this->assertTrue($test->getCell(0, 2) === 'somevalue');

	    $this->assertTrue($test->getCell(0, 4) === null);
	    $this->assertTrue($test->setCell(0, 4, 'somevalue4') === 'somevalue4');
	    $this->assertTrue($test->getCell(0, 4) === 'somevalue4');

	    $this->assertTrue($test->getCell(2, 0) === null);
	    $this->assertTrue($test->setCell(2, 0, '2-0') === '2-0');
	    $this->assertTrue($test->getCell(2, 0) === '2-0');

	    $this->assertTrue($test->getCell(2, 2) === null);
	    $this->assertTrue($test->setCell(2, 2, '2-2') === '2-2');
	    $this->assertTrue($test->getCell(2, 2) === '2-2');

	    $this->assertTrue($test->getCell(4, 4) === null);
	    $this->assertTrue($test->setCell(4, 4, '4-4') === '4-4');
	    $this->assertTrue($test->getCell(4, 4) === '4-4');

	    // Test wrong values
	    try {
	        $test->setCell(-1, 0, '');
	        $this->exceptionMessage = '-1,0 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->setCell(10, 0, '');
	        $this->exceptionMessage = '10,0 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->setCell(0, -1, '');
	        $this->exceptionMessage = '0,-1 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->setCell(0, 10, '');
	        $this->exceptionMessage = '0,10 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->setCell(0, 0, 10);
	        $this->exceptionMessage = '10 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->setCell(0, 0, new stdClass());
	        $this->exceptionMessage = 'new stdClass() value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    // Test exceptions
	    // Already tested
	}


	/**
	 * testIsCSV
	 *
	 * @return void
	 */
	public function testIsCSV(){

	    // Test empty values
	    $this->assertFalse(CSVObject::isCSV(null));
	    $this->assertTrue(CSVObject::isCSV(''));
	    $this->assertFalse(CSVObject::isCSV(0));
	    $this->assertFalse(CSVObject::isCSV([]));
	    $this->assertFalse(CSVObject::isCSV(new stdClass()));
	    $this->assertTrue(CSVObject::isCSV('     '));
	    $this->assertTrue(CSVObject::isCSV("\n\n\n"));

	    // Test ok values
	    $this->assertTrue(CSVObject::isCSV('value'));
	    $this->assertTrue(CSVObject::isCSV(',,'));
	    $this->assertTrue(CSVObject::isCSV("c1,c2,c3\r\n,,"));
	    $this->assertTrue(CSVObject::isCSV('a,b,c'));
	    $this->assertTrue(CSVObject::isCSV("c1,c2,c3\n1,2,3"));
	    $this->assertTrue(CSVObject::isCSV('"a","b","c"'));
	    $this->assertTrue(CSVObject::isCSV("c1,c2,c3\r\"a\",\"b\",\"c\""));
	    $this->assertTrue(CSVObject::isCSV(' a ,b  ,c  '));
	    $this->assertTrue(CSVObject::isCSV("1,2,3\na,b,c\r\n4,5,6\r"));
	    $this->assertTrue(CSVObject::isCSV(' """"" 1",",,,2",    "3", "4,"   ,  "5 " '));
	    $this->assertTrue(CSVObject::isCSV("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));
	    $this->assertTrue(CSVObject::isCSV("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

	    foreach ($this->csvFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);

	        $this->assertTrue(CSVObject::isCSV($fileData));
	    }

	    // Test wrong values
	    $this->assertFalse(CSVObject::isCSV(12));
	    $this->assertFalse(CSVObject::isCSV([1,4,5,6]));
	    $this->assertFalse(CSVObject::isCSV(['  ']));
	    $this->assertFalse(CSVObject::isCSV(new Exception()));
	    $this->assertFalse(CSVObject::isCSV(-1909));

	    // Test exceptions
	    // Not necessary
	}


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

	    // Test empty values
	    $test = new CSVObject();

	    $this->assertTrue($test->isEqualTo(''));
	    $this->assertTrue($test->isEqualTo(new CSVObject()));

	    try {
	        $test->isEqualTo(null);
	        $this->exceptionMessage = 'null value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->isEqualTo([]);
	        $exceptionMessage = '[] value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->isEqualTo(new stdClass());
	        $exceptionMessage = 'new stdClass() value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $test->isEqualTo(0);
	        $exceptionMessage = '0 value did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    // Test ok and wrong values
	    for ($i = 1; $i < $this->csvFilesCount; $i++) {

	        if($i == 1){

	            $previousFileData = $this->filesManager->readFile($this->basePath.'/'.$this->csvFiles[$i-1]);
	            $previousTest = new CSVObject($previousFileData, StringUtils::countStringOccurences($this->csvFiles[$i-1], 'WithHeader') === 1);

	        }else{

	            $previousFileData = $fileData;
	            $previousTest = $test;
	        }

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$this->csvFiles[$i]);
	        $test = new CSVObject($fileData, StringUtils::countStringOccurences($this->csvFiles[$i], 'WithHeader') === 1);

	        // TODO - This is added for performance reasons. If performance is improved on
	        // isEqualTo method, this constraint can be removed
	        if($test->countRows() < 1000 && $previousTest->countRows() < 1000){

	            $this->assertTrue($test->isEqualTo($fileData));
	            $this->assertTrue($test->isEqualTo($test));

	            $this->assertFalse($test->isEqualTo($previousFileData));
	            $this->assertFalse($test->isEqualTo($previousTest));
	        }
        }

	    // Test exceptions
        try {
            $test->isEqualTo(123234);
            $exceptionMessage = '123234 value did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            $test->isEqualTo([1,'dfgdfg']);
            $exceptionMessage = '[1,"dfgdfg"] value did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            $test->isEqualTo(new Exception());
            $exceptionMessage = 'new Exception() value did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }
	}


	/**
	 * testToString
	 *
	 * @return void
	 */
	public function testToString(){

	    // Test empty values
	    $test = new CSVObject();
	    $this->assertTrue($test->toString() === '');

	    $test = new CSVObject('');
	    $this->assertTrue($test->toString() === '');

	    $test = new CSVObject('      ');
	    $this->assertTrue($test->toString() === '');

	    $test = new CSVObject("\n\n\n\n");
	    $this->assertTrue($test->toString() === '');

	    $test = new CSVObject("\r\n\r\n\r\n\r\n");
	    $this->assertTrue($test->toString() === '');

	    // Test ok values

	    // Single value csv
	    $test = new CSVObject('value');
	    $this->assertEquals('value', $test->toString());

	    // Simple one row empty csv
	    $test = new CSVObject(',,');
	    $this->assertEquals(',,', $test->toString());

	    // Simple one row empty csv with headers
	    $test = new CSVObject("c1,c2,c3\r\n,,", true);
	    $this->assertEquals("c1,c2,c3\r\n,,", $test->toString());

	    // Simple one row csv without headers
	    $test = new CSVObject('a,b,c');
	    $this->assertEquals('a,b,c', $test->toString());

	    // Simple one row csv with headers
	    $test = new CSVObject("c1,c2,c3\n1,2,3", true);
	    $this->assertEquals("c1,c2,c3\r\n1,2,3", $test->toString());

	    // Simple one row csv without headers and scaped fields
	    $test = new CSVObject('"a","b","c"');
	    $this->assertEquals('a,b,c', $test->toString());

	    // Simple one row csv with headers and scaped fields
	    $test = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
	    $this->assertEquals("c1,c2,c3\r\na,b,c", $test->toString());

	    // Simple csv without headers and edge cases
	    $test = new CSVObject(' a ,b  ,c  ');
	    $this->assertEquals(' a ,b  ,c  ', $test->toString());

	    // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
	    $test = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
	    $this->assertEquals("1,2,3\r\na,b,c\r\n4,5,6", $test->toString());

	    // Simple csv without headers and scaped fields and characters with edge cases
	    $test = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
	    $this->assertEquals('""""" 1",",,,2",3,"4,",5 ', $test->toString());

	    // Simple two row csv without headers and scaped fields and characters
	    $test = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
	    $this->assertEquals("1,2,3\r\n\"a\"\"a\",b,c", $test->toString());

	    // Simple two row csv with headers and mixed scaped and non scaped fields and characters
	    $test = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
	    $this->assertEquals("c1,\"c,\"\"2\",c3\r\n1,2, 3 \r\n\"a \"\",a\",b,c", $test->toString());

	    foreach ($this->csvFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);

	        $test = new CSVObject($fileData, StringUtils::countStringOccurences($file, 'WithHeader') === 1);

	        // TODO - This is added for performance reasons. If performance is improved on
	        // isEqualTo method, this constraint can be removed
	        if($test->countRows() < 1000){

	           $this->assertTrue($test->isEqualTo($test->toString()), $file.' has a problem');
	           $this->assertTrue($test->isEqualTo($test), $file.' has a problem');
	        }
	    }

	    // Test wrong values
	    // Already tested at constructor test

	    // Test exceptions
	    // Already tested at constructor test
	}

}

?>