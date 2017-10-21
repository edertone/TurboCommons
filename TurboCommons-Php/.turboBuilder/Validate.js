"use strict";

/**
 * This is the script that performs all the enabled turbo builder validations
 */


// Import Utils
load(project.getProperty("basedir") + '/Utils.js');


/** Array that will contain all the warnings detected by this script and will be displayed at the end */
var antWarnings = [];

/** Array that will contain all the errors detected by this script and will be displayed at the end */
var antErrors = [];

/** Full paths to the most common project folders */
var projectBaseDir = project.getProperty("basedir") + "/../";
var projectSrcDir = projectBaseDir + '/src';


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Aux methods


function validateFilesThatMustExist(errorPrefix, path, filesThatMustExist){
	
	for(var i = 0; i < filesThatMustExist.length; i++){
		
		if(!fileExists(path + filesThatMustExist[i])){
			
			antErrors.push(errorPrefix + path + filesThatMustExist[i] + " does not exist");
		}
	}
}


function validateAllowedFolders(errorPrefix, parentFolders, allowedSubFolders){
	
	for(i = 0; i < parentFolders.length; i++){
		
		var foldersList = getFoldersList(parentFolders[i]);
		
		for(var j = 0; j < foldersList.length; j++){
			
			if(!inArray(foldersList[j], allowedSubFolders)){
					
				antErrors.push(errorPrefix + foldersList[j] + " is not allowed inside " + parentFolders[i]);
			}						
		}
	}
}


