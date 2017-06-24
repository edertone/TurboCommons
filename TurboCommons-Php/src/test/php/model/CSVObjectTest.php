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

        $this->emptyValues = [null, [], new stdClass(), 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/csvObject';

        $this->csvFiles = [];
        $files = $this->filesManager->getDirectoryList($this->basePath);

        foreach ($files as $file) {

            if(StringUtils::getFileExtension($file) === 'csv'){

                $this->csvFiles[] = $file;
            }
        }
    }


    /**
     * @see PHPUnit_Framework_TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Nothing necessary here
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
	    $exceptionMessage = '';

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
                $exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Exception $e) {
                // We expect an exception to happen
            }
	    }

	    // Test ok values

	    // Simple one row empty csv
	    $test = new CSVObject(',,');
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
	    $this->assertTrue($test->isEqualTo("c1,c2,c3\n,,"));

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
	    $this->assertTrue($test->isEqualTo('c1,c2,c3\na,b,c'));

	    // Simple csv without headers and edge cases
	    $test = new CSVObject(' a ,b  ,c  ');
	    $this->assertEquals(' a ', $test->getCell(0, 0));
	    $this->assertEquals('b  ', $test->getCell(0, 1));
	    $this->assertEquals('c  ', $test->getCell(0, 2));
	    $this->assertTrue($test->isEqualTo(' a ,b  ,c  '));

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
	    // TODO

	    // Test exceptions
	    // TODO

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}


	/**
	 * testSetCell
	 *
	 * @return void
	 */
	public function testSetCell(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}


	/**
	 * testIsCSV
	 *
	 * @return void
	 */
	public function testIsCSV(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}


	/**
	 * testToString
	 *
	 * @return void
	 */
	public function testToString(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}

}

?>