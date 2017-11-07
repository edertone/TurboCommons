<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\utils;

use Exception;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\utils\SerializationUtils;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\utils\XmlUtils;



// ******************************************************************************************************************
// TODO - NOT WORKING!!!!!!!!!!
// TODO - This tests must be fully reviewed
// ******************************************************************************************************************



/**
 * SerializationUtils tests
 *
 * @return void
 */
class SerializationUtilsTest extends TestCase {


    /**
     * testHashMapObjectToClass
     *
     * @return void
     */
    public function testHashMapObjectToClass(){

        $basePath = __DIR__.'/../resources/utils/serializationUtils/hashMapObjectToClass';

        $validationManager = new ValidationManager();

        // TODO
    }


    /**
     * testJavaPropertiesObjectToString
     *
     * @return void
     */
    public function testJavaPropertiesObjectToString(){

        // TODO
    }


    /**
     * testStringToJavaPropertiesObject
     *
     * @return void
     */
    public function testStringToJavaPropertiesObject(){

        // TODO
    }


    /**
     * testStringToXmlObject
     *
     * @return void
     */
    public function testStringToXmlObject(){

        // TODO
        return;

        $basePath = __DIR__.'/../resources/utils/serializationUtils/stringToXmlObject';

        // test empty cases
        $this->assertTrue(SerializationUtils::stringToXmlObject(null) === null);
        $this->assertTrue(SerializationUtils::stringToXmlObject('') === null);
        $this->assertTrue(SerializationUtils::stringToXmlObject('     ') === null);

        // test incorrect cases
        $exceptionMessage = '';

        try {
            SerializationUtils::stringToXmlObject(1);
            $exceptionMessage = '1 did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::stringToXmlObject('sdafgsdt4567');
            $exceptionMessage = 'sdafgsdt4567 did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::stringToXmlObject('<caca>');
            $exceptionMessage = '<caca> did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::stringToXmlObject([1,2,3,4]);
            $exceptionMessage = '[1,2,3,4] did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }

        // Test correct cases
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject('<document><from>Joe</from></document>')) == 'SimpleXMLElement');
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject("<?xml version='1.0'?><document><from>Joe</from></document>")) == 'SimpleXMLElement');

        $filesManager = new FilesManager();

        $xmlData1 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test1.xml');
        $xmlData2 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test2.xml');
        $xmlData3 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test3.xml');
        $xmlData4 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test4.xml');

        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject($xmlData1)) == 'SimpleXMLElement');
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject($xmlData2)) == 'SimpleXMLElement');
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject($xmlData3)) == 'SimpleXMLElement');
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject($xmlData4)) == 'SimpleXMLElement');

        // This case deliverately adds empty spaces at the beginning of the xml string. It is not well formed,
        // but the stringToXmlObject method handles it by trimming the received string
        $xmlData5 = '      '.$filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test2.xml').'       ';
        $this->assertTrue(get_class(SerializationUtils::stringToXmlObject($xmlData5)) == 'SimpleXMLElement');
    }


    /**
     * testXmlObjectToString
     *
     * @return void
     */
    public function testXmlObjectToString(){

        // TODO
        return;

        // Test empty cases
        $this->assertTrue(SerializationUtils::xmlObjectToString(null) === '');
        $this->assertTrue(SerializationUtils::xmlObjectToString('') === '');
        $this->assertTrue(SerializationUtils::xmlObjectToString('     ') === '');

        // Test correct cases
        $this->assertTrue(XmlUtils::isEqualTo(SerializationUtils::xmlObjectToString('<root t="1"><a>1</a></root>'), '<root t="1"><a>1</a></root>'));
        $this->assertTrue(XmlUtils::isEqualTo(SerializationUtils::xmlObjectToString('<root><a>1</a><b>1</b></root>'), "<?xml version='1.0'?><root><a>1</a><b>1</b></root>"));

        // Test Test exceptions
        $exceptionMessage = '';

        try {
            SerializationUtils::xmlObjectToString(1);
            $exceptionMessage = '1 did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::xmlObjectToString('sdafgsdt4567');
            $exceptionMessage = 'sdafgsdt4567 did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::xmlObjectToString('<caca>');
            $exceptionMessage = '<caca> did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        try {
            SerializationUtils::xmlObjectToString([1,2,3,4]);
            $exceptionMessage = '[1,2,3,4] did not cause exception';
        } catch (Exception $e) {
            // We expect an exception to happen
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }
    }
}

?>