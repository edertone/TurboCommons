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
import { HashMapObject } from '../model/HashMapObject';

   
/**
 * Class that contains functionalities related to the HTTP protocol and its most common operations
 */ 
export class HTTPManager{
    
    
    /** 
     * Defines if the http comunications made by this class will be synchronous (code execution will be stopped while 
     * waiting for the response) or asynchronous (execution flow will continue and response will be processed once received)
     * Note: Synchronous requests are normally NOT, NOT a good idea 
     */
    asynchronous = true;
    
    
    /** 
     * Defines how much miliseconds will the http requests wait before failing with a timeout.
     * If set to 0, no value will be specifically defined, so the platform default will be used.
     */
    timeout = 0;
    

    /** 
     * Defines a list with internet urls that will be used to test network availability by the 
     * isInternetAvailable() method. We mainly use globally available CDN urls, cause these are 
     * not blocked by cross-orining policies on the browsers and are widely available and replicated.
     * It may be interesting to add your own server side url at the bengining of this list, so it will 
     * be the first one to be tested, and you will also check that your server is correctly responding.
     * Note that when an url request is successful, process ends and internet connection is considered
     * to be working.
     */
    internetCheckLocations = ['https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
                              'https://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js',
                              'https://code.jquery.com/jquery-3.2.1.slim.min.js'];
    
    
    /** Error message that is used when a timeout happens */
    private static ERROR_TIMEOUT =  ' ms Timeout reached';
    
    
    /**
     * Class that contains functionalities related to the HTTP protocol and its most common operations
     * 
     * @param asynchronous Specify if the HTTP manager instance will work in asynchronous or synchronous mode. 
     * (Synchronous mode is NOT recommended)
     */
    constructor(asynchronous = true){
        
        if(typeof asynchronous !== 'boolean'){
            
            throw new Error("HTTPManager.constructor: value is not a boolean");
        }
        
        this.asynchronous = asynchronous;
    }
        
    
    /**
     * Tells if there's currently a working internet connection available or not.
     *  
     * @param yesCallback Executed if the internet connection is available and working
     * @param noCallback Executed if the internet connection is NOT available
     * 
     * @return void
     */
    isInternetAvailable(yesCallback: () => void, noCallback: () => void){
        
        if(typeof yesCallback  !== 'function' || typeof noCallback !== 'function'){
            
            throw new Error("HTTPManager.isInternetAvailable: params must be functions");
        }
        
        if(this.internetCheckLocations.length <= 0){
            
            throw new Error("HTTPManager.isInternetAvailable: no check locations specified");
        }
        
        // A recursive function that will loop all the defined list of urls to check
        // And execute the appropiate result callback
        let recursiveUrlTest = (urls: string[]) => {
        
            if(urls.length <= 0){
                
                return noCallback();
            }
            
            let url = urls.shift();
                
            if(!StringUtils.isUrl(url)){
                
                throw new Error("HTTPManager.isInternetAvailable: invalid check url : " + url);
            }
            
            // We must prevent the browser cache from giving false positives, so we generate
            // an url containing a random GET parameter
            this.urlExists(String(url + '?r=' + StringUtils.generateRandom(15, 15)), () => {
                
                return yesCallback();
                
            }, () => {
                
                recursiveUrlTest(urls);
            });
        }
        
        if(navigator.onLine === false){
            
            noCallback();
        
        }else{
        
            // Note that we use slice to create a clone of the array to prevent it from being modified
            // by the recursive method
            recursiveUrlTest(this.internetCheckLocations.slice(0));
        }       
    }
    
    
    /**
     * Test if the specified url exists by trying to connect to it.
     * Note that crossdomain security rules may prevent this method from working correctly if you try
     * to check the existence of an url that does not allow CORS outside your application domain.
     * 
     * @param url An full valid internet address to check
     * @param yesCallback Executed if the url exists
     * @param noCallback Executed if the url does not exist (or is not accessible).
     *
     * @return void
     */
    urlExists(url:string, yesCallback: () => void, noCallback: () => void){
    
        if(!StringUtils.isString(url)){

            throw new Error("url must be a string");
        }
        
        if(typeof yesCallback  !== 'function' || typeof noCallback !== 'function'){
            
            throw new Error("params must be functions");
        }

        if(!StringUtils.isUrl(url)){

            noCallback();
            
            return;
        }

        this.get(url, (result) =>{
            
            yesCallback();
            
        }, (error) => {
            
            noCallback();
        });        
    }
    
    
    /**
     * Get the Http headers for a given url.
     * Note that crossdomain security rules may prevent this method from working correctly
     *
     * @param url The url for which we want to get the http headers.
     * @param successCallback Executed when headers are read. An array of strings will be passed to this method
     * containing all the read headers with each header line as an array element.
     * @param errorCallback Executed if headers cannot be read. A string containing the error description and the error
     *                      code will be passed to this method.
     * 
     * @return void
     */
    getUrlHeaders(url:string,
                  successCallback: (headersArray:string[]) => void,
                  errorCallback: (errorMsg:string, errorCode:number) => void){
    
        if(!StringUtils.isString(url)){

            throw new Error("url must be a string");
        }
        
        if(typeof successCallback  !== 'function' || typeof errorCallback !== 'function'){
            
            throw new Error("params must be functions");
        }

        if(!StringUtils.isUrl(url)){

            throw new Error("invalid url " + url);
        }
        
        let request = new XMLHttpRequest();
        
        if(this.timeout > 0){
        
            request.timeout = this.timeout;
        }
        
        request.open('GET', url, this.asynchronous);
        
        request.onload = () => {
            
            successCallback(request.getAllResponseHeaders().split("\n"));
        };

        request.onerror = () => {
        
            errorCallback(request.statusText, request.status);          
        };
        
        request.ontimeout = () => {
            
            errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT, 408);          
        };
        