function validateNamespaceString(namespace, filePath, mustContain){
	
	if(mustContain != ""){
		
		var strings = mustContain.split(",");
		var explodedPath = filePath.split('\\').reverse();
		
		var path = filePath.split('\\');
		path.pop();
		path = path.join('\\');
		
		for(var i = 0; i<strings.length; i++){
			
			// Replace the wildcards on the mustContain
			strings[i] = strings[i].replace('$path', path);
			
			for(var j = 0; j < explodedPath.length; j++){
				
				strings[i] = strings[i].replace('$' + String(j), explodedPath[j]);
			}
			
			if(namespace.indexOf(strings[i]) < 0){
				
				return strings[i];
			}
		}
	}
		
	return '';
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Apply the ProjectStructure rule if enabled
if(project.getProperty("Validate.ProjectStructure.enabled") !== "false"){
	
	validateFilesThatMustExist("Validate.ProjectStructure -> ", projectBaseDir, [".turboBuilder", "src/main", "src/test", "TurboBuilder.xml", "TurboBuilder-OneTime.properties"]);
	
	validateAllowedFolders("Validate.ProjectStructure -> ", [projectBaseDir + "src/main", projectBaseDir + "src/test"], ["css", "js", "ts", "php", "java", "resources"]);
	
	if(project.getProperty("Validate.ProjectStructure.forceSupportFolder") !== "false" && !fileExists(projectBaseDir + "support")){
			
		antErrors.push("Validate.ProjectStructure.forceSupportFolder -> " + projectBaseDir + "support folder does not exist");
	}
	
	if(project.getProperty("Validate.ProjectStructure.forceTODOFile") !== "false" && !fileExists(projectBaseDir + "support/TODO.txt")){
			
		antErrors.push("Validate.ProjectStructure.forceTODOFile -> " + projectBaseDir + "support/TODO.txt file does not exist");
	}

	if(project.getProperty("Validate.ProjectStructure.resourcesStructure") !== "false"){
		
		// TODO Validate resources folders structure
	}
	
	// Validate that gitIgnore file is correct
	if(project.getProperty("Validate.ProjectStructure.phpStructure") !== "false"){
	
		// TODO Validate php folders structure
	}
	
	if(project.getProperty("Validate.ProjectStructure.jsStructure") !== "false"){
		
		// TODO Validate js folders structure
	}
	
	if(project.getProperty("Validate.ProjectStructure.tsStructure") !== "false"){
		
		// TODO Validate ts folders structure
	}
	
	if(project.getProperty("Validate.ProjectStructure.javaStructure") !== "false"){
		
		// TODO Validate java folders structure
	}
	
	if(project.getProperty("Validate.ProjectStructure.cssStructure") !== "false"){
		
		// TODO Validate css folders structure
	}
		
	// Validate that gitIgnore file is correct
	if(project.getProperty("Validate.ProjectStructure.checkGitIgnore") !== "false"){
		
		try{
			
			var gitIgnore = loadFileAsString(projectBaseDir + "../.gitignore");
		
			var gitIgnoreLines = ["target/", "bin/", "cache.properties", "TurboBuilder-OneTime.properties", 
				".DS_Store", ".DS_Store?", ".Spotlight-V100", ".Trashes", "ehthumbs.db", "Thumbs.db", "thumbs.db"];
			
			for(i = 0; i < gitIgnoreLines.length; i++){
				
				if(gitIgnore.indexOf(gitIgnoreLines[i]) < 0){
					
					antErrors.push("Validate.ProjectStructure.checkGitIgnore -> " + projectBaseDir + "../.gitignore file does not contain " + gitIgnoreLines[i]);
				}
			}
			
		}catch(e){
			
			antErrors.push("Validate.ProjectStructure.checkGitIgnore -> " + projectBaseDir + "../.gitignore file does not exist");
		}		
	}	
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Apply the PhpNamespaces rule if enabled
if(project.getProperty("Validate.PhpNamespaces.enabled") === "true"){
		
	var phpFiles = getFilesList(projectSrcDir, "**/*.php", project.getProperty("Validate.PhpNamespaces.excludes"));
	
	for(var i = 0; i < phpFiles.length; i++){
		
		var file = loadFileAsString(projectSrcDir + "/" + phpFiles[i]);
		
		if(file.indexOf("namespace") >= 0){

			var namespace = file.split("namespace")[1].split(";")[0];
			
			var validateNamespace = validateNamespaceString(namespace, phpFiles[i], project.getProperty("Validate.PhpNamespaces.mustContain"));
			
			if(validateNamespace !== ''){
			
				antErrors.push("Validate.PhpNamespaces -> " + phpFiles[i] + " namespace <" + namespace + "> is invalid. Must contain <" + validateNamespace + ">");
			}	
			
		}else{
			
			if(project.getProperty("Validate.PhpNamespaces.mandatory") === "true"){
			
				antErrors.push("Validate.PhpNamespaces.mandatory -> " + phpFiles[i] + " does not contain a namespace declaration");
			}			
		}
	}		
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Apply the JsNamespaces rule if enabled
if(project.getProperty("Validate.JsNamespaces.enabled") === "true"){
		
	var jsFiles = getFilesList(projectSrcDir, "**/*.js", project.getProperty("Validate.JsNamespaces.excludes"));
	
	for(i = 0; i < jsFiles.length; i++){
		
		var jsFile = loadFileAsString(projectSrcDir + "/" + jsFiles[i]);
		
		if(jsFile.indexOf("@namespace") >= 0){

			var namespace = jsFile.split("var")[1].split("=")[0];
			
			var validateNamespace = validateNamespaceString(namespace, jsFiles[i], project.getProperty("Validate.JsNamespaces.mustContain"));
			
			if(validateNamespace !== ''){
			
				antErrors.push("Validate.JsNamespaces -> " + jsFiles[i] + " namespace <" + namespace + "> is invalid. Must contain <" + validateNamespace + ">");
			}	
			
		}else{
			
			if(project.getProperty("Validate.JsNamespaces.mandatory") === "true"){
			
				antErrors.push("Validate.JsNamespaces.mandatory -> " + jsFiles[i] + " does not contain a namespace declaration");
			}			
		}
	}		
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//Apply the TsNamespaces rule if enabled
if(project.getProperty("Validate.TsNamespaces.enabled") === "true"){
		
	var tsFiles = getFilesList(projectSrcDir, "**/*.ts", project.getProperty("Validate.TsNamespaces.excludes"));
	
	for(i = 0; i < tsFiles.length; i++){
		
		var tsFile = loadFileAsString(projectSrcDir + "/" + tsFiles[i]);
		
		if(tsFile.indexOf("namespace") >= 0){

			var namespace = tsFile.split("namespace")[1].split("{")[0];
			
			var validateTsNamespace = validateNamespaceString(namespace, tsFiles[i], project.getProperty("Validate.TsNamespaces.mustContain"));
			
			if(validateTsNamespace !== ''){
			
				antErrors.push("Validate.TsNamespaces -> " + tsFiles[i] + " namespace <" + namespace + "> is invalid. Must contain <" + validateTsNamespace + ">");
			}	
			
		}else{
			
			if(project.getProperty("Validate.TsNamespaces.mandatory") === "true"){
			
				antErrors.push("Validate.TsNamespaces.mandatory -> " + tsFiles[i] + " does not contain a namespace declaration");
			}			
		}
	}		
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Apply the Css rule if enabled
if(project.getProperty("Validate.Css.enabled") !== "false"){
	
	// TODO - Apply w3c css validator
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Apply the CopyrightHeaders rule if enabled
if(project.getProperty("Validate.CopyrightHeaders.enabled") !== "false"){
	
	try{
		
		var paths = project.getProperty("Validate.CopyrightHeaders.Header").split(",");
		var appliesTo = project.getProperty("Validate.CopyrightHeaders.Header.appliesTo").split(",");
		var includes = project.getProperty("Validate.CopyrightHeaders.Header.includes").split(",");
		var excludes = project.getProperty("Validate.CopyrightHeaders.Header.excludes").split(",");
			
		for(i = 0; i < paths.length; i++){
			
			var header = loadFileAsString(projectBaseDir + paths[i]);
			
			var files = getFilesList(projectBaseDir + appliesTo[i], includes[i], excludes[i]);
			
			for(var j = 0; j < files.length; j++){
				
				file = loadFileAsString(projectBaseDir + appliesTo[i] + "/" + files[j]);
				
				if(file.indexOf(header) != 0){
					
					antErrors.push("Validate.CopyrightHeaders -> " + appliesTo[i] + "/" + files[j]  + " bad copyright header. Must be as defined in " + paths[i]);
				}
			}	
		}
		
	}catch(e){
		
		antErrors.push("Validate.CopyrightHeaders -> There was a problem validating copyright headers. Check that setup is correctly defined.");
	}	
}


//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Show warnings and errors if any was generated

//Define the echo task to use for warnings and errors
var echo = project.createTask("echo");
var error = new org.apache.tools.ant.taskdefs.Echo.EchoLevel();
error.setValue("error");
echo.setLevel(error);

//Display all the detected warnings
for(i = 0; i < antWarnings.length; i++){

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