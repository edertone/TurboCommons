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
 * Fully featured translation manager to be used with any application that requires text internationalization.
 */
export class LocalizationManager {


    /**
     * Enable this flag to load all the specified paths as urls instead of file system paths
     */
    pathsAreUrls = true;
    

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
     * Tells if the class has been initialized or not
     */
    private _initialized = false;
    
    
    /**
     * The list of languages that are used by this class to translate the given keys, sorted by preference.
     * 
     * @see this.locales()
     */
    private _locales: string[] = [];


    /**
     * Stores the latest resource bundle that's been used to read a localized value
     */
    private _lastBundle = '';
    
    
    /**
     * Stores the latest path that's been used to read a localized value
     */
    private _lastPath = '';
    
    
    /**
     * Stores all the loaded localization data by path, bundle and locales
     */
    protected _loadedData: {[path:string]: {[bundle: string]: {[locale: string]: {[key:string]: string}}}} = {};


    /**
     * An http manager instance used to load the data when paths are urls
     */
    private _httpManager = new HTTPManager();
    
    
    /**
     * Checks if the specified locale is currently loaded for the currently defined bundles and paths.
     * 
     * @param locale A locale to check. For example 'en_US'
     * 
     * @return True if the locale is currently loaded on the class, false if not.
     */
    isLocaleLoaded(locale: string){
        
        return (this._locales.indexOf(locale) >= 0);
    }
    
    
    /**
     * Performs the initial data load by looking for resource bundles on all the specified paths.
     * All the translations will be loaded for each of the specified locales.
     * 
     * Calling this method is mandatory before starting to use this class.
     * 
     * @param locales List of languages for which we want to load the translations. The list also defines the preferred
     *        translation order when a specified key is not found for a locale.
     * @param bundles A structure containing a list with the association between paths and their respective bundles.
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
     * @param finishedCallback A method that will be executed once the initialization ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @return void
     */
    initialize(locales: string[],
               bundles: {path: string, bundles: string[]}[],
               finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void),
               progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null) {

        this._locales = [];
        this._lastBundle = '';
        this._lastPath = '';
        this._loadedData = {};
        
        this._loadData(locales, bundles, (errors) => {
            
            this._initialized = true;
            
            finishedCallback(errors);
            
        }, progressCallback);
    }
    
    
    /**
     * Adds extra languages to the list of currently loaded translation data.
     * 
     * This method can only be called after the class has been initialized in case we need to add more translations.
     * 
     * @param locales List of languages for which we want to load the translations. The list will be appended to any previously
     *        loaded locales and included in the preferred translation order.
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @return void
     */
    loadLocales(locales: string[],
                finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void),
                progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before loading more locales');
        }
        
        let bundles:any[] = [];
        
        for (let path of ObjectUtils.getKeys(this._loadedData)) {
	
            bundles.push({path: path, bundles: ObjectUtils.getKeys(this._loadedData[path])});
        }
        
        this._loadData(locales, bundles, finishedCallback, progressCallback);   
    }
    
    
    /**
     * Adds extra bundles to the currently loaded translation data
     * 
     * This method can only be called after the class has been initialized in case we need to add more bundles to the loaded translations.
     * 
     * @param path The path where the extra bundles to load are located. See initialize method for information about the path format.
     * @param bundles List of bundles to load from the specified path
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @see this.initialize()
     * 
     * @return void
     */
    loadBundles(path: string,
                bundles: string[],
                finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void),
                progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before loading more bundles');
        }

        this._loadData(this._locales, [{path: path, bundles: bundles}], finishedCallback, progressCallback);   
    }
    
    
    /**
     * Auxiliary method used by the initialize and load methods to perform the data load for the locales and bundles
     * 
     * @see this.initialize()
     * 
     * @param locales List of locales to load
     * @param bundles Information relative to paths and the bundles they contain
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback Executed after each request is performed
     */
    private _loadData(locales: string[],
                      bundles: {path: string, bundles: string[]}[],
                      finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void),
                      progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!ArrayUtils.isArray(locales) || locales.length <= 0){
            
            throw new Error('no locales defined');
        }
        
        if(!ArrayUtils.isArray(bundles) || bundles.length <= 0){
            
            throw new Error('bundles must be an array of objects');
        }
        
        // Generate the list of paths to be loaded
        let pathsToLoad: string[] = [];
        let pathsToLoadInfo: any[] = [];
        
        for (let data of bundles) {
    
            for (let bundle of data.bundles){
                
                for (let locale of locales) {
                    
                    // TODO if pathsAreUrls is false, we must try to load the path as a file system path 
                    
                    pathsToLoadInfo.push({locale: locale, bundle: bundle, path: data.path});
                    
                    pathsToLoad.push(data.path.replace('$locale', locale).replace('$bundle', bundle));
                }
            }
        }
        
        // Execute all the requests
        this._httpManager.multiGetRequest(pathsToLoad, (results, anyError) =>{
            
            let errors: {path:string, errorMsg:string, errorCode:number}[] = [];
            
            for (let i = 0; i < results.length; i++) {
                
                if(results[i].isError){
                    
                    errors.push({
                        path: results[i].path,
                        errorMsg: results[i].errorMsg,
                        errorCode: results[i].errorCode 
                    });
                    
                }else{
                    
                    let locale = pathsToLoadInfo[i].locale;
                    let bundle = pathsToLoadInfo[i].bundle;
                    let path = pathsToLoadInfo[i].path;
                    
                    if (!this._loadedData.hasOwnProperty(path)) {

                        this._loadedData[path] = {};
                    }
                    
                    if (!this._loadedData[path].hasOwnProperty(bundle)) {

                        this._loadedData[path][bundle] = {};
                    }
                    
                    switch (StringUtils.getPathExtension(pathsToLoad[i])) {

                        case 'json':
                            this._loadedData[path][bundle][locale] = this.parseJson(results[i].response);
                            break;

                        case 'properties':
                            this._loadedData[path][bundle][locale] = this.parseProperties(results[i].response);
                            break;
                    }
                }
            }
            
            this._locales = ArrayUtils.removeDuplicateElements(this._locales.concat(locales));
            this._lastBundle = pathsToLoadInfo[pathsToLoadInfo.length - 1].bundle;
            this._lastPath = pathsToLoadInfo[pathsToLoadInfo.length - 1].path;

            finishedCallback(errors);
            
        }, null, (completedUrl, totalUrls) => {
            
            if (progressCallback !== null) {

                progressCallback(completedUrl, totalUrls);
            }
        });
    }
    

    /**
     * Get the translation for the given key, bundle and path
     *
     * @param key The key we want to read from the specified resource bundle and path
     * @param bundle The name for the resource bundle file. If not specified, the value
     *        that was used on the inmediate previous call of this method will be used. This can save us lots of typing
     *        if we are reading multiple consecutive keys from the same bundle.
     * @param path In case we have multiple bundles with the same name on different paths, we can set this parameter with
     *        the path value to uniquely reference the bundle and resolve the conflict. If all of our bundles have different
     *        names, this parameter can be ignored. Just like the bundle parameter, this one is remembered between get() calls.
     * 
     * @returns The localized text
     */
    get(key: string, bundle = '', path = '') {

        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before requesting translated texts');
        }
        
        // If no path specified, autodetect it or use the last one
        if (path === '') {

            path = this._lastPath;
        }

        // If no bundle is specified, the last one will be used
        if (bundle === '') {

            bundle = this._lastBundle;
        }
        
        if (Object.keys(this._loadedData).indexOf(path) === -1) {

            throw new Error('Path <' + path + '> not loaded');
        }
        
        if (Object.keys(this._loadedData[path]).indexOf(bundle) === -1) {

            throw new Error('Bundle <' + bundle + '> not loaded');
        }

        // Store the specified bundle name and path as the lasts that have been used till now
        this._lastBundle = bundle;
        this._lastPath = path;

        const bundleData = this._loadedData[path][bundle];

        // Loop all the locales to find the first one with a value for the specified key
        for (const locale of this._locales) {

            if (Object.keys(bundleData).indexOf(locale) >= 0 &&
                    Object.keys(bundleData[locale]).indexOf(key) >= 0) {

                return bundleData[locale][key];
            }
        }

        if (this.missingKeyFormat.indexOf('$exception') >= 0) {

            throw new Error('key <' + key + '> not found on ' + bundle + ' - ' + path);
        }

        return this.missingKeyFormat.replace('$key', key);
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
    locales(){
    
        return this._locales as ReadonlyArray<string>;
    }
    
    
    /**
     * Define the locale that will be placed at the front of the currently loaded locales list.
     * 
     * This will be the first locale to use when trying to get a translation.
     *
     * @param locale A currently loaded locale that will be moved to the first position of the loaded locales list. If the specified locale
     *        is not currently loaded, an exception will happen.
     *  
     * @return void
     */
    setPrimaryLocale(locale: string){
        
        if(!this.isLocaleLoaded(locale)){
            
            throw new Error(locale + ' not loaded');                
        }
        
        let result = [locale];
        
        for (let l of this._locales) {
	
            if(l !== locale){
                
                result.push(l);
            }
        }
        
        this._locales = result;
    }
    
    
    /**
     * Change the loaded locales translation preference order. The same locales that are currently loaded must be passed
     * but with a different order to change the translation priority.
     *
     * @param locales A list with the new locales translation priority
     *  
     * @return void
     */
    setLocalesOrder(locales: string[]){
        
        if(locales.length !== this._locales.length){
            
            throw new Error('locales must contain all the currently loaded locales');
        }
        
        for (let locale of locales) {
	
            if(!this.isLocaleLoaded(locale)){
                
                throw new Error(locale + ' not loaded');                
            }                
        }
        
        this._locales = locales;
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
    getStartCase(key: string, bundle = '', path = '') {

        return StringUtils.formatCase(this.get(key, bundle, path), StringUtils.FORMAT_START_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllUpperCase(key: string, bundle = '', path = '') {

        return StringUtils.formatCase(this.get(key, bundle, path), StringUtils.FORMAT_ALL_UPPER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllLowerCase(key: string, bundle = '', path = '') {

        return StringUtils.formatCase(this.get(key, bundle, path), StringUtils.FORMAT_ALL_LOWER_CASE);
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
    getFirstUpperRestLower(key: string, bundle = '', path = ''){
        
        return StringUtils.formatCase(this.get(key, bundle, path), StringUtils.FORMAT_FIRST_UPPER_REST_LOWER);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Json formatted
     * resource bundles
     * 
     * @param data An object with the read resourcebundle data after being parsed by JSON.parse
     */
    protected parseJson(data: string): {[key: string]: string} {

        return JSON.parse(data);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Java properties
     * formatted resource bundles
     * 
     * @param data A string containing the read resourcebundle
     */
    protected parseProperties(data: string): {[key: string]: string} {

        let result: {[key: string]: string} = {};
        
        let javaPropertiesObject = new JavaPropertiesObject(data);
        
        for (let key of javaPropertiesObject.getKeys()) {
	
            result[key] = javaPropertiesObject.get(key);
        }
        
        return result;
    } 
}
