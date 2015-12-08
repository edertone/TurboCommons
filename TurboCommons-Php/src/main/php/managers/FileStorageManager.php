<?php

namespace com\edertone\libTurboPhp\src\main\php\managers;


/**
 * This class is an interface to a storage system that can be used to store milions of binary files. The system supports a variety of different usages:<br>
 *
 * - class methods that start with 'dB' to manage files that are linked to db entities.
 * - class methods that start with 'tmp' to manage temporary files. The storage system will take care of the life time for the files, leaving us free to worry about deleting them.
 * - class methods that start with 'custom' to manage the custom folder. It is a place where anything can be freely stored by the user, just like a standard file system or ftp storage.
 * - class methods that start with 'cache' to manage files that contain different types of cached data like php cached pages, thumbnails, or any other resources.
 * - class methods that start with 'binary' to manage executable command line external applications that may be required by the project.
 *
 * The following folders can be found on the storage system: db, tmp, custom, cache, binary
 * The cache folder is the only one that can be safely deleted, as it will be regenerated each time a resource is not found.
 */
class FileStorageManager extends BaseStrictClass{


	/** The path where the root of the storage folder is located (without the last /, for example: ../storage). It must have permission for the current script and cannot be used for nothing more than storage system. */
	private $_storagePath = '';


	/**
	 * @param string $storagePath The path where the root of the storage folder is located (without the last /, for example: ../storage). It must have permission for the current script and cannot be used for nothing more than storage system.
	 */
	public function __construct($storagePath){

		$this->_storagePath = StringUtils::formatFilePath($storagePath);
	}


	/**
	 * Adds a file to the storage system db folder from already loaded binary data
	 *
	 * @param string $entityName Name for the entity or entity class name that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name that we want to set to the stored file on file system
	 * @param string $binaryData Binary data that will be stored to the file
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function dBAddFile($entityName, $id, $fileName, $binaryData){

		$folderData = $this->_createMassiveFolderStructure('db', $entityName, $id);

		return FileSystemUtils::createFile($folderData[0].DIRECTORY_SEPARATOR.$folderData[1].$fileName, $binaryData);
	}


	/**
	 * Adds a file to the storage system db folder from an existing filesystem path, by copying it. Original file remains untouched.
	 *
	 * @param string $entityName Name for the entity that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name that we want to set to the stored file on file system
	 * @param string $path Full path to the file that will be copied to the storage system. Must include the file name itself
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function dBAddFileFromPath($entityName, $id, $fileName, $path){

		$folderData = $this->_createMassiveFolderStructure('db', $entityName, $id);

		return FileSystemUtils::copyFile($path, $folderData[0].DIRECTORY_SEPARATOR.$folderData[1].$fileName);
	}


	/**
	 * Performs maximum jpg optimization possible to an existing db jpg file by calling the jpegtran command line tool.
	 *
	 * @param string $entityName Name for the entity that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name for the entity instance linked file that we want to get
	 *
	 * @see PictureUtils::compressJpgPicture
	 *
	 * @return boolean True if compression was performed or false if something failed
	 */
	public function dBCompressJpgPicture($entityName, $id, $fileName){

		$path = $this->_getMassiveFolderPath('db', $entityName, $id, $fileName);

		if(!is_file($path)){

			trigger_error('Specified db storage file does not exist! '.$path, E_USER_WARNING);
			die();
		}

		return PictureUtils::compressJpgPicture($path);
	}


	/**
	 * Get the filesize for the specified storage file db folder
	 *
	 * @param string $entityName Name for the entity that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name for the entity instance linked file that we want to get its size
	 *
	 * @return int the size of the file in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public function dBGetFileSize($entityName, $id, $fileName){

		return FileSystemUtils::getFileSize($this->_getMassiveFolderPath('db', $entityName, $id, $fileName));
	}



	/**
	 * Get the total size for all the files that are linked to the specified entity instance
	 *
	 * @param string $entityName Name for the entity that links the files
	 * @param int $id Id for the entity instance that links the files
	 *
	 * @return int the total size of the files in bytes, or false (and generates an error of level E_WARNING) in case of an error.
	 */
	public function dBGetAllFilesSize($entityName, $id){

		// TODO: Aixo té tota la pinta de estar malament!! en aquest folder hi haurà arxius de més d'una entitat de forma que caldra recorrels i calcular només el tamany dels que siguin de la entitat indicada!

		return FileSystemUtils::getDirectorySize($this->_getMassiveFolderPath('db', $entityName, $id));
	}


