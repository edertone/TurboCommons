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
use PHPUnit_Framework_TestCase;
use stdClass;
use org\turbocommons\src\main\php\utils\JavaPropertiesUtils;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbodepot\src\main\php\managers\FilesManager;


/**
 * JavaPropertiesUtilsTest tests
 *
 * @return void
 */
class JavaPropertiesUtilsTest extends PHPUnit_Framework_TestCase {


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

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->wrongValues = ['', 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, new stdclass()];
        $this->wrongValuesCount = count($this->wrongValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/javaPropertiesObject';

        $this->propertiesFiles = $this->filesManager->getDirectoryList($this->basePath);

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
     * testIsJavaProperties
     *
     * @return void
     */
    public function testIsJavaProperties(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            $this->assertFalse(JavaPropertiesUtils::isJavaProperties($this->emptyValues[$i]));
        }

        $this->assertTrue(JavaPropertiesUtils::isJavaProperties(new JavaPropertiesObject()));
        $this->assertTrue(JavaPropertiesUtils::isJavaProperties(new JavaPropertiesObject('')));

        // Test ok values
        $this->assertTrue(JavaPropertiesUtils::isJavaProperties('key='));
        $this->assertTrue(JavaPropertiesUtils::isJavaProperties('key:'));
        $this->assertTrue(JavaPropertiesUtils::isJavaProperties('key=value'));
        $this->assertTrue(JavaPropertiesUtils::isJavaProperties('key:value'));

        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
            $test = new JavaPropertiesObject($fileData);
            $this->assertTrue(JavaPropertiesUtils::isJavaProperties($fileData));
            $this->assertTrue(JavaPropertiesUtils::isJavaProperties($test));
        }

        // Test wrong values
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            $this->assertFalse(JavaPropertiesUtils::isJavaProperties($this->wrongValues[$i]));
        }

        // Test exceptions
        // Already tested at wrong values
    }


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

	    // Test empty values
	    $exceptionMessage = '';

	    for ($i = 0; $i < $this->emptyValuesCount; $i++) {

	        for ($j = 0; $j < $this->emptyValuesCount; $j++) {

	            try {
	                JavaPropertiesUtils::isEqualTo($this->emptyValues[$i], $this->emptyValues[$j]);
	                $exceptionMessage = 'empty value did not cause exception';
	            } catch (Exception $e) {
	                // We expect an exception to happen
	            }
	        }
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    // Test ok values
	    foreach ($this->propertiesFiles as $file) {

	        $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
	        $test = new JavaPropertiesObject($fileData);
	        $this->assertTrue(JavaPropertiesUtils::isEqualTo($test, $fileData));
	    }

	    // Test wrong values
	    $exceptionMessage = '';

	    for ($i = 0; $i < $this->wrongValuesCount; $i++) {

	        for ($j = 0; $j < $this->wrongValuesCount; $j++) {

	            try {
	                JavaPropertiesUtils::isEqualTo($this->wrongValues[$i], $this->wrongValues[$j]);
	                $exceptionMessage = 'wrong value did not cause exception';
	            } catch (Exception $e) {
	                // We expect an exception to happen
	            }
	        }
	    }

	    if($exceptionMessage != ''){

	        $this->fail($exceptionMessage);
	    }

	    // Test exceptions
	    // Already tested at wrong values
	}
}

?>