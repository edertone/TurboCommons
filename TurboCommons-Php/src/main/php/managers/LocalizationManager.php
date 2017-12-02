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

use UnexpectedValueException;
use org\turbocommons\src\main\php\model\BaseSingletonClass;
use org\turbocommons\src\main\php\utils\SerializationUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * A class that is used to manage the internationalization for our application texts.<br>
 * Main features in brief:<br><br>
 * - Loads resource bundles from one or more specified paths, by order of preference<br>
 * - Supports several resourcebundle formats<br>
 * - A list of locales can be specified so the class will load them by order of preference if any tag is missing.<br>
 * - Supports diferent folder structures for the resourcebundles organization.<br>
 * - Uses a lazy method to load only the requested bundles and tries to minimize path requests.
 */
class LocalizationManager extends BaseSingletonClass{


    /**
     * Defines the JAVA properties file format, which is the JAVA standard for text internationalization.
     *
     * JAVA properties format is a plain text format that stores KEY/VALUE pairs as 'Key=Value'.
     * The file is encoded as ISO-8859-1 by definition, so it is not recommended to use UTF-8 when creating a .properties file.
     * All special characters that are not ISO-8859-1 need to be escaped as unicode characters like \u0009, \u00F1 inside the file.
     */
    const FORMAT_JAVA_PROPERTIES = 'FORMAT_JAVA_PROPERTIES';


    /**
     * TODO
     */
    const FORMAT_ANDROID_XML = 'FORMAT_ANDROID_XML';


    /**
     * A list of languages that will be used by this class to translate the given keys, sorted by preference. When a key and bundle are requested for translation,
     * the class will check on the first language of this list for a translated text. If missing, the next one will be used, and so.<br><br>
     * For example: Setting this property to ['en_US', 'es_ES', 'fr_FR'] and calling
     * LocalizationManager::getInstance()->get('HELLO', 'Greetings') will try to locate the en_US value for the
     * HELLO tag on the Greetings bundle. If the tag is not found for the specified locale and bundle, the same
     * search will be performed for the es_ES locale, and so, till a value is found or no more locales are defined.
     *
     * @var array
     */
    public $locales = [];


    /**
     * Specifies the expected format for the loaded resourcebundle files on each of the specified paths.
     *
     * We can define a different format for each of the paths in the $paths property of this class, but is not mandatory.
     * We can define a single format for all of the specified paths, or we can specify the first n. If there are more
     * defined paths than formats, the last format will be used for all the subsequent paths on the $paths array.
     *
     * Possible values: LocalizationManager::FORMAT_JAVA_PROPERTIES, LocalizationManager::FORMAT_ANDROID_XML
     *
     * TODO: Add support for more internationalization formats
     *
     * @var array
     */
    public $bundleFormat = [self::FORMAT_JAVA_PROPERTIES];


    /**
     * List of filesystem paths (relative or absolute) where the roots of our resourcebundles are located.
     * The class will try to load the data from the paths in order of preference. If a bundle name is duplicated on different paths, the bundle located on the first
     * path of the list will be always used.<br><br>
     * For example, if $paths = ['path1', 'path2'] and we have the same bundle named 'Customers' on both paths, the translation
     * for a key called 'NAME' will be always retrieved from path1. In case path1 does not contain the key, path2 will NOT be used to find a bundle.
     *
     * Example: ['../locales', 'src/resources/shared/locales']
     *
     * @var array
     */
    public $paths = [];


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
     * @var array
     */
    public $pathStructure = ['$locale/$bundle.properties'];


    /**
     * Stores the locales data as it is read from disk
     *
     * @var array
     */
    private $_loadedData = [];


    /** Stores the latest resource bundle that's been used to read a localized value */
    private $_lastBundle = '';


    /**
     * Reads the value for the specified bundle, key and locale.
     *
     * @param string $key The key we want to read from the specified resource bundle
     * @param string $bundle The name for the resource bundle file. If not specified, the value
     * that was used on the inmediate previous call of this method will be used. This can save us lots of typing
     * if we are reading multiple consecutive keys from the same bundle.
     * @param string $locale The locale we are requesting from the specified bundle and key. If not specified, the value
     * that is defined on the locales attribute of this class will be used.
     *
     * @return string The localized text
     */
    public function get(string $key, string $bundle = '', string $locale = ''){

        // We copy the locales array to prevent it from being altered by this method
        $localesArray = $this->locales;

        // Locales must be an array
        if(!is_array($localesArray)){

            throw new UnexpectedValueException('LocalizationManager->get: locales property must be an array');
        }

        // Paths verifications
        if(!is_array($this->paths)){

            throw new UnexpectedValueException('LocalizationManager->get: paths property must be an array');
        }

        if(!is_array($this->pathStructure)){

            throw new UnexpectedValueException('LocalizationManager->get: pathStructure property must be an array');
        }

        if(count($this->pathStructure) > count($this->paths)){

            throw new UnexpectedValueException('LocalizationManager->get: pathStructure cannot have more elements than paths');
        }

        // Check if we need to load the last used bundle
        if($bundle == ''){

            $bundle = $this->_lastBundle;
        }

        if($bundle == ''){

            throw new UnexpectedValueException('LocalizationManager->get: No resource bundle specified');
        }

        // Store the specified bundle name as the last that's been used till now
        $this->_lastBundle = $bundle;

        // Add the specified locale at the start of the list of locales
        if($locale != ''){

            array_unshift($localesArray, $locale);
        }

        // Loop all the locales to find the first one with a value for the specified key
        foreach ($localesArray as $locale) {

            // Check if we need to load the bundle from disk
            if(!isset($this->_loadedData[$bundle][$locale])){

                $this->_loadBundle($bundle, $locale);
            }

            if($this->_loadedData[$bundle][$locale]->isKey($key)){

                return $this->_loadedData[$bundle][$locale]->get($key);
            }
        }

        throw new UnexpectedValueException('LocalizationManager->get: Specified key <'.$key.'> was not found on locales list: ['.implode(', ', $localesArray).']');
    }


    /**
     * Read the specified bundle and locale from disk and store the values on memory
     *
     * @param string $bundle The name for the bundle we want to load
     * @param string $locale The specific language we want to load
     *
     * @return void
     */
    private function _loadBundle(string $bundle, string $locale){

        $filesManager = new FilesManager();
        $pathStructureArray = $this->pathStructure;

        foreach ($this->paths as $path) {

            $processedPathStructure = '';

            // Process the path format string
            if(count($pathStructureArray) > 0){

                $processedPathStructure = str_replace(['$bundle', '$locale'], [$bundle, $locale], array_shift($pathStructureArray));
            }

            $bundlePath = StringUtils::formatPath($path.DIRECTORY_SEPARATOR.$processedPathStructure);

            if($filesManager->isFile($bundlePath)){

                $bundleData = $filesManager->readFile($bundlePath);

                $this->_loadedData[$bundle][$locale] = SerializationUtils::stringToJavaPropertiesObject($bundleData);

                return;
            }
        }

        throw new UnexpectedValueException('LocalizationManager->_loadBundle: Could not load bundle <'.$bundle.'> and locale <'.$locale.'>');
    }
}

?>