	/**
	 * Get the binary data for the specified file from storage system db folder
	 *
	 * @param string $entityName Name for the entity that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name for the entity instance linked file that we want to get
	 *
	 * @return string the file binary information
	 */
	public function dBReadFile($entityName, $id, $fileName){

		$path = $this->_getMassiveFolderPath('db', $entityName, $id, $fileName);

		$res = FileSystemUtils::readFile($path);

		if($res === ''){

			trigger_error('Specified db storage file does not exist! '.$path, E_USER_WARNING);
			die();
		}

		return $res;
	}


	/**
	 * Get the binary data for the specified file from storage system db folder, but writting it directly to browser as a buffered stream.<br>
	 * This method is used to download very large files as the data is not totally loaded on the script memory to prevent it to fail.
	 *
	 * @param string $entityName Name for the entity that links the file
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName name for the entity instance linked file that we want to get
	 *
	 * @return  int the number of bytes read from the file.
	 */
	public function dBReadFileBuffered($entityName, $id, $fileName){

		return FileSystemUtils::readFileBuffered($this->_getMassiveFolderPath('db', $entityName, $id, $fileName));
	}


	/**
	 * Delete the specified single file from the specified entity instance. Normaly used when a file that is linked to an entity is not used anymore.
	 *
	 * @param string $entityName Name for the entity that links the file to delete
	 * @param int $id Id for the entity instance that links the file
	 * @param string $fileName The filename we want to delete from the entity
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function dBDeleteFileFromId($entityName, $id, $fileName){

		// Convert the specified id to a 9 digits fixed lenght string
		$idCode = str_pad($id, 9, '0', STR_PAD_LEFT);

		// Convert the fixed string id to a list of strings with 2 digits / 2 digits / 3 digits / 2 digits each
		$f = array(substr($idCode, 0, 2), substr($idCode, 2, 2), substr($idCode, 4, 3), substr($idCode, 7, 2));

		// Get the path where the entity files are stored
		$basePath = $this->_storagePath.DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.$entityName.DIRECTORY_SEPARATOR.$f[0].DIRECTORY_SEPARATOR.$f[1].DIRECTORY_SEPARATOR.$f[2];

		$dirIterator = new DirectoryIterator($basePath);

		// Delete the specified file for the specified entity and id
		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot() && $fileInfo->getFilename() == $f[3].'_'.$fileName){

				if(!unlink($basePath.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

					return false;
				}

				break;
			}
		}

		// Only for windows, to prevent folder deleting permision error
		unset($dirIterator);
		unset($fileInfo);

		// Delete the parent files folder if it is empty
		if(FileSystemUtils::isDirectoryEmpty($basePath)){

			FileSystemUtils::deleteDirectory($basePath);
		}

		return true;
	}


	/**
	 * Delete all linked files for a given entity instance. Normally used when an entity is deleted and therefore all the linked files must also be.
	 *
	 * @param string $entityName Name for the entity that links the file or files
	 * @param array|int $ids Id for the entity instance that links the file or files, or an array of ids if we want to delete files from multiple entity instances
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function dBDeleteAllFilesFromId($entityName, $ids){

		return $this->_deleteAllMassiveFolderIdFiles('db', $entityName, $ids);
	}


	/**
	 * Adds the specified file binary data to the storage system as a temporary file.
	 *
	 * @param string $id The identifier we want to assign to the created file. Leaving it empty means an automatic id will be generated.
	 * @param string $binaryData Binary data to be stored on the file
	 * @param number $minutesToLive The number of minutes that the file will be available on storage. Once it is created, after the specified number of minutes passes, the file will be deleted. (One day equals 1440 minutes)
	 *
	 * @return int a numeric identifier that will be required to retrieve this file later. The one we specified or an auto generated one
	 */
	public function tmpAddFile($id = '', $binaryData, $minutesToLive = 2880){

		// Create tmp folder if not exists
		if(!is_dir($this->_storagePath.DIRECTORY_SEPARATOR.'tmp')){

			FileSystemUtils::createDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		}else{

			$this->_tmpClearFiles();
		}

		// Check if a custom id has been specified or not
		if($id == '') {

			// Find an identifier for the file to add
			$fileId = $this->_tmpFindFreeId();

		}else{

			$fileId = $id;

			// Delete any existing file with the same identifier
			$this->_tmpDeleteFile($id);
		}

		// Calculate the file expiry date
		$expiryDate =  date('Y-m-d_H-i-s', strtotime('+'.$minutesToLive.' minutes'));

		// Store the file
		if(!FileSystemUtils::createFile($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$expiryDate.'_'.$fileId, $binaryData)){

			return -1;
		}

		return $fileId;
	}


