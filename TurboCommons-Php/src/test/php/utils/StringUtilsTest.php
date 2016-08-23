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

use PHPUnit_Framework_TestCase;
use org\turbocommons\src\main\php\utils\StringUtils;


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
		$this->assertTrue(!StringUtils::isEmpty('EMPTY'));
		$this->assertTrue(!StringUtils::isEmpty('EMPTY test', ['EMPTY']));
		$this->assertTrue(StringUtils::isEmpty('EMPTY', ['EMPTY']));
	}


	/**
	 * testCountWords
	 *
	 * @return void
	 */
	public function testCountWords(){

		$this->assertTrue(StringUtils::countWords(null) == 0);
		$this->assertTrue(StringUtils::countWords('') == 0);
		$this->assertTrue(StringUtils::countWords('  ') == 0);
		$this->assertTrue(StringUtils::countWords('       ') == 0);
		$this->assertTrue(StringUtils::countWords('hello') == 1);
		$this->assertTrue(StringUtils::countWords('hello baby') == 2);
		$this->assertTrue(StringUtils::countWords("try\nto\r\n\t\ngo\r\nup") == 4);
		$this->assertTrue(StringUtils::countWords("     \n      \r\n") == 0);
		$this->assertTrue(StringUtils::countWords("     \n   1   \r\n") == 1);
		$this->assertTrue(StringUtils::countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
		$this->assertTrue(StringUtils::countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
	}


	/**
	 * testLimitLen
	 *
	 * @return void
	 */
	public function testLimitLen(){

		$this->assertTrue(StringUtils::limitLen(null, 0) === '');
		$this->assertTrue(StringUtils::limitLen(null, 10) === '');
		$this->assertTrue(StringUtils::limitLen('', 0) === '');
		$this->assertTrue(StringUtils::limitLen('', 10) === '');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 1) === ' ');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 2) === ' .');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 3) === ' ..');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 4) === ' ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 5) === 'h ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 18) === 'hello dear how ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 19) === 'hello dear how  ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 20) === 'hello dear how a ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 22) === 'hello dear how are you');
		$this->assertTrue(StringUtils::limitLen('hello dear how are you', 50) === 'hello dear how are you');

		// Test non numeric limit value gives exception
		$this->setExpectedException('Exception');
		$this->assertTrue(StringUtils::limitLen('hello', null) === '');
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
		$this->assertTrue(StringUtils::extractLines('single line') === ['single line']);
		$this->assertTrue(StringUtils::extractLines("line1\nline2\nline3") == ['line1', 'line2', 'line3']);
		$this->assertTrue(StringUtils::extractLines("line1\n        \nline2") == ['line1', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\n\n\n\t\r       \nline2") == ['line1', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\r\n   \r\nline2") == ['line1', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\n 1  \nline2") == ['line1', ' 1  ', 'line2']);

		$this->assertTrue(StringUtils::extractLines('          ', []) === ['          ']);
		$this->assertTrue(StringUtils::extractLines("line1\n   \nline2", []) == ['line1', '   ', 'line2']);
		$this->assertTrue(StringUtils::extractLines("line1\r\n   \r\nline2", []) == ['line1', '   ', 'line2']);
	}


	/**
	 * testExtractKeyWords
	 *
	 * @return void
	 */
	public function testExtractKeyWords(){

		// TODO

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
		$this->assertTrue(StringUtils::formatPath('test\\test/hello\\\\') == 'test'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'hello');

		// Test non string paths throw exception
		$this->setExpectedException('Exception');
		StringUtils::formatPath(['1']);
	}
}

?>