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

		$basePath = __DIR__.'/../../resources/utils/serializationUtils/propertiesToArray';

		// Test the properties file 1
		$test = FileSystemUtils::readFile($basePath.'/Test1.properties');
		$test = SerializationUtils::propertiesToArray($test);

		$this->assertTrue(count($test) == 45);
		$this->assertTrue($test['period.maintenance.InMillis'] == '86400000');
		$this->assertTrue($test['decontamination.frequency.inDays'] == '30');
		$this->assertTrue($test['decontamination.warningBeforehand.inDays'] == '1');
		$this->assertTrue($test['technicalServiceInspection.frequency.inMonths'] == '12');
		$this->assertTrue($test['technicalServiceInspection.warningBeforehand.inDays'] == '7');
		$this->assertTrue($test['instrument.restartFrequency.inDays'] == '7');
		$this->assertTrue($test['start.purgeFinishedTestsPriorToTheLast.inDays'] == '7');
		$this->assertTrue($test['max.error.count'] == '-1');
		$this->assertTrue($test['error.delimeter'] == '(!)');
		$this->assertTrue($test['log.stdout'] == 'N');
		$this->assertTrue($test['portalrolemembership.bb.controlled.fields'] == '');
		$this->assertTrue($test['membership.datasource.key'] == '');
		$this->assertTrue($test['reconcile'] == 'Y');

		// Test the properties file 2
		$test = FileSystemUtils::readFile($basePath.'/Test2.properties');
		$test = SerializationUtils::propertiesToArray($test);

		$this->assertTrue(count($test) == 11);
		$this->assertTrue($test['Currency_Converter'] == 'Chuyen doi tien te  ');
		$this->assertTrue($test['Enter_Amount'] == 'Nhập vào số lượng  ');
		$this->assertTrue($test['Target_Currency'] == 'Đơn vị chuyển  ');
		$this->assertTrue($test['Alert_Mess'] == 'Vui lòng nhập một số hợp lệ  ');
		$this->assertTrue($test['Alert_Title'] == 'Thong bao ');

		// Test the properties file 3
		$test = FileSystemUtils::readFile($basePath.'/Test3.properties');
		$test = SerializationUtils::propertiesToArray($test);

		$this->assertTrue(count($test) == 5);
		$this->assertTrue($test['website'] == 'http://en.wikipedia.org/');
		$this->assertTrue($test['language'] == 'English');
		$this->assertTrue($test['message'] == 'Welcome to Wikipedia!');
		$this->assertTrue($test['key with spaces'] == 'This is the value that could be looked up with the key "key with spaces".');
		// TODO $this->assertTrue($test2['tab'] == "\t");

		// Test the properties file 4
		$test = FileSystemUtils::readFile($basePath.'/Test4.properties');
		$test = SerializationUtils::propertiesToArray($test);

		$this->assertTrue(count($test) == 3);
		$this->assertTrue($test['key with spaces'] == "This line contains lots ' of \" special # characers \\!#'=.::sooo");
		$this->assertTrue($test['escaped key!=:# is __good'] == 'And must work as "escaped key!=:# is __good"');
		//$this->assertTrue($test['multiline.values'] == "line 1\nline 2\nline 3\nline 4\\");
	}
}

?>