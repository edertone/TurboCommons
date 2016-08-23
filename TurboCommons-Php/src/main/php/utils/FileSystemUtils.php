<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use Exception;
use DirectoryIterator;


/**
 * Class that helps with the most common file system operations
 */
class FileSystemUtils{


	/**
	 * Check if the specified path is a file or not
	 *
	 * @param string $path The (supposed) file path
	 *
	 * @return bool true if the path exists and is a file, false otherwise.
	 */
	public static function isFile($path){

		return is_file($path);
	}


	/**
	 * Check if the specified path is a directory or not
	 *
	 * @param string $path The (supposed) directory path
	 *
	 * @return bool true if the path exists and is a directory, false otherwise.
	 */
	public static function isDirectory($path){

		return is_dir($path);
	}


	/**
	 * Checks that the specified folder is empty
	 *
	 * @param string $path The path to the directory we want to check
	 *
	 * @return boolean True if directory is empty, false if not. If it does not exist or cannot be read, an exception will be generated
	 */
	public static function isDirectoryEmpty($path) {

		if (!is_readable($path)){

			throw new Exception('FileSystemUtils->isDirectoryEmpty: Path does not exist: '.$path);
		}

		$handle = opendir($path);

		while (false !== ($entry = readdir($handle))) {

			if ($entry != '.' && $entry != '..') {

				return false;
			}
		}

		// Required on windows to prevent permision denied errors
		closedir($handle);

		return true;
	}


	/**
	 * Gives us the current OS directory separator character, so we can build cross platform file paths
	 *
	 * @return string The current OS directory separator character
	 */
	public static function getDirectorySeparator(){

		return DIRECTORY_SEPARATOR;
	}


	/**
	 * Search for a folder name that does not exist on the provided path.
	 *
	 * If we want to create a new folder inside another one without knowing for sure what does it contain, this method will
	 * guarantee us that we have a unique directory name that does not collide with any other folder or file that currently exists on the path.
	 *
	 * NOTE: This method does not create any folder or alter the given path in any way.
	 *
	 * @param string $path The full path to the directoy we want to check for a unique folder name
	 * @param string $desiredName We can specify a suggested name for the unique directory. This method will verify that it does not exist, or otherwise give us a name that is unique for the given path
	 * @param string $text Text that will be appended to the suggested name in case it already exists. For example: Setting text to 'copy' will generate a result like 'NewFolder-copy-1' if a folder named 'NewFolder' already exists
	 * @param string $separator String that will be used to join the suggested name with the text and the numeric file counter. For example: Setting separator to '---' will generate a result like 'NewFolder---copy---1' if a folder named 'NewFolder' already exists
	 * @param string $isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result. For example, setting this to true will generate a result like 'copy-1-NewFolder' if a folder named 'NewFolder' already exists
	 *
	 * @return string A directory name that can be safely created on the specified path, cause no one exists with the same name (No path is returned with this method, only a directory name. For example: 'folder-1', 'directoryName-5', etc..).
	 */
	public static function findUniqueDirectoryName($path, $desiredName = '', $text = '', $separator = '-', $isPrefix = false){

		$i = 1;
		$path = StringUtils::formatPath($path);
		$result = ($desiredName == '' ? $i : $desiredName);

		while(is_dir($path.DIRECTORY_SEPARATOR.$result) || is_file($path.DIRECTORY_SEPARATOR.$result)){

			$result = self::_generateUniqueNameAux($i, $desiredName, $text, $separator, $isPrefix);

			$i++;
		}

		return $result;
	}


