/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
   
import { StringUtils } from '../utils/StringUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { JavaPropertiesObject } from '../model/JavaPropertiesObject';
import { HTTPManager } from './HTTPManager';


/**
 * Fully featured translation manager to be used with any application that requires text internationalization.
 */
export class LocalizationManager {


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
    wildCardsFormat = '{N}';

    
    /**
     * If set to true, when we call any get method from this class to retrieve a translated text, we will be forced
     * to provide the bundle for the key we are looking for. If set to false, only the key parameter will be required.
     */
    isBundleMandatory = false;


    /**
     * Tells if the class has been initialized or not
     */
    private _initialized = false;
    
    
    /**
     * @see this.locales()
     */
    private _locales: string[] = [];
    
    
    /**
     * @see this.languages()
     */
    private _languages: string[] = [];


    /**
     * Stores the latest resource bundle that's been used to read a localized value.
     * This is used by default when calling get without a bundle value
     */
    private _activeBundle = '';
    
    
    /**
     * Stores the label for the latest location that's been used to read a localized value
     * This is used by default when calling get without a location value
     */
    private _activeLocation = '';
    
    
    /**
     * Stores all the provided location definitions
     */
    private _definedLocations: {label: string, path: string, bundles: string[]}[] = [];

    
    /**
     * Stores all the loaded localization data by location name, locales, bundle and key
     */
    protected _loadedTranslations: {[location:string]: {[locale: string]: {[bundle: string]: {[key:string]: string}}}} = {};

    
    /**
     * A files manager instance used to load the data when paths are from file system.
     * 
     * (Note that FilesManager class is part of the TurboDepot library) 
     */
    private _filesManager:any = null;
    

    /**
     * An http manager instance used to load the data when paths are urls
     */
    private _httpManager:HTTPManager|null = null;


    /**
     * Check if the class has been correctly initialized
     */
    isInitialized(){
        
        return this._initialized;
    }


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
     * Checks if the specified 2 digit language is currently loaded for the currently defined bundles and paths.
     *
     * @param language A language to check. For example 'en'
     *
     * @return True if the language is currently loaded on the class, false if not.
     */
    isLanguageLoaded(language: string){
        
        if(language.length !== 2){

            throw new Error('language must be a valid 2 digit value');
        }
        
        return (this._languages.indexOf(language) >= 0);
    }
    
    
    /**
     * Performs the initial data load by looking for resource bundles on all the specified locations.
     * All the translations will be loaded for each of the specified locales.
     * 
     * Calling this method is mandatory before starting to use this class.
     * 
     * @param locationsLoader An instance of HTTPManager or FilesManager that will be used to load the provided locations. If we are working
     *        with paths that are urls, we will pass here an HTTPManager. If we are working with file system paths, we will pass a FilesManager.
     *        (Note that FilesManager class is part of the TurboDepot library) 
     * @param locales List of languages for which we want to load the translations. The list also defines the preferred
     *        translation order when a specified key is not found for a locale.
     * @param locations A list (sorted by preference) where each item defines a translations location and must have three properties:
     *        - label: A name that will be used to reference the location
     *        - path: A relative or absolute string that defines a location where resourcebundles reside. It must contain some wildcards:
     *          - $locale wildcard will be replaced by each specific locale when trying to reach a path
     *          - $bundle wildcard will be replaced by each specific bundle name when trying to reach a path
     *              Example1: 'myFolder/$locale/$bundle.txt' will resolve to
     *                        'myFolder/en_US/Customers.txt' when trying to load the Customers bundle for US english locale.
     *              Example2: 'myFolder/$bundle_$locale.properties' will resolve to
     *                        'myFolder/Customers_en_US.properties' when trying to load the Customers bundle for US english locale.
     *        - bundles: The list of bundles to be loaded from the specified path
     * @param finishedCallback A method that will be executed once the initialization ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @return void
     */
    initialize(locationsLoader:any,
               locales: string[],
               locations: {label: string, path: string, bundles: string[]}[],
               finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
               progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null) {

        if(locationsLoader as HTTPManager){
            
            this._httpManager = locationsLoader;
        
        }else{
            
            this._filesManager = locationsLoader;
        }
        
        this._locales = [];
        this._languages = [];
        this._activeBundle = '';
        this._activeLocation = '';
        this._definedLocations = locations;
        this._loadedTranslations = {};
        
        // Validate the locations are correctly defined
        if(!ArrayUtils.isArray(locations) || locations.length <= 0){
            
            throw new Error('Locations must be an array of objects');
        }
        
        for (let location of locations) {

            if(!location.label || StringUtils.isEmpty(location.label) ||
               !location.path || StringUtils.isEmpty(location.path) ||
               !location.bundles || !ArrayUtils.isArray(location.bundles)){
                
                throw new Error('Invalid locations specified');
            }
        }

        this._loadData(locales,
            locations.map((l:any) => {return {label: l.label, bundles: l.bundles}}),
            (errors) => {
            
            this._initialized = true;
            
            if(finishedCallback !== null){

                finishedCallback(errors);
            }
            
        }, progressCallback);
    }
    
    
    /**
     * Adds extra locales to the end of the list of currently active locales and load its related translation data.
     *
     * This method can only be called after the class has been initialized in case we need to load the translations for more languages.
     * If any of the provided new locales is already loaded, its translation data will be refreshed
     *
     * @param locales List of languages for which we want to load the translations. The list will be appended at the end of any previously
     *        loaded locales and included in the preferred translation order. The translation data will be loaded from all the currently defined locations.
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @return void
     */
    loadLocales(locales: string[],
                finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
                progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before loading more locales');
        }
        
