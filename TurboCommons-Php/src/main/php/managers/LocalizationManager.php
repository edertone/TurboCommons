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
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * TODO - adapt docs from ts
 */
class LocalizationManager {


    // TODO !!!!! - update everything on this file with the TS version that is NEWER


    /**
     * A list of languages that will be used by this class to translate the given keys, sorted by preference.
     * When a key and bundle are requested for translation, the class will check on the first language of this
     * list for a translated text. If missing, the next one will be used, and so.
     *
     * For example: Setting this property to ['en_US', 'es_ES', 'fr_FR'] and calling
     * localizationManager.get('HELLO', 'Greetings') will try to locate the en_US value for the
     * HELLO tag on the Greetings bundle. If the tag is not found for the specified locale and bundle, the same
     * search will be performed for the es_ES locale, and so, till a value is found or no more locales are defined.
     *
     * @var array
     */
    public $locales = [];


    /**
     * List of filesystem paths (relative or absolute) where our resourcebundles are located.
     *
     * Each path must contain wildcards to define how locales and bundles are structured:
     * - $locale wildcard will be replaced by each specific locale when trying to reach a path
     * - $bundle wildcard will be replaced by each specific bundle name when trying to reach a path
     *
     * Example1: ['myFolder/$locale/$bundle.txt'] will resolve to
     * 'myFolder/en_US/Customers.txt' when trying to load the Customers bundle for US english locale.
     *
     * Example2: ['myFolder/$bundle_$locale.properties'] will resolve to
     * 'myFolder/Customers_en_US.properties' when trying to load the Customers bundle for US english locale.
     *
     * The class will try to load the data from the paths in order of preference. If a bundle name is duplicated
     * on different paths, the bundle located on the first path of the list will be always used.<br><br>
     *
     * For example, if $paths = ['path1', 'path2'] and we have the same bundle named 'Customers' on both paths, the translation
     * for a key called 'NAME' will be always retrieved from path1. In case path1 does not contain the key,
     * path2 will NOT be used to find a bundle.
     *
     * Example: ['../locales/$locale/$bundle.json', 'src/resources/shared/locales/$bundle_$locale.properties']
     *
     * @var array
     */
    public $paths = ['$locale/$bundle.json'];


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
     * Enable this flag to load all specified paths as urls instead of file system paths
     */
    public $pathsAreUrls = true;


    /**
     * Stores all the loaded localization data
     *
     * @var array
     */
    private $_loadedData = [];


    /**
     * Stores the latest resource bundle that's been used to read a localized value
     */
    private $_lastBundle = '';


    // TODO - adapt from ts
    public function loadBundle(){


    }


    // TODO - adapt from ts
    public function get(string $key, string $bundle = '', string $locale = ''){

    }


    // TODO - adapt from ts
    public function getStartCase(){

    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @return The localized and case formatted text
     */
    public function getAllUpperCase(string $key, string $bundle = ''){

        return strtoupper($this->get($key, $bundle));
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    public function getAlllowerCase(string $key, string $bundle = ''){

        return strtolower($this->get($key, $bundle));
    }


    // TODO - adapt from ts
    public function getFirstUpperRestLower(){

    }


    // TODO - adapt from ts
    protected function parseJson($data){

    }


    // TODO - adapt from ts
    protected function parseProperties($data){

    }
}

?>