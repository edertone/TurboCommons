/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { StringUtils } from '../utils/StringUtils';
import { ArrayUtils } from '../utils/ArrayUtils';


/**
 * Class that contains the most common file system interaction functionalities
 */
export class FilesManager{


    /**
     * Gives us the current OS directory separator character, so we can build cross platform file paths
     *
     * @return The current OS directory separator character
     */
    dirSep(){

        return this.path.sep;
    }
    
    
    /**
     * Typescript / Javascript is a bit special regarding file system operations.
     * If we run our application in a browser environment, we won't be able to access the file system,
     * so this class will only work in a server side environment (nodejs).
     * 
     * This constructor requires some node modules to work, which are passed as dependencies
     *  
     * @param fs A node fs module instance (const fs = require('fs'))
     * @param os A node os module instance (const os = require('os'))
     * @param path A node path module instance (const path = require('path'))
     * @param process A node process module instance
     * 
     * @return A FilesManager instance
     */
    constructor(private fs:any,
                private os:any,
                private path:any,
                private process: any,
                private crypto: any) {

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
     * Check if two provided files are identical
     *
     * @param file1 The first file to compare
     * @param file2 The second file to compare
     *
     * @throws Error
     *
     * @return True if both files are identical, false otherwise
     */
    isFileEqualTo(file1: string, file2: string){

        if(!this.isFile(file1)){

            throw new Error('Not a file: ' + file1);
        }

        if(!this.isFile(file2)){

            throw new Error('Not a file: ' + file2);
        }
        
        let file1Hash = this.crypto.createHash('md5').update(this.readFile(file1), 'utf8').digest('hex');
        let file2Hash = this.crypto.createHash('md5').update(this.readFile(file2), 'utf8').digest('hex');
        
        if (this.getFileSize(file1) === this.getFileSize(file2) &&
                file1Hash === file2Hash){

                return true;
        }

        return false;
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
            
            return this.fs.lstatSync(this.fs.realpathSync(path)).isDirectory();
            
        } catch (e) {

            return false;
        }
    }
    
    
    /**
     * Check if two directories contain exactly the same folder structure and files.
     *
     * @param path1 The full path to the first directory to compare
     * @param path2 The full path to the second directory to compare
     *
     * @return true if both paths are valid directories and contain exactly the same files and folders tree.
     */
    isDirectoryEqualTo(path1: string, path2: string){

        path1 = StringUtils.formatPath(path1, this.dirSep());
        path2 = StringUtils.formatPath(path2, this.dirSep());

        let path1Items = this.getDirectoryList(path1, 'nameAsc');
        let path2Items = this.getDirectoryList(path2, 'nameAsc');

        // Both paths must be exactly the same
        if(!ArrayUtils.isEqualTo(path1Items, path2Items)){

            return false;
        }

        for (let i = 0; i < path1Items.length; i++) {

            let item1Path = path1 + this.dirSep() + path1Items[i];
            let item2Path = path2 + this.dirSep() + path2Items[i];
            let isItem1ADir = this.isDirectory(item1Path);

            if(isItem1ADir && !this.isDirectoryEqualTo(item1Path, item2Path)){

                return false;
            }

            if (!isItem1ADir && !this.isFileEqualTo(item1Path, item2Path)){

                return false;
            }
        }

        return true;
    }
    
    
    /**
     * Checks if the specified folder is empty
     *
     * @param path The path to the directory we want to check
     *
     * @return True if directory is empty, false if not. If it does not exist or cannot be read, an exception will be generated
     */
    isDirectoryEmpty(path: string) {

        return this.getDirectoryList(path).length <= 0;
    }
    
    
    /**
     * Find all the elements on a directory which name matches the specified regexp pattern
     *
     * @param path A directory where the search will be performed
     *
     * @param searchRegexp A regular expression that files or folders must match to be included
     *        into the results. Here are some useful patterns:<br>
     *        /.*\.txt$/i   - Match all items which name ends with '.txt' (case insensitive)<br>
     *        /^some.*./   - Match all items which name starts with 'some'<br>
     *        /text/       - Match all items which name contains 'text'<br>
     *        /^file\.txt$/ - Match all items which name is exactly 'file.txt'
     *        /^.*\.(jpg|jpeg|png|gif)$/i - Match all items which name ends with .jpg,.jpeg,.png or .gif (case insensitive)
     *        /^(?!.*\.(jpg|png|gif)$)/i - Match all items that do NOT end with .jpg, .png or .gif (case insensitive)
     *
     * @param returnFormat Defines how will be returned the array of results. Three values are possible:<br>
     *        - If set to 'name' each result element will contain its file (with extension) or folder name<br>
     *        - If set to 'relative' each result element will contain its file (with extension) or folder name plus its path relative to the search root<br>
     *        - If set to 'absolute' each result element will contain its file (with extension) or folder name plus its full OS absolute path
     *
     * @param searchItemsType Defines the type for the directory elements to search: 'files' to search only files, 'folders'
     *        to search only folders, 'both' to search on all the directory contents
     *
     * @param depth Defines the maximum number of subfolders where the search will be performed:<br>
     *        - If set to -1 the search will be performed on the whole folder contents<br>
     *        - If set to 0 the search will be performed only on the path root elements<br>
     *        - If set to 2 the search will be performed on the root, first and second depth level of subfolders
     *
     * @return A list formatted as defined in returnFormat, with all the elements that meet the search criteria
     */
    findDirectoryItems(path: string,
                       searchRegexp: string,
                       returnFormat = 'relative',
                       searchItemsType = 'both',
                       depth = -1): string[]{

        let result: string[] = [];
        path = StringUtils.formatPath(path, this.dirSep());

        for (let item of this.getDirectoryList(path)) {

            let itemPath = path + this.dirSep() + item;
            let isItemADir = this.isDirectory(itemPath);
            let isItemAFile = this.isFile(itemPath);

            if(searchItemsType === 'folders' && isItemAFile){

                continue;
            }

            if((new RegExp(searchRegexp)).test(item)){

                if(!(searchItemsType === 'files' && isItemADir)){

                    result.push(itemPath);
                }
            }

            if(depth !== 0 && isItemADir){

                result = result.concat(this.findDirectoryItems(itemPath, searchRegexp, 'absolute', searchItemsType, depth - 1));
            }
        }

        // Process the results with the specified format
        if(returnFormat !== 'absolute'){

            for (let i = 0; i < result.length; i++){

                result[i] = (returnFormat === 'name') ?
                    StringUtils.getPathElement(result[i]) :
                    StringUtils.replace(result[i], path + this.dirSep(), '');
            }
        }

        return result;
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

        path = StringUtils.formatPath(path, this.dirSep());
        
        if (!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }
        
        let i = 1;
        let result = (desiredName == '' ? i : desiredName);
        
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

        path = StringUtils.formatPath(path, this.dirSep());
        
        if (!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }
        
        let i = 1;
        let result = (desiredName == '' ? i : desiredName);
        let extension = StringUtils.getPathExtension(desiredName);

        while(this.isDirectory(path + this.dirSep() + result) ||
              this.isFile(path + this.dirSep() + result)){

            result = this._generateUniqueNameAux(i, StringUtils.getPathElementWithoutExt(desiredName), text, separator, isPrefix);

            if(extension != ''){

                result += '.' + extension;
            }

            i++;
        }

        return result;
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

            if(!recursive){
            
                this.fs.mkdirSync(path);
            
            }else{
            
                let reconstructedPath = '';
                    
                for (let i = 0; i < StringUtils.countPathElements(path); i++) {
                    
                    reconstructedPath += StringUtils.getPathElement(path, i) + this.dirSep();
                    
                    if(!this.isDirectory(reconstructedPath)){
                        
                        this.fs.mkdirSync(reconstructedPath);
                    }
                }
            }
            
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

            this._tempDirectoriesToDelete.push(tempDirectory);
            
            if(this._tempDirectoriesToDelete.length < 2){
              
                this.process.once('exit', () => {
                    
                    for (let temp of this._tempDirectoriesToDelete) {

                        if(this.isDirectory(temp)){
                        
                            this.deleteDirectory(temp);
                        }
                    }                
                });
            }
        }

