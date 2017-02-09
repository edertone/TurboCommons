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
use org\turbocommons\src\main\php\utils\XmlUtils;
use org\turbocommons\src\main\php\managers\FilesManager;


/**
 * XmlUtilsTest
 *
 * @return void
 */
class XmlUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

		// Test non xml values must launch exception
		$exceptionMessage = '';

		try {
			XmlUtils::isEqualTo(null, null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			XmlUtils::isEqualTo(1, 1);
			$exceptionMessage = '1 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			XmlUtils::isEqualTo('asfasf1', '345345');
			$exceptionMessage = 'asfasf1 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}

		// Test identical elements with strict order
		$this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><a/></root>', false, false));
		$this->assertTrue(XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root a="1"><a/></root>', false, false));
		$this->assertTrue(XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"><a c="23"/></root>', false, false));
		$this->assertTrue(XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>1</a></root>', false, false));
		$this->assertTrue(XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>1</b></root>", false, false));
		$this->assertTrue(XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/><c/></a></root>', false, false));

		// Test identical elements without strict order
		$this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><a/></root>', true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/></root>', '<root><a/><c/></root>', true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/><b/></root>', '<root><a/><c/><b/></root>', true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root a="1"></root>', '<root a="1"></root>', true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root a="1" c="2" b="34"></root>', '<root b="34" c="2" a="1"></root>', true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root><c/><a/><b a="1" c="2" b="34"/></root>', '<root><c/><b b="34" c="2" a="1"/><a/></root>', true, true));

		// Test different cases with strict order
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<raat><a/></raat>', false, false));
		$this->assertTrue(!XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root><a/></root>', false, false));
		$this->assertTrue(!XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"></root>', false, false));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>2</a></root>', false, false));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>3</b></root>", false, false));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/></a><c/></root>', false, false));

		// Test different cases without strict order
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<raat><a/></raat>', true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root a="1"><a/></root>', '<root><a/></root>', true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root a="1" b="test"><a c="23"/></root>', '<root a="1" b="test"></root>', true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a></root>', '<root><a>2</a></root>', true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a>1</a><b>1</b></root>', "<?xml version='1.0'?><root><a>1</a><b>3</b></root>", true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a><b/><c/></a></root>', '<root><a><b/></a><c/></root>', true, true));

		// Test ignore case option
		$this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<Root><a/></Root>', false, false, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root><a/></root>', '<root><A/></root>', true, true, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<ROOT><A/></ROOT>', '<raat><A/></raat>', false, false, true));
		$this->assertTrue(!XmlUtils::isEqualTo('<root><a/></root>', '<RAAT><a/></RAAT>', true, true, true));
		$this->assertTrue(XmlUtils::isEqualTo('<root a="CASE"><a/></root>', '<root a="case"><a/></root>', true, true, true));

		// Test big xml files
		$basePath = __DIR__.'/../resources/utils/xmlUtils/isEqualTo/';

		$filesManager = FilesManager::getInstance();

		$xmlData1 = $filesManager->readFile($basePath.'Test1.xml');
		$xmlData2 = $filesManager->readFile($basePath.'Test2.xml');
		$xmlData3 = $filesManager->readFile($basePath.'Test3.xml');

		$this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, false, false));
		$this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, false, true));
		$this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, true, false));
		$this->assertTrue(XmlUtils::isEqualTo($xmlData1, $xmlData1, true, true));
		$this->assertTrue(XmlUtils::isEqualTo(strtolower($xmlData2), $xmlData2, true, true, true));

		$this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, false, false));
		$this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, false, true));
		$this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, true, false, true));
		$this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData2, true, true, true));

		$this->assertTrue(!XmlUtils::isEqualTo($xmlData1, $xmlData3, false, false));
		$this->assertTrue(!XmlUtils::isEqualTo($xmlData2, $xmlData3, false, true));
		$this->assertTrue(!XmlUtils::isEqualTo($xmlData2, $xmlData3, true, false, true));
		$this->assertTrue(!XmlUtils::isEqualTo(strtolower($xmlData2), $xmlData3, true, true, true));
	}


	/**
	 * testAddChild
	 *
	 * @return void
	 */
	public function testAddChild(){

		// Test empty cases
		// TODO

		// Test correct cases
		//$this->assertTrue(XmlUtils::addChild('<root></root>', '<a>1</a>')->asXML() == '<root><a>1</a></root>');

		// Test incorrect cases
		// TODO

		// Test exceptions
		// TODO

	}
}

?>