	/**
	 * Search for a file name that does not exist on the provided path.
	 *
	 * If we want to create a new file inside a folder without knowing for sure what does it contain, this method will
	 * guarantee us that we have a unique file name that does not collide with any other file or folder that currently exists on the path.
	 *
	 * NOTE: This method does not create any file or alter the given path in any way.
	 *
	 * @param string $path The full path to the directoy we want to check for a unique file name
	 * @param string $desiredName We can specify a suggested name for the unique file. This method will verify that it does not exist, or otherwise give us a name that is unique for the given path
	 * @param string $text Text that will be appended to the suggested name in case it already exists. For example: Setting text to 'copy' will generate a result like 'NewFile-copy-1' if a file named 'NewFile' already exists
	 * @param string $separator String that will be used to join the suggested name with the text and the numeric file counter. For example: Setting separator to '---' will generate a result like 'NewFile---copy---1' if a file named 'NewFile' already exists
	 * @param string $isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result. For example, setting this to true will generate a result like 'copy-1-NewFile' if a file named 'NewFile' already exists
	 *
	 * @return string A file name that can be safely created on the specified path, cause no one exists with the same name (No path is returned with this method, only a file name. For example: 'file-1', 'fileName-5', etc..).
	 */
	public static function findUniqueFileName($path, $desiredName = '', $text = '', $separator = '-', $isPrefix = false){

		$i = 1;
		$path = StringUtils::formatPath($path);
		$result = ($desiredName == '' ? $i : $desiredName);
		$extension = StringUtils::extractFileExtension($desiredName);

		while(is_dir($path.DIRECTORY_SEPARATOR.$result) || is_file($path.DIRECTORY_SEPARATOR.$result)){

			$result = self::_generateUniqueNameAux($i, StringUtils::extractFileNameWithoutExtension($desiredName), $text, $separator, $isPrefix);

			if($extension != ''){

				$result .= '.'.$extension;
			}

			$i++;
		}

		return $result;
	}


	/**
	 * Auxiliary method that is used by the findUniqueFileName and findUniqueDirectoryName methods
	 *
	 * @param int $i Current index for the name generation
	 * @param string $desiredName Desired name as used on the parent method
	 * @param string $text text name as used on the parent method
	 * @param string $separator separator name as used on the parent method
	 * @param bool $isPrefix isPrefix name as used on the parent method
	 *
	 * @return string The generated name
	 */
	private static function _generateUniqueNameAux($i, $desiredName, $text, $separator, $isPrefix){

		$result = [];

		if($isPrefix){

			if($text != ''){

				array_push($result, $text);
			}

			array_push($result, $i);

			if($desiredName != ''){

				array_push($result, $desiredName);
			}

		}else{

			if($desiredName != ''){

				array_push($result, $desiredName);
			}

			if($text != ''){

				array_push($result, $text);
			}

			array_push($result, $i);
		}

		return implode($separator, $result);
	}


	/**
	 * Create a directory at the specified filesystem path
	 *
	 * @param string $path The full path to the directoy we want to create
	 * @param bool $recursive Allows the creation of nested directories specified in the pathname. Defaults to false.
	 * @param int $mode Is 0755 by default, which means the widest possible access. Ignored on windows
	 *
	 * @return bool Returns true on success or false if the folder already exists (an exception may be also thrown if a file exists with the same name).
	 */
	public static function createDirectory($path, $recursive = false, $mode = 0755){

		// If folder already exists, nothing to do
		if(is_dir($path)){

			return false;
		}

		// If folder exists but is a file, we must launch a warning
		if(is_file($path)){

			trigger_error('Specified path <'.$path.'> is an existing file', E_USER_WARNING);
			return false;
		}

		// Create the requested folder
		try{

			mkdir($path, $mode, $recursive);

		}catch(Exception $e){

			// It is possible that multiple concurrent calls create the same folder. To prevent unwanted warnings for this situation (that in fact is not a problem), we
			// will check that the folder is still not created. If it exists, another concurrent call created it, so we have no problem with it.
			if(!is_dir($path)){

				trigger_error($e->getMessage(), E_USER_WARNING);
				return false;
			}
		}

		return chmod($path, $mode);
	}


