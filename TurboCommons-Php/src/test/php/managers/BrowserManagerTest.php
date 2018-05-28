<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\managers;

use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\managers\BrowserManager;


/**
 * BrowserManager tests
 *
 * @return void
 */
class BrowserManagerTest extends TestCase {


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

        $this->exceptionMessage = '';

        $this->sut = new BrowserManager();
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

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
     * testGetCurrentUrl
     *
     * @return void
     */
    // This test is not possible otuside of a webserver


    /**
     * testIsDocumentLoaded
     *
     * @return void
     */
    // This test is not possible otuside of a webserver


    /**
     * testIsDocumentLoaded
     *
     * @return void
     */
    public function testIsCookie(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testSetCookie
     *
     * @return void
     */
    public function testSetCookie(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetCookie
     *
     * @return void
     */
    public function testGetCookie(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testDeleteCookie
     *
     * @return void
     */
    public function testDeleteCookie(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetPreferredLanguage
     *
     * @return void
     */
    // This test is not possible otuside of a webserver
}

?>