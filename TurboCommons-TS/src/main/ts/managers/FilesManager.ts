/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { StringUtils } from '../utils/StringUtils';


/**
 * Class that contains the most common file system interaction functionalities
 * 
 * NOTE: The ts/js version of this class works only on server side enviroments (nodejs).
 *       File system functionalities are not available at the browser.
 */
export class FilesManager{
    
    
    /**
     * Typescript / Javascript is a bit special regarding file system operations.
     * If we run our application in a browser environment, we won't be able to access the file system,
     * so this class will only work in a server side environment (nodejs).
     * 
     * This constructor requires some node modules to work, which are passed as dependencies
     *  
     * @param fs A node fs module instance (const fs = require('fs'))
     * @param os A os fs module instance (const os = require('os'))
     * @param path A path fs module instance (const path = require('path'))
     * @param process A process fs module instance
     * 
     * @return A FilesManager instance
     */
    constructor(private fs:any,
                private os:any,
                private path:any,
                private process: any) {
	
    }
    
    
    /**
     * Check if the specified path is a file or not.
     *
     * @param path An Operating system path to test
     *
     * @return true if the path exists and is a file, false otherwise.
     */
    isFile(path: string){
        
        if (!StringUtils.isString(path)){

            throw new Error('path must be a string');
        }
        
        try {
            
            return this.fs.lstatSync(path).isFile();
            
        } catch (e) {

            return false;
        }
    }
    
    
    /**
     * Check if the specified path is a directory or not.
     *
     * @param path An Operating system path to test
     *
     * @return true if the path exists and is a directory, false otherwise.
     */
    isDirectory(path: any){

        if (!StringUtils.isString(path)){

            throw new Error('path must be a string');
        }
        
        try {
            
            return this.fs.lstatSync(path).isDirectory();
            
        } catch (e) {

            return false;
        }
    }
    
    
    /**
     * Checks if the specified folder is empty
     *
     * @param path The path to the directory we want to check
     *
     * @return True if directory is empty, false if not. If it does not exist or cannot be read, an exception will be generated
     */
    isDirectoryEmpty(path: string) {

        if (!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }

        let files = this.getDirectoryList(path);

        for (let file of files) {
            
            if (file !== '.' && file !== '..') {

                return false;
            }
        }

        return true;
    }