        this._loadData(locales,
                this._definedLocations.map((l:any) => {return {label: l.label, bundles: l.bundles}}),
                finishedCallback,
                progressCallback);   
    }
    
    
    /**
     * Loads on the specified location the translation data for the specified bundles.
     * This method can only be called after the class has been initialized in case we need to refresh or add more bundles to an already loaded location.
     * 
     * @param bundles List of bundles to load from the specified location
     * @param location The label for an already defined location. The extra bundles translation data will be added to the already loaded ones. If not defined,
     *        the current active location will be used.
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback A method that can be used to track the loading progress when lots of bundles and locales are used.
     * 
     * @see this.initialize()
     * 
     * @return void
     */
    loadBundles(bundles: string[],
                location = '',
                finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
                progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!ArrayUtils.isArray(bundles) || bundles.length === 0){

            throw new Error('no bundles specified to load on ' + location + ' location');
        }
        
        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before loading more bundles to a location');
        }

        // If no location specified, use the active one
        if (location === '') {

            location = this._activeLocation;
        }
        
        this._loadData(this._locales, [{label: location, bundles: bundles}], finishedCallback, progressCallback);   
    }
    
    
    /**
     * Auxiliary method used to perform the translation data load
     * 
     * @see this.initialize()
     * 
     * @param locales List of locales to load
     * @param locations A list of locations and respective bundles to be loaded
     * @param finishedCallback A method that will be executed once the load ends. An errors variable will be passed
     *        to this method containing an array with information on errors that may have happened while loading the data.
     * @param progressCallback Executed after each request is performed
     */
    private _loadData(locales: string[],
                      locations: {label: string, bundles: string[]}[],
                      finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
                      progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        if(!ArrayUtils.isArray(locales) || locales.length <= 0){
            
            throw new Error('no locales defined');
        }
        
        // Generate the list of paths to be loaded
        let pathsToLoad: string[] = [];
        let pathsToLoadInfo: any[] = [];
        
        for (let location of locations) {
    
            for (let bundle of location.bundles){
                
                for (let locale of locales) {
                    
                    let locationFound = false;
                    
                    for (let definedLocation of this._definedLocations) {
                        
                        if(definedLocation.label === location.label){
                            
                            locationFound = true;
                            
                            pathsToLoadInfo.push({locale: locale, bundle: bundle, location: location.label});
                            
                            pathsToLoad.push(StringUtils.replace(definedLocation.path, ['$locale', '$bundle'], [locale, bundle]));
                            
                            break;
                        }
                    }
                    
                    if(!locationFound){
                        
                        throw new Error('Undefined location: ' + location.label);
                    }
                }
            }
        }
        
        this._locales = this._locales.concat(locales);
        
        this._locales = ArrayUtils.removeDuplicateElements(this._locales);
        this._languages = this._locales.map(l => l.substr(0, 2));
        
        if(this._activeLocation === ''){
            
            this._activeLocation = locations[locations.length - 1].label;
        }
        
        if(this._filesManager !== null){
            
            // TODO this._loadDataFromFiles(pathsToLoad, pathsToLoadInfo, finishedCallback, progressCallback);
             
        }else{
            
            this._loadDataFromUrls(pathsToLoad, pathsToLoadInfo, finishedCallback, progressCallback);
        }
    }
    
    
    /**
     * Perform the paths load from file system
     *
     * @param pathsToLoad list of paths that need to be loaded
     * @param pathsToLoadInfo original info about the paths to load
     * @param finishedCallback method to execute once finished
     * @param progressCallback method to execute after each path is loaded
     */
