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
     * Wildcards are string fragments that are placed inside the translated texts. Their main purpose is to be replaced at
     * runtime by custom values like for example a user name, a date, a numeric value, etc..
     *
     * This class helps with this process by including a parameter called 'toReplace' on all .get methods which allows us
     * to specify a string or list of strings that will replace the respective wildcards on the translated text. Each wildcard
     * must follow the format specified here, and contain a numeric digit that will be used to find the replacement text at the
     * 'toReplace' list. For example, if we define $N as the wildcard format, and we have a translation that contains $0, $1, $2,
     * $0 will be replaced with the first element on toReplace, $1 with the second and so.
     *
     * Note that N is mandayory on the wildcards format and the first index value is 0.
     */
    public $wildCardsFormat = '{N}';


    /**
     * Tells if the class has been initialized or not
     */
    private $_initialized = false;


    /**
     * @see LocalizationManager::locales()
     */
    private $_locales = [];


    /**
     * @see LocalizationManager::languages()
     */
    private $_languages = [];


    /**
     * Stores the latest resource bundle that's been used to read a localized value.
     * This is used by default when calling get without a bundle value
     */
    private $_activeBundle = '';


    /**
     * Stores the latest path that's been used to read a localized value
     * This is used by default when calling get without a path value
     */
    private $_activePath = '';


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
     * @param string $locale A locale to check. For example 'en_US'
     *
     * @return boolean True if the locale is currently loaded on the class, false if not.
     */
    public function isLocaleLoaded(string $locale){

        return in_array($locale, $this->_locales);
    }


    /**
     * Checks if the specified 2 digit language is currently loaded for the currently defined bundles and paths.
     *
     * @param string $language A language to check. For example 'en'
     *
     * @return boolean True if the language is currently loaded on the class, false if not.
     */
    public function isLanguageLoaded(string $language){

        if(strlen($language) !== 2){

            throw new UnexpectedValueException('language must be a valid 2 digit value');
        }

        return in_array($language, $this->_languages);
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
                               callable $finishedCallback = null,
                               $progressCallback = null) {

        if(StringUtils::getPathElement(get_class($pathsManager)) === 'HTTPManager'){

            $this->_httpManager = $pathsManager;

        }else{

            $this->_filesManager = $pathsManager;
        }

        $this->_locales = [];
        $this->_languages = [];
        $this->_activeBundle = '';
        $this->_activePath = '';
        $this->_loadedData = [];

        $this->_loadData($locales, $bundles, function ($errors) use ($finishedCallback) {

            $this->_initialized = true;

            if($finishedCallback !== null){

                $finishedCallback($errors);
            }

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
    public function loadLocales(array $locales, callable $finishedCallback = null, callable $progressCallback = null){

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized. Call initialize() before loading more locales');
        }

        $bundles = [];

        foreach (array_keys($this->_loadedData) as $path) {

            $bundleNames = [];

            foreach ($this->_loadedData[$path] as $localeData) {

                $bundleNames = array_merge($bundleNames, array_keys($localeData));
            }

            $bundles[] = ['path' => $path, 'bundles' => ArrayUtils::removeDuplicateElements($bundleNames)];
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
    public function loadBundles(string $path, array $bundles, callable $finishedCallback = null, callable $progressCallback = null){

        if(!ArrayUtils::isArray($bundles) || count($bundles) === 0){

            throw new UnexpectedValueException('no bundles specified for path: '.$path);
        }

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
                               callable $finishedCallback = null,
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

        $this->_locales = array_merge($this->_locales, $locales);

        if($this->_filesManager !== null){

            $this->_loadDataFromFiles($pathsToLoad, $pathsToLoadInfo, $finishedCallback, $progressCallback);

        }else{

            $this->_loadDataFromUrls($pathsToLoad, $pathsToLoadInfo, $finishedCallback, $progressCallback);
        }
    }


    /**
     * Perform the paths load from file system
     *
     * @param array $pathsToLoad list of paths that need to be loaded
     * @param array $pathsToLoadInfo original info about the paths to load
     * @param callable $finishedCallback method to execute once finished
     * @param callable $progressCallback method to execute after each path is loaded
     */
    private function _loadDataFromFiles(array $pathsToLoad,
                                        array $pathsToLoadInfo,
                                        callable $finishedCallback = null,
                                        callable $progressCallback = null){

        $this->_locales = ArrayUtils::removeDuplicateElements($this->_locales);
        $this->_languages = array_map(function ($l) {return substr($l, 0, 2);}, $this->_locales);

        $errors = [];

        for ($i = 0, $l = count($pathsToLoad); $i < $l; $i++) {

            try {

                $fileContents = $this->_filesManager->readFile($pathsToLoad[$i]);

                $locale = $pathsToLoadInfo[$i]['locale'];
                $bundle = $pathsToLoadInfo[$i]['bundle'];
                $path = $pathsToLoadInfo[$i]['path'];
                $bundleFormat = StringUtils::getPathExtension($pathsToLoad[$i]);

                if (!array_key_exists($path, $this->_loadedData)) {

                    $this->_loadedData[$path] = [];
                }

                if (!array_key_exists($locale, $this->_loadedData[$path])) {

                    $this->_loadedData[$path][$locale] = [];
                }

                $this->_loadedData[$path][$locale][$bundle] = $bundleFormat === 'json' ?
                $this->parseJson($fileContents) :
                $this->parseProperties($fileContents);

            } catch (Exception $e) {

                $errors[] = [
                    'path' => $pathsToLoad[$i],
                    'errorMsg' => $e,
                    'errorCode' => ''
                ];
            }

            if ($progressCallback !== null) {

                $progressCallback($pathsToLoad[$i], $l);
            }
        }

        if(count($pathsToLoadInfo) > 0){

            $this->_activeBundle = $pathsToLoadInfo[count($pathsToLoadInfo) - 1]['bundle'];
            $this->_activePath = $pathsToLoadInfo[count($pathsToLoadInfo) - 1]['path'];
        }

        if($finishedCallback !== null){

            $finishedCallback($errors);
        }
    }


    /**
     * Perform the paths load from urls
     *
     * @param array $pathsToLoad list of paths that need to be loaded
     * @param array $pathsToLoadInfo original info about the paths to load
     * @param callable $finishedCallback method to execute once finished
     * @param callable $progressCallback method to execute after each path is loaded
     */
    private function _loadDataFromUrls(array $pathsToLoad,
                                       array $pathsToLoadInfo,
                                       callable $finishedCallback = null,
                                       callable $progressCallback = null){

        // TODO
        // Use the _httpManager instance to load all the locales from the specified urls
    }


    /**
     * A list of strings containing the locales that are used by this class to translate the given keys, sorted by preference.
     * Each string is formatted as a standard locale code with language and country joined by an underscore, like: en_US, fr_FR
     *
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
     * A list of strings containing the languages that are used by this class to translate the given keys, sorted by preference.
     * Each string is formatted as a 2 digit language code, like: en, fr
     *
     * @see LocalizationManager::locales()
     */
    public function languages(){

        return $this->_languages;
    }


    /**
     * Get the bundle that is currently being used by default when traslating texts
     *
     * @return string The name for the currently active bundle
     */
    public function activeBundle(){

        return $this->_activeBundle;
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
     * Get the first language from the list of loaded locales, which is the currently used to search for translated texts.
     *
     * @return string The 2 digit language code that is defined as the primary one. For example: en, es, ..
     */
    public function primaryLanguage(){

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized');
        }

        return $this->_languages[0];
    }


    /**
     * Define the bundle that is used by default when no bundle is specified on the get methods
     *
     * @param string $bundle A currently loaded bundle to be used as the active one
     *
     * @return void
     */
    public function setActiveBundle(string $bundle){

        foreach (array_keys($this->_loadedData) as $path) {

            foreach ($this->_loadedData[$path] as $localeData) {

                if(in_array($bundle, array_keys($localeData))){

                    $this->_activeBundle = $bundle;
                    $this->_activePath = $path;
                    return;
                }
            }
        }

        throw new UnexpectedValueException($bundle.' bundle not loaded');
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
        $this->_languages = array_map(function ($l) {return substr($l, 0, 2);}, $this->_locales);
    }


    /**
     * Define the 2 digit language that will be placed at the front of the currently loaded locales list.
     *
     * This will be the first language to use when trying to get a translation.
     *
     * @param string $language A 2 digit language code that matches with any of the currently loaded locales, which will
     *        be moved to the first position of the loaded locales list. If the specified language does not match with
     *        a locale that is not currently loaded, an exception will happen.
     *
     * @return void
     */
    public function setPrimaryLanguage(string $language){

        foreach ($this->_locales as $locale) {

            if(substr($locale, 0, 2) === $language){

                return $this->setPrimaryLocale($locale);
            }
        }

        throw new UnexpectedValueException($language.' not loaded');
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

        if(count($locales) !== count($this->_locales)){

            throw new UnexpectedValueException('locales must contain all the currently loaded locales');
        }

        foreach ($locales as $locale) {

            if(!$this->isLocaleLoaded($locale)){

                throw new UnexpectedValueException($locale.' not loaded');
            }
        }

        $this->_locales = $locales;
        $this->_languages = array_map(function ($l) {return substr($l, 0, 2);}, $this->_locales);
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
     * @param mixed $toReplace A list of values that will replace the wildcards that are found on the translated text. Each wildcard
     *        will be replaced with the element whose index on the list matches it. Check the documentation for this.wildCardsFormat
     *        property to know more about how to setup wildcards.
     *
     * @return string The localized text
     */
    public function get(string $key, string $bundle = '', string $path = '', $toReplace = []) {

        if(!$this->_initialized){

            throw new UnexpectedValueException('LocalizationManager not initialized. Call initialize() before requesting translated texts');
        }

        // If no path specified, autodetect it or use the last one
        if ($path === '') {

            $path = $this->_activePath;
        }

        // If no bundle is specified, the last one will be used
        if ($bundle === '') {

            $bundle = $this->_activeBundle;
        }

        if (!in_array($path, array_keys($this->_loadedData))) {

            throw new UnexpectedValueException('Path <'.$path.'> not loaded');
        }

        // Loop all the locales to find the first one with a value for the specified key
        foreach ($this->_locales as $locale) {

            if (in_array($locale, array_keys($this->_loadedData[$path]))) {

                if (!in_array($bundle, array_keys($this->_loadedData[$path][$locale]))) {

                    throw new UnexpectedValueException('Bundle <'.$bundle.'> not loaded');
                }

                if(in_array($key, ObjectUtils::getKeys($this->_loadedData[$path][$locale][$bundle]))){

                    // Store the specified bundle name and path as the lasts that have been used till now
                    $this->_activeBundle = $bundle;
                    $this->_activePath = $path;

                    $result = $this->_loadedData[$path][$locale][$bundle]->$key;

                    // Replace all wildcards on the text with the specified replacements if any
                    $replacements = is_String($toReplace) ? [$toReplace] : $toReplace;

                    for ($i = 0, $l = count($replacements); $i < $l; $i++) {

                        $result = StringUtils::replace($result,
                            StringUtils::replace($this->wildCardsFormat, 'N', $i),
                            $replacements[$i]);
                    }

                    return $result;
                }
            }
        }

        if (strpos($this->missingKeyFormat, '$exception') !== false) {

            throw new UnexpectedValueException('key <'.$key.'> not found on '.$bundle.' - '.$path);
        }

        return StringUtils::replace($this->missingKeyFormat, '$key', $key);
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
    public function getStartCase(string $key, string $bundle = '', string $path = '', $toReplace = []) {

        return StringUtils::formatCase($this->get($key, $bundle, $path, $toReplace), StringUtils::FORMAT_START_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getAllUpperCase(string $key, string $bundle = '', string $path = '', $toReplace = []) {

        return StringUtils::formatCase($this->get($key, $bundle, $path, $toReplace), StringUtils::FORMAT_ALL_UPPER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager::get
     * @see StringUtils::formatCase
     *
     * @returns string The localized and case formatted text
     */
    public function getAllLowerCase(string $key, string $bundle = '', string $path = '', $toReplace = []) {

        return StringUtils::formatCase($this->get($key, $bundle, $path, $toReplace), StringUtils::FORMAT_ALL_LOWER_CASE);
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
    public function getFirstUpperRestLower(string $key, string $bundle = '', string $path = '', $toReplace = []){

        return StringUtils::formatCase($this->get($key, $bundle, $path, $toReplace), StringUtils::FORMAT_FIRST_UPPER_REST_LOWER);
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