        return tempDirectory;
    }
    
    
    /**
     * Stores a list of paths to temporary folders that must be removed on application execution end.
     */
    private _tempDirectoriesToDelete: string[] = [];


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
    getDirectoryList(path: string, sort = ''): string[]{

        // If folder does not exist, we will throw an exception
        if(!this.isDirectory(path)){

            throw new Error('path does not exist: ' + path);
        }

        // Get all the folder contents
        let result = [];
        // TODO let sortRes = [];

        for (let fileInfo of this.fs.readdirSync(path)) {

            if(fileInfo !== '.' && fileInfo !== '..'){

                switch(sort) {

                    case 'mDateAsc':
                    case 'mDateDesc':
                        // TODO - Date sort is not implemented. Translate from php
                        break;

                    default:
                        result.push(fileInfo);
                        break;
                }
            }
        }

        // Apply result sorting as requested
        switch(sort) {

            case 'nameAsc':
                result.sort();
                break;

            case 'nameDesc':
                result.sort();
                result.reverse();
                break;

            case 'mDateAsc':
                // TODO - Date sort is not implemented. Translate from php
                break;

            case 'mDateDesc':
                // TODO - Date sort is not implemented. Translate from php
                break;

            default:
                if(sort !== ''){

                    throw new Error('Unknown sort method');
                }
        }

        return result; 
    }


    /**
     * Calculate the full size in bytes for a specified folder and all its contents.
     *
     * @param path Full path to the directory we want to calculate its size
     *
     * @return the size of the file in bytes. An exception will be thrown if value cannot be obtained
     */
    getDirectorySize(path: string){

        let result = 0;

        for (let fileOrDir of this.getDirectoryList(path)) {

            let fileOrDirPath = path + this.dirSep() + fileOrDir;

            result += this.isDirectory(fileOrDirPath) ?
                    this.getDirectorySize(fileOrDirPath) :
                    this.getFileSize(fileOrDirPath);
        }

        return result;
    }
    
    
    /**
     * Copy all the contents from a source directory to a destination one (Both source and destination paths must exist).
     *
     * Any source files that exist on destination will be overwritten without warning.
     * Files that exist on destination but not on source won't be modified, removed or altered in any way.
     *
     * @param sourcePath The full path to the source directory where files and folders to copy exist
     * @param destPath The full path to the destination directory where files and folders will be copied
     * @param destMustBeEmpty if set to true, an exception will be thrown if the destination directory is not empty.
     *
     * @throws Error
     *
     * @return True if copy was successful, false otherwise
     */
    copyDirectory(sourcePath: string, destPath: string, destMustBeEmpty = true){

        sourcePath = StringUtils.formatPath(sourcePath, this.dirSep());
        destPath = StringUtils.formatPath(destPath, this.dirSep());

        if(sourcePath === destPath){

            throw new Error('cannot copy a directory into itself: ' + sourcePath);
        }

        if(destMustBeEmpty && !this.isDirectoryEmpty(destPath)){

            throw new Error('destPath must be empty');
        }

        for (let sourceItem of this.getDirectoryList(sourcePath)) {

            let sourceItemPath = sourcePath + this.dirSep() + sourceItem;
            let destItemPath = destPath + this.dirSep() + sourceItem;

            if(this.isDirectory(sourceItemPath)){

                if(!this.isDirectory(destItemPath) && !this.createDirectory(destItemPath)){

                    return false;
                }

                if(!this.copyDirectory(sourceItemPath, destItemPath, destMustBeEmpty)){

                    return false;
                }

            }else{

                if(!this.copyFile(sourceItemPath, destItemPath)){

                    return false;
                }
            }
        }

        return true;
    }


    /**
     * TODO - translate from php
     */
    syncDirectories(){

        // TODO - translate from php
    }
    
    
    /**
     * Renames a directory.

     *
     * @param sourcePath The full path to the source directory that must be renamed (including the directoy itself).
     * @param destPath The full path to the new directoy name (including the directoy itself). It must not exist.
     *
     * @return boolean true on success or false on failure.
     */
    renameDirectory(sourcePath:string, destPath:string){

        if(!this.isDirectory(sourcePath) || this.isDirectory(destPath) || this.isFile(destPath)){

            return false;
        }
        
        try {
            
            this.fs.renameSync(sourcePath, destPath);
            
            return true;
            
        } catch (e) {

            return false;
        }
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

            throw new Error('Not a directory: ' + path);
        }

        for (let file of this.getDirectoryList(path)) {
  
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
     * Writes the specified data to a physical file, which will be created (if it does not exist) or overwritten without warning.
     * This method can be used to create a new empty file, a new file with any contents or to overwrite an existing one.
     *
     * We must check for file existence before executing this method if we don't want to inadvertently replace existing files.
     *
     * @see FilesManager.isFile
     *
     * @param pathToFile The path including full filename where data will be saved. File will be created or overwritten without warning.
     * @param data Any information to save on the file.
     * @param append Set it to true to append the data to the end of the file instead of overwritting it. File will be created if it does
     *        not exist, even with append set to true.
     *
     * @return True on success or false on failure.
     */
    saveFile(pathToFile: string, data = '', append = false){

        try {

            if(append){
                
                this.fs.appendFileSync(pathToFile, data);
                
            }else{
                
                this.fs.writeFileSync(pathToFile, data);
            }
            
            return true;
            
        } catch (e) {

            return false;
        }
    }


    /**
     * TODO - translate from php
     */
    createTempFile(){

        // TODO - translate from php
    }

    
    /**
     * Concatenate all the provided files, one after the other, into a single destination file.
     *
     * @param sourcePaths A list with the full paths to the files we want to join. The result will be generated in the same order.
     * @param destFile The full path where the merged file will be stored, including the full file name (will be overwitten if exists).
     * @param separator An optional string that will be concatenated between each file content. We can for example use "\n\n" to
     *        create some empty space between each file content
     *
     * @return True on success or false on failure.
     */
    mergeFiles(sourcePaths: string[], destFile: string, separator = ''){

        let mergedData = '';

        for (var i = 0; i < sourcePaths.length; i++) {

            mergedData += this.readFile(sourcePaths[i]);

            // Place separator string on all files except the last one
            if(i < sourcePaths.length - 1 && separator !== ''){

                mergedData += separator;
            }
        }

        return this.saveFile(destFile, mergedData);
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
    readFileBuffered(){

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
     * Renames a file.
     *
     * @param sourcePath The full path to the source file that must be renamed (including the filename itself).
     * @param destPath The full path to the new file name (including the filename itself). It must not exist.
     *
     * @return True on success or false on failure.
     */
    renameFile(sourcePath: string, destPath: string){

        if(!this.isFile(sourcePath) || this.isDirectory(destPath) || this.isFile(destPath)){

            return false;
        }

        try {
            
            this.fs.renameSync(sourcePath, destPath);
            
            return true;
            
        } catch (e) {

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
    
    
    /**
     * Delete a list of filesystem files.
     *
     * @param paths A list of filesystem paths to delete
     *
     * @return Returns true on success or false if any of the files failed to be deleted
     */
    deleteFiles(paths: string[]){

        let result = true;

        for (let i = 0; i < paths.length; i++) {

            if(!this.deleteFile(paths[i])){

                result = false;
            }
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
}