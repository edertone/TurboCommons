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


use Exception;
use stdClass;
use UnexpectedValueException;
use org\turbocommons\src\main\php\model\BaseStrictClass;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\utils\ObjectUtils;


/**
 * Fully featured translation manager to be used with any application that requires text internationalization.
 */
class LocalizationManager extends BaseStrictClass{


    /**
     * Defines the behaviour for get(), getStartCase(), etc... methods when a key is not found on
     * a bundle or the bundle does not exist
     *
     * If this value is empty, all missing keys will return an empty value
     * If this value contains a string, all missing keys will return that string
     * If this value contains a string with some of the following wildcards:
     *    - $key will be replaced with the key name. For example: get("NAME") will output [NAME] if the key is not found and missingKeyFormat = '[$key]'
     *    - $exception (This is the default value) will throw an exception with the problem cause description.
     */
    public $missingKeyFormat = '$exception';


    /**
     * Tells if the class has been initialized or not
     */
    private $_initialized = false;


    /**
     * The list of languages that are used by this class to translate the given keys, sorted by preference.
     *
     * @see LocalizationManager::locales()
     */
    private $_locales = [];


    /**
     * Stores the latest resource bundle that's been used to read a localized value
     */
    private $_lastBundle = '';


    /**
     * Stores the latest path that's been used to read a localized value
     */
    private $_lastPath = '';


    /**
     * Stores all the loaded localization data by path, bundle and locales
     */
    protected $_loadedData = [];


    /**
     * A files manager instance used to load the data when paths are from file system
     */
    private $_filesManager = null;


    /**
     * An http manager instance used to load the data when paths are urls
     */
    private $_httpManager = null;


    /**
     * Checks if the specified locale is currently loaded for the currently defined bundles and paths.
     *
     * @param $locale string A locale to check. For example 'en_US'
     *
     * @return boolean True if the locale is currently loaded on the class, false if not.
     */
    public function isLocaleLoaded(string $locale){

        return in_array($locale, $this->_locales);
    }


    /**
     * Performs the initial data load by looking for resource bundles on all the specified paths.
     * All the translations will be loaded for each of the specified locales.
     *
     * Calling this method is mandatory before starting to use this class.
     *
     * @param pathsManager An instance of HTTPManager or FilesManager that will be used to load the provided paths. If we are working
     *        with paths that are urls, we will pass here an HTTPManager. If we are working with file system paths, we will pass a FilesManager.
     * @param array $locales List of languages for which we want to load the translations. The list also defines the preferred
     *        translation order when a specified key is not found for a locale.
     * @param array $bundles A structure containing a list with the association between paths and their respective bundles.
     *        Each path is a relative or absolute string that defines a location where resourcebundles reside and must contain
     *        wildcards to define how locales and bundles are structured:
     *          - $locale wildcard will be replaced by each specific locale when trying to reach a path
     *          - $bundle wildcard will be replaced by each specific bundle name when trying to reach a path
     *
     *          Example1: 'myFolder/$locale/$bundle.txt' will resolve to
     *                    'myFolder/en_US/Customers.txt' when trying to load the Customers bundle for US english locale.
     *
     *          Example2: 'myFolder/$bundle_$locale.properties' will resolve to
     *                    'myFolder/Customers_en_US.properties' when trying to load the Customers bundle for US english locale.
     * @param callable $finishedCallback A method that will be executed once the initialization ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param callable $progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     *
     * @return void
     */
    public function initialize($pathsManager,
                               array $locales,
                               array $bundles,
                               callable $finishedCallback,
                               $progressCallback = null) {

        if(StringUtils::getPathElement(get_class($pathsManager)) === 'HTTPManager'){

            $this->_httpManager = $pathsManager;

        }else{

            $this->_filesManager = $pathsManager;
        }

        $this->_locales = [];
        $this->_lastBundle = '';
        $this->_lastPath = '';
        $this->_loadedData = [];

        $this->_loadData($locales, $bundles, function ($errors) use ($finishedCallback) {

            $this->_initialized = true;

            $finishedCallback($errors);

        }, $progressCallback);
    }


