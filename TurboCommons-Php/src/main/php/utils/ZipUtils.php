<?php

namespace com\edertone\turboCommons\src\main\php\utils;


/** Zip utils
 *
 * IMPORTANT! This class requires ZipArchive PHP library for some operations.
 */
class ZipUtils{

	/**
	 * Add a folder to a zip and save the file to an FTP folder. It will return a boolean telling if the file zip success or not
	 *
	 * @param string $folderPath		The folder path to be zipped. Final / is not necessary
	 * @param string $destinationPath	The destination path to save the generated zip file, including the file name and format. Example: ftp/folder/file.zip
	 * @param int $permisions			The zip file permisions. 0644 by default.
	 *
	 * @return Boolean
	 *
	 */
	public static function addFolderToZip($folderPath, $destinationPath, $permisions = 0644){

		// Verify if zip extension is available on PHP or not
		if (!extension_loaded('zip')){

			error_log('ZipArchive not installed');
			return false;
		}

		// Verify that the folder that we want to add exists on filesystem
		if(!file_exists($folderPath)){

			error_log('Folder to zip does not exist: '.$folderPath);
			return false;
		}

		// Create the zip file
		$zip = new ZipArchive;

		if ($zip->open($destinationPath, ZIPARCHIVE::CREATE) !== true) {

			error_log('Destination path is not valid: '.$destinationPath);
			return false;
		}

		// Initialize an iterator and pass the directory to be processed
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS));

		// Iterate over the directory and add each file found to the zip archive
		foreach ($iterator as $k){

			$relativePath = rawurldecode(substr((string)$k, strlen($folderPath) + 1));
			$zip->addFile(realpath($k), $relativePath);
		}

		// Close and save archive
		$zip->close();

		// Set file permisions
		if(file_exists($destinationPath)){
			chmod($destinationPath, $permisions);
		}
		else{
			error_log('Destination path does not exist: '.$destinationPath);
			return false;
		}

		return true;
	}
}

?>