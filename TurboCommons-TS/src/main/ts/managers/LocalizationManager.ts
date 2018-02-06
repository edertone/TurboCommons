/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
   
import { StringUtils } from '../utils/StringUtils';
import { ObjectUtils } from '../utils/ObjectUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { JavaPropertiesObject } from '../model/JavaPropertiesObject';
import { HTTPManager } from './HTTPManager';


/**
 * A class that is used to manage the application texts internationalization<br>
 * Main features in brief:<br><br>
 * TODO
 */
export class LocalizationManager {


    /**
     * A list of languages that will be used by this class to translate the given keys, sorted by preference.
     * When a key and bundle are requested for translation, the class will check on the first language of this
     * list for a translated text. If missing, the next one will be used, and so.
     *
     * For example: Setting this property to ['en_US', 'es_ES', 'fr_FR'] and calling
     * localizationManager.get('HELLO', 'Greetings') will try to locate the en_US value for the
     * HELLO tag on the Greetings bundle. If the tag is not found for the specified locale and bundle, the same
     * search will be performed for the es_ES locale, and so, till a value is found or no more locales are defined.
     */
    locales: string[] = [];


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
     */
    paths: string[] = [];


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
    missingKeyFormat = '$exception';

    
    /**
     * Enable this flag to load all specified paths as urls instead of file system paths
     */
    pathsAreUrls = true;
    

    /**
     * Stores all the loaded localization data
     */
    protected _loadedData: any = {};


    /**
     * Stores the latest resource bundle that's been used to read a localized value
     */
    private _lastBundle = '';
    
    
    /**
     * Load the specified bundle
     *
     * @param bundle The name of the bundle to load
     * @param successCallback A method that will be executed after the bundle's been correctly loaded
     * @param errorCallback A method that will be executed if any error happens
     * @param pathIndex Define the position on the paths array that will be used to lookup for the bundle.
     *
     * @returns void
     */
    loadBundle(bundle: string, successCallback: Function|null = null, errorCallback: Function|null = null, pathIndex = 0) {

        if(!StringUtils.isString(bundle) || StringUtils.isEmpty(bundle)){
            
            throw new Error('bundle must be a non empty string');
        }
        
        if(this.locales.length <= 0){
            
            throw new Error('no locales defined');
        }
        
        if(this.paths.length <= 0){
            
            throw new Error('no paths defined');
        }
        
        if(pathIndex >= this.paths.length){
            
            throw new Error('invalid pathIndex');
        }
    
        let errorHappened = false;
        let callsCount = 0;
        
        let http = new HTTPManager();

        for (const locale of this.locales) {

            const path = this.paths[pathIndex].replace('$locale', locale).replace('$bundle', bundle);

            // TODO - if pathsAreUrls is false, we must try to load the path as a file system path 
            
            http.get(path, (result) => {
                
                callsCount++;

                if (!this._loadedData.hasOwnProperty(bundle)) {

                    this._loadedData[bundle] = {};
                }

                switch (StringUtils.getFileExtension(path)) {

                    case 'json':
                        this._loadedData[bundle][locale] = this.parseJson(JSON.parse(result));
                        break;

                    case 'properties':
                        // TODO
                        this._loadedData[bundle][locale] = this.parseProperties(result);
                        break;
                }

                if (!errorHappened && callsCount >= this.locales.length) {

                    this._lastBundle = bundle;

                    if (successCallback !== null) {

                        successCallback();
                    }
                }
                
            }, (err) => {

                callsCount++;
                
                if (!errorHappened && errorCallback !== null) {

                    errorCallback();
                }  
                
                errorHappened = true;
            });
        }
    }


    /**
     * Get the translation for the given key and bundle
     *
     * @param key The key we want to read from the specified resource bundle
     * @param bundle The name for the resource bundle file. If not specified, the value
     * that was used on the inmediate previous call of this method will be used. This can save us lots of typing
     * if we are reading multiple consecutive keys from the same bundle.
     * 
     * @returns The localized text
     */
    get(key: string, bundle = '') {

        // If no bundle is specified, the last one will be used
        if (bundle === '') {

            bundle = this._lastBundle;
        }

        if (Object.keys(this._loadedData).indexOf(bundle) === -1) {

            if(this.missingKeyFormat.indexOf('$exception') >= 0){
            
                throw new Error('Bundle <' + bundle + '> does not exist');
            
            }else{
            
                return this.missingKeyFormat.replace('$key', key);
            }
        }

        // Store the specified bundle name as the last that's been used till now
        this._lastBundle = bundle;

        const bundleData = this._loadedData[bundle];

        // Loop all the locales to find the first one with a value for the specified key
        for (const locale of this.locales) {

            if (Object.keys(bundleData).indexOf(locale) >= 0 &&
                    Object.keys(bundleData[locale]).indexOf(key) >= 0) {

                return bundleData[locale][key];
            }
        }

        if (this.missingKeyFormat.indexOf('$exception') >= 0) {

            throw new Error('key <' + key + '> not found');
        }

        return this.missingKeyFormat.replace('$key', key);
    }
    
    
    /**
     * Get the translation for the given key and bundle as a string with all words first character capitalized 
     * and all the rest of the word with lower case
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getStartCase(key: string, bundle = '') {

        return StringUtils.formatCase(this.get(key, bundle), StringUtils.FORMAT_START_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllUpperCase(key: string, bundle = '') {

        return StringUtils.formatCase(this.get(key, bundle), StringUtils.FORMAT_ALL_UPPER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllLowerCase(key: string, bundle = '') {

        return StringUtils.formatCase(this.get(key, bundle), StringUtils.FORMAT_ALL_LOWER_CASE);
    }
    
    
    /**
     * Get the translation for the given key and bundle as a string with the first character as Upper case
     * and all the rest as lower case
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getFirstUpperRestLower(key: string, bundle = ''){
        
        return StringUtils.formatCase(this.get(key, bundle), StringUtils.FORMAT_FIRST_UPPER_REST_LOWER);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Json formatted
     * resource bundles
     * 
     * @param data An object resulting the JSON.parse of the resourcebundle read
     */
    protected parseJson(data: Object): Object {

        return data;
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Java properties
     * formatted resource bundles
     * 
     * @param data A string containing the read resourcebundle
     */
    protected parseProperties(data: string): JavaPropertiesObject {

        return new JavaPropertiesObject(data);
    }
}
