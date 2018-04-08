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
use stdClass;
use Throwable;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * FilesManager tests
 *
 * @return void
 */
class FilesManagerTest extends TestCase {


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

        $this->sut = new FilesManager();

        // Create a temporary folder
        $this->tempFolder = $this->sut->createTempDirectory('TurboCommons-FilesManagerTest');
        $this->assertTrue(strpos($this->tempFolder, 'TurboCommons-FilesManagerTest') !== false);
        $this->assertTrue($this->sut->isDirectoryEmpty($this->tempFolder));
        $this->assertFalse($this->sut->isFile($this->tempFolder));
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Delete temporary folder
        $this->sut->deleteDirectory($this->tempFolder);

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
     * Helper method to create a dummy structure of folders with some parameters
     *
     * @param string $root Base directory where the structure will be created
     * @param int $folders Number of folders to create per depth level
     * @param int $depth Number of subfolders to create
     * @param string $fileBaseName Base name for each file to be created
     * @param int $filesPerFolder Number of files to create per each folder
     * @param string $filesContent Content to place inside each created file
     *
     * @return void
     */
    private function createDummyDirectoryStucture(string $root,
                                                  int $folders,
                                                  int $depth,
                                                  string $fileBaseName,
                                                  int $filesPerFolder,
                                                  string $filesContent){

        $s = DIRECTORY_SEPARATOR;

        // Create the structure of folders
        for ($i = 0; $i < $folders; $i++) {

            $pathToCreate = $root;

            for ($j = 0; $j < $depth; $j++) {

                $pathToCreate = $pathToCreate.$s.'folder-'.$i.'-'.$j;

                $this->sut->createDirectory($pathToCreate, true);

                for ($k = 0; $k < $filesPerFolder; $k++) {

                    $fileToCreate = $pathToCreate.$s.$fileBaseName.'-'.$i.'-'.$j.'-'.$k.'.txt';

                    $this->sut->createFile($fileToCreate, $filesContent);

                    $this->assertTrue($this->sut->isFile($fileToCreate));
                }
            }

            $this->assertTrue($this->sut->isDirectory($pathToCreate));
        }
    }


    /**
     * testDirSep
     *
     * @return void
     */
    public function testDirSep(){

        $this->assertTrue($this->sut->dirSep() === DIRECTORY_SEPARATOR);
    }


	/**
	 * testIsFile
	 *
	 * @return void
	 */
	public function testIsFile(){

	    // Test empty values
	    try {
	        $this->sut->isFile(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isFile(new stdClass());
	        $this->exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isFile(0);
	        $this->exceptionMessage = '0 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    $this->assertFalse($this->sut->isFile(''));
	    $this->assertFalse($this->sut->isFile('          '));
	    $this->assertFalse($this->sut->isFile("\n\n\n"));

	    // Test ok values
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt', '');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt'));
	    $this->sut->deleteFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt');
	    $this->assertFalse($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt'));

	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt', 'Hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt'));
        $this->sut->deleteFile($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt');
	    $this->assertFalse($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt'));

	    // Test wrong values
	    $this->assertFalse($this->sut->isFile($this->tempFolder));
	    $this->assertFalse($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'asdfsdf.txt353455'));
	    $this->assertFalse($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'asdfsdf.txt'));
	    $this->assertFalse($this->sut->isFile('49568456'));
	    $this->assertFalse($this->sut->isFile('http://www.adkgadsifi.com/ieriteroter3453458852t.pdf'));
	    $this->assertFalse($this->sut->isFile('http://www.google.com'));
	    $this->assertFalse($this->sut->isFile('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'));
	    $this->assertFalse($this->sut->isFile('http://www.facebook.com'));

	    // Test exceptions
	    // Not necessary
	}


	/**
	 * testIsDirectory
	 *
	 * @return void
	 */
	public function testIsDirectory(){

	    // Test empty values
	    try {
	        $this->sut->isDirectory(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectory(new stdClass());
	        $this->exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectory(0);
	        $this->exceptionMessage = '0 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    $this->assertFalse($this->sut->isDirectory(''));
	    $this->assertFalse($this->sut->isDirectory('          '));
	    $this->assertFalse($this->sut->isDirectory("\n\n\n"));

	    // Test ok values
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder));

	    $averageDirectory = $this->tempFolder.DIRECTORY_SEPARATOR.'some folder';
	    $this->sut->createDirectory($averageDirectory, true);
	    $this->assertTrue($this->sut->isDirectory($averageDirectory));
	    $this->sut->deleteDirectory($averageDirectory);
	    $this->assertFalse($this->sut->isDirectory($averageDirectory));

	    $recursiveDirectory = $this->tempFolder.DIRECTORY_SEPARATOR.'a'.DIRECTORY_SEPARATOR.'b'.DIRECTORY_SEPARATOR.'c';
	    $this->sut->createDirectory($recursiveDirectory, true);
	    $this->assertTrue($this->sut->isDirectory($recursiveDirectory));
	    $this->sut->deleteDirectory($recursiveDirectory);
	    $this->assertFalse($this->sut->isDirectory($recursiveDirectory));
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'a'.DIRECTORY_SEPARATOR.'b'));

