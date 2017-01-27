"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

/** @namespace */
var org_turbocommons_src_main_js_managers = org_turbocommons_src_main_js_managers || {};


/**
 * SINGLETON class that is used to manage the internationalization for our application texts.<br>
 * Main features in brief:<br><br>
 * - Loads resource bundles from one or more specified paths, by order of preference<br>
 * - Supports several resourcebundle formats<br>
 * - A list of locales can be specified so the class will load them by order of preference if any tag is missing.<br>
 * - Supports diferent folder structures for the resourcebundles organization.<br>
 * - Uses a lazy method to load only the requested bundles and tries to minimize path requests.
 * 
 * <pre><code>
 * Usage example:
 * 
 * TODO - add detailed usage example
 * ...
 * </code></pre>
 * 
 * @class
 */
org_turbocommons_src_main_js_managers.LocalesManager = {

	_localesManager : null,

	/**
	 * Get the global singleton class instance
	 * 
	 * @memberOf org_turbocommons_src_main_js_managers.LocalesManager
	 * 
	 * @returns {org_turbocommons_src_main_js_managers.LocalesManager} The singleton instance
	 */
	getInstance : function(){

		if(!this._localesManager){

			this._localesManager = {


				/**
				 * A list of languages that will be used by this class to translate the given keys, sorted by preference. When a key and bundle are requested for translation,
				 * the class will check on the first language of this list for a translated text. If missing, the next one will be used, and so.<br><br>
				 * For example: Setting this property to ['en_US', 'es_ES', 'fr_FR'] and calling
				 * LocalesManager::getInstance()->get('HELLO', 'Greetings') will try to locate the en_US value for the
				 * HELLO tag on the Greetings bundle. If the tag is not found for the specified locale and bundle, the same
				 * search will be performed for the es_ES locale, and so, till a value is found or no more locales are defined.
				 *
				 * @type {array}
				 */
				locales : [],


				/**
				 * Specifies the expected format for the loaded resourcebundle files on each of the specified paths.
				 *
				 * We can define a different format for each of the paths in the $paths property of this class, but is not mandatory.
				 * We can define a single format for all of the specified paths, or we can specify the first n. If there are more
				 * defined paths than formats, the last format will be used for all the subsequent paths on the $paths array.
				 *
				 * Possible values: LocalesManager::FORMAT_JAVA_PROPERTIES, LocalesManager::FORMAT_ANDROID_XML
				 *
				 * TODO: Add support for more internationalization formats
				 *
				 * @type {array}
				 */
				bundleFormat : [org_turbocommons_src_main_js_managers.LocalesManager.FORMAT_JAVA_PROPERTIES],


				/**
				 * List of filesystem paths (relative or absolute) where the roots of our resourcebundles are located.
				 * The class will try to load the data from the paths in order of preference. If a bundle name is duplicated on different paths, the bundle located on the first
				 * path of the list will be always used.<br><br>
				 * For example, if $paths = ['path1', 'path2'] and we have the same bundle named 'Customers' on both paths, the translation
				 * for a key called 'NAME' will be always retrieved from path1. In case path1 does not contain the key, path2 will NOT be used to find a bundle.
				 *
				 * Example: ['../locales', 'src/resources/shared/locales']
				 *
				 * @type {array}
				 */
				paths : [],


				/**
				 * List that defines the expected structure for each of the specified bundle root folders. Its main purpose is to allow us storing
				 * the bundles with any directory structure we want.
				 *
				 * Following format is expected: 'somefolder/somefolder/$locale/$bundle.extension', where $locale will be replaced by the
				 * current locale, and $bundle by the current bundle name.
				 *
				 * Note:  If there are less elements in $pathStructure than in $paths, the last element of the list
				 * list will be used for the rest of the paths that do no have an explicit path structure defined.
				 *
				 * Example: ['myFolder/$locale/$bundle.txt'] will resolve to 'myFolder/en_US/Customers.txt' when trying to load the Customers bundle for US english locale.
				 *
				 * @type {array}
				 */
				pathStructure : ['$locale/$bundle.properties'],


				/**
				 * Stores the locales data as it is read from disk
				 *
				 * @type {array}
				 */
				_loadedData : [],


				/** 
				 * Stores the latest resource bundle that's been used to read a localized value
				 * 
				 * @type {string}
				 */
				_lastBundle : '',


				/**
				 * Reads the value for the specified bundle, key and locale.
				 *
				 * @param {string} key The key we want to read from the specified resource bundle
				 * @param {string} bundle The name for the resource bundle file. If not specified, the value
				 * that was used on the inmediate previous call of this method will be used. This can save us lots of typing
				 * if we are reading multiple consecutive keys from the same bundle.
				 * @param {string} locale The locale we are requesting from the specified bundle and key. If not specified, the value
				 * that is defined on the locales attribute of this class will be used.
				 *
				 * @returns {string} The localized text
				 */
				get : function(key, bundle, locale){

					// Set default values if they are not defined
					bundle = bundle === undefined ? '' : bundle;
					locale = locale === undefined ? '' : locale;

					var localesManager = org_turbocommons_src_main_js_managers.LocalesManager.getInstance();
					var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

					// We copy the locales array to prevent it from being altered by this method
					var localesArray = localesManager.locales;

					// Locales must be an array
					if(!validationManager.isArray(localesArray)){

						throw new Error("LocalesManager.get - locales property must be an array");
					}

					// Paths verifications
					if(!validationManager.isArray(localesManager.paths)){

						throw new Error("LocalesManager.get - paths property must be an array");
					}

					if(!validationManager.isArray(localesManager.pathStructure)){

						throw new Error("LocalesManager.get - pathStructure property must be an array");
					}

					if(localesManager.pathStructure.length > localesManager.paths.length){

						throw new Error("LocalesManager.get - pathStructure cannot have more elements than paths");
					}

					// Check if we need to load the last used bundle
					if(bundle == ''){

						bundle = localesManager._lastBundle;
					}

					if(bundle == ''){

						throw new Error("LocalesManager.get - No resource bundle specified");
					}

					// Store the specified bundle name as the last that's been used till now
					localesManager._lastBundle = bundle;

					// Add the specified locale at the start of the list of locales
					if(locale != ''){

						localesArray.unshift(locale);
					}

					// Loop all the locales to find the first one with a value for the specified key
					for(var i = 0; i < localesArray.length; i++){

						// Check if we need to load the bundle from disk
						// TODO - Javascript loads from a url
						//						if(!isset($this->_loadedData[$bundle][$locale])){
						//
						//							$this->_loadBundle($bundle, $locale);
						//						}

						//						if(isset($this->_loadedData[$bundle][$locale][$key])){
						//
						//							return $this->_loadedData[$bundle][$locale][$key];
						//						}
					}

					throw new Error('LocalesManager.get: Specified key <' + key + '> was not found on locales list: [' + localesArray.join(', ') + ']');
				},


				/**
				 * Read the specified bundle and locale from disk and store the values on memory
				 *
				 * @param {string} bundle The name for the bundle we want to load
				 * @param {string} locale The specific language we want to load
				 *
				 * @returns void
				 */
				_loadBundle : function(bundle, locale){

					// Alias namespaces
					var ut = org_turbocommons_src_main_js_utils;

					var localesManager = org_turbocommons_src_main_js_managers.LocalesManager.getInstance();
					var directorySeparator = ut.FileSystemUtils.getDirectorySeparator();

					var pathStructureArray = localesManager.pathStructure;

					for(var i = 0; i < localesManager.paths.length; i++){

						var pathStructure = '';

						// Process the path format string
						if(pathStructureArray.length > 0){

							pathStructure = pathStructureArray.shift().replace('$bundle', bundle).replace('$locale', locale);
						}

						var bundlePath = ut.StringUtils.formatPath(localesManager.paths[i] + directorySeparator + pathStructure);

						// TODO - js loads the bundles from urls
						//						if(ut.FileSystemUtils.isFile(bundlePath)){
						//
						//							var bundleData = ut.FileSystemUtils.readFile(bundlePath);
						//
						//							localesManager._loadedData[bundle][locale] = SerializationUtils::propertiesToArray($bundleData);
						//
						//							return;
						//						}						
					}

					throw new Error('LocalesManager._loadBundle: Could not load bundle <' + bundle + '> and locale <' + locale + '>');
				},
			};
		}

		return this._localesManager;
	}
};


/**
 * Defines the JAVA properties file format, which is the JAVA standard for text internationalization.
 *
 * JAVA properties format is a plain text format that stores KEY/VALUE pairs as 'Key=Value'.
 * The file is encoded as ISO-8859-1 by definition, so it is not recommended to use UTF-8 when creating a .properties file.
 * All special characters that are not ISO-8859-1 need to be escaped as unicode characters like \u0009, \u00F1 inside the file.
 * 
 * @constant {string}
 */
org_turbocommons_src_main_js_managers.LocalesManager.FORMAT_JAVA_PROPERTIES = '';


/**
 * TODO
 * 
 * @constant {string}
 */
org_turbocommons_src_main_js_managers.LocalesManager.FORMAT_ANDROID_XML = 'FORMAT_ANDROID_XML';