	/**
	 * Create a TEMPORARY directory on the operating system tmp files location, and gives us the full path to access it.
	 * OS should take care of its removal but it is not assured, so it is recommended to make sure all the tmp data is deleted after
	 * using it (This is specially important if the tmp folder contains sensitive data). Even so, this method tries to delete the generated tmp
	 * folder by default when the application ends.
	 *
	 * @param string $desiredName A name we want for the new directory to be created. If name is not available, a unique one (based on the given name) will be generated automatically.
	 * @param boolean $deleteOnExecutionEnd Defines if the generated temp folder must be deleted after the current script execution finishes. Note that when files inside the folder are still used by the app or OS, exceptions or problems may happen, and it is not 100% guaranteed that the folder will be always deleted.
	 *
	 * @return string The full path to the newly created temporary directory, including the directory itself. For example: C:\Users\Me\AppData\Local\Temp\MyDesiredName
	 */
	public static function createTempDirectory($desiredName, $deleteOnExecutionEnd = true) {

		$tempRoot = StringUtils::formatPath(sys_get_temp_dir());

		$tempDirectory = $tempRoot.DIRECTORY_SEPARATOR.self::findUniqueDirectoryName($tempRoot, $desiredName);

		if(!self::createDirectory($tempDirectory)){

			throw new Exception('FileSystemUtils->createTempDirectory: Could not create TMP directory '.$tempDirectory);
		}

		// Add a shutdown function to try to delete the file when the current script execution ends
		if($deleteOnExecutionEnd){

			register_shutdown_function(function () use ($tempDirectory) {

				self::deleteDirectory($tempDirectory);
			});
		}

		return $tempDirectory;
	}


	/**
	 * Gives the list of items that are stored on the specified folder. It will give files and directories, and each element will be the item name, without the path to it.
	 * The contents of any subfolder will not be listed. We must call this method for each child folder if we want to get it's list.
	 * (The method ignores the . and .. items if exist).
	 *
	 * @param string $path Full path to the directory we want to list
	 * @param string $sort Specifies the sort for the result:<br>
	 * &emsp;&emsp;'' will not sort the result.<br>
	 * &emsp;&emsp;'nameAsc' will sort the result by filename ascending.
	 * &emsp;&emsp;'nameDesc' will sort the result by filename descending.
	 * &emsp;&emsp;'mDateAsc' will sort the result by modification date ascending.
	 * &emsp;&emsp;'mDateDesc' will sort the result by modification date descending.
	 *
	 * @return array The list of item names inside the specified path sorted as requested, or an empty array if no items found inside the folder.
	 */
	public static function getDirectoryList($path, $sort = ''){

		// If folder does not exist, we will throw an exception
		if(!is_dir($path)){

			throw new Exception('Specified path <'.$path.'> does not exist or is not a directory');
		}

		// Get all the folder contents
		$result = [];

		if($path != ''){

			$dirIterator = new DirectoryIterator($path);

			foreach ($dirIterator as $fileInfo){

				if(!$fileInfo->isDot()){

					switch($sort) {

						case 'mDateAsc':
						case 'mDateDesc':
							$result[$fileInfo->getMTime()] = $fileInfo->getFilename();
							break;

						default:
							array_push($result, $fileInfo->getFilename());
							break;
					}
				}
			}
		}

		// Apply result sorting as requested
		switch($sort) {

			case 'nameAsc':
				sort($result);
				break;

			case 'nameDesc':
				rsort($result);
				break;

			case 'mDateAsc':
				$result = call_user_func_array('array_merge', ksort($result));
				break;

			case 'mDateDesc':
				$result = call_user_func_array('array_merge', krsort($result));
				break;
		}

		return $result;
	}


	/**
	 * Calculate the full size in bytes for a specified folder.
	 *
	 * @param string $path Full path to the directory we want to calculate its size
	 *
	 * @return int the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public static function getDirectorySize($path){

		$result = 0;

		if($path != ''){

			$dirIterator = new DirectoryIterator($path);

			foreach ($dirIterator as $fileInfo){

				$currentFile = $path.DIRECTORY_SEPARATOR.$fileInfo->getFilename();

				if(!$fileInfo->isDot()){

					if (is_dir($currentFile)) {

						$result += self::getDirectorySize($currentFile);

					}else {

						$result += filesize($currentFile);
					}
				}
			}
		}

		return $result;

	}


	/**
	 * Delete a directory from the filesystem and return a boolean telling if the directory delete success or not
	 *
	 * @param string $path The path to the directory
	 * @param string $deleteDirectoryItself Set it to true if the specified directory must also be deleted.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public static function deleteDirectory($path, $deleteDirectoryItself = true){

		$path = StringUtils::formatPath($path);

		if (!file_exists($path)){

			return false;
		}

		if (!is_dir($path)){

			return false;
		}

		$dirIterator = new DirectoryIterator($path);

		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot()){

				if(is_dir($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

					if(!self::deleteDirectory($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

						return false;
					}

				}else{

					if(!unlink($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

						return false;
					}
				}
			}
		}

		// Only for windows, to prevent folder deleting permision error
		unset($dirIterator);
		unset($fileInfo);

		return $deleteDirectoryItself ? rmdir($path) : true;
	}


	/**
	 * Create a file to the specified filesystem path and write the specified data to it.
	 *
	 * @param string $path The full path where the file will be stored, including the full file name
	 * @param string $fileData Information to store on the file (a string, a block of bytes, etc...)
	 * @param int $permisions The file permisions. If not specified, the default system one will be used, (normally 0644)
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public static function createFile($path, $fileData = '', $permisions = ''){

		$fp = fopen($path, 'wb');

		if($fp === false){
			return false;
		}

		$fw = fwrite($fp, $fileData);

		if($fw === false){
			return false;
		}

		if(!fclose($fp)){
			return false;
		}

		// Modify the file permisions if required
		if($permisions != ''){

			if(!chmod($path, $permisions)){
				return false;
			}
		}

		return true;

	}


	/** TODO */
	public static function createTempFile(){

	}


