<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use DirectoryIterator;
use Exception;
use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\model\BaseStrictClass;


/**
 * Class that contains the most common file system interaction functionalities
 */
class FilesManager extends BaseStrictClass{


    /**
     * Check if the specified path is a file or not.
     *
     * @param string $path An Operating system path to test
     *
     * @return bool true if the path exists and is a file, false otherwise.
     */
    public function isFile($path){

        if (!is_string($path)){

            throw new UnexpectedValueException('path must be a string');
        }

        try {

            return is_file($path);

        } catch (Exception $e) {

            return false;
        }
    }


    /**
     * Check if the specified path is a directory or not.
     *
     * @param string $path An Operating system path to test
     *
     * @return bool true if the path exists and is a directory, false otherwise.
     */
    public function isDirectory($path){

        if (!is_string($path)){

            throw new UnexpectedValueException('path must be a string');
        }

        try {

            return is_dir($path);

        } catch (Exception $e) {

            return false;
        }
    }


    /**
     * Checks if the specified folder is empty
     *
     * @param string $path The path to the directory we want to check
     *
     * @return boolean True if directory is empty, false if not. If it does not exist or cannot be read, an exception will be generated
     */
    public function isDirectoryEmpty($path) {

        if (!$this->isDirectory($path)){

            throw new UnexpectedValueException('path does not exist: '.$path);
        }

        $files = $this->getDirectoryList($path);

        foreach ($files as $file) {

            if ($file !== '.' && $file !== '..') {

                return false;
            }
        }

        return true;
    }


    /**
     * Gives us the current OS directory separator character, so we can build cross platform file paths
     *
     * @return string The current OS directory separator character
     */
    public function dirSep(){

        return DIRECTORY_SEPARATOR;
    }


    /**
     * Search for a folder name that does not exist on the provided path.
     *
     * If we want to create a new folder inside another one without knowing for sure what does it contain, this method will
     * guarantee us that we have a unique directory name that does not collide with any other folder or file that currently
     * exists on the path.
     *
     * NOTE: This method does not create any folder or alter the given path in any way.
     *
     * @param string $path The full path to the directoy we want to check for a unique folder name
     * @param string $desiredName We can specify a suggested name for the unique directory. This method will verify that it
     *                            does not exist, or otherwise give us a name based on our desired one that is unique for the path
     * @param string $text Text that will be appended to the suggested name in case it already exists.
     *                     For example: text='copy' will generate a result like 'NewFolder-copy' or 'NewFolder-copy-1' if a folder named 'NewFolder' exists
     * @param string $separator String that will be used to join the suggested name with the text and the numeric file counter.
     *                          For example: separator='---' will generate a result like 'NewFolder---copy---1' if a folder named 'NewFolder' already exists
     * @param string $isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result.
     *                         For example: isPrefix=true will generate a result like 'copy-1-NewFolder' if a folder named 'NewFolder' already exists
     *
     * @return string A directory name that can be safely created on the specified path, cause no one exists with the same name
     *                (No path is returned by this method, only a directory name. For example: 'folder-1', 'directoryName-5', etc..).
     */
    public function findUniqueDirectoryName(string $path,
                                            string $desiredName = '',
                                            string $text = '',
                                            string $separator = '-',
                                            bool $isPrefix = false){

        if (!$this->isDirectory($path)){

            throw new UnexpectedValueException('path does not exist: '.$path);
        }

        $i = 1;
        $result = ($desiredName == '' ? $i : $desiredName);
        $path = StringUtils::formatPath($path, DIRECTORY_SEPARATOR);

        while(is_dir($path.DIRECTORY_SEPARATOR.$result) ||
              is_file($path.DIRECTORY_SEPARATOR.$result)){

            $result = $this->_generateUniqueNameAux($i, $desiredName, $text, $separator, $isPrefix);

            $i++;
        }

        return $result;
    }


