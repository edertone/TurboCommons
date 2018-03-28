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

use PHPUnit\Framework\TestCase;


/**
 * SerializationManagerTest
 *
 * @return void
 */
class SerializationManagerTest extends TestCase {

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

        // TODO - translate from TS

        $this->exceptionMessage = '';
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // TODO - translate from TS

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
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
     * testClassToJson
     *
     * @return void
     */
    public function testClassToJson(){

        // Test empty values
        // TODO - review from ts

        // Test ok values
        // TODO - review from ts

        // Test wrong values
        // TODO - review from ts

        // Test exceptions
        // TODO - review from ts

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testClassToObject
     *
     * @return void
     */
    public function testClassToObject(){

        // Test empty values
        // TODO - review from ts

        // Test ok values
        // TODO - review from ts

        // Test wrong values
        // TODO - review from ts

        // Test exceptions
        // TODO - review from ts

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testHashMapObjectToClass
     *
     * @return void
     */
    public function testHashMapObjectToClass(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testJavaPropertiesObjectToString
     *
     * @return void
     */
    public function testJavaPropertiesObjectToString(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testJsonToClass
     *
     * @return void
     */
    public function testJsonToClass(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testObjectToClass
     *
     * @return void
     */
    public function testObjectToClass(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testStringToJavaPropertiesObject
     *
     * @return void
     */
    public function testStringToJavaPropertiesObject(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testStringToXmlObject
     *
     * @return void
     */
    public function testStringToXmlObject(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testXmlObjectToString
     *
     * @return void
     */
    public function testXmlObjectToString(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>