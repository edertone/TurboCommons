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


//--------------------------------------------------------------------------------------------------------------------------------------
// Apply the ProjectStructure rule if enabled
if(project.getProperty("Validate.ProjectStructure.enabled") === "true"){
	
	// TODO
}


//--------------------------------------------------------------------------------------------------------------------------------------
//Apply the PhpNamespaces rule if enabled
if(project.getProperty("Validate.PhpNamespaces.enabled") === "true"){
		
	var phpFiles = getFilesList(projectSrcDir, "**/*.php", project.getProperty("Validate.PhpNamespaces.excludes"));
	
	for(var i = 0; i < phpFiles.length; i++){
		
		var file = loadFileAsString(projectSrcDir + "/" + phpFiles[i]);
		
		if(file.indexOf("namespace") >= 0){

			var fileNamespace = file.split("namespace")[1].split(";")[0];
			
			var namespace = phpFiles[i].split('\\');
			namespace.pop();
			namespace = namespace.join('\\');
			
			if(fileNamespace.indexOf(namespace) < 0){
				
				antErrors.push(phpFiles[i] + " namespace <" + fileNamespace + "> is invalid. Must contain <" + namespace + ">");
			}
			
			var mustContain = project.getProperty("Validate.PhpNamespaces.mustContain");
			
			if(mustContain != "" && fileNamespace.indexOf(mustContain) < 0){
				
				antErrors.push(phpFiles[i] + " namespace <" + fileNamespace + "> is invalid. Must contain <" + mustContain + ">");
			}
			
		}else{
			
			if(project.getProperty("Validate.PhpNamespaces.mandatory") === "true"){
			
				antErrors.push(phpFiles[i] + " does not contain a namespace declaration");
			}			
		}
	}		
}


//--------------------------------------------------------------------------------------------------------------------------------------
// Apply the Css rule if enabled
if(project.getProperty("Validate.Css.enabled") === "true"){
	
	// TODO
}


// --------------------------------------------------------------------------------------------------------------------------------------
// Apply the CopyrightHeaders rule if enabled
if(project.getProperty("Validate.CopyrightHeaders.enabled") === "true"){
	
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
				
				antErrors.push(appliesTo[i] + "/" + files[j]  + " bad copyright header. Must be as defined in " + paths[i]);
			}
		}	
	}
}


//--------------------------------------------------------------------------------------------------------------------------------------
// Show warnings and errors if any was generated
echoWarningsAndErrors(antWarnings, antErrors);