//    private _loadDataFromFiles(pathsToLoad: string[],
//                               pathsToLoadInfo: any[],
//                               finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
//                               progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
//        
//        // TODO
//        // Use the filesManager instance to load all the locales from the specified paths
//    }
    
    
    /**
     * Perform the paths load from urls
     *
     * @param pathsToLoad list of paths that need to be loaded
     * @param pathsToLoadInfo original info about the paths to load
     * @param finishedCallback method to execute once finished
     * @param progressCallback method to execute after each path is loaded
     */
    private _loadDataFromUrls(pathsToLoad: string[],
                              pathsToLoadInfo: any[],
                              finishedCallback: ((errors: {path:string, errorMsg:string, errorCode:number}[]) => void) | null = null,
                              progressCallback: ((completedUrl: string, totalUrls: number) => void) | null = null){
        
        // Aux method to execute when data load is done
        let processWhenDone = (errors: {path:string, errorMsg:string, errorCode:number}[] = []) => {
            
            if(pathsToLoadInfo.length > 0){
                
                this._activeBundle = pathsToLoadInfo[pathsToLoadInfo.length - 1].bundle;
            }

            if(finishedCallback !== null){
               
                finishedCallback(errors);
            }            
        }
        
        if(pathsToLoad.length <= 0){
            
            processWhenDone();
            return;
        }
        
        (this._httpManager as HTTPManager).execute(pathsToLoad, (results, _anyError) => {
            
            let errors: {path:string, errorMsg:string, errorCode:number}[] = [];
            
            for (let i = 0; i < results.length; i++) {
                
                if(results[i].isError){
                    
                    errors.push({
                        path: results[i].url,
                        errorMsg: results[i].errorMsg,
                        errorCode: results[i].code 
                    });
                    
                }else{
                    
                    let locale = pathsToLoadInfo[i].locale;
                    let bundle = pathsToLoadInfo[i].bundle;
                    let location = pathsToLoadInfo[i].location;
                    let bundleFormat = StringUtils.getPathExtension(pathsToLoad[i]);

                    if (!this._loadedTranslations.hasOwnProperty(location)) {

                        this._loadedTranslations[location] = {};
                    }
                    
                    if (!this._loadedTranslations[location].hasOwnProperty(locale)) {

                        this._loadedTranslations[location][locale] = {};
                    }
                    
                    this._loadedTranslations[location][locale][bundle] = bundleFormat === 'json' ?
                            this.parseJson(results[i].response) :
                            this.parseProperties(results[i].response);
                }
            }
            
            processWhenDone(errors);
            
        }, (completedUrl, totalRequests) => {
            
            if (progressCallback !== null) {

                progressCallback(completedUrl, totalRequests);
            }
        });
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
    locales(){
    
        return this._locales as ReadonlyArray<string>;
    }
    
    
    /**
     * A list of strings containing the languages that are used by this class to translate the given keys, sorted by preference.
     * Each string is formatted as a 2 digit language code, like: en, fr
     *
     * This list is the same as the locales() one, but containing only the language part of each locale (the first two digits)
     *
     * @see this.locales()
     */
    languages(){

        return this._languages as ReadonlyArray<string>;
    }
    
    
    /**
     * Get the bundle that is currently being used by default when traslating texts
     *
     * @return The name for the currently active bundle
     */
    activeBundle(){

        return this._activeBundle;
    }
    
    
    /**
     * Get the first locale from the list of loaded locales, which is the currently used to search for translated texts.
     * 
     * @return The locale that is defined as the primary one. For example: en_US, es_ES, ..
     */
    primaryLocale(){

        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized');
        }

        return this._locales[0];
    }
    
    
    /**
     * Get the first language from the list of loaded locales, which is the currently used to search for translated texts.
     *
     * @return The 2 digit language code that is defined as the primary one. For example: en, es, ..
     */
    primaryLanguage(){

        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized');
        }

        return this._languages[0];
    }
    
    
    /**
     * Define the bundle that is used by default when no bundle is specified on the get methods
     *
     * @param bundle A currently loaded bundle to be used as the active one
     *
     * @return void
     */
    setActiveBundle(bundle: string){

        for (let location of Object.keys(this._loadedTranslations)) {
            
            for (let locale of Object.keys(this._loadedTranslations[location])) {
            
                if(Object.keys(this._loadedTranslations[location][locale]).indexOf(bundle) >= 0){
                    
                    this._activeBundle = bundle;
                    this._activeLocation = location;
                    return;
                }
            }
        }

        throw new Error(bundle + ' bundle not loaded');
    }


    /**
     * Define the locale that will be placed at the front of the currently loaded locales list (moving all the others one position to the right).
     *
     * This will be the first locale to use when trying to get a translation.
     *
     * @param locale A currently loaded locale that will be moved to the first position of the loaded locales list. If the specified locale
     *        is not currently loaded, an exception will happen.
     *  
     * @return void
     */
    setPrimaryLocale(locale: string){
        
        if(!StringUtils.isString(locale)){
            
            throw new Error('Invalid locale value');
        }
        
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
        this._languages = this._locales.map(l => l.substr(0, 2));
    }
    
    
    /**
     * Moves the specified locales to the beginning of the locales list. This also alters the translation priority by setting the first
     * provided locale as the most prioritary, the second as the next one and so.
     * 
     * This method basically works exactly the same way as setPrimaryLocale but letting us add many locales at once.
     * 
     * @see LocalizationManager.setPrimaryLocale()
     *
     * @param locales A list of locales to be moved to the beginning of the translation priority. First locales item will be the prefered
     *        locale for translation, second will be the next one in case some key is not translated for the first one and so. If any of the
     *        specified locales is not currently loaded, an exception will happen.
     *  
     * @return void
     */
    setPrimaryLocales(locales: string[]){
        
        if(!ArrayUtils.isArray(locales) ||
           ArrayUtils.hasDuplicateElements(locales) ||
           locales.length <= 0){
            
            throw new Error('locales must be non empty string array with no duplicate elements');
        }
        
        for (var i = locales.length - 1; i >= 0; i--) {

            this.setPrimaryLocale(locales[i]);
        }
    }
    
    
    /**
     * Define the 2 digit language that will be placed at the front of the currently loaded locales list (moving all the others one position to the right).
     *
     * This will be the first language to use when trying to get a translation.
     *
     * @param language A 2 digit language code that matches with any of the currently loaded locales, which will
     *        be moved to the first position of the loaded locales list. If the specified language does not match with
     *        a locale that is currently loaded, an exception will happen.
     *
     * @return void
     */
    setPrimaryLanguage(language: string){

        for (let locale of this._locales) {

            if(locale.substr(0, 2) === language){

                return this.setPrimaryLocale(locale);
            }
        }

        throw new Error(language + ' not loaded');
    }
    
    
    /**
     * Moves the locales that match the specified languages to the beginning of the locales list.
     * Works the same as setPrimaryLocales() but with a list of the 2 digit language codes that match the respective locales.
     * 
     * @see LocalizationManager.setPrimaryLocale()
     * @see LocalizationManager.setPrimaryLanguage()
     *
     * @param languages A list of 2 digit language codes to be moved to the beginning of the translation priority. If any of the
     *        specified languages does not match with a locale that is currently loaded, an exception will happen.
     *  
     * @return void
     */
    setPrimaryLanguages(languages: string[]){
        
        if(!ArrayUtils.isArray(languages) ||
            ArrayUtils.hasDuplicateElements(languages) ||
            languages.length <= 0){
            
            throw new Error('languages must be non empty string array with no duplicate elements');
        }

        for (var i = languages.length - 1; i >= 0; i--) {
            
            this.setPrimaryLanguage(languages[i]);
        }
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
        
        if(!ArrayUtils.isArray(locales)){
        
            throw new Error('locales must be an array');
        }
        
        if(locales.length !== this._locales.length){
            
            throw new Error('locales must contain all the currently loaded locales');
        }
        
        for (let locale of locales) {

            if(!this.isLocaleLoaded(locale)){
                
                throw new Error(locale + ' not loaded');                
            }                
        }
        
        this._locales = locales;
        this._languages = this._locales.map(l => l.substr(0, 2));
    }
    
    
    /**
     * Get the translation for the given key, bundle and location
     *
     * @param key The key we want to read from the specified resource bundle and path
     * @param bundle The name for the resource bundle file. If not specified, the value
     *        that was used on the inmediate previous call of this method will be used. This can save us lots of typing
     *        if we are reading multiple consecutive keys from the same bundle.
     * @param location In case we have multiple bundles with the same name on different locations, we can set this parameter with
     *        the location label to uniquely reference the bundle and resolve the conflict. If all of our bundles have different
     *        names, this parameter can be ignored. Just like the bundle parameter, this one is remembered between get() calls.
     * @param toReplace A list of values that will replace the wildcards that are found on the translated text. Each wildcard
     *        will be replaced with the element whose index on the list matches it. Check the documentation for this.wildCardsFormat
     *        property to know more about how to setup wildcards.
     * 
     * @returns The localized text
     */
    get(key: string, bundle = '', location = '', toReplace: string|string[] = []) {

        if(!this._initialized){
            
            throw new Error('LocalizationManager not initialized. Call initialize() before requesting translated texts');
        }
        
        // If no location specified, use the active one
        if (location === '') {

            location = this._activeLocation;
        }

        // If no bundle is specified, the last one will be used
        if (bundle === '') {

            if(this.isBundleMandatory){
                
                throw new Error('bundle is mandatory for key ' + key);
            }
            
            bundle = this._activeBundle;
        }
        
        if (Object.keys(this._loadedTranslations).indexOf(location) === -1) {

            throw new Error('Location <' + location + '> not loaded');
        }
        
        // Loop all the locales to find the first one with a value for the specified key
        for (const locale of this._locales) {

            if (Object.keys(this._loadedTranslations[location]).indexOf(locale) >= 0) {

                if (Object.keys(this._loadedTranslations[location][locale]).indexOf(bundle) === -1) {

                    throw new Error('Bundle <' + bundle + '> not loaded');
                }
                
                if(Object.keys(this._loadedTranslations[location][locale][bundle]).indexOf(key) >= 0){

                    // Store the specified bundle name and location as the lasts that have been used till now
                    this._activeBundle = bundle;
                    this._activeLocation = location;
                    
                    let result = this._loadedTranslations[location][locale][bundle][key];
                    
                    // Replace all wildcards on the text with the specified replacements if any
                    let replacements = StringUtils.isString(toReplace) ? [String(toReplace)] : toReplace;

                    for (let i = 0; i < replacements.length; i++) {

                        result = StringUtils.replace(result,
                                    StringUtils.replace(this.wildCardsFormat, 'N', String(i)),
                                    replacements[i]);
                    }
                    
                    return result;
                }
            }
        }

        if (this.missingKeyFormat.indexOf('$exception') >= 0) {

            throw new Error('key <' + key + '> not found on ' + bundle + ' - ' + location);
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
    getStartCase(key: string, bundle = '', location = '', toReplace: string|string[] = []) {

        return StringUtils.formatCase(this.get(key, bundle, location, toReplace), StringUtils.FORMAT_START_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all upper case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllUpperCase(key: string, bundle = '', location = '', toReplace: string|string[] = []) {

        return StringUtils.formatCase(this.get(key, bundle, location, toReplace), StringUtils.FORMAT_ALL_UPPER_CASE);
    }


    /**
     * Get the translation for the given key and bundle as an all lower case string
     *
     * @see LocalizationManager.get
     * @see StringUtils.formatCase
     *
     * @returns The localized and case formatted text
     */
    getAllLowerCase(key: string, bundle = '', location = '', toReplace: string|string[] = []) {

        return StringUtils.formatCase(this.get(key, bundle, location, toReplace), StringUtils.FORMAT_ALL_LOWER_CASE);
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
    getFirstUpperRestLower(key: string, bundle = '', location = '', toReplace: string|string[] = []){
        
        return StringUtils.formatCase(this.get(key, bundle, location, toReplace), StringUtils.FORMAT_FIRST_UPPER_REST_LOWER);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Json formatted
     * resource bundles
     * 
     * @param jsonString An object with the read resourcebundle json string
     */
    protected parseJson(jsonString: string): {[key: string]: string} {

        return JSON.parse(jsonString);
    }


    /**
     * Auxiliary method that can be overriden when extending this class to customize the parsing of Java properties
     * formatted resource bundles
     * 
     * @param propertiesString A string containing the read resourcebundle java properties format string
     */
    protected parseProperties(propertiesString: string): {[key: string]: string} {

        let result: {[key: string]: string} = {};
        
        let javaPropertiesObject = new JavaPropertiesObject(propertiesString);
        
        for (let key of javaPropertiesObject.getKeys()) {

            result[key] = javaPropertiesObject.get(key);
        }
        
        return result;
    } 
}
