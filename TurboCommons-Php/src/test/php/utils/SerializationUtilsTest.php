<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\test\php\utils;

use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\utils\FileSystemUtils;
use com\edertone\turboCommons\src\main\php\utils\SerializationUtils;


/**
 * SerializationUtils tests
 *
 * @return void
 */
class SerializationUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testPropertiesToArray
	 *
	 * @return void
	 */
	public function testPropertiesToArray(){

		// Test properties file encoding
		// TODO

		// Test the properties file 1
		$test1 = FileSystemUtils::readFile(__DIR__.'/../../resources/utils/serializationUtils/Test1.properties');
		$test1 = SerializationUtils::propertiesToArray($test1);

		$this->assertTrue($test1['period.maintenance.InMillis'] == '86400000');
		$this->assertTrue($test1['decontamination.frequency.inDays'] == '30');
		$this->assertTrue($test1['decontamination.warningBeforehand.inDays'] == '1');
		$this->assertTrue($test1['technicalServiceInspection.frequency.inMonths'] == '12');
		$this->assertTrue($test1['technicalServiceInspection.warningBeforehand.inDays'] == '7');
		$this->assertTrue($test1['instrument.restartFrequency.inDays'] == '7');
		$this->assertTrue($test1['start.purgeFinishedTestsPriorToTheLast.inDays'] == '7');

		// Test the properties file 2
		$test2 = FileSystemUtils::readFile(__DIR__.'/../../resources/utils/serializationUtils/Test2.properties');
		$test2 = SerializationUtils::propertiesToArray($test2);

		$this->assertTrue($test2['website'] == 'http://en.wikipedia.org/');
		$this->assertTrue($test2['language'] == 'English');
		// TODO $this->assertTrue($test2['message'] == 'Welcome to Wikipedia!');
		// TODO $this->assertTrue($test2['key with spaces'] == 'English');
		// TODO $this->assertTrue($test2['tab'] == "\t");
	}
}

?>