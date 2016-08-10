<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\test\php\managers;

use Exception;
use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\utils\FileSystemUtils;


/**
 * FileSystemUtils tests
 *
 * @return void
 */
class FileSystemUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testIsDirectoryEmpty
	 *
	 * @return void
	 */
	public function testIsDirectoryEmpty(){

		// Create a temporary folder
		$basePath = FileSystemUtils::createTempDirectory('TurboCommons-Php');
		$this->assertTrue(FileSystemUtils::isDirectoryEmpty($basePath));

		// Create some file
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
		$this->assertTrue(!FileSystemUtils::isDirectoryEmpty($basePath));

		// test that exception happens with non existant folder
		$this->setExpectedException('Exception');
		FileSystemUtils::isDirectoryEmpty($basePath.DIRECTORY_SEPARATOR.'asdfwer');
	}


	/**
	 * testFindUniqueDirectoryName
	 *
	 * @return void
	 */
	public function testFindUniqueDirectoryName(){

		// Create a temporary folder
		$basePath = FileSystemUtils::createTempDirectory('TurboCommons-Php');
		$this->assertTrue(FileSystemUtils::isDirectoryEmpty($basePath));

		// Test generated directory names for the created empty folder
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath) == '1');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', '-') == 'NewFolder');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', '-', true) == 'NewFolder');

		// Create some folders
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'1');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'1'));
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder'));

		// Create a file that is named like a directory (without extension)
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'2', 'test file');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'2'));

		// Verify generated dir names when folders already exist at destination path
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath) == '3');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder-1');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', '', '-', true) == '1-NewFolder');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-1');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');

		// Create some more folders
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'3');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'3'));
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-1');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-1'));
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-copy-1');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-copy-1'));

		// Verify generated names again
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath) == '4');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder-2');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', '', '-', true) == '1-NewFolder');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-2');
		$this->assertTrue(FileSystemUtils::findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');
	}


	/**
	 * testFindUniqueFileName
	 *
	 * @return void
	 */
	public function testFindUniqueFileName(){

		// Create a temporary folder
		$basePath = FileSystemUtils::createTempDirectory('TurboCommons-Php');
		$this->assertTrue(FileSystemUtils::isDirectoryEmpty($basePath));

		// Test generated file names for the created empty folder
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath) == '1', 'error '.FileSystemUtils::findUniqueFileName($basePath));
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', '-') == 'NewFile.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', '-', true) == 'NewFile.txt');

		// Create some files
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'1', 'hello baby');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'1'));
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'NewFile.txt', 'hello baby');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'NewFile.txt'));

		// Create a folder that is named like a possible file
		FileSystemUtils::createDirectory($basePath.DIRECTORY_SEPARATOR.'2');
		$this->assertTrue(FileSystemUtils::isDirectory($basePath.DIRECTORY_SEPARATOR.'2'));

		// Verify generated file names when files already exist at destination path
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath) == '3');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile-1.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-1.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');

		// Create some more files
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'3', 'hello baby');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'3'));
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'NewFile-1.txt', 'hello baby');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'NewFile-1.txt'));
		FileSystemUtils::createFile($basePath.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt', 'hello baby');
		$this->assertTrue(FileSystemUtils::isFile($basePath.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt'));

		// Verify generated names again
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath) == '4');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile-2.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-2.txt');
		$this->assertTrue(FileSystemUtils::findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');
	}
}

?>