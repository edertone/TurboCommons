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


// ******************************************************************************************************************
// TODO - IMPORTANT!!!! THE FOLLOWING COMENTED CODE IS THE OLD XMLUTILS TESTS THAT MUST BE ADDED TO THE XMLOBJECT TESTS
// TODO - THE ROADMAP NOW IS TO CREATE THE XMLOBJECT NATIVELY VIA TYPESCRIPT AND THEN TRANSLATE IT TO PHP, SO MAYBE THE FOLLOWING
// TODO - COMMENTED CODE IS OBSOLETE BY THAT MOMENT
// ******************************************************************************************************************



// <?php

// /**
//  * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
//  *
//  * Website : -> http://www.turbocommons.org
//  * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
//  * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
//  * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
//  */

// namespace org\turbocommons\src\test\php\utils;

// use Exception;
// use SimpleXMLElement;
// use PHPUnit\Framework\TestCase;
// use stdClass;
// use org\turbocommons\src\main\php\utils\XmlUtils;
// use org\turbocommons\src\main\php\model\XMLObject;
// use org\turbocommons\src\main\php\managers\FilesManager;


// /**
//  * XmlUtilsTest
//  *
//  * @return void
//  */
// class XmlUtilsTest extends TestCase {


//     /**
//      * testIsXML
//      *
//      * @return void
//      */
//     public function testIsXML(){

//         // TODO
//         return;

//         // Test empty values
//         $this->assertFalse(XmlUtils::isXML(null));
//         $this->assertFalse(XmlUtils::isXML(''));
//         $this->assertFalse(XmlUtils::isXML('   '));
//         $this->assertFalse(XmlUtils::isXML([]));
//         $this->assertFalse(XmlUtils::isXML(new stdClass()));

//         // test correct values
//         $this->assertTrue(XmlUtils::isXML('<root><a/></root>'));
//         $this->assertTrue(XmlUtils::isXML('<root><a><b/><c/></a></root>'));
//         $this->assertTrue(XmlUtils::isXML('<root><c/><a/><b a="1" c="2" b="34"/></root>'));
//         $this->assertTrue(XmlUtils::isXML('<root a="CASE"><a/></root>'));
//         $this->assertTrue(XmlUtils::isXML("<?xml version='1.0'?><root><a>1</a><b>3</b></root>"));
//         $this->assertTrue(XmlUtils::isXML('<root a="1"><!-- test a different comment --></root>'));
//         $this->assertTrue(XmlUtils::isXML(new XMLObject()));
//         $this->assertTrue(XmlUtils::isXML(new XMLObject('<root><a/></root>')));
//         $this->assertTrue(XmlUtils::isXML(new XMLObject('<root><a><b/><c/></a></root>')));

//         // test incorrect values (Note that this method should never throw an exception)
//         $this->assertFalse(XmlUtils::isXML([1,2,3]));
//         $this->assertFalse(XmlUtils::isXML('234234'));
//         $this->assertFalse(XmlUtils::isXML(123123));
//         $this->assertFalse(XmlUtils::isXML(new Exception()));
//         $this->assertFalse(XmlUtils::isXML(new SimpleXMLElement('<a></a>')));
//         $this->assertFalse(XmlUtils::isXML(12.56));
//         $this->assertFalse(XmlUtils::isXML('<a/>'));
//         $this->assertFalse(XmlUtils::isXML('<a>b<'));
//         $this->assertFalse(XmlUtils::isXML('<a><b attribute="hello"/></c>'));
//         $this->assertFalse(XmlUtils::isXML('<a><b attribute="hello"/></a><b></b>'));
//     }


//     /**
//      * testIsEqualTo
//      *
//      * @return void
//      */
//     public function testIsEqualTo(){

//         // TODO
//         return;

//         // Test non xml values must launch exception
//         $exceptionMessage = '';

//         try {
//             XmlUtils::isEqualTo(null, null);
//             $exceptionMessage = 'null did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::isEqualTo(1, 1);
//             $exceptionMessage = '1 did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::isEqualTo('asfasf1', '345345');
//             $exceptionMessage = 'asfasf1 did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         if($exceptionMessage != ''){

//             $this->fail($exceptionMessage);
//         }

//         // Test identical elements with strict order
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><a/></root>', true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root a="1"><a/></root>', true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"><a c="23"/></root>', true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>1</a></root>', true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>1</b></root>", true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/><c/></a></root>', true, true));

