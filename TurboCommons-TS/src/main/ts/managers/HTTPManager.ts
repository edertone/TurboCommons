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
     * @param yesCallback A function that will be executed if the internet connection is available and working
     * @param noCallback A function that will be executed if the internet connection is NOT available
     * 
     * @return void
     */
    isInternetAvailable(yesCallback: () => any, noCallback: () => any){
        
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
            this.urlExists(String(url + '?r=' + StringUtils.generateRandomPassword(15)), () => {
                
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
     * @param yesCallback A method that will be executed if the url exists
     * @param noCallback A method that will be executed if the url does not exist (or is not accessible).
     *
     * @return void
     */
    urlExists(url:string, yesCallback: () => any, noCallback: () => any){
    
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

        this.getUrlHeaders(url, (result) =>{
            
            for (let code of ['404', '405']) {

                if (result[0].indexOf(code) >= 0){

                    noCallback();
                }
            }

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
     * @param successCallback A method that will be executed when headers are read. An array of strings will be passed to this method
     * containing all the read headers with each header line as an array element.
     * @param errorCallback A method that will be executed if headers cannot be read. A string containing the error description
     * will be passed to this method.
     * 
     * @return void
     */
    getUrlHeaders(url:string, successCallback: (e:string[]) => any, errorCallback: (e:string) => any){
    
        if(!StringUtils.isString(url)){

            throw new Error("url must be a string");
        }
        
        if(typeof successCallback  !== 'function' || typeof errorCallback !== 'function'){
            
            throw new Error("params must be functions");
        }

        if(!StringUtils.isUrl(url)){

            throw new Error("invalid url");
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
        
            errorCallback(request.statusText);          
        };
        
        request.ontimeout = () => {
            
            errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT);          
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
     *@see https://en.wikipedia.org/wiki/Query_string
     *@see HashMapObject
     *
     *@returns A valid query string that can be used with any url: http://www.url.com?query_string
     */
    generateUrlQueryString(object:any){
        
        let result = '';
        let keys:string[] = [];
        let values:string[] = [];
        
        if(ObjectUtils.isObject(object)){
        
            if(object.constructor.name === 'HashMapObject'){
                
                keys = (object as HashMapObject).getKeys();
                values = (object as HashMapObject).getValues();
            
            }else if(object.constructor.name === 'Object'){
                
                keys = Object.getOwnPropertyNames(object);

                for(var i = 0; i < keys.length; i++){

                    values.push(object[keys[i]]);
                }
            
            }else{
                
                throw new Error('object must be a HashMapObject or an Object');
            }
            
            for (var i = 0; i < keys.length; i++) {
                
                result += '&' + encodeURIComponent(keys[i]) + '=' + encodeURIComponent(values[i]);
            }

            return result.substring(1, result.length);        
        }

        throw new Error('object must be a HashMapObject or an Object');
    }
        
    
    /**
     * Perform an HTTP get request to the specified location
     * 
     * @param url The url to call
     * @param successCallback A method that will be executed once request is successful. Request result will be passed as a string
     * @param errorCallback A method that will be executed once request fails. The error description will be passed as a string
     * @param params TODO - Implement this feature
     * 
     * @returns void
     */
    get(url:string, successCallback: ((s: string) => any), errorCallback: ((s: string) => any), params:any = null){
        
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
            
                errorCallback(request.statusText);
            }
        };

        request.onerror = () => {
          
            // There was a connection error of some sort
            errorCallback(request.statusText);
        };
        
        request.ontimeout = () => {
            
            errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT);          
        };

        request.send();
    }
    
    
    post(){
        
        // TODO
    }
}