	/**
	 * Get the binary data for a stored temporary file, given its identifier
	 *
	 * @param string $id Identifier for the file we want to obtain
	 *
	 * @return string The file binary contents
	 */
	public function tmpReadFile($id){

		$dirIterator = new DirectoryIterator($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot()){

				$itemParts = explode('_', $fileInfo->getFilename());

				if($id == $itemParts[2]){

					return FileSystemUtils::readFile($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileInfo->getFilename());
				}
			}
		}

		return null;
	}


	/**
	 * Creates a temporary folder on the storage system.
	 *
	 * @param string $id The identifier we want to assign to the created folder. Leaving it empty means an automatic id will be generated.
	 * @param number $minutesToLive The number of minutes that the folder will be available on storage. Once it is created, after the specified number of minutes passes, the folder and all its contents will be deleted. (One day equals 1440 minutes)
	 *
	 * @return string an identifier that will be required to retrieve this folder contents later or the folder name if we specify it, or empty string if a failure happens.
	 */
	public function tmpAddDirectory($id = '', $minutesToLive = 2880){

		// Create tmp folder if not exists
		if(!is_dir($this->_storagePath.DIRECTORY_SEPARATOR.'tmp')){

			FileSystemUtils::createDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		}else{

			$this->_tmpClearFiles();
		}

		// Find an identifier for the folder to add
		if($id == '') {

			// Find an identifier for the file to add
			$directoryId = $this->_tmpFindFreeId();

		}else{

			$directoryId = $id;

			// If the specified folder already exists, we won't do anything.
			if($this->_tmpGetDirectoryNameFromId($id) != ''){

				return $directoryId;
			}
		}

		// Calculate the folder expiry date
		$expiryDate = date('Y-m-d_H-i-s', strtotime('+'.$minutesToLive.' minutes'));

		// If folder exists, fail
		if(FileSystemUtils::isDirectoryEmpty($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$expiryDate.'_'.$directoryId)){

			return '';
		}

		// Store the folder
		if(!FileSystemUtils::createDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$expiryDate.'_'.$directoryId)){

			return '';
		}

		return $directoryId;
	}


	/**
	 * Gives the full OS filesystem path to a temporary file, given its id.
	 *
	 * @param string $id The identifier for the file we want to retrieve.
	 *
	 * @return string The requested path or an empty string if the file does not exist.
	 */
	public function tmpGetFilePath($id){

		$fileName = $this->_tmpGetDirectoryNameFromId($id);

		if(!file_exists($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileName)){
			return '';
		}

		// Get the full path
		return $this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileName;
	}


	/**
	 * Gives the full OS filesystem path to a temporary directory, given its id. This can be used to add or remove files and subfolders.
	 *
	 * @param string $id The identifier for the directory we want to retrieve.
	 *
	 * @return string The requested path or an empty string if the folder does not exist.
	 */
	public function tmpGetDirectoryPath($id){

		$folderName = $this->_tmpGetDirectoryNameFromId($id);

		if(!is_dir($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$folderName) || $folderName == ''){
			return '';
		}

		// Get the full path
		return $this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$folderName;
	}


	/**
	 * Force the specified temporary folder to be deleted with all its contents, even if it's not expired yet.
	 *
	 * @param string $id Identifier for the directory we want to get the file data
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function tmpDeleteDirectory($id){

		// Find the folder that matches the specified id
		$folderName = $this->_tmpGetDirectoryNameFromId($id);

		// Destroy the folder
		return FileSystemUtils::deleteDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$folderName);
	}


	/**
	 * Method that gives us the full os path to the root of the custom directory (without the last /), so we can use it with other file system operations like create files, list, delete, etc...
	 * The Custom directory is a general purpose folder where we can store any files we want (even manually via ftp).
	 *
	 * @return string the full os path to the root of the custom folder, without the last '/'.
	 */
	public function customGetRootPath(){

		// Create custom folder if not exists
		$basePath = $this->_storagePath.DIRECTORY_SEPARATOR.'custom';

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath);
		}

		return $basePath;
	}


	/**
	 * Creates the folder structure so the specified resource can be cached (if folders already exist, nothing will be done)
	 *
	 * @param string $resourceName Name for the type of cached resource. For example: picture, etc...
	 * @param int $id Id for the resource. This will be used to generate a folder structure that will allow us to store milions of files.
	 *
	 * @return array Array with two values: First the full path that's been created for the given parameters, and secondth the prefix that must be placed at the start of the file names that are placed inside this path.
	 */
	public function cacheCreateFileFolders($resourceName, $id){

		return $this->_createMassiveFolderStructure('cache'.DIRECTORY_SEPARATOR.'resources', $resourceName, $id);
	}


	/**
	 * Adds a file to the cache system from already loaded binary data
	 *
	 * @param string $binaryData Binary data that will be stored to the file
	 * @param string $resourceName Name for the type of cached resource. For example: picture, file, etc...
	 * @param string $fileName name that we want to set to the stored file on file system
	 * @param int $id Id for the resource. This will be used to generate a folder structure that will allow us to store milions of files.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function cacheAddFile($binaryData, $resourceName, $fileName, $id){

		$folderData = $this->_createMassiveFolderStructure('cache'.DIRECTORY_SEPARATOR.'resources', $resourceName, $id);

		// Store the file
		return FileSystemUtils::createFile($folderData[0].DIRECTORY_SEPARATOR.$folderData[1].$fileName, $binaryData);
	}


	/**
	 * Get the binary data for the specified file from storage system
	 *
	 * @param string $resourceName Name for the type of cached resource. For example: picture, phpCode, etc...
	 * @param int $id Id for the resource. This will be used to generate a folder structure that will allow us to store milions of files.
	 * @param string $fileName name that we want to set to the stored file on file system
	 *
	 * @return string the file binary information
	 */
	public function cacheReadFile($resourceName, $id, $fileName){

		return FileSystemUtils::readFile($this->_getMassiveFolderPath('cache'.DIRECTORY_SEPARATOR.'resources', $resourceName, $id, $fileName));
	}


	/**
	 * Delete all files that have been cached for the specified id. Normally used when the original resource that generated the cached files is deleted, and therefore we want to delete also the cached files
	 *
	 * @param string $resourceName Name for the type of cached resource. For example: picture, phpCode, etc...
	 * @param array|int $ids Id for the resource instance that links the file or files, or an array of ids if we want to delete files from multiple resource instances
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function cacheDeleteAllFilesFromId($resourceName, $ids){

		return $this->_deleteAllMassiveFolderIdFiles('cache'.DIRECTORY_SEPARATOR.'resources', $resourceName, $ids);
	}


	/**
	 * Add a list of Js files or raw js code to the storage cache, by merging everything to a single file.
	 *
	 * @param array $files Array where each element contains the full file system path to a js file or raw javascript code.
	 * @param int $buildVersion A numeric value representing the build version of the files to merge. This is important cause the cache will regenerate the cached js file if versions are different.
	 *
	 * @return string The name for the merged file as it is stored on the storage/cache/jsCode folder. For example: iuygfyvgf618tfghjjh_v123v_.js
	 */
	public function cacheAddJsCode(array $files, $buildVersion){

		// Define the jsCode path on the storage cache
		$cacheFolder = $this->_storagePath.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'jsCode';

		// Generate a unique name for the cached file and build version
		$cacheFile = md5(implode('', $files)).'_v'.$buildVersion.'v_.js';

		// Check if we must generate a new version or the current one is still valid
		if(file_exists($cacheFolder.DIRECTORY_SEPARATOR.$cacheFile)){

			return $cacheFile;

		}else{

			// Create the jsCode subFolder if it does not exist on storage/cache
			if(!is_dir($cacheFolder)){

				if(!FileSystemUtils::createDirectory($cacheFolder, 0755, true)){

					trigger_error('Could not create js disk cache folder.', E_USER_ERROR);

					die();
				}
			}

			// Join all the files on a single one
			$merged = '';

			foreach($files as $file){

				// Check if we are dealing with a js script or raw javascript code
				if(substr($file, -3) == '.js' && strpos($file, '/') !== false){

					if(!file_exists($file)){

						throw new Exception('FileStorageManager::cacheAddJsCode Specified js file does not exist: '.$file, E_USER_WARNING);
					}

					$merged .= file_get_contents($file)."\n\n";

				}else{

					$merged .= $file."\n\n";
				}
			}

			file_put_contents($cacheFolder.DIRECTORY_SEPARATOR.$cacheFile, $merged);

			return $cacheFile;
		}
	}


	/**
	 * Begin caching all the output of the current php script, till the end of it.
	 * On successive url loadings, all the generated php output since this method was called will be printed from the cached version, which will live for the specified number of seconds.
	 * If we need to refresh the dynamic content, we can call clearCache in other parts of our application at any moment to reset all cached files.
	 * This method is used to greatly improve performance on php scripts that are heavily intensive on cpu processing or db access and therefore take much time to generate the output. In these cases, generating a cached version is extremely faster as the computation part is avoided.
	 *
	 * @param int $cacheTime Number of seconds to store the cached version of the php script output. (1 hour = 3600 seconds).
	 * @param string $ignoreIfUrl Cache will be ignored if the current url contains the specified string fragment. Very useful to prevent the cache when not in production, for example, setting it to: '_preview'
	 *
	 * @return void
	 */
	public function cachePhpStart($cacheTime = 3600, $ignoreIfUrl = ''){

		// if the current url contains the specified string, cache won't start
		if($ignoreIfUrl != ''){

			if (strpos('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $ignoreIfUrl) !== false) {

				return;
			}
		}

		// Create the phpCode subFolder if it does not exist on storage/cache
		$cacheFolder = $this->_storagePath.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'phpCode';

		if(!is_dir($cacheFolder)){

			if(!FileSystemUtils::createDirectory($cacheFolder, 0755, true)){

				trigger_error('Could not create php disk cache folder.', E_USER_ERROR);

				die();
			}
		}

		// Generate a unique name for the cached file
		$cacheFile = $cacheFolder.DIRECTORY_SEPARATOR.md5($_SERVER['REQUEST_URI']);
		$cacheFileTime = file_exists($cacheFile) ? time() - filemtime($cacheFile) : $cacheTime + 1;

		// Check if we must show the cached version or generate a new one
		if($cacheFileTime < $cacheTime){

			readfile($cacheFile);
			die();

		}else{

			$sto = $this->_storagePath;
			$caf = $cacheFile;

			// Add a function that will be executed on script shutdown, to store all the generated php output to the cache file
			register_shutdown_function(function () use ($sto, $caf) {

				file_put_contents($caf, ob_get_contents());

				ob_end_flush();
			});

			// Start caching all php output
			ob_start();
		}
	}


	/**
	 * Delete all the php cached data
	 *
	 * @return void
	 */
	public function cacheClearPhp(){

		FileSystemUtils::deleteDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'phpCode', false);
	}


	/**
	 * Delete all the JS cached data when the number of cached files exceeds the specified value
	 *
	 * @param number $maxFiles 0 by default. When the Js cache contains more than the specified number of files, js cache folder will be cleared
	 *
	 * @return void
	 */
	public function cacheClearJs($maxFiles = 0){

		try {

			$filesCount = count(FileSystemUtils::getDirectoryList($this->_storagePath.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'jsCode'));

		} catch (Exception $e) {

			return;
		}

		if($filesCount > $maxFiles){

			FileSystemUtils::deleteDirectory($this->_storagePath.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'jsCode', false);
		}
	}


	/**
	 * Deletes ALL the contents inside the cache storage system.
	 *
	 * @return void
	 */
	public function cacheClear(){

		// TODO: Aqui caldra borrar el contingut de la carpeta cache, pero abans de fer-ho, renombrarem la carpeta a algo com ---cache, i després la borrarem. Això ho farem per evitar que hi hagi
		// conflictes a la carpeta mentre s'estigui borrant i per algun motiu es vulguin crear nous arxius a la cache.
	}


	/**
	 * Method that gives us the full os path to the specified application file on the storage binary folder, so we can use it to execute the application or tool.
	 *
	 * @param string $appName The name of the application binary executable that is placed on the storage binary folder. For example: pdfinfo, jpegtran, etc..
	 *
	 * @return string the full os path to the application binary file so it can be executed.
	 */
	public function binaryGetAppPath($appName){

		// Create custom folder if not exists
		$appPath = $this->_storagePath.DIRECTORY_SEPARATOR.'binary'.DIRECTORY_SEPARATOR.$appName;

		if(!is_executable($appPath)){

			trigger_error('Specified '.$appName.' application binary ('.$appPath.') does not exist or execute permisions are disabled', E_USER_WARNING);
		}

		return $appPath;
	}


	/**
	 * Auxiliary method to generate the correct folder structure to store a massive amount of files (If structure already exists, nothing will be done).
	 * Folders will have the following format: parentFolder/subFolder/00/00/000/00_ with 9 numeric digits that represent an identifier for some object, so we can store up to 1 thousand milion files.
	 *
	 * @param string $parentFolder The main storage folder where structure will be placed
	 * @param string $subFolder A subfolder with a name that will identify some class inside the main folder, like an entity name, a cached file type, etc...
	 * @param int $id Numeric value that will be used to generate the massive folder structure.
	 *
	 * @return array Array with two values: First the full path that's been created for the given parameters, and secondth the prefix that must be placed at the start of the file names that are placed inside this path (the 00_ part).
	 */
	private function _createMassiveFolderStructure($parentFolder, $subFolder, $id){

		// Create parent folder if not exists
		$basePath = $this->_storagePath.DIRECTORY_SEPARATOR.$parentFolder;

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath, 0755, true);
		}

		// Create entity folder if not exists
		$basePath = $basePath.DIRECTORY_SEPARATOR.$subFolder;

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath);
		}

		// Convert the specified id to a 9 digits fixed lenght string
		$idCode = str_pad($id, 9, '0', STR_PAD_LEFT);

		// Convert the fixed string id to a list of strings with 2 digits / 2 digits / 3 digits / 2 digits each
		$f = array(substr($idCode, 0, 2), substr($idCode, 2, 2), substr($idCode, 4, 3), substr($idCode, 7, 2));

		// Create the first folder if not exists
		$basePath = $basePath.DIRECTORY_SEPARATOR.$f[0];

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath);
		}

		// Create the secondth folder if not exists
		$basePath = $basePath.DIRECTORY_SEPARATOR.$f[1];

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath);
		}

		// Create the third folder if not exists
		$basePath = $basePath.DIRECTORY_SEPARATOR.$f[2];

		if(!is_dir($basePath)){

			FileSystemUtils::createDirectory($basePath);
		}

		return array($basePath, $f[3].'_');
	}


	/**
	 * Auxiliary method that generates a path to get a resource that is stored using the massive folders structure
	 *
	 * @param string $parentFolder The main storage folder where structure be placed
	 * @param string $subFolder A subfolder with a name that identifies some class inside the main folder, like an entity name, a cached file type, etc...
	 * @param int $id Numeric value that was used to generate the massive folder structure.
	 * @param string $fileName The name for the file that we want to get. If empty, only the path to its container folder will be given (without the last /).
	 *
	 * @return string The full path to get the requested file
	 */
	private function _getMassiveFolderPath($parentFolder, $subFolder, $id, $fileName = ''){

		// Convert the specified id to a 9 digits fixed lenght string
		$idCode = str_pad($id, 9, '0', STR_PAD_LEFT);

		// Convert the fixed string id to a list of strings with 2 digits / 2 digits / 3 digits / 2 digits each
		$f = array(substr($idCode, 0, 2), substr($idCode, 2, 2), substr($idCode, 4, 3), substr($idCode, 7, 2));

		$folder = $this->_storagePath.DIRECTORY_SEPARATOR.$parentFolder.DIRECTORY_SEPARATOR.$subFolder.DIRECTORY_SEPARATOR.$f[0].DIRECTORY_SEPARATOR.$f[1].DIRECTORY_SEPARATOR.$f[2];

		return ($fileName == '') ? $folder : $folder.DIRECTORY_SEPARATOR.$f[3].'_'.$fileName;
	}


	/**
	 * Deletes all the files that are related to the specified id or ids, on a massive storage folders structure
	 *
	 * @param string $parentFolder The main storage folder where structure is placed
	 * @param string $subFolder A subfolder with a name that identifies some class inside the main folder, like an entity name, a cached file type, etc...
	 * @param array|int $ids Id for the resource instance that links the file or files, or an array of ids if we want to delete files from multiple resource instances
	 *
	 * @return boolean Returns true on success or false on failure.
	 */
	private function _deleteAllMassiveFolderIdFiles($parentFolder, $subFolder, $ids){

		// Verify that we are not receiving empty values
		if($subFolder == '' || $ids == '' || count($ids) <= 0){

			return false;
		}

		// Detect if we are receiving a single id or an array of ids
		if(!is_array($ids)){

			$list = array($ids);

		}else{

			$list = $ids;
		}

		foreach($list as $id){

			// Convert the specified id to a 9 digits fixed lenght string
			$idCode = str_pad($id, 9, '0', STR_PAD_LEFT);

			// Convert the fixed string id to a list of strings with 2 digits / 2 digits / 3 digits / 2 digits each
			$f = array(substr($idCode, 0, 2), substr($idCode, 2, 2), substr($idCode, 4, 3), substr($idCode, 7, 2));

			// Delete all the files for the specified entity and id
			$basePath = $this->_storagePath.DIRECTORY_SEPARATOR.$parentFolder.DIRECTORY_SEPARATOR.$subFolder.DIRECTORY_SEPARATOR.$f[0].DIRECTORY_SEPARATOR.$f[1].DIRECTORY_SEPARATOR.$f[2];

			// If basepath folder does not exist, we will simply return true, as no files are linked to the specified entity
			if(!is_dir($basePath)){
				return true;
			}

			$dirIterator = new DirectoryIterator($basePath);

			foreach ($dirIterator as $fileInfo){

				if(!$fileInfo->isDot() && $f[3] == substr($fileInfo->getFilename(), 0, 2)){

					if(!unlink($basePath.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

						return false;
					}
				}
			}

			// Only for windows, to prevent folder deleting permision error
			unset($dirIterator);
			unset($fileInfo);

			// Delete the parent files folder if it is empty
			if(FileSystemUtils::isDirectoryEmpty($basePath)){

				FileSystemUtils::deleteDirectory($basePath);
			}
		}

		return true;
	}


	/**
	 * Auxiliary method to delete an existing temporary file given its identifier. (Does not work with directories)
	 *
	 * @param string $id Identifier for the file we want to delete
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	private function _tmpDeleteFile($id){

		$dirIterator = new DirectoryIterator($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot()){

				$itemParts = explode('_', $fileInfo->getFilename());

				if($id == $itemParts[2] && !is_dir($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

					return FileSystemUtils::deleteFile($this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileInfo->getFilename());
				}
			}
		}

		return false;
	}


	/**
	 * Performs a search on the temporary folder for all the files that have expired and deletes them.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	private function _tmpClearFiles(){

		$now = strtotime('now');

		$dirIterator = new DirectoryIterator($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot()){

				$itemParts = explode('_', $fileInfo->getFilename());

				$expiryDate = strtotime($itemParts[0].' '.str_replace('-', ':', $itemParts[1]));

				if($now > $expiryDate){

					$itemPath = $this->_storagePath.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileInfo->getFilename();

					if(is_dir($itemPath)){

						if(!FileSystemUtils::deleteDirectory($itemPath)){
							return false;
						}

					}else{

						if(!unlink($itemPath)){
							return false;
						}
					}
				}
			}
		}

		return true;
	}


	/**
	 * Find an identifier number that is free to use on a file or folder
	 *
	 * @return int The identifier found
	 */
	private function _tmpFindFreeId(){

		$id = 0;
		$dirIterator = new DirectoryIterator($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		do{
			$id ++;
			$idIsFound = false;

			foreach ($dirIterator as $fileInfo){

				if (!$fileInfo->isDot()){

					$itemParts = explode('_', $fileInfo->getFilename());

					if($itemParts[2] == $id){
						$idIsFound = true;
						break;
					}
				}
			}

		}while($idIsFound);

		return $id;
	}


	/**
	 * Gets the directory name given its identifier.
	 *
	 * @param string $id identifier for the temporary folder we want to find
	 *
	 * @return string The folder name for the requested directory
	 */
	private function _tmpGetDirectoryNameFromId($id){

		if(!is_dir($this->_storagePath.DIRECTORY_SEPARATOR.'tmp')){
			return '';
		}

		// Find the folder that matches the specified id
		$dirIterator = new DirectoryIterator($this->_storagePath.DIRECTORY_SEPARATOR.'tmp');

		foreach ($dirIterator as $fileInfo){

			if(!$fileInfo->isDot()){

				$itemParts = explode('_', $fileInfo->getFilename());

				if($id == $itemParts[2]){

					return $fileInfo->getFilename();
				}
			}
		}

		return '';
	}
}

?>