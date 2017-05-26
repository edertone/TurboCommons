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
use Exception;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\managers\FilesManager;


/**
 * FilesManager tests
 *
 * @return void
 */
class FilesManagerTest extends PHPUnit_Framework_TestCase {


	/**
	 * testIsFile
	 *
	 * @return void
	 */
	public function testIsFile(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));
		$this->assertTrue(!$filesManager->isFile($basePath));

		// Test with urls disabled
		$filesManager->acceptUrls = false;

		$this->assertTrue(!$filesManager->isFile(null));
		$this->assertTrue(!$filesManager->isFile(''));
		$this->assertTrue(!$filesManager->isFile('49568456'));
		$this->assertTrue(!$filesManager->isFile('http://www.adkgadsifi.com/ieriteroter3453458852t.pdf'));
		$this->assertTrue(!$filesManager->isFile('http://www.google.com'));
		$this->assertTrue(!$filesManager->isFile('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'));
		$this->assertTrue(!$filesManager->isFile('http://www.facebook.com'));

		// Test a created file
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'File.txt'));

		// Test with urls enabled
		$filesManager->acceptUrls = true;

		$this->assertTrue(!$filesManager->isFile(null));
		$this->assertTrue(!$filesManager->isFile(''));
		$this->assertTrue(!$filesManager->isFile('49568456'));
		$this->assertTrue(!$filesManager->isFile('http://www.adkgadsifi.com/ieriteroter3453458852t.pdf'));

		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'File.txt'));
	}


	/**
	 * testIsDirectory
	 *
	 * @return void
	 */
	public function testIsDirectory(){

		$filesManager = new FilesManager();

		// Test with urls disabled
		$filesManager->acceptUrls = false;

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));
		$this->assertTrue($filesManager->isDirectory($basePath));

		// Test a non existing file
		$this->assertTrue(!$filesManager->isDirectory(null));
		$this->assertTrue(!$filesManager->isDirectory(''));
		$this->assertTrue(!$filesManager->isDirectory('49568456'));

		// Test a created file
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
		$this->assertTrue(!$filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'File.txt'));

		// Test with urls enabled
		$filesManager->acceptUrls = true;

		$this->assertTrue(!$filesManager->isDirectory(null));
		$this->assertTrue(!$filesManager->isDirectory(''));
		$this->assertTrue(!$filesManager->isDirectory('49568456'));
	}


	/**
	 * testIsDirectoryEmpty
	 *
	 * @return void
	 */
	public function testIsDirectoryEmpty(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		// Create some file
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
		$this->assertTrue(!$filesManager->isDirectoryEmpty($basePath));

		// test that exception happens with non existant folder
		$exceptionMessage = '';

		try {
			$filesManager->isDirectoryEmpty($basePath.DIRECTORY_SEPARATOR.'asdfwer');
			$exceptionMessage = 'asdfwer did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->isDirectoryEmpty(null);
			$exceptionMessage = 'Null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->isDirectoryEmpty('etrtert');
			$exceptionMessage = 'etrtert did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDirectorySeparator
	 *
	 * @return void
	 */
	public function testGetDirectorySeparator(){

		$filesManager = new FilesManager();

		$this->assertTrue($filesManager->getDirectorySeparator() === DIRECTORY_SEPARATOR);
	}


	/**
	 * testFindUniqueDirectoryName
	 *
	 * @return void
	 */
	public function testFindUniqueDirectoryName(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		// Test generated directory names for the created empty folder
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath) == '1');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', '-') == 'NewFolder');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', '-', true) == 'NewFolder');

		// Create some folders
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'1');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'1'));
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder'));

		// Create a file that is named like a directory (without extension)
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'2', 'test file');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'2'));

		// Verify generated dir names when folders already exist at destination path
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath) == '3');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder-1');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', '', '-', true) == '1-NewFolder');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-1');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');

		// Create some more folders
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'3');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'3'));
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-1');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-1'));
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-copy-1');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'NewFolder-copy-1'));

		// Verify generated names again
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath) == '4');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder') == 'NewFolder-2');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', '', '-', true) == '1-NewFolder');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-2');
		$this->assertTrue($filesManager->findUniqueDirectoryName($basePath, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');
	}


	/**
	 * testFindUniqueFileName
	 *
	 * @return void
	 */
	public function testFindUniqueFileName(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		// Test generated file names for the created empty folder
		$this->assertTrue($filesManager->findUniqueFileName($basePath) == '1', 'error '.$filesManager->findUniqueFileName($basePath));
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', '-') == 'NewFile.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', '-', true) == 'NewFile.txt');

		// Create some files
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'1', 'hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'1'));
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'NewFile.txt', 'hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'NewFile.txt'));

		// Create a folder that is named like a possible file
		$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'2');
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'2'));

		// Verify generated file names when files already exist at destination path
		$this->assertTrue($filesManager->findUniqueFileName($basePath) == '3');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile-1.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-1.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');

		// Create some more files
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'3', 'hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'3'));
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'NewFile-1.txt', 'hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'NewFile-1.txt'));
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt', 'hello baby');
		$this->assertTrue($filesManager->isFile($basePath.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt'));

		// Verify generated names again
		$this->assertTrue($filesManager->findUniqueFileName($basePath) == '4');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt') == 'NewFile-2.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-2.txt');
		$this->assertTrue($filesManager->findUniqueFileName($basePath, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');
	}


	/**
	 * testCreateDirectory
	 *
	 * @return void
	 */
	public function testCreateDirectory(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		// Test empty and wrong parameters
		$exceptionMessage = '';

		try {
			$filesManager->createDirectory(null);
			$exceptionMessage = 'Null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->createDirectory('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->createDirectory('     ');
			$exceptionMessage = '"     " did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->createDirectory('\345\ertert');
			$exceptionMessage = '\345\ertert did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test correct cases
		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'test1'));
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'test1'));

		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'1234'));
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'1234'));

		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'-go-'));
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'-go-'));

		// Test already existing folders
		$this->assertTrue(!$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'test1'));
		$this->assertTrue(!$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'1234'));
		$this->assertTrue(!$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'-go-'));

		// Test already existing files
		$filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'3', 'hello baby');
		try {
			$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'3');
			$exceptionMessage = 'basepath did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		// Test creating recursive folders
		try {
			$filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5');
			$exceptionMessage = 'test55 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5', true));
		$this->assertTrue($filesManager->isDirectory($basePath.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5'));

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testCreateTempDirectory
	 *
	 * @return void
	 */
	public function testCreateTempDirectory(){

		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));
		$this->assertTrue(strpos($basePath, 'TurboCommons-Php') !== false);

		// Create a temp directory without specifying a name
		$basePath = $filesManager->createTempDirectory('');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		$basePath = $filesManager->createTempDirectory(null);
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));
		$this->assertTrue(strpos($basePath, 'null') === false);

		// Test wrong parameters
		$exceptionMessage = '';

		try {
			$filesManager->createTempDirectory([]);
			$exceptionMessage = '[] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testGetDirectoryList
	 *
	 * @return void
	 */
	public function testGetDirectoryList(){

		$validationManager = new ValidationManager();
		$filesManager = new FilesManager();

		// Create a temporary folder
		$basePath = $filesManager->createTempDirectory('TurboCommons-Php');
		$this->assertTrue($filesManager->isDirectoryEmpty($basePath));

		// Create some folders and files
		$this->assertTrue($filesManager->createFile($basePath.DIRECTORY_SEPARATOR.'file.txt', 'hello baby'));
		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'test1'));
		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'1234'));
		$this->assertTrue($filesManager->createDirectory($basePath.DIRECTORY_SEPARATOR.'-go-'));

		// Check that list is ok
		$res = $filesManager->getDirectoryList($basePath);
		$this->assertTrue($validationManager->isArray($res));
		$this->assertTrue(count($res) == 4);
		$this->assertTrue(in_array('file.txt', $res));
		$this->assertTrue(in_array('test1', $res));
		$this->assertTrue(in_array('1234', $res));
		$this->assertTrue(in_array('-go-', $res));

		// Check sorted lists
		$res = $filesManager->getDirectoryList($basePath, 'nameAsc');
		$this->assertTrue(ArrayUtils::isEqualTo($res, ['-go-', '1234', 'file.txt', 'test1']));

		$res = $filesManager->getDirectoryList($basePath, 'nameDesc');
		$this->assertTrue(ArrayUtils::isEqualTo($res, ['test1', 'file.txt', '1234', '-go-']));

		//$res = $filesManager->getDirectoryList($basePath, 'mDateAsc');
		//$this->assertTrue(ArrayUtils::isEqualTo($res, ['file.txt', 'test1', '1234', '-go-']));

		//$res = $filesManager->getDirectoryList($basePath, 'mDateDesc');
		//$this->assertTrue(ArrayUtils::isEqualTo($res, ['-go-', '1234', 'test1', 'file.txt']));

		// Test wrong parameteres
		$exceptionMessage = '';

		try {
			$filesManager->getDirectoryList(null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->getDirectoryList('');
			$exceptionMessage = '"" did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			$filesManager->getDirectoryList('wrtwrtyeyery');
			$exceptionMessage = 'wrtwrtyeyery did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	// TODO - Add all missing tests

}

?>