    /**
     * Search for a file name that does not exist on the provided path.
     *
     * If we want to create a new file inside a folder without knowing for sure what does it contain, this method will
     * guarantee us that we have a unique file name that does not collide with any other folder or file that currently
     * exists on the path.
     *
     * NOTE: This method does not create any file or alter the given path in any way.
     *
     * @param string $path The full path to the directoy we want to check for a unique file name
     * @param string $desiredName We can specify a suggested name for the unique file. This method will verify that it
     *                            does not exist, or otherwise give us a name based on our desired one that is unique for the path
     * @param string $text Text that will be appended to the suggested name in case it already exists.
     *                     For example: text='copy' will generate a result like 'NewFile-copy' or 'NewFile-copy-1' if a file named 'NewFile' exists
     * @param string $separator String that will be used to join the suggested name with the text and the numeric file counter.
     *                          For example: separator='---' will generate a result like 'NewFile---copy---1' if a file named 'NewFile' already exists
     * @param string $isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result.
     *                         For example: isPrefix=true will generate a result like 'copy-1-NewFile' if a file named 'NewFile' already exists
     *
     * @return string A file name that can be safely created on the specified path, cause no one exists with the same name
     *                (No path is returned by this method, only a file name. For example: 'file-1', 'fileName-5', etc..).
     */
     public function findUniqueFileName(string $path,
                                        string $desiredName = '',
                                        string $text = '',
                                        string $separator = '-',
                                        bool $isPrefix = false){

        if (!$this->isDirectory($path)){

            throw new UnexpectedValueException('path does not exist: '.$path);
        }

        $i = 1;
        $path = StringUtils::formatPath($path, DIRECTORY_SEPARATOR);
        $result = ($desiredName == '' ? $i : $desiredName);
        $extension = StringUtils::getFileExtension($desiredName);

        while(is_dir($path.DIRECTORY_SEPARATOR.$result) ||
              is_file($path.DIRECTORY_SEPARATOR.$result)){

            $result = $this->_generateUniqueNameAux($i, StringUtils::getFileNameWithoutExtension($desiredName), $text, $separator, $isPrefix);

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
    private function _generateUniqueNameAux(int $i, string $desiredName, string $text, string $separator, bool $isPrefix){

        $result = [];

        if($isPrefix){

            if($text !== ''){

                $result[] = $text;
            }

            $result[] = $i;

            if($desiredName !== ''){

                $result[] = $desiredName;
            }

        }else{

            if($desiredName !== ''){

                $result[] = $desiredName;
            }

            if($text !== ''){

                $result[] = $text;
            }

            $result[] = $i;
        }

        return implode($separator, $result);
    }


    /**
     * Create a directory at the specified filesystem path
     *
     * @param string $path The full path to the directoy we want to create. For example: c:\apps\my_new_folder
     * @param bool $recursive Allows the creation of nested directories specified in the pathname. Defaults to false.
     *
     * @return bool Returns true on success or false if the folder already exists (an exception may be thrown if a file exists with the same name or folder cannot be created).
     */
    public function createDirectory(string $path, bool $recursive = false){

        // If folder already exists we won't create it
        if(is_dir($path)){

            return false;
        }

        // If specified folder exists as a file, exception will happen
        if(is_file($path)){

            throw new UnexpectedValueException('specified path is an existing file '.$path);
        }

        // Create the requested folder
        try{

            mkdir($path, null, $recursive);

        }catch(Exception $e){

            // It is possible that multiple concurrent calls create the same folder at the same time.
            // We will ignore those exceptions cause there's no problen with this situation, the first of the calls creates it and we are ok with it.
            // But if the folder to create does not exist at the time of catching the exception, we will throw it, cause it will be another kind of error.
            if(!is_dir($path)){

                throw $e;
            }

            return false;
        }

        return true;
    }


    /**
     * Create a TEMPORARY directory on the operating system tmp files location, and get us the full path to access it.
     * OS should take care of its removal but it is not assured, so it is recommended to make sure all the tmp data is deleted after
     * using it (This is specially important if the tmp folder contains sensitive data).
     *
     * @param string $desiredName A name we want for the new directory to be created. If name is not available, a unique one
     *                            (based on the provided desired name) will be generated automatically.
     * @param boolean $deleteOnExecutionEnd Defines if the generated temp folder must be deleted after the current application execution finishes.
     *                                      Note that when files inside the folder are still used by the app or OS, exceptions or problems may happen,
     *                                      and it is not 100% guaranteed that the folder will be always deleted. So it is better to always handle the
     *                                      temporary folder removal in our code
     *
     * @return string The full path to the newly created temporary directory, including the directory itself (without a trailing slash).
     *                For example: C:\Users\Me\AppData\Local\Temp\MyDesiredName
     */
    public function createTempDirectory(string $desiredName, $deleteOnExecutionEnd = true) {

        $tempRoot = StringUtils::formatPath(sys_get_temp_dir(), DIRECTORY_SEPARATOR);

        $tempDirectory = $tempRoot.DIRECTORY_SEPARATOR.$this->findUniqueDirectoryName($tempRoot, $desiredName);

        if(!$this->createDirectory($tempDirectory)){

            throw new UnexpectedValueException('Could not create TMP directory '.$tempDirectory);
        }

        // Add a shutdown function to try to delete the file when the current script execution ends
        if($deleteOnExecutionEnd){

            register_shutdown_function(function () use ($tempDirectory) {

                $this->deleteDirectory($tempDirectory);
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
    public function getDirectoryList(string $path, string $sort = ''){

        // If folder does not exist, we will throw an exception
        if(!is_dir($path)){

            throw new UnexpectedValueException('path does not exist: '.$path);
        }

        // Get all the folder contents
        $result = [];
        $sortRes = [];

        $dirIterator = new DirectoryIterator($path);

        foreach ($dirIterator as $fileInfo){

            if(!$fileInfo->isDot()){

                switch($sort) {

                    case 'mDateAsc':
                    case 'mDateDesc':
                        $sortRes[$fileInfo->getMTime()] = $fileInfo->getFilename();
                        break;

                    default:
                        $result[] = $fileInfo->getFilename();
                        break;
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
                ksort($sortRes);
                foreach ($sortRes as $value) {

                    $result[] = $value;
                }
                break;

            case 'mDateDesc':
                krsort($sortRes);
                foreach ($sortRes as $value) {

                    $result[] = $value;
                }
                break;

            default:
                if($sort !== ''){

                    throw new UnexpectedValueException('Unknown sort method');
                }

        }

        return $result;
    }


    /**
     * Calculate the full size in bytes for a specified folder.
     *
     * @param string $path Full path to the directory we want to calculate its size
     *
     * @return int the size of the file in bytes. An exception will be thrown if value cannot be obtained
     */
    public function getDirectorySize(string $path){

        $result = 0;

        // If folder does not exist, we will throw an exception
        if(!is_dir($path)){

            throw new UnexpectedValueException('Specified path <'.$path.'> does not exist or is not a directory');
        }

        $contents = $this->getDirectoryList($path);

        foreach ($contents as $fileOrDir){

            $fileOrDirPath = $path.DIRECTORY_SEPARATOR.$fileOrDir;

            if ($fileOrDir !== '.' && $fileOrDir !== '..') {

                if (is_dir($fileOrDirPath)) {

                    $result += $this->getDirectorySize($fileOrDirPath);

                }else {

                    $result += $this->getFileSize($fileOrDirPath);
                }
            }
        }

        return $result;
    }


    /**
     * Delete a directory from the filesystem and return a boolean telling if the directory delete succeeded or not
     * Note: All directory contents, folders and files will be also removed.
     *
     * @param string $path The path to the directory
     * @param string $deleteDirectoryItself Set it to true if the specified directory must also be deleted.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function deleteDirectory(string $path, bool $deleteDirectoryItself = true){

        $path = StringUtils::formatPath($path, DIRECTORY_SEPARATOR);

        if (!is_dir($path)){

            return false;
        }

        $dirIterator = new DirectoryIterator($path);

        foreach ($dirIterator as $fileInfo){

            if(!$fileInfo->isDot()){

                if(is_dir($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

                    if(!$this->deleteDirectory($path.DIRECTORY_SEPARATOR.$fileInfo->getFilename())){

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
    public function createFile(string $path, string $fileData = '', string $permisions = ''){

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
        if($permisions != '' && !chmod($path, $permisions)){

            return false;
        }

        return true;

    }


    /** TODO */
    public function createTempFile(){

    }


    /**
     * Get the size from a file
     *
     * @param string $path The file full path, including the file name and extension
     *
     * @return int the size of the file in bytes. An exception will be thrown if value cannot be obtained
     */
    public function getFileSize(string $path){

        if(!is_file($path)){

            throw new UnexpectedValueException('File not found - '.$path);
        }

        $fileSize = filesize($path);

        if($fileSize === false){

            throw new UnexpectedValueException('Error reading file size');
        }

        return $fileSize;
    }


    /**
     * Read and return the content of a file. Not suitable for big files (More than 5 MB) cause the script memory
     * may get full and the execution fail
     *
     * @param string $path An Operating system full or relative path containing some file
     *
     * @return string The file contents as a string. If the file is not found or cannot be read, an exception will be thrown.
     */
    public function readFile(string $path){

        if(!is_file($path)){

            throw new UnexpectedValueException('File not found - '.$path);
        }

        if(($contents = file_get_contents($path, true)) === false){

            throw new UnexpectedValueException('Error reading file - '.$path);
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
    public function readFileBuffered(string $path, int $downloadRateLimit = 0){

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

        fclose($handle);

        // return num. bytes delivered like readfile() does.
        return $cnt;
    }


    /**
     * Copies a file from a source location to the defined destination
     * If the destination file already exists, it will be overwritten.
     *
     * @param string $sourcePath The full path to the source file that must be copied (including the filename itself).
     * @param string $destPath The full path to the destination where the file must be copied (including the filename itself).
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function copyFile(string $sourcePath, string $destPath){

        return copy($sourcePath, $destPath);
    }


    /**
     * Delete a filesystem file.
     *
     * @param string $path The file filesystem path
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function deleteFile(string $path){

        if(!is_file($path)){

            return false;
        }

        return unlink($path);
    }
}

?>