	    // Test wrong values
	    $this->assertFalse($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'asdfsdf.txt353455'));
	    $this->assertFalse($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'asdfsdf.txt'));
	    $this->assertFalse($this->sut->isDirectory('49568456'));
	    $this->assertFalse($this->sut->isDirectory('http://www.adkgadsifi.com/ieriteroter3453458852t.pdf'));
	    $this->assertFalse($this->sut->isDirectory('http://www.google.com'));
	    $this->assertFalse($this->sut->isDirectory('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'));
	    $this->assertFalse($this->sut->isDirectory('http://www.facebook.com'));

	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt', '');
	    $this->assertFalse($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt'));

	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt', 'Hello baby');
	    $this->assertFalse($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'File2.txt'));
	}


	/**
	 * testIsDirectoryEqualTo
	 *
	 * @return void
	 */
	public function testIsDirectoryEqualTo(){

	    // Test empty values
	    try {
	        $this->sut->isDirectoryEqualTo(null, null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo(new stdClass(), new stdClass());
	        $this->exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo(0, 0);
	        $this->exceptionMessage = '0 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo('', '');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo("\n\n\n", "\n\n\n");
	        $this->exceptionMessage = '"\n\n\n" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    $this->assertTrue($this->sut->isDirectoryEqualTo($this->tempFolder, $this->tempFolder));

	    // Create some folder structures
	    $dir1 = $this->tempFolder.DIRECTORY_SEPARATOR.'dir1';
	    $dir2 = $this->tempFolder.DIRECTORY_SEPARATOR.'dir2';
	    $dir3 = $this->tempFolder.DIRECTORY_SEPARATOR.'dir3';
	    $dir4 = $this->tempFolder.DIRECTORY_SEPARATOR.'dir4';
	    $this->createDummyDirectoryStucture($dir1, 4, 4, 'somefile', 5, 'file content');
	    $this->createDummyDirectoryStucture($dir2, 4, 4, 'somefile', 5, 'file content');
	    $this->createDummyDirectoryStucture($dir3, 4, 4, 'somefile', 5, 'file conten');
	    $this->createDummyDirectoryStucture($dir4, 8, 2, 'somefile', 2, 'f');

	    $this->assertTrue($this->sut->isDirectoryEqualTo($dir1, $dir1));
	    $this->assertTrue($this->sut->isDirectoryEqualTo($dir1, $dir2));
	    $this->assertTrue($this->sut->isDirectoryEqualTo($dir2, $dir2));
	    $this->assertTrue($this->sut->isDirectoryEqualTo($dir3, $dir3));
	    $this->assertTrue($this->sut->isDirectoryEqualTo($dir4, $dir4));

	    $this->assertFalse($this->sut->isDirectoryEqualTo($dir1, $dir3));
	    $this->assertFalse($this->sut->isDirectoryEqualTo($dir2, $dir3));
	    $this->assertFalse($this->sut->isDirectoryEqualTo($dir1, $dir4));
	    $this->assertFalse($this->sut->isDirectoryEqualTo($dir3, $dir4));

	    $this->sut->deleteFiles($this->sut->findDirectoryItems($dir1, '/^somefile-0-0-0\.txt$/', 'absolute'));
	    $this->assertFalse($this->sut->isDirectoryEqualTo($dir1, $dir2));

	    // Test wrong values
	    // Test exceptions
	    try {
	        $this->sut->isDirectoryEqualTo($this->tempFolder, $this->tempFolder.DIRECTORY_SEPARATOR.'asdfwer');
	        $this->exceptionMessage = 'asdfwer did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo($this->tempFolder, 'etrtert');
	        $this->exceptionMessage = 'etrtert did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEqualTo($this->tempFolder, 'http://www.google.com');
	        $this->exceptionMessage = 'http://www.google.com did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testIsDirectoryEmpty
	 *
	 * @return void
	 */
	public function testIsDirectoryEmpty(){

	    // Test empty values
	    try {
	        $this->sut->isDirectoryEmpty(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty(new stdClass());
	        $this->exceptionMessage = 'new stdClass() did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty(0);
	        $this->exceptionMessage = '0 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty('          ');
	        $this->exceptionMessage = '"         " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty("\n\n\n");
	        $this->exceptionMessage = '"\n\n\n" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    $this->assertTrue($this->sut->isDirectoryEmpty($this->tempFolder));

	    $averageDirectory = $this->tempFolder.DIRECTORY_SEPARATOR.'some folder';
	    $this->sut->createDirectory($averageDirectory);
	    $this->assertTrue($this->sut->isDirectoryEmpty($averageDirectory));
	    $this->sut->createFile($averageDirectory.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
	    $this->assertFalse($this->sut->isDirectoryEmpty($averageDirectory));

	    // Test wrong values
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'File.txt', 'Hello baby');
	    $this->assertFalse($this->sut->isDirectoryEmpty($this->tempFolder));

	    // Test exceptions
	    try {
	        $this->sut->isDirectoryEmpty($this->tempFolder.DIRECTORY_SEPARATOR.'asdfwer');
	        $this->exceptionMessage = 'asdfwer did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty('etrtert');
	        $this->exceptionMessage = 'etrtert did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->isDirectoryEmpty('http://www.google.com');
	        $this->exceptionMessage = 'http://www.google.com did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testFindDirectoryItems
	 *
	 * @return void
	 */
	public function testFindDirectoryItems(){

	    // Test empty values
	    try {
	        $this->sut->findDirectoryItems(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findDirectoryItems(0);
	        $this->exceptionMessage = '0 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findDirectoryItems('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findDirectoryItems('       ');
	        $this->exceptionMessage = '"       " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    $this->assertTrue(ArrayUtils::isEqualTo($this->sut->findDirectoryItems($this->tempFolder, '/file/'), []));
	    $this->assertTrue(ArrayUtils::isEqualTo($this->sut->findDirectoryItems($this->tempFolder, '/.*/'), []));
	    $this->assertTrue(ArrayUtils::isEqualTo($this->sut->findDirectoryItems($this->tempFolder, '/^name$/'), []));

	    // Create a structure of folders and files
	    $this->createDummyDirectoryStucture($this->tempFolder, 4, 4, 'somefile', 5, 'file content');

	    // Test resultFormat = 'name'

	    // Test finding all *.txt files on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'name')) === 4 * 4 * 5);

	    // Test finding all files or folders on the 1st folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*$/', 'name', 0)) === 4);

	    // Test finding all *.txt files on the 1st 2d and 3d folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'name', 0)) === 0);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'name', 1)) === 20);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'name', 2)) === 40);

	    // Test finding all files starting with somefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^somefile.*/', 'name')) === 4 * 4 * 5);

	    // Test finding all files starting with samefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^samefile.*/', 'name')) === 0);

	    // Test finding all files named somefile-2.txt on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-0-2.txt$/', 'name') === ['somefile-0-0-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-1-2.txt$/', 'name') === ['somefile-0-1-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-2-2-2.txt$/', 'name') === ['somefile-2-2-2.txt']);

	    // Test finding all files named *-4.txt on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'name')) === 16);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'name', 0)) === 0);

	    // Test finding all folders named folder-3-3 on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-3-3$/', 'name') === ['folder-3-3']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'name') === ['folder-1-2']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'name', 0) === []);

	    // Test resultFormat = 'relative'

	    // Test finding all *.txt files on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'relative')) === 4 * 4 * 5);

	    // Test finding all files or folders on the 1st folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*$/', 'relative', 0)) === 4);

	    // Test finding all *.txt files on the 1st 2d and 3d folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'relative', 0)) === 0);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'relative', 1)) === 20);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'relative', 2)) === 40);

	    // Test finding all files starting with somefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^somefile.*/', 'relative')) === 4 * 4 * 5);

	    // Test finding all files starting with samefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^samefile.*/', 'relative')) === 0);

	    // Test finding all files named somefile-2.txt on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-0-2.txt$/', 'relative') === ['folder-0-0'.DIRECTORY_SEPARATOR.'somefile-0-0-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-1-2.txt$/', 'relative') === ['folder-0-0'.DIRECTORY_SEPARATOR.'folder-0-1'.DIRECTORY_SEPARATOR.'somefile-0-1-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-2-2-2.txt$/', 'relative') === ['folder-2-0'.DIRECTORY_SEPARATOR.'folder-2-1'.DIRECTORY_SEPARATOR.'folder-2-2'.DIRECTORY_SEPARATOR.'somefile-2-2-2.txt']);

	    // Test finding all files named *-4.txt on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'relative')) === 16);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'relative', 0)) === 0);

	    // Test finding all folders named folder-3-3 on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-3-3$/', 'relative') === ['folder-3-0'.DIRECTORY_SEPARATOR.'folder-3-1'.DIRECTORY_SEPARATOR.'folder-3-2'.DIRECTORY_SEPARATOR.'folder-3-3']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'relative') === ['folder-1-0'.DIRECTORY_SEPARATOR.'folder-1-1'.DIRECTORY_SEPARATOR.'folder-1-2']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'relative', 0) === []);

	    // Test resultFormat = 'absolute'

	    // Test finding all *.txt files on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'absolute')) === 4 * 4 * 5);

	    // Test finding all files or folders on the 1st folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*$/', 'absolute', 0)) === 4);

	    // Test finding all *.txt files on the 1st 2d and 3d folder depth
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'absolute', 0)) === 0);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'absolute', 1)) === 20);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/.*\.txt$/', 'absolute', 2)) === 40);

	    // Test finding all files starting with somefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^somefile.*/', 'absolute')) === 4 * 4 * 5);

	    // Test finding all files starting with samefile on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^samefile.*/', 'absolute')) === 0);

	    // Test finding all files named somefile-2.txt on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-0-2.txt$/', 'absolute') === [$this->tempFolder.DIRECTORY_SEPARATOR.'folder-0-0'.DIRECTORY_SEPARATOR.'somefile-0-0-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-0-1-2.txt$/', 'absolute') === [$this->tempFolder.DIRECTORY_SEPARATOR.'folder-0-0'.DIRECTORY_SEPARATOR.'folder-0-1'.DIRECTORY_SEPARATOR.'somefile-0-1-2.txt']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^somefile-2-2-2.txt$/', 'absolute') === [$this->tempFolder.DIRECTORY_SEPARATOR.'folder-2-0'.DIRECTORY_SEPARATOR.'folder-2-1'.DIRECTORY_SEPARATOR.'folder-2-2'.DIRECTORY_SEPARATOR.'somefile-2-2-2.txt']);

	    // Test finding all files named *-4.txt on the folder
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'absolute')) === 16);
	    $this->assertTrue(count($this->sut->findDirectoryItems($this->tempFolder, '/^.*-4.txt$/', 'absolute', 0)) === 0);

	    // Test finding all folders named folder-3-3 on the folder
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-3-3$/', 'absolute') === [$this->tempFolder.DIRECTORY_SEPARATOR.'folder-3-0'.DIRECTORY_SEPARATOR.'folder-3-1'.DIRECTORY_SEPARATOR.'folder-3-2'.DIRECTORY_SEPARATOR.'folder-3-3']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'absolute') === [$this->tempFolder.DIRECTORY_SEPARATOR.'folder-1-0'.DIRECTORY_SEPARATOR.'folder-1-1'.DIRECTORY_SEPARATOR.'folder-1-2']);
	    $this->assertTrue($this->sut->findDirectoryItems($this->tempFolder, '/^folder-1-2$/', 'absolute', 0) === []);

	    // Test wrong values
	    // Not necessary

	    // Test exceptions
	    // Not necessary
	}


	/**
	 * testFindUniqueDirectoryName
	 *
	 * @return void
	 */
	public function testFindUniqueDirectoryName(){

	    // Test empty values
	    try {
	        $this->sut->findUniqueDirectoryName(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueDirectoryName('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueDirectoryName(new stdClass());
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueDirectoryName('           ');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    // Test generated directory names for the created empty folder
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder) == '1');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder') == 'NewFolder');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', '-') == 'NewFolder');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', '-', true) == 'NewFolder');

	    // Create some folders
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1'));
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder'));

	    // Create a file that is named like a directory (without extension)
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'2', 'test file');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'2'));

	    // Verify generated dir names when folders already exist at destination path
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder) == '3');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder') == 'NewFolder-1');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', '', '-', true) == '1-NewFolder');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-1');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');

	    // Create some more folders
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'3');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'3'));
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder-1');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder-1'));
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder-copy-1');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'NewFolder-copy-1'));

	    // Verify generated names again
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder) == '4');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder') == 'NewFolder-2');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', '', '-', true) == '1-NewFolder');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', 'copy', '-', false) == 'NewFolder-copy-2');
	    $this->assertTrue($this->sut->findUniqueDirectoryName($this->tempFolder, 'NewFolder', 'copy', '-', true) == 'copy-1-NewFolder');

	    // Test wrong values
	    // not necessary

	    // Test exceptions
	    // not necessary
	}


	/**
	 * testFindUniqueFileName
	 *
	 * @return void
	 */
	public function testFindUniqueFileName(){

	    // Test empty values
	    try {
	        $this->sut->findUniqueFileName(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueFileName('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueFileName(new stdClass());
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->findUniqueFileName('           ');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    // Test generated file names for the created empty folder
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder) == '1', 'error '.$this->sut->findUniqueFileName($this->tempFolder));
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt') == 'NewFile.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', '-') == 'NewFile.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', '-', true) == 'NewFile.txt');

	    // Create some files
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'1', 'hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'1'));
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile.txt', 'hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile.txt'));

	    // Create a folder that is named like a possible file
	    $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'2');
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'2'));

	    // Verify generated file names when files already exist at destination path
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder) == '3');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt') == 'NewFile-1.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-1.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');

	    // Create some more files
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'3', 'hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'3'));
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile-1.txt', 'hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile-1.txt'));
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt', 'hello baby');
	    $this->assertTrue($this->sut->isFile($this->tempFolder.DIRECTORY_SEPARATOR.'NewFile-copy-1.txt'));

	    // Verify generated names again
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder) == '4');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt') == 'NewFile-2.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', '', '-', true) == '1-NewFile.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', 'copy', '-', false) == 'NewFile-copy-2.txt');
	    $this->assertTrue($this->sut->findUniqueFileName($this->tempFolder, 'NewFile.txt', 'copy', '-', true) == 'copy-1-NewFile.txt');

	    // Test wrong values
	    // not necessary

	    // Test exceptions
	    // not necessary
	}


	/**
	 * testCreateDirectory
	 *
	 * @return void
	 */
	public function testCreateDirectory(){

	    // Test empty values
	    try {
	        $this->sut->createDirectory(null);
	        $this->exceptionMessage = 'Null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createDirectory('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createDirectory('     ');
	        $this->exceptionMessage = '"     " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createDirectory("\n\n\n");
	        $this->exceptionMessage = '"     " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test1'));
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test1'));

	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1234'));
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1234'));

	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'-go-'));
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'-go-'));

	    // Test already existing folders
	    $this->assertTrue(!$this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test1'));
	    $this->assertTrue(!$this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1234'));
	    $this->assertTrue(!$this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'-go-'));

	    // Test already existing files
	    $this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'3', 'hello baby');
	    try {
	        $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'3');
	        $this->exceptionMessage = 'basepath did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test creating recursive folders
	    try {
	        $this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5');
	        $this->exceptionMessage = 'test55 did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5', true));
	    $this->assertTrue($this->sut->isDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test55'.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'tes5'.DIRECTORY_SEPARATOR.'t5'));

	    // Test wrong values
	    // not necessary

	    // Test exceptions
	    try {
	        $this->sut->createDirectory('\345\ertert');
	        $this->exceptionMessage = '\345\ertert did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createDirectory(['\345\ertert', 1]);
	        $this->exceptionMessage = '\345\ertert did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testCreateTempDirectory
	 *
	 * @return void
	 */
	public function testCreateTempDirectory(){

	    // Test empty values
	    try {
	        $this->sut->createTempDirectory(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createTempDirectory('   ');
	        $this->exceptionMessage = '"    " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createTempDirectory([]);
	        $this->exceptionMessage = '[] did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->createTempDirectory("\n\n\n");
	        $this->exceptionMessage = '"\n\n\n"did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values

	    // Create a temp directory without specifying a name
	    $emptyTempFolder = $this->sut->createTempDirectory('');
	    $this->assertTrue($this->sut->isDirectoryEmpty($emptyTempFolder));
	    $this->assertTrue(NumericUtils::isNumeric(StringUtils::getFileNameWithExtension($emptyTempFolder)));

	    // Create a temp directory with a name
	    $someTempFolder = $this->sut->createTempDirectory('some-temp-folder');
	    $this->assertTrue($this->sut->isDirectoryEmpty($someTempFolder));
	    $this->assertTrue(strpos($someTempFolder, 'some-temp-folder') !== false);

	    // Try to create a temp folder with the same name
	    $someTempFolder2 = $this->sut->createTempDirectory('some-temp-folder');
	    $this->assertTrue($this->sut->isDirectoryEmpty($someTempFolder2));
	    $this->assertFalse(($someTempFolder === $someTempFolder2));
	    $this->assertTrue(strpos($someTempFolder, 'some-temp-folder') !== false);

	    // Test wrong values
	    // not necesary

	    // Test exceptions
	    // already tested
	}


	/**
	 * testGetDirectoryList
	 *
	 * @return void
	 */
	public function testGetDirectoryList(){

	    $validationManager = new ValidationManager();

	    // Test empty values
	    try {
	        $this->sut->getDirectoryList(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectoryList('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectoryList('       ');
	        $this->exceptionMessage = '"      " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values

	    // Create some folders and files
	    $this->assertTrue($this->sut->createFile($this->tempFolder.DIRECTORY_SEPARATOR.'file.txt', 'hello baby'));
	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'test1'));
	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'1234'));
	    $this->assertTrue($this->sut->createDirectory($this->tempFolder.DIRECTORY_SEPARATOR.'-go-'));

	    // Check that list is ok
	    $res = $this->sut->getDirectoryList($this->tempFolder);
	    $this->assertTrue($validationManager->isArray($res));
	    $this->assertTrue(count($res) == 4);
	    $this->assertTrue(in_array('file.txt', $res));
	    $this->assertTrue(in_array('test1', $res));
	    $this->assertTrue(in_array('1234', $res));
	    $this->assertTrue(in_array('-go-', $res));

	    // Check sorted lists
	    $res = $this->sut->getDirectoryList($this->tempFolder, 'nameAsc');
	    $this->assertTrue(ArrayUtils::isEqualTo($res, ['-go-', '1234', 'file.txt', 'test1']));

	    $res = $this->sut->getDirectoryList($this->tempFolder, 'nameDesc');
	    $this->assertTrue(ArrayUtils::isEqualTo($res, ['test1', 'file.txt', '1234', '-go-']));

	    // TODO - test sort by modification date
	    //$res = $this->sut->getDirectoryList($this->tempFolder, 'mDateAsc');
	    //$this->assertTrue(ArrayUtils::isEqualTo($res, ['file.txt', 'test1', '1234', '-go-']));

	    //$res = $this->sut->getDirectoryList($this->tempFolder, 'mDateDesc');
	    //$this->assertTrue(ArrayUtils::isEqualTo($res, ['-go-', '1234', 'test1', 'file.txt']));

	    // Test wrong values
	    // Test exceptions
	    try {
	        $this->sut->getDirectoryList('wrtwrtyeyery');
	        $this->exceptionMessage = 'wrtwrtyeyery did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectoryList([1,2,3,4]);
	        $this->exceptionMessage = 'wrtwrtyeyery did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testGetDirectorySize
	 *
	 * @return void
	 */
	public function testGetDirectorySize(){

	    // Test empty values
	    try {
	        $this->sut->getDirectorySize(null);
	        $this->exceptionMessage = 'null did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectorySize('');
	        $this->exceptionMessage = '"" did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectorySize('       ');
	        $this->exceptionMessage = '"      " did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    // Test ok values
	    $this->createDummyDirectoryStucture($this->tempFolder, 4, 4, 'somefile', 5, 'file content');
	    $this->assertTrue($this->sut->getDirectorySize($this->tempFolder) === 960);

	    $this->createDummyDirectoryStucture($this->tempFolder.DIRECTORY_SEPARATOR.'testsize-1', 2, 2, 'biggerFile', 2, StringUtils::generateRandom(250, 250));
	    $this->assertTrue($this->sut->getDirectorySize($this->tempFolder.DIRECTORY_SEPARATOR.'testsize-1') === 2000);

	    $this->createDummyDirectoryStucture($this->tempFolder.DIRECTORY_SEPARATOR.'testsize-2', 2, 2, 'biggerFile', 2, StringUtils::generateRandom(1250, 1250));
	    $this->assertTrue($this->sut->getDirectorySize($this->tempFolder.DIRECTORY_SEPARATOR.'testsize-2') === 10000);

	    $this->assertTrue($this->sut->getDirectorySize($this->tempFolder) === 12960);

	    // Test wrong values
	    // Test exceptions
	    try {
	        $this->sut->getDirectorySize('wrtwrtyeyery');
	        $this->exceptionMessage = 'wrtwrtyeyery did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }

	    try {
	        $this->sut->getDirectorySize([1,2,3,4]);
	        $this->exceptionMessage = 'wrtwrtyeyery did not cause exception';
	    } catch (Throwable $e) {
	        // We expect an exception to happen
	    }
	}


	/**
	 * testCopyDirectory
	 *
	 * @return void
	 */
	public function testCopyDirectory(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}


	/**
	 * testSyncDirectories
	 *
	 * @return void
	 */
	public function testSyncDirectories(){

	    // Test empty values
	    // TODO

	    // Test ok values
	    // TODO

	    // Test wrong values
	    // TODO

	    // Test exceptions
	    // TODO
	}


	// TODO - Add all missing tests
}

?>