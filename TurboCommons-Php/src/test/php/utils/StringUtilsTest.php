<?php


namespace com\edertone\turboDB\src\test\php\managers;


use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\utils\StringUtils;


/**
 * Stringutils tests
 *
 * @return void
 */
class StringUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * TestisEmpty
	 *
	 * @return void
	 */
	public function testIsEmpty(){

		$this->assertTrue(StringUtils::isEmpty(''));
		$this->assertTrue(StringUtils::isEmpty('      '));
		$this->assertTrue(StringUtils::isEmpty("\n\n  \n"));
		$this->assertTrue(StringUtils::isEmpty("\t   \n     \r\r"));
		$this->assertTrue(!StringUtils::isEmpty('adsadf'));
		$this->assertTrue(!StringUtils::isEmpty('    sdfasdsf'));
	}

}

?>