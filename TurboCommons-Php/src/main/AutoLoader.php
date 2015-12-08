<?php

// Register the autoload method that will locate and automatically load the library classes
spl_autoload_register(function($className){

	// Replace all slashes to the correct OS directory sepparator
	$classPath = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $className));

	// Remove unwanted classname path parts
	$classPath = array_pop(explode('src'.DIRECTORY_SEPARATOR.'main'.DIRECTORY_SEPARATOR, $classPath)).'.php';

	if(file_exists(__DIR__.DIRECTORY_SEPARATOR.$classPath)){

		require_once __DIR__.DIRECTORY_SEPARATOR.$classPath;
	}
});

?>