//         // Test identical elements without strict order
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><a/></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><a></a></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/></root>', '<root><a/><c/></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/><b/></root>', '<root><a/><c/><b/></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1"></root>', '<root a="1"></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1"><!-- test a comment --></root>', '<root a="1"></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1"><!-- test a comment --></root>', '<root a="1"><!-- test a different comment --></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="1" c="2" b="34"></root>', '<root b="34" c="2" a="1"></root>', false, false));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/><b a="1" c="2" b="34"/></root>', '<root><c/><b b="34" c="2" a="1"/><a/></root>', false, false));

//         // Test different cases with strict order
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<raat><a/></raat>', true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root><a/></root>', true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"></root>', true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>2</a></root>', true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>3</b></root>", true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/></a><c/></root>', true, true));

//         // Test different cases without strict order
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<raat><a/></raat>', false, false));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root><a/></root>', false, false));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"></root>', false, false));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>2</a></root>', false, false));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>3</b></root>", false, false));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/></a><c/></root>', false, false));

//         // Test ignore case option
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<Root><a/></Root>', true, true, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><A/></root>', false, false, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<ROOT><A/></ROOT>', '<raat><A/></raat>', true, true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<RAAT><a/></RAAT>', false, false, true));
//         $this->assertTrue(XmlUtils::isEqualTo('<root a="CASE"><a/></root>', '<root a="case"><a/></root>', false, false, true));

//         // Test big xml files
//         $basePath = __DIR__.'/../resources/utils/xmlUtils/isEqualTo/';

//         $filesManager = new FilesManager();

//         $xmlData1 = $filesManager->readFile($basePath.'Test1.xml');
//         $xmlData2 = $filesManager->readFile($basePath.'Test2.xml');
//         $xmlData3 = $filesManager->readFile($basePath.'Test3.xml');

//         $this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, true, true));
//         $this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, true, false));
//         $this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, false, true));
//         $this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, false, false));
//         $this->assertTrue(XmlUtils::isEqualTo(strtolower($xmlData2), $xmlData2, false, false, true));

//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, true, false));
//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, false, true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, false, false, true));

//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData3, true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData2, $xmlData3, true, false));
//         $this->assertTrue(!XmlUtils::isEqualTo($xmlData2, $xmlData3, false, true, true));
//         $this->assertTrue(!XmlUtils::isEqualTo(strtolower($xmlData2), $xmlData3, false, false, true));
//     }


//     /**
//      * testAddChild
//      *
//      * @return void
//      */
//     public function testAddChild(){

//         // TODO
//         return;

//         // Test correct cases
//         $parent = XmlUtils::addChild(new SimpleXMLElement('<root></root>'), new SimpleXMLElement('<a></a>'));
//         $this->assertTrue(XmlUtils::isEqualTo($parent, '<root><a/></root>'));

//         $parent = XmlUtils::addChild(new SimpleXMLElement('<root></root>'), '<a><b attribute="hello"/><c></c></a>');
//         $this->assertTrue(XmlUtils::isEqualTo($parent, '<root><a><c/><b attribute="hello"/></a></root>'));

//         $parent = XmlUtils::addChild(new SimpleXMLElement('<root></root>'), new SimpleXMLElement('<a><b attribute="hello"/></a>'));
//         $this->assertTrue(XmlUtils::isEqualTo($parent, '<root><a><b attribute="hello"/></a></root>'));

//         $parent = XmlUtils::addChild($parent, new SimpleXMLElement('<c><b attribute="hello"/></c>'));
//         $this->assertTrue(XmlUtils::isEqualTo($parent, '<root><a><b attribute="hello"/></a><c><b attribute="hello"/></c></root>'));

//         // Test exceptions
//         $exceptionMessage = '';

//         try {
//             XmlUtils::addChild(null, null);
//             $exceptionMessage = 'null did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::addChild(null, '');
//             $exceptionMessage = 'string did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::addChild([1,2,3], new SimpleXMLElement('<a></a>'));
//             $exceptionMessage = 'array did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::addChild('<root></root>', '<a></a></b>');
//             $exceptionMessage = '<root></root> did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         try {
//             XmlUtils::addChild(new SimpleXMLElement('<root></root>'), '<a></a></b>');
//             $exceptionMessage = '<a></a></b> did not cause exception';
//         } catch (Exception $e) {
//             // We expect an exception to happen
//         }

//         if($exceptionMessage != ''){

//             $this->fail($exceptionMessage);
//         }
//     }
// }

?>
















?>