    /**
     * Adds extra languages to the list of currently loaded translation data.
     *
     * This method can only be called after the class has been initialized in case we need to add more translations.
     *
     * @param array $locales List of languages for which we want to load the translations. The list will be appended to any previously
     *        loaded locales and included in the preferred translation order.
     * @param callable $finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param callable $progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     *
     * @return void
     */
    public function loadLocales(array $locales, callable $finishedCallback, callable $progressCallback = null){

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized. Call initialize() before loading more locales');
        }

        $bundles = [];

        foreach (array_keys($this->_loadedData) as $path) {

            $bundles[] = ['path' => $path, 'bundles' => array_keys($this->_loadedData[$path])];
        }

        $this->_loadData($locales, $bundles, $finishedCallback, $progressCallback);
    }


    /**
     * Adds extra bundles to the currently loaded translation data
     *
     * This method can only be called after the class has been initialized in case we need to add more bundles to the loaded translations.
     *
     * @param string $path The path where the extra bundles to load are located. See initialize method for information about the path format.
     * @param array $bundles List of bundles to load from the specified path
     * @param callable $finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param callable $progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     *
     * @see LocalizationManager::initialize()
     *
     * @return void
     */
    public function loadBundles(string $path, array $bundles, callable $finishedCallback, callable $progressCallback = null){

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized. Call initialize() before loading more bundles');
        }

        $this->_loadData($this->_locales, [['path' => $path, 'bundles' => $bundles]], $finishedCallback, $progressCallback);
    }


    /**
     * Auxiliary method used by the initialize and load methods to perform the data load for the locales and bundles
     *
     * @see LocalizationManager::initialize()
     *
     * @param array $locales List of locales to load
     * @param array $bundles Information relative to paths and the bundles they contain
     * @param callable $finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param callable $progressCallback Executed after each request is performed
     */
    private function _loadData(array $locales,
                               array $bundles,
                               callable $finishedCallback,
                               callable $progressCallback = null){

        if(!ArrayUtils::isArray($locales) || count($locales) <= 0){

            throw new UnexpectedValueException('no locales defined');
        }

        if(!ArrayUtils::isArray($bundles) || count($bundles) <= 0){

            throw new UnexpectedValueException('bundles must be an array of objects');
        }

        // Generate the list of paths to be loaded
        $pathsToLoad = [];
        $pathsToLoadInfo = [];

        foreach ($bundles as $data) {

            foreach ($data['bundles'] as $bundle) {

                foreach ($locales as $locale) {

                    $pathsToLoadInfo[] = ['locale' => $locale, 'bundle' => $bundle, 'path' => $data['path']];

                    $pathsToLoad[] = StringUtils::replace($data['path'], ['$locale', '$bundle'], [$locale, $bundle]);
                }
            }
        }

        if($this->_filesManager !== null){

            $this->_loadDataFromFiles($locales, $pathsToLoad, $pathsToLoadInfo, $finishedCallback, $progressCallback);

        }else{

            $this->_loadDataFromUrls($locales, $pathsToLoad, $pathsToLoadInfo, $finishedCallback, $progressCallback);
        }
    }


    /**
     * Perform the paths load from file system
     *
     * @param array $locales List of locales to load
     * @param array $pathsToLoad list of paths that need to be loaded
     * @param array $pathsToLoadInfo original info about the paths to load
     * @param callable $finishedCallback method to execute once finished
     * @param callable $progressCallback method to execute after each path is loaded
     */
    private function _loadDataFromFiles(array $locales,
                                        array $pathsToLoad,
                                        array $pathsToLoadInfo,
                                        callable $finishedCallback,
                                        callable $progressCallback = null){

        // Load all the specified paths as files
        $errors = [];

        for ($i = 0, $l = count($pathsToLoad); $i < $l; $i++) {

            try {

                $fileContents = '';
                $fileContents = $this->_filesManager->readFile($pathsToLoad[$i]);

            } catch (Exception $e) {

                $errors[] = [
                    'path' => $pathsToLoad[$i],
                    'errorMsg' => $e,
                    'errorCode' => ''
                ];
            }

            $locale = $pathsToLoadInfo[$i]['locale'];
            $bundle = $pathsToLoadInfo[$i]['bundle'];
            $path = $pathsToLoadInfo[$i]['path'];

            if (!array_key_exists($path, $this->_loadedData)) {

                $this->_loadedData[$path] = [];
            }

            if (!array_key_exists($bundle, $this->_loadedData[$path])) {

                $this->_loadedData[$path][$bundle] = [];
            }

            switch (StringUtils::getPathExtension($pathsToLoad[$i])) {

                case 'json':
                    $this->_loadedData[$path][$bundle][$locale] = $this->parseJson($fileContents);
                    break;

                case 'properties':
                    $this->_loadedData[$path][$bundle][$locale] = $this->parseProperties($fileContents);
                    break;
            }

            if ($progressCallback !== null) {

                $progressCallback($pathsToLoad[$i], $l);
            }
        }

        $this->_locales = ArrayUtils::removeDuplicateElements(array_merge($this->_locales, $locales));
        $this->_lastBundle = $pathsToLoadInfo[count($pathsToLoadInfo) - 1]['bundle'];
        $this->_lastPath = $pathsToLoadInfo[count($pathsToLoadInfo) - 1]['path'];

        $finishedCallback($errors);
    }


    /**
     * Perform the paths load from urls
     *
     * @param array $locales List of locales to load
     * @param array $pathsToLoad list of paths that need to be loaded
     * @param array $pathsToLoadInfo original info about the paths to load
     * @param callable $finishedCallback method to execute once finished
     * @param callable $progressCallback method to execute after each path is loaded
     */
    private function _loadDataFromUrls(array $locales,
                                       array $pathsToLoad,
                                       array $pathsToLoadInfo,
                                       callable $finishedCallback,
                                       callable $progressCallback = null){

        // TODO
        // Use the _httpManager instance to load all the locales from the specified urls
    }


    /**
     * Get the translation for the given key, bundle and path
     *
     * @param string $key The key we want to read from the specified resource bundle and path
     * @param string $bundle The name for the resource bundle file. If not specified, the value
     *        that was used on the inmediate previous call of this method will be used. This can save us lots of typing
     *        if we are reading multiple consecutive keys from the same bundle.
     * @param string $path In case we have multiple bundles with the same name on different paths, we can set this parameter with
     *        the path value to uniquely reference the bundle and resolve the conflict. If all of our bundles have different
     *        names, this parameter can be ignored. Just like the bundle parameter, this one is remembered between get() calls.
     *
     * @return string The localized text
     */
    public function get(string $key, string $bundle = '', string $path = '') {

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized. Call initialize() before requesting translated texts');
        }

        // If no path specified, autodetect it or use the last one
        if ($path === '') {

            $path = $this->_lastPath;
        }

        // If no bundle is specified, the last one will be used
        if ($bundle === '') {

            $bundle = $this->_lastBundle;
        }

        if (!in_array($path, array_keys($this->_loadedData))) {

            throw new UnexpectedValueException('Path <'.$path.'> not loaded');
        }

        if (!in_array($bundle, array_keys($this->_loadedData[$path]))) {

            throw new UnexpectedValueException('Bundle <'.bundle.'> not loaded');
        }

        // Store the specified bundle name and path as the lasts that have been used till now
        $this->_lastBundle = $bundle;
        $this->_lastPath = $path;

        $bundleData = $this->_loadedData[$path][$bundle];

        // Loop all the locales to find the first one with a value for the specified key
        foreach ($this->_locales as $locale) {

            if (in_array($locale, array_keys($bundleData)) &&
                in_array($key, ObjectUtils::getKeys($bundleData[$locale]))) {

                return $bundleData[$locale]->$key;
            }
        }

        if (strpos($this->missingKeyFormat, '$exception') !== false) {

            throw new UnexpectedValueException('key <'.$key.'> not found on '.$bundle.' - '.$path);
        }

        return StringUtils::replace($this->missingKeyFormat, '$key', $key);
    }


    /**
     * The list of languages (sorted by preference) that are currently available by this class to translate the given keys.
     * When a key and bundle are requested for translation, the class will check on the first language of this
     * list for a translated text. If missing, the next one will be used, and so. This list is constructed after the initialize
     * or loadLocales methods are called.
     *
     * @example: After loading the following list of locales ['en_US', 'es_ES', 'fr_FR'] if we call localizationManager.get('HELLO', 'Greetings')
     * the localization manager will try to locate the en_US value for the HELLO tag on the Greetings bundle. If the tag is not found for the
     * specified locale and bundle, the same search will be performed for the es_ES locale, and so, till a value is found or no more locales
     * are defined.
     */
    public function locales(){

        return $this->_locales;
    }


    /**
     * Get the first locale from the list of loaded locales, which is the currently used to search for translated texts.
     *
     * @return string The locale that is defined as the primary one. For example: en_US, es_ES, ..
     */
    public function primaryLocale(){

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized');
        }

        return $this->_locales[0];
    }


    /**
     * Define the locale that will be placed at the front of the currently loaded locales list.
     *
     * This will be the first locale to use when trying to get a translation.
     *
     * @param string $locale A currently loaded locale that will be moved to the first position of the loaded locales list. If the specified locale
     *        is not currently loaded, an exception will happen.
     *
     * @return void
     */
    public function setPrimaryLocale(string $locale){

        if(!StringUtils::isString($locale)){

            throw new UnexpectedValueException('Invalid locale value');
        }

        if(!$this->isLocaleLoaded($locale)){

            throw new UnexpectedValueException($locale.' not loaded');
        }

        $result = [$locale];

        foreach ($this->_locales as $l) {

            if($l !== $locale){

                $result[] = $l;
            }
        }

        $this->_locales = $result;
    }


    /**
     * Change the loaded locales translation preference order. The same locales that are currently loaded must be passed
     * but with a different order to change the translation priority.
     *
     * @param array $locales A list with the new locales translation priority
     *
     * @return void
     */
    public function setLocalesOrder(array $locales){

        if(!ArrayUtils::isArray($locales)){

            throw new UnexpectedValueException('locales must be an array');
        }

        if(count($locales) !== count($this->_locales)){

            throw new UnexpectedValueException('locales must contain all the currently loaded locales');
        }

        foreach ($locales as $locale) {

            if(!$this->isLocaleLoaded($locale)){

                throw new UnexpectedValueException($locale.' not loaded');
            }
        }

        $this->_locales = $locales;
    }


    /**
     * Get the translation for the given key and bundle as a string with all words first character capitalized
     * and all the rest of the word with lower case
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getStartCase(string $key, string $bundle = '', string $path = '') {

        return StringUtils::formatCase($this->get($key, $bundle, $path), StringUtils::FORMAT_START_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getAllUpperCase(string $key, string $bundle = '', string $path = '') {

        return StringUtils::formatCase($this->get($key, $bundle, $path), StringUtils::FORMAT_ALL_UPPER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getAllLowerCase(string $key, string $bundle = '', string $path = '') {

        return StringUtils::formatCase($this->get($key, $bundle, $path), StringUtils::FORMAT_ALL_LOWER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as a string with the first character as Upper case
     * and all the rest as lower case
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getFirstUpperRestLower(string $key, string $bundle = '', string $path = ''){

        return StringUtils::formatCase($this->get($key, $bundle, $path), StringUtils::FORMAT_FIRST_UPPER_REST_LOWER);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Json formatted
     * resource bundles
     *
     * @param string $jsonString An object with the read resourcebundle json string
     */
    protected function parseJson(string $jsonString){

        return json_decode($jsonString);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Java properties
     * formatted resource bundles
     *
     * @param string $propertiesString A string containing the read resourcebundle java properties format string
     */
    protected function parseProperties(string $propertiesString){

        $result = new stdClass();

        $javaPropertiesObject = new JavaPropertiesObject($propertiesString);

        foreach ($javaPropertiesObject->getKeys() as $key) {

            $result->$key = $javaPropertiesObject->get($key);
        }

        return $result;
    }
}

?>