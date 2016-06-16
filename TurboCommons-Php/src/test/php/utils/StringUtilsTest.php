<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\test\php\managers;

use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\utils\StringUtils;


/**
 * Stringutils tests
 *
 * @return void
 */
class StringUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testIsEmpty
	 *
	 * @return void
	 */
	public function testIsEmpty(){

		$this->assertTrue(StringUtils::isEmpty(null));
		$this->assertTrue(StringUtils::isEmpty(''));
		$this->assertTrue(StringUtils::isEmpty('      '));
		$this->assertTrue(StringUtils::isEmpty("\n\n  \n"));
		$this->assertTrue(StringUtils::isEmpty("\t   \n     \r\r"));
		$this->assertTrue(!StringUtils::isEmpty('adsadf'));
		$this->assertTrue(!StringUtils::isEmpty('    sdfasdsf'));
	}


	/**
	 * testExtractLines
	 *
	 * @return void
	 */
	public function testExtractLines(){

		$this->assertTrue(StringUtils::extractLines(null) === []);
		$this->assertTrue(StringUtils::extractLines('') === []);
		$this->assertTrue(StringUtils::extractLines('          ') === []);
		$this->assertTrue(StringUtils::extractLines("line1\nline2\nline3") == ['line1', 'line2', 'line3']);
		$this->assertTrue(StringUtils::extractLines("line1\n        \nline2") == ['line1', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\n\n\n\t\r       \nline2") == ['line1', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\n   \nline2", []) == ['line1', '   ', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\n 1  \nline2") == ['line1', ' 1  ', 'line2']);
	}


	/**
	 * testExtractFileNameWithExtension
	 *
	 * @return void
	 */
	public function testExtractFileNameWithExtension(){

		$this->assertTrue(StringUtils::extractFileNameWithExtension('') === '');
		$this->assertTrue(StringUtils::extractFileNameWithExtension(null) === '');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('       ') === '');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('C:\Program Files\CCleaner\CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('//folder/folder2/folder3/file.txt') == 'file.txt');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('\\\\\\CCleaner64.exe') == 'CCleaner64.exe');
		$this->assertTrue(StringUtils::extractFileNameWithExtension('\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64.exe');
	}


	/**
	 * testExtractFileNameWithoutExtension
	 *
	 * @return void
	 */
	public function testExtractFileNameWithoutExtension(){

		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('') === '');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension(null) === '');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('       ') === '');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('C:\Program Files\CCleaner\CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('//folder/folder2/folder3/file.txt') == 'file');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('\\\\\\CCleaner64.exe') == 'CCleaner64');
		$this->assertTrue(StringUtils::extractFileNameWithoutExtension('\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64');
	}


	/**
	 * testExtractFileExtension
	 *
	 * @return void
	 */
	public function testExtractFileExtension(){

		$this->assertTrue(StringUtils::extractFileExtension('') === '');
		$this->assertTrue(StringUtils::extractFileExtension(null) === '');
		$this->assertTrue(StringUtils::extractFileExtension('       ') === '');
		$this->assertTrue(StringUtils::extractFileExtension('C:\Program Files\CCleaner\CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::extractFileExtension('\\Files/CCleaner/CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::extractFileExtension('//folder/folder2/folder3/file.txt') == 'txt');
		$this->assertTrue(StringUtils::extractFileExtension('CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::extractFileExtension('\\\\\\CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::extractFileExtension('\some long path containing lots of spaces\\///CCleaner64.exe') == 'exe');
		$this->assertTrue(StringUtils::extractFileExtension('CCleaner64.EXE', true) == 'EXE');
		$this->assertTrue(StringUtils::extractFileExtension('\\\\\\CCleaner64.eXEfile', true) == 'eXEfile');
	}


	/**
	 * TestFormatPath
	 *
	 * @return void
	 */
	public function testFormatPath(){

		$this->assertTrue(StringUtils::formatPath(null) === '');
		$this->assertTrue(StringUtils::formatPath('') === '');
		$this->assertTrue(StringUtils::formatPath('       ') === '       ');
		$this->assertTrue(StringUtils::formatPath('test//test/') == 'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('////test//////test////') == DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('\\\\////test//test/') == DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'test');
		$this->assertTrue(StringUtils::formatPath('test\test/hello\\\\') == 'test'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'hello');
	}
}

?>