	/**
	 * Gets file size
	 *
	 * @param string $path The file full path
	 *
	 * @return int the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public static function getFileSize($path){

		return filesize($path);
	}


	/**
	 * Read and return a filesystem file contents. Not suitable for big files (More than 5 MB)
	 *
	 * @param string $path The file full or relative path
	 *
	 * @return string The file contents (binary or string). If the file is not found or cannot be read, an exception will be thrown.
	 */
	public static function readFile($path){

		if(!is_file($path)){

			throw new Exception('FileSystemUtils->readFile: File not found - '.$path);
		}

		$contents = file_get_contents($path, true);

		if($contents === false){

			throw new Exception('FileSystemUtils->readFile: Error reading file - '.$path);
		}

		return $contents;
	}


	/**
	 * Reads a file and performs a buffered output to the browser, by sending it as small fragments.<br>
	 * This method is mandatory with big files, as reading the whole file to memory will cause the script or RAM to fail.<br><br>
	 *
	 * Adapted from code suggested at: http://php.net/manual/es/function.readfile.php
	 *
	 * @param string $path The file full path
	 * @param float $downloadRateLimit If we want to limit the download rate of the file, we can do it by setting this value to > 0. For example: 20.5 will set the file download rate to 20,5 kb/s
	 *
	 * @return int the number of bytes read from the file.
	 */
	public static function readFileBuffered($path, $downloadRateLimit = 0){

		if(!is_file($path)){

			return 0;
		}

		// Disable script time limit
		set_time_limit(0);

		// How many bytes per chunk
		if($downloadRateLimit <= 0){

			$chunkSize = 1*(1024*1024);

		}else{

			$chunkSize = round($downloadRateLimit * 1024);
		}

		$buffer = '';
		$cnt = 0;

		$handle = fopen($path, 'rb');

	   	if($handle === false) {

	   		return $cnt;
	   	}

	   	// Output the file chunk by chunk
		while(!feof($handle)){

			$buffer = fread($handle, $chunkSize);

			echo $buffer;

			// This makes sure that when output buffering is on, the file data will be written to browser
			if(ob_get_level() > 0){

				ob_flush();
			}

			// Forces a write of the data to the browser
		    flush();

			$cnt += strlen($buffer);

			// Sleep one second if download rate limit is set
			if($downloadRateLimit > 0){

				sleep(1);
			}
		}

		$status = fclose($handle);

		// return num. bytes delivered like readfile() does.
		return $cnt;
	}


	/**
	 * Copies a file from a source location to the defined destination
	 *
	 * @param string $sourcePath The full path to the source file that must be copied (including the filename itself).
	 * @param string $destPath The full path to the destination where the file must be copied (including the filename itself).
	 *
	 * @return boolean Returns true on success or false on failure.
	 */
	public static function copyFile($sourcePath, $destPath){

		return copy($sourcePath, $destPath);

	}


	/**
	 * Delete a filesystem file.
	 *
	 * @param string $path	The file filesystem path
	 *
	 * @return boolean Returns true on success or false on failure.
	 */
	public static function deleteFile($path){

		if(!is_file($path)){

			return false;
		}

		return unlink($path);

	}

}

?>