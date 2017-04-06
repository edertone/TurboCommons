"use strict";

/**
 * Utility methods used by TurboBuilder
 */


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
 * Output to ant console the warnings and errors if exist
 */
function echoWarningsAndErrors(antWarnings, antErrors){
	
	//Define the echo task to use for warnings and errors
	var echo = project.createTask("echo");
	var error = new org.apache.tools.ant.taskdefs.Echo.EchoLevel();
	error.setValue("error");
	echo.setLevel(error);

	//Display all the detected warnings
	for(var i = 0; i < antWarnings.length; i++){

		echo.setMessage("WARNING: " + antWarnings[i]);
		echo.perform();
	}

	//Display all the detected errors
	for(i = 0; i < antErrors.length; i++){

		echo.setMessage("ERROR: " + antErrors[i]);
		echo.perform();
	}

	//Set a failure to the ant build if errors are present
	if(antErrors.length > 0){

		project.setProperty("javascript.fail.message", "Source analisis detected errors.");
	}
}