        request.send();
    }
    
    
    /**
     * This method generates a GET url query from a set of key/value pairs
     * 
     * A query string is the part of an url that contains the GET parameters. It is placed after
     * the ? symbol and contains a list of parameters and values that are sent to the url.
     * 
     * @param object An object or a HashMapObject containing key/value pairs that will be used to construct the query string
     * 
     * @see https://en.wikipedia.org/wiki/Query_string
     * @see HashMapObject
     *
     * @returns A valid query string that can be used with any url: http://www.url.com?query_string (Note that ? symbol is not included)
     */
    generateUrlQueryString(object: { [key: string]: string } | HashMapObject){
        
        let result = '';
        let keys:string[] = [];
        let values:string[] = [];
        
        if(ObjectUtils.isObject(object) && ObjectUtils.getKeys(object).length > 0){
        
            if(object instanceof HashMapObject){
                
                keys = (object as HashMapObject).getKeys();
                values = (object as HashMapObject).getValues();
            
            } else {
                
                keys = Object.getOwnPropertyNames(object);

                for(var i = 0; i < keys.length; i++){

                    values.push(object[keys[i]]);
                }
            }
            
            for (var i = 0; i < keys.length; i++) {
                
                result += '&' + encodeURIComponent(keys[i]) + '=' + encodeURIComponent(values[i]);
            }

            return result.substring(1, result.length);        
        }

        throw new Error('object must be a HashMapObject or a non empty Object');
    }
        
    
    /**
     * Perform an HTTP get request to the specified location
     * 
     * @param url The url to call
     * @param successCallback Executed once request is successful. Request result will be passed as a string
     * @param errorCallback Executed if headers cannot be read. A string containing the error description and the error
     *                      code will be passed to this method.
     * @param parameters TODO - Implement this feature
     * 
     * @returns void
     */
    get(url:string,
        successCallback: (response: string) => void,
        errorCallback: (errorMsg:string, errorCode:number) => void,
        parameters: { [s: string]: string } | HashMapObject | null = null){
        
        if(!StringUtils.isString(url) || StringUtils.isEmpty(url)){
            
            throw new Error('url must be a non empty string');
        }
        
        var request = new XMLHttpRequest();
        
        if(this.timeout > 0){
            
            request.timeout = this.timeout;
        }  
        
        // TODO - we must implement the params parameter
        
        request.open('GET', url, this.asynchronous);
        
        request.onload = () => {
        
            if (request.status >= 200 && request.status < 400) {
            
                successCallback(request.responseText);
            
            } else {
            
                errorCallback(request.statusText, request.status);
            }
        };

        request.onerror = () => {
          
            // There was a connection error of some sort
            errorCallback(request.statusText, request.status);
        };
        
        request.ontimeout = () => {
            
            errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT, 408);          
        };

        request.send();
    }
    
    
    // TODO
    post(){
        
        // Implement this method
    }
    
    
    /**
     * Performs a sequential execution of GET http requests and obtains the response data for each one.
     * 
     * After a list of urls is provided, this method will secuentially execute each one of them as a GET
     * request, one after the other and in the same order as they are provided. Once all have 
     * finished correctly, the result data will be available as an array of objects stored with the same
     * order as the provided source urls.
     * 
     * This method can be used to load multiple resource files at once, process batch requests, etc..
     *
     * @param paths List with all the urls that we want to execute as http GET requests
     * @param finishedCallback Executed once all the urls have been called and responses received. An array of
     *        objects containing the results information of each request and a boolean variable telling if any
     *        error happened will be passed to this method 
     * @param parameters TODO - implement this feature after this.get implements it
     * @param progressCallback Executed after each one of the urls is executed. A string with the requested url and
     *        the total requests to perform will be passed to this method.
     * 
     * @returns void
     */
    multiGetRequest(paths: string[],
                    finishedCallback: (results: {path:string, response:string, isError:boolean, errorMsg:string, errorCode:number}[], anyError:boolean) => void,
                    parameters: [{[s: string]: string}] | [HashMapObject] | null = null,
                    progressCallback: null | ((completedUrl: string, totalUrls: number) => void) = null){
    
        if(!ArrayUtils.isArray(paths) || paths.length <= 0){
            
            throw new Error('paths must be a non empty array');
        }

        // Recursive method that will perform the calls to the specified requests
        let perform = (paths: string[],
                       results: {path:string, response:string, isError:boolean, errorMsg:string, errorCode:number}[],
                       anyError: boolean,
                       totalCount: number) => {
            
            if(paths.length > 0){
                
                let url = String(paths.shift());
                
                this.get(url, (response: string) => {
                    
                    results.push({
                        path: url,
                        response: response,
                        isError: false,
                        errorMsg: '',
                        errorCode: 0
                    });
                    
                    if(progressCallback !== null){
                    
                        progressCallback(url, totalCount);
                    }
                    
                    perform(paths, results, anyError ? true : false, totalCount);
                    
                }, (errorMsg:string, errorCode:number) => {
                    
                    results.push({
                        path: url,
                        response: '',
                        isError: true,
                        errorMsg: errorMsg,
                        errorCode: errorCode
                    });

                    perform(paths, results, true, totalCount);                    
                });
            
            }else{
            
                finishedCallback(results, anyError);
            }
        };
        
        perform(ObjectUtils.clone(paths), [], false, paths.length);
    }
    
    
    // TODO
    multiPostRequest(){
                
        // Implement this method
    }
    
    
    /**
     * Given a url that contains a list of resources (files), this method will perform a request for each one of them and
     * store the whole file contents inside an array. After all the process completes, the array containing all the loaded
     * data will be available.
     * 
     * This method implements a technique that allows us to read a big list of files from an http server without needing to
     * write much code. We simply put the files on the server, create a list with all the file names, and call this method.
     * When the process succeeds, we will have all the files loaded and ready to be used. We have also an progress callback
     * that will notify us when each one of the files is correctly loaded.
     * 
     * @param urlToResourcesList A url that contains the list of resources that will be loaded. It normally contains a list of file names
     * @param basePath A url that will be used as the root for all the files of the list when the load is performed. This usually is the path
     *                 to the folder that contains the files
     * @param successCallback Executed once all the resources have been loaded. Two parameters will be passed to this method: An array with
     *                        The list of resources as they are defined on the urlToResourcesList, and an array containing all the data for each
     *                        one of the loaded resources. 
     * @param errorCallback Executed if a failure happens on any of the requests. The url that caused the error,
     *                      the error description and the error code will be passed to this method.
     * @param progressCallback Executed after each one of the resources is correctly loaded. A string with the correctly
     *                         requested url will be passed to this method.
     * 
     * @returns void
     */
    loadResourcesFromList(urlToResourcesList: string,
                          basePath: string,
                          successCallback: (resourcesList: string[], resourcesData: string[]) => void,
                          errorCallback: (errorUrl:string, errorMsg:string, errorCode:number) => void,
                          progressCallback: ((completedUrl: string) => void) | null = null){
        
        if(!StringUtils.isString(basePath) || StringUtils.isEmpty(basePath)){
            
            throw new Error('basePath must be a non empty string');
        }

        this.get(urlToResourcesList, (response) => {
        
            let resourcesFullUrls: string[] = [];
            let basePathWithSlash = basePath + ((basePath.charAt(basePath.length - 1) === '/') ? '' : '/');
        
            var resourcesList = StringUtils.getLines(response);
            
            for (var resource of resourcesList){
                
                resourcesFullUrls.push(StringUtils.formatPath(basePathWithSlash + resource, '/'));  
            }
            
            this.multiGetRequest(resourcesFullUrls,
                    (results: {path:string, response:string, isError:boolean, errorMsg:string, errorCode:number}[], anyError:boolean) => {

                let resultsData: string[] = [];
                
                for (let result of results) {

                    if(result.isError){
                
                        return errorCallback(result.path, result.errorMsg, result.errorCode);
                    }
                    
                    resultsData.push(result.response);
                }
                    
                successCallback(resourcesList, resultsData);
                
            }, null, progressCallback);
            
        }, (errorMsg, errorCode) => {
            
            errorCallback(urlToResourcesList, errorMsg, errorCode);
        });
    }
}