    /**
     * Gives us the current OS directory separator character, so we can build cross platform file paths
     *
     * @return The current OS directory separator character
     */
    dirSep(){

        return this.path.sep;
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
     * @param path The full path to the directoy we want to check for a unique folder name
     * @param desiredName We can specify a suggested name for the unique directory. This method will verify that it
     *                    does not exist, or otherwise give us a name based on our desired one that is unique for the path
     * @param text Text that will be appended to the suggested name in case it already exists.
     *             For example: text='copy' will generate a result like 'NewFolder-copy' or 'NewFolder-copy-1' if a folder named 'NewFolder' exists
     * @param separator String that will be used to join the suggested name with the text and the numeric file counter.
     *                  For example: separator='---' will generate a result like 'NewFolder---copy---1' if a folder named 'NewFolder' already exists
     * @param isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result.
     *                 For example: isPrefix=true will generate a result like 'copy-1-NewFolder' if a folder named 'NewFolder' already exists
     *
     * @return A directory name that can be safely created on the specified path, cause no one exists with the same name
     *         (No path is returned by this method, only a directory name. For example: 'folder-1', 'directoryName-5', etc..).
     */
    findUniqueDirectoryName(path: string,
                            desiredName = '',
                            text = '',
                            separator = '-',
                            isPrefix = false){

        if (!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }
        
        let i = 1;
        let result = (desiredName == '' ? i : desiredName);
        path = StringUtils.formatPath(path, this.dirSep());
        
        while(this.isDirectory(path + this.dirSep() + result) ||
              this.isFile(path + this.dirSep() + result)){

            result = this._generateUniqueNameAux(i, desiredName, text, separator, isPrefix);

            i++;
        }

        return result;
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
     * @param path The full path to the directoy we want to check for a unique file name
     * @param desiredName We can specify a suggested name for the unique file. This method will verify that it
     *                    does not exist, or otherwise give us a name based on our desired one that is unique for the path
     * @param text Text that will be appended to the suggested name in case it already exists.
     *             For example: text='copy' will generate a result like 'NewFile-copy' or 'NewFile-copy-1' if a file named 'NewFile' exists
     * @param separator String that will be used to join the suggested name with the text and the numeric file counter.
     *                  For example: separator='---' will generate a result like 'NewFile---copy---1' if a file named 'NewFile' already exists
     * @param isPrefix Defines if the extra text that will be appended to the desired name will be placed after or before the name on the result.
     *                 For example: isPrefix=true will generate a result like 'copy-1-NewFile' if a file named 'NewFile' already exists
     *
     * @return A file name that can be safely created on the specified path, cause no one exists with the same name
     *         (No path is returned by this method, only a file name. For example: 'file-1', 'fileName-5', etc..).
     */
    findUniqueFileName(path: string,
                       desiredName = '',
                       text = '',
                       separator = '-',
                       isPrefix = false){

        if (!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }
        
        let i = 1;
        path = StringUtils.formatPath(path, this.dirSep());
        let result = (desiredName == '' ? i : desiredName);
        let extension = StringUtils.getFileExtension(desiredName);

        while(this.isDirectory(path + this.dirSep() + result) ||
              this.isFile(path + this.dirSep() + result)){

            result = this._generateUniqueNameAux(i, StringUtils.getFileNameWithoutExtension(desiredName), text, separator, isPrefix);

            if(extension != ''){

                result += '.' + extension;
            }

            i++;
        }

        return result;
    }


    /**
     * Auxiliary method that is used by the findUniqueFileName and findUniqueDirectoryName methods
     *
     * @param i Current index for the name generation
     * @param desiredName Desired name as used on the parent method
     * @param text text name as used on the parent method
     * @param separator separator name as used on the parent method
     * @param isPrefix isPrefix name as used on the parent method
     *
     * @return The generated name
     */
    private _generateUniqueNameAux(i: number, desiredName: string, text: string, separator: string, isPrefix: boolean){

        let result: string[] = [];

        if(isPrefix){

            if(text !== ''){

                result.push(text);
            }

            result.push(String(i));

            if(desiredName !== ''){

                result.push(desiredName);
            }

        }else{

            if(desiredName !== ''){

                result.push(desiredName);
            }

            if(text !== ''){

                result.push(text);
            }

            result.push(String(i));
        }

        return result.join(separator);
    }


    /**
     * Create a directory at the specified filesystem path
     *
     * @param path The full path to the directoy we want to create. For example: c:\apps\my_new_folder
     * @param recursive Allows the creation of nested directories specified in the pathname. Defaults to false.
     *
     * @return Returns true on success or false if the folder already exists (an exception may be thrown if a file exists with the same name or folder cannot be created).
     */
    createDirectory(path: string, recursive = false){

        // If folder already exists we won't create it
        if(this.isDirectory(path)){

            return false;
        }

        // If specified folder exists as a file, exception will happen
        if(this.isFile(path)){

            throw new Error('specified path is an existing file ' + path);
        }

        // Create the requested folder
        try{

            // TODO - recursive option is currently not working
            this.fs.mkdirSync(path);

        }catch(e){

            // It is possible that multiple concurrent calls create the same folder at the same time.
            // We will ignore those exceptions cause there's no problen with this situation, the first of the calls creates it and we are ok with it.
            // But if the folder to create does not exist at the time of catching the exception, we will throw it, cause it will be another kind of error.
            if(!this.isDirectory(path)){

                throw e;
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
     * @param desiredName A name we want for the new directory to be created. If name is not available, a unique one
     *                    (based on the provided desired name) will be generated automatically.
     * @param deleteOnExecutionEnd Defines if the generated temp folder must be deleted after the current application execution finishes.
     *                             Note that when files inside the folder are still used by the app or OS, exceptions or problems may happen,
     *                             and it is not 100% guaranteed that the folder will be always deleted. So it is better to always handle the
     *                             temporary folder removal in our code
     *
     * @return The full path to the newly created temporary directory, including the directory itself (without a trailing slash).
     *         For example: C:\Users\Me\AppData\Local\Temp\MyDesiredName
     */
    createTempDirectory(desiredName: string, deleteOnExecutionEnd = true) {

        let tempRoot = StringUtils.formatPath(this.os.tmpdir(), this.dirSep());

        let tempDirectory = tempRoot + this.dirSep() + this.findUniqueDirectoryName(tempRoot, desiredName);

        if(!this.createDirectory(tempDirectory)){

            throw new Error('Could not create TMP directory ' + tempDirectory);
        }

        // Add a shutdown function to try to delete the file when the current script execution ends
        if(deleteOnExecutionEnd){

            this.process.on('exit', () => {
                
                this.deleteDirectory(tempDirectory);
            });
        }

        return tempDirectory;
    }


    /**
     * Gives the list of items that are stored on the specified folder. It will give files and directories, and each element will be the item name, without the path to it.
     * The contents of any subfolder will not be listed. We must call this method for each child folder if we want to get it's list.
     * (The method ignores the . and .. items if exist).
     *
     * @param path Full path to the directory we want to list
     * @param sort Specifies the sort for the result:<br>
     * &emsp;&emsp;'' will not sort the result.<br>
     * &emsp;&emsp;'nameAsc' will sort the result by filename ascending.
     * &emsp;&emsp;'nameDesc' will sort the result by filename descending.
     * &emsp;&emsp;'mDateAsc' will sort the result by modification date ascending.
     * &emsp;&emsp;'mDateDesc' will sort the result by modification date descending.
     *
     * @return The list of item names inside the specified path sorted as requested, or an empty array if no items found inside the folder.
     */
    getDirectoryList(path: string, sort = ''){

        // TODO - code is temporary. adapt from the PHP version
        
        return this.fs.readdirSync(path);   
    }


    /**
     * Calculate the full size in bytes for a specified folder.
     *
     * @param path Full path to the directory we want to calculate its size
     *
     * @return the size of the file in bytes. An exception will be thrown if value cannot be obtained
     */
    getDirectorySize(path: string){

        let result = 0;

        // If folder does not exist, we will throw an exception
        if(!this.isDirectory(path)){

            throw new Error('Specified path <' + path + '> does not exist or is not a directory');
        }

        let contents = this.getDirectoryList(path);
        
        for (let fileOrDir of contents) {

            let fileOrDirPath = path + this.dirSep() + fileOrDir;

            if(fileOrDir !== '.' && fileOrDir !== '..'){

                if (this.isDirectory(fileOrDirPath)) {

                    result += this.getDirectorySize(fileOrDirPath);

                }else {

                    result += this.getFileSize(fileOrDirPath);
                }
            }
        }

        return result;
    }


    /**
     * Delete a directory from the filesystem and return a boolean telling if the directory delete succeeded or not
     * Note: All directory contents, folders and files will be also removed.
     * 
     * @param path The path to the directory
     * @param deleteDirectoryItself Set it to true if the specified directory must also be deleted.
     *
     * @return Returns true on success or false on failure.
     */
    deleteDirectory(path: string, deleteDirectoryItself = true){

        path = StringUtils.formatPath(path, this.dirSep());

        if (!this.isDirectory(path)){

            return false;
        }

        let files = this.getDirectoryList(path);

        for (let file of files) {

            if(file !== '.' && file !== '..'){

                if(this.isDirectory(path + this.dirSep() + file)){

                    if(!this.deleteDirectory(path + this.dirSep() + file)){

                        return false;
                    }

                }else{

                    if(!this.deleteFile(path + this.dirSep() + file)){

                        return false;
                    }
                }
            }
        }

        if(deleteDirectoryItself){
            
            try {
	
                this.fs.rmdirSync(path);
                
                return true;
                
            } catch (e) {

                return false;
            }
            
        } else {
            
            return true;
        }
    }


    /**
     * Create a file to the specified filesystem path and write the specified data to it.
     *
     * @param path The full path where the file will be stored, including the full file name
     * @param fileData Information to store on the file (a string, a block of bytes, etc...)
     * @param permisions The file permisions. If not specified, the default system one will be used, (normally 0644)
     *
     * @return Returns true on success or false on failure.
     */
    createFile(path: string, fileData = '', permisions = ''){

        // TODO - translate from php
    }


    /** TODO */
    createTempFile(){

    }


    /**
     * Get the size from a file
     *
     * @param path The file full path, including the file name and extension
     *
     * @return int the size of the file in bytes. An exception will be thrown if value cannot be obtained
     */
    getFileSize(path: string){

        if(!this.isFile(path)){

            throw new Error('File not found - ' + path);
        }

        try {
	
            return this.fs.statSync(path).size;

        } catch (e) {
            
            throw new Error('Error reading file size');
        }
    }


    /**
     * Read and return the content of a file. Not suitable for big files (More than 5 MB) cause the script memory
     * may get full and the execution fail
     *
     * @param path An Operating system full or relative path containing some file
     *
     * @return The file contents (binary or string). If the file is not found or cannot be read, an exception will be thrown.
     */
    readFile(path: string){

        if(!this.isFile(path)){

            throw new Error('File not found - ' + path);
        }

        return this.fs.readFileSync(path, "utf8");
    }


    /**
     * Reads a file and performs a buffered output to the browser, by sending it as small fragments.<br>
     * This method is mandatory with big files, as reading the whole file to memory will cause the script or RAM to fail.<br><br>
     *
     * Adapted from code suggested at: http://php.net/manual/es/function.readfile.php
     *
     * @param path The file full path
     * @param downloadRateLimit If we want to limit the download rate of the file, we can do it by setting this value to > 0. For example: 20.5 will set the file download rate to 20,5 kb/s
     *
     * @return the number of bytes read from the file.
     */
    readFileBuffered(path: string, downloadRateLimit = 0){

        // TODO - translate from php
    }


    /**
     * Copies a file from a source location to the defined destination
     * If the destination file already exists, it will be overwritten. 
     * 
     * @param sourcePath The full path to the source file that must be copied (including the filename itself).
     * @param destPath The full path to the destination where the file must be copied (including the filename itself).
     *
     * @return Returns true on success or false on failure.
     */
    copyFile(sourcePath: string, destPath: string){

        try{
            
            this.fs.copyFileSync(sourcePath, destPath);

            return true;
            
        }catch(e){
        
            return false;
        }
    }


    /**
     * Delete a filesystem file.
     *
     * @param path The file filesystem path
     *
     * @return Returns true on success or false on failure.
     */
    deleteFile(path: string){

        if(!this.isFile(path)){

            return false;
        }

        try {
	
            this.fs.unlinkSync(path);
            
            return true;
            
        } catch (e) {
            
            return false;
        }
    }
}