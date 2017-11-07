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
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\model\XMLObject;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\utils\SerializationUtils;




// ******************************************************************************************************************
// TODO - NOT WORKING!!!!!!!!!!
// TODO - This tests must be fully reviewed to synchronize them with the typescript version
// ******************************************************************************************************************





/**
 * XMLObjectTest
 *
 * @return void
 */
class XMLObjectTest extends TestCase {


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->markTestIncomplete('This test has not been implemented yet.');

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/xmlObject';

        $this->xmlFiles = [];
        $files = $this->filesManager->getDirectoryList($this->basePath);

        foreach ($files as $file) {

            if($file === 'ValidDocumentsAndValues.csv'){

                $this->okValues = SerializationUtils::stringToCSVObject($this->filesManager->readFile($this->basePath.'/'.$file));

                // TODO - this test is waiting for CSVObject to be ready.
                // Els ok values els agafarem de un fitxer csv que contindra diferents columnes: Document xml, numero de atributs, numero de children, etc... per a que es pugui verificar posteriorment.
            }

            if($file === 'InvalidSingleLineDocuments.txt'){

                $this->wrongValues = StringUtils::getLines($this->filesManager->readFile($this->basePath.'/'.$file));
                $this->wrongValuesCount = count($this->wrongValues);
                // TODO - test invalid characters in node and attributes
            }

            if(StringUtils::getFileExtension($file) === 'xml'){

                $this->xmlFiles[] = $file;
            }
        }
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::tearDownAfterClass()
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

	    try {
	        new XMLObject();
	        $exceptionMessage = 'empty instance did not cause exception';
	    } catch (Exception $e) {
	        // We expect an exception to happen
	    }

	    for ($i = 0; $i < $this->emptyValuesCount; $i++) {

	        try {
	            new XMLObject($this->emptyValues[$i]);
	            $exceptionMessage = 'empty value did not cause exception';
	        } catch (Exception $e) {
	            // We expect an exception to happen
	        }
	    }

        // Test ok values
	    for ($i = 0; $i < $this->okValuesCount; $i++) {

	        $test = new XMLObject($this->okValues[$i]);
	        $this->assertTrue($test->getName() !== '');
	        $this->assertTrue($test->getAttributes()->length() === $test->countAttributes());
	        $this->assertTrue($test->countAttributes() >= 0);
	        $this->assertTrue($test->countChildren() >= 0);
	    }

	    foreach ($this->xmlFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
	        $test = new XMLObject($fileData);
	        $this->assertTrue($test->getName() !== '');
	    }

	    // Test wrong values
	    for ($i = 0; $i < $this->wrongValuesCount; $i++) {

	        try {
	            new XMLObject($this->wrongValues[$i]);
	            $exceptionMessage = $this->wrongValues[$i].'  did not cause exception';
	        } catch (Exception $e) {
	            // We expect an exception to happen
	        }
	    }

	    // Test exceptions
	    // Already tested with wrong values
	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }
	}


	/**
	 * testGetName
	 *
	 * @return void
	 */
	public function testGetName(){

	    // Test empty values
	    // Already tested at constructor test

	    // Test ok values
	    for ($i = 0; $i < $this->okValuesCount; $i++) {
	        // TODO - this test is waiting for CSVObject to be ready.
	        $test = new XMLObject($this->okValues[$i]);
	        $this->assertTrue($test->getName() === $this->okValuesNames[$i]);
	    }

	    foreach ($this->xmlFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
	        $test = new XMLObject($fileData);

	        switch ($file) {

	            case 'BasicNoteData.xml':
	                $this->assertTrue($test->getName() === 'note');
	               break;

	            case 'BooksCatalog.xml':
	                $this->assertTrue($test->getName() === 'catalog');
	                break;

	            case 'CompactDiscCatalog.xml':
	                $this->assertTrue($test->getName() === 'CATALOG');
	                break;

	            case 'EmployeesData.xml':
	                $this->assertTrue($test->getName() === 'company');
	                break;

	            case 'EmployeesDataWithAttributes.xml':
	                $this->assertTrue($test->getName() === 'company');
	                break;

	            case 'RootWithStringAsValue.xml':
	                $this->assertTrue($test->getName() === 'note');
	                break;

	            default:
	                $this->assertTrue(false, $file.' is not evaluated. Add a new case to check the root element name');
	               break;
	        }
	    }

	    // Test wrong values
	    // Already tested at constructor test

	    // Test exceptions
	    // Already tested at constructor test
	}


	/**
	 * testGetValue
	 *
	 * @return void
	 */
	public function testGetValue(){

	    // Test empty values
	    // Already tested at constructor test

	    // Test ok values
	    for ($i = 0; $i < $this->okValuesCount; $i++) {
	        // TODO - this test is waiting for CSVObject to be ready.
	        $test = new XMLObject($this->okValues[$i]);
	        $this->assertTrue($test->getValue() === $this->okValuesValues[$i]);
	    }

	    foreach ($this->xmlFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
	        $test = new XMLObject($fileData);

	        switch ($file) {

	            case 'BasicNoteData.xml':
	                $expectedValue = '        ';
	                break;

	            case 'BooksCatalog.xml':
	                $expectedValue = '                                    ';
	                break;

	            case 'CompactDiscCatalog.xml':
	                $expectedValue = '                                                    ';
	                break;

	            case 'EmployeesData.xml':
	                $expectedValue = '                        ';
	                break;

	            case 'EmployeesDataWithAttributes.xml':
	                $expectedValue = '                        ';
	                break;

	            case 'RootWithStringAsValue.xml':
	                $expectedValue = 'this is some textual value for the note node element';
	                break;

	            default:
	                $this->assertTrue(false, $file.' is not evaluated. Add a new case to check the root element name');
	                break;
	        }

	        $this->assertEquals($expectedValue, StringUtils::removeNewLineCharacters($test->getValue()), $file.' file value expected fail');
	    }

	    // Test wrong values
	    // Already tested at constructor test

	    // Test exceptions
	    // Already tested at constructor test
	}


	/**
	 * testAttributesCount
	 *
	 * @return void
	 */
	public function testAttributesCount(){

	    // Test empty values
	    // Already tested at constructor test

	    // Test ok values
	    for ($i = 0; $i < $this->okValuesCount; $i++) {
	        // TODO - this test is waiting for CSVObject to be ready.
	        $test = new XMLObject($this->okValues[$i]);
	        $this->assertTrue($test->countAttributes() === $this->okValuesAttributesCount[$i]);
	    }

	    foreach ($this->xmlFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
	        $test = new XMLObject($fileData);

	        switch ($file) {

	            case 'BasicNoteData.xml':
	                $expectedValue = 0;
	                break;

	            case 'BooksCatalog.xml':
	                $expectedValue = 0;
	                break;

	            case 'CompactDiscCatalog.xml':
	                $expectedValue = 0;
	                break;

	            case 'EmployeesData.xml':
	                $expectedValue = 0;
	                break;

	            case 'EmployeesDataWithAttributes.xml':
	                $expectedValue = 3;
	                break;

	            case 'RootWithStringAsValue.xml':
	                $expectedValue = 0;
	                break;

	            default:
	                $this->assertTrue(false, $file.' is not evaluated. Add a new case to check the root element name');
	                break;
	        }

	        $this->assertEquals($expectedValue, $test->countAttributes(), $file.' file value expected fail');
	    }

	    // Test wrong values
	    // Already tested at constructor test

	    // Test exceptions
	    // Already tested at constructor test
	}


	/**
	 * testTodo
	 *
	 * @return void
	 */
	public function testTodo2(){

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