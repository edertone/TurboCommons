"use strict";

/**
 * --------------------------------------------------------------------------------------------------------------------------------------
 * Utility methods used by TurboBuilder
 * --------------------------------------------------------------------------------------------------------------------------------------
 */


/**
 * Check that the specified value is found inside an array
 */
function inArray(value, array){
	
	for(var i = 0; i < array.length; i++){
		
		if(array[i] === value){
			
			return true;
		}
	}
	
	return false;
}


/**
 * Check if the specified file or folder exists or not
 */
function fileExists(path){

	try{
	
		var f = new java.io.File(path);
	    
		return f.exists();
		
	}catch(e){

		// Nothing to do
	}
	
	return false;
}


/**
 * Load all the file contents and return it as a string
 */
function loadFileAsString(path, replaceWhiteSpaces){

	var file = new java.io.File(path);
	var fr = new java.io.FileReader(file);
	var br = new java.io.BufferedReader(fr);

	var line;
	var lines = "";

	while((line = br.readLine()) != null){

		if(replaceWhiteSpaces){

			lines = lines + line.replace(" ", "");

		}else{

			lines = lines + line;
		}
	}

	return lines;
}


/**
 * Get a list with all the first level folders inside the specified path.
 * 
 * @param path A full file system path from which we want to get the list of first level folders
 * 
 * @returns An array containing all the first level folders inside the given path. Each array element will be 
 * relative to the provided path. For example, if we provide "src/main" as path, 
 * resulting folders may be like "php", "css", ... and so.
 */
function getFoldersList(path){
	
	var ds = project.createDataType("dirset");
	
	ds.setDir(new java.io.File(path));
	ds.setIncludes("*");
	
	var srcFolders = ds.getDirectoryScanner(project).getIncludedDirectories();
    
    var result = [];
    
    for (var i = 0; i<srcFolders.length; i++){
        
    	result.push(srcFolders[i]);
    }
    
    return result;
}


/**
 * Get a list with all the files inside the specified path and all of its subfolders.
 * 
 * @param path A full file system path from which we want to get the list of files
 * @param includes comma- or space-separated list of patterns of files that must be included; all files are included when omitted.
 * @param excludes comma- or space-separated list of patterns of files that must be excluded; no files (except default excludes) are excluded when omitted.
 * 
 * @returns An array containing all the matching files inside the given path and subfolders. Each array element will be 
 * the full filename plus the relative path to the provided path. For example, if we provide "src/main" as path, 
 * resulting files may be like "php/managers/BigManager.php", ... and so.
 */
function getFilesList(path, includes, excludes){
	
	// Init default vars values
	includes = (includes === undefined || includes == null || includes == '') ? "**" : includes;
	excludes = (excludes === undefined || excludes == null || excludes == '') ? "" : excludes;
	
	var fs = project.createDataType("fileset");
	
	fs.setDir(new java.io.File(path));
    
	if(includes != ""){
	
		fs.setIncludes(includes);
	}	
    
    if(excludes != ""){
    
    	fs.setExcludes(excludes);
    }    

    var srcFiles = fs.getDirectoryScanner(project).getIncludedFiles();
    
    var result = [];
    
    for (var i = 0; i<srcFiles.length; i++){
        
    	result.push(srcFiles[i]);
    }
    
    return result;
}


/**
 * Copy all the contents from the given folder to another specified folder.
 * 
 * @param source A file system path where the files and folders to copy are found.
 * @param dest A file system path where the source files and folders will be copied.
 * 
 * @returns void
 */
function copyFolderTo(source, dest){
	
	var fs = project.createDataType("fileset");

	fs.setDir(new java.io.File(source));
    	
	var copy = project.createTask("copy");
	
	copy.setTodir(new java.io.File(dest));
	copy.setOverwrite(true);
	copy.addFileset(fs);
	copy.perform();
}


/**
 * Copy the specified file to the specified folder.
 * 
 * @param source A file system path including the filename that will be copied
 * @param dest A file system path where the file will be copied.
 * 
 * @returns void
 */
function copyFileTo(source, dest){
	
	var copy = project.createTask("copy");
	
	copy.setFile(new java.io.File(source));
	copy.setTodir(new java.io.File(dest));
	copy.setOverwrite(true);
	copy.perform();
}


/**
 * Create a file with the specified content
 * 
 * @param path Full path including the file name to be created
 * @param contents String containing the text to be written to the file
 * 
 * @returns void
 */
function createFile(path, contents){
	
	var echo = project.createTask("echo");
	
	echo.setFile(new java.io.File(path));
	echo.setMessage(contents);
	echo.perform();
}


/**
 * change the name of a file
 * 
 * @param from Full path including the file name to be renamed
 * @param to Full path including the file name that will be assigned
 * 
 * @returns void
 */
function renameFile(from, to){
	
	var move = project.createTask("move");
	
	move.setFile(new java.io.File(from));
	move.setTofile(new java.io.File(to));
	move.perform();
}


/**
 * Open an url with the specified browser
 * 
 * @param url Url to open 
 * @param browserExecutable Full path to the browser executable
 * 
 * @returns void
 */
function launchOnBrowser(url, browserExecutable){
	
	var exec = project.createTask("exec");
	exec.setExecutable(browserExecutable);
	exec.setSpawn(true);

	exec.createArg().setLine(encodeURI(url));
	
	exec.perform();
}