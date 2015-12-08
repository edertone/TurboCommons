<?php

	namespace com\edertone\libTurboPhp\src\main\php\utils;


/** File system utils */
class FileSystemUtils{


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
	 * Gives us the current OS directory sepparator character, so we can build cross platform file paths
	 *
	 * @return string The current OS directory sepparator character
	 */
	public static function getDirectorySepparator(){

		return DIRECTORY_SEPARATOR;
	}


	/**
	 * Create a directory to the specified filesystem path
	 *
	 * @param string $path The full path to the directoy we want to create
	 * @param int $mode Is 0755 by default, which means the widest possible access. Ignored on windows
	 * @param bool $recursive Allows the creation of nested directories specified in the pathname. Defaults to false.
	 *
	 * @return bool Returns true on success or false if the folder already exists (an exception may be also thrown if a file exists with the same name).
	 */
	public static function createDirectory($path, $mode = 0755, $recursive = false){

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
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public static function deleteDirectory($path, $deleteDirectoryItself = true){

		$path = StringUtils::formatFilePath($path);

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
	 * Checks if the specified folder is empty or not
	 *
	 * @param string $path The path to the directory we want to check
	 *
	 * @return null|boolean True if directory is empty, false if not, and null if does not exist or cannot be read
	 */
	public static function isDirectoryEmpty($path) {

		if (!is_readable($path)) return null;

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
	 * @param string $path The file full path
	 *
	 * @return string The file contents (binary or string), an empty string if the file does not exist or false if the file cannot be read.
	 */
	public static function readFile($path){

		if(!is_file($path)){

			return '';
		}

		return file_get_contents($path, true);
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