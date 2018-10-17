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
import { HTTPManagerGetRequest } from './httpmanager/HTTPManagerGetRequest';
import { HTTPManagerBaseRequest } from './httpmanager/HTTPManagerBaseRequest';

   
/**
 * Class that contains functionalities related to the HTTP protocol and its most common requests
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
    
    
    /**
     * Error message that is used when a timeout happens
     */
    private static ERROR_TIMEOUT = ' ms Timeout reached';
    
    
    /**
     * Structure containing all the created request queues and their status
     */
    private _queues:{name:string,
                     isRunning: boolean,
                     pendingRequests: HTTPManagerBaseRequest[]}[] = [];
    
    
    /**
     * Class that contains functionalities related to the HTTP protocol and its most common requests
     * 
     * @param asynchronous Specify if the HTTP manager instance will work in asynchronous or synchronous mode. 
     * (Synchronous mode is NOT recommended)
     */
    constructor(asynchronous = true){
        
        if(typeof asynchronous !== 'boolean'){
            
            throw new Error("asynchronous is not boolean");
        }
        
        this.asynchronous = asynchronous;
    }
    
    
    /**
     * Create a new http queue. Requests can then be added to this queue with the queue() method.
     * 
     * @param name The name we want to define for this queue
     * 
     * @see this.queue()
     * 
     * @returns void
     */
    createQueue(name: string){
        
        if(StringUtils.isEmpty(name)){
        
            throw new Error('name must be a non empty string');
        }
        
        for (let queue of this._queues) {
	
            if(queue.name === name){
               
                throw new Error(`queue ${name} already exists`);
            }
        }
        
        this._queues.push({name: name, isRunning: false, pendingRequests: []});
    }
    
    
    /**
     * Get the number of created queues. Some may be running and some may be not
     * 
     * @see this.queue()
     * 
     * @returns The number of existing queues
     */
    countQueues(){
        
        return this._queues.length;
    }
    
    
    /**
     * Check if the specified queue is currently executing http requests
     * 
     * @param name The name for the queue we want to check
     * 
     * @see this.queue()
     * 
     * @returns boolean True if the specified queue is actually running its http requests
     */
    isQueueRunning(name: string){
        
        if(StringUtils.isEmpty(name)){
            
            throw new Error('name must be a non empty string');
        }
        
        for (let queue of this._queues) {
            
            if(queue.name === name){
               
                return queue.isRunning;
            }
        }
        
        throw new Error(`queue ${name} does not exist`);
    }
    
    
    /**
     * Remove the specified queue from this manager. 
     * Make sure the queue is not running when calling this method, or an exception will happen
     * 
     * @param name The name for the queue we want to remove
     * 
     * @see this.queue()
     * 
     * @returns void
     */
    deleteQueue(name: string){
        
        if(StringUtils.isEmpty(name)){
            
            throw new Error('name must be a non empty string');
        }

        for (var i = 0; i < this._queues.length; i++) {
	
            if(this._queues[i].name === name){
                
                if(this._queues[i].isRunning){
                    
                    throw new Error(`queue ${name} is currently running`);
                }
                
                this._queues.splice(i, 1);
                
                return;
            }
        }
        
        throw new Error(`queue ${name} does not exist`);
    }
    
    
    /**
     * This method generates a GET url query from a set of key/value pairs
     * 
     * A query string is the part of an url that contains the GET parameters. It is placed after
     * the ? symbol and contains a list of parameters and values that are sent to the url.
     * 
     * @param keyValuePairs An object or a HashMapObject containing key/value pairs that will be used to construct the query string
     * 
     * @see https://en.wikipedia.org/wiki/Query_string
     * @see HashMapObject
     *
     * @returns A valid query string that can be used with any url: http://www.url.com?query_string (Note that ? symbol is not included)
     */
    generateUrlQueryString(keyValuePairs: { [key: string]: string } | HashMapObject){
        
        let result = '';
        let keys:string[] = [];
        let values:string[] = [];
        
        if(ObjectUtils.isObject(keyValuePairs) && ObjectUtils.getKeys(keyValuePairs).length > 0){
        
            if(keyValuePairs instanceof HashMapObject){
                
                keys = (keyValuePairs as HashMapObject).getKeys();
                values = (keyValuePairs as HashMapObject).getValues();
            
            } else {
                
                keys = Object.getOwnPropertyNames(keyValuePairs);

                for(var i = 0; i < keys.length; i++){

                    values.push(keyValuePairs[keys[i]]);
                }
            }
            
            for (var i = 0; i < keys.length; i++) {
                
                result += '&' + encodeURIComponent(keys[i]) + '=' + encodeURIComponent(values[i]);
            }

            return result.substring(1, result.length);        
        }

        throw new Error('keyValuePairs must be a HashMapObject or a non empty Object');
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
            
            throw new Error("params must be functions");
        }
        
        if(this.internetCheckLocations.length <= 0){
            
            throw new Error("no check locations specified");
        }
        
        // A recursive function that will loop all the defined list of urls to check
        // And execute the appropiate result callback
        let recursiveUrlTest = (urls: string[]) => {
        
            if(urls.length <= 0){
                
                return noCallback();
            }
            
            let url = urls.shift();
                
            if(!StringUtils.isUrl(url)){
                
                throw new Error("invalid check url : " + url);
            }
            
            // We must prevent the browser cache from giving false positives, so we generate
            // an url containing a random GET parameter
            this.urlExists(String(url + '?r=' + StringUtils.generateRandom(15, 15)),
                yesCallback,
                () => recursiveUrlTest(urls));
        }
        
        if(navigator.onLine === false){
            
            // Navigator.online is only fiable when it returns false. If it returns true, we still need to
            // test the internet connectivity by performing a real check via recursiveUrlTest
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
        
        let request = new HTTPManagerGetRequest(url);
        
        request.successCallback = () => yesCallback(); 
        request.errorCallback = () => noCallback();
        
        this.execute(request);        
    }
    
    
    /**
     * Get the Http headers for a given url.
     * Note that crossdomain security rules may prevent this method from working correctly
     *
     * @param url The url for which we want to get the http headers.
     * @param successCallback Executed when headers are read. An array of strings will be passed to this method
     *        containing all the read headers with each header line as an array element.
     * @param errorCallback Executed if headers cannot be read. A string containing the error description and the error
     *        code will be passed to this method.
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
        
        let xmlHttprequest = new XMLHttpRequest();
        
        if(this.timeout > 0){
        
            xmlHttprequest.timeout = this.timeout;
        }
        
        xmlHttprequest.open('GET', url, this.asynchronous);
        
        xmlHttprequest.onload = () => successCallback(xmlHttprequest.getAllResponseHeaders().split("\n"));

        xmlHttprequest.onerror = () => errorCallback(xmlHttprequest.statusText, xmlHttprequest.status);
        
        xmlHttprequest.ontimeout = () => errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT, 408);
        
        xmlHttprequest.send();
    }
       
    
    /**
     * Launch one or more http requests without caring about their execution order.
     * 
     * @param requests One or more requests to be inmediately launched (at the same time if possible). Each request can be defined as a string
     *        that will be used as a GET request url, or as an HTTPManagerBaseRequest instance in case we want to define parameters and callbacks.
     * @param finishedCallback A method to be executed once all the http requests have finished (either succesfully or with errors). The callback will
     *        receive two parameters: results (an array with information about each request result in the same order as provided to this method) and
     *        anyError (true if any of the requests has failed)      
     * @param progressCallback Executed after each one of the urls finishes (either successfully or with an error). A string with the requested url and
     *        the total requests to perform will be passed to this method.
     *        
     * @returns void
     */
    execute(requests: string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[],
            finishedCallback: ((results: {url:string, response:string, isError:boolean, errorMsg:string, errorCode:number}[], anyError:boolean) => void) | null = null,
            progressCallback: null | ((completedUrl: string, totalRequests: number) => void) = null){
        
        let requestsList = this._generateValidRequestsList(requests);
        
        // Validate callbacks are ok
        if((finishedCallback !== null && !(finishedCallback instanceof Function)) ||
           (progressCallback !== null && !(progressCallback instanceof Function))){
        
            throw new Error('finishedCallback and progressCallback must be functions');
        }
        
        let finishedCount = 0;
        let finishedAnyError = false;
        let finishedResults:{url:string, response:string, isError:boolean, errorMsg:string, errorCode:number}[] = [];
        
        // A method that will be executed every time a request is finished (even successfully or with errors)
        const processFinishedRequest = (requestWithIndex:{index:number, request:HTTPManagerBaseRequest},
                                        response:string,
                                        isError:boolean = false,
                                        errorMsg:string = '',
                                        errorCode:number = -1) => {
            
            let request = requestWithIndex.request;
            
            finishedCount ++;
            finishedResults[requestWithIndex.index] = {url:request.url, response:response, isError:isError, errorMsg:errorMsg, errorCode:errorCode};
            
            if(isError){
                
                finishedAnyError = true;
                request.errorCallback(errorMsg, errorCode);
            
            }else{
                
                request.successCallback(response);
            }
            
            request.finallyCallback();
            
            if(progressCallback !== null){
                
                progressCallback(request.url, requestsList.length);
            }
            
            if(finishedCount >= requestsList.length && finishedCallback !== null){
                
                finishedCallback(finishedResults, finishedAnyError);
            }
        };
        
        // Execute each one of the received requests and process their results
        for (var i = 0; i < requestsList.length; i++) {
	
            let requestWithIndex = {index: i, request: requestsList[i]};
            
            if(!StringUtils.isString(requestsList[i].url) || StringUtils.isEmpty(requestsList[i].url)){
                
                throw new Error(`url ${i} must be a non empty string`);
            }
            
            let xmlHttprequest = new XMLHttpRequest();
            
            // Define the request timeout if specified on the request or the httpmanager class
            if(requestsList[i].timeout > 0 || this.timeout > 0){
                
                xmlHttprequest.timeout = requestsList[i].timeout > 0 ? requestsList[i].timeout : this.timeout;
            }
            
            // Detect the request type
            let requestType = requestsList[i] instanceof HTTPManagerGetRequest ? 'GET' : 'POST';
            
            // TODO - implement the request GET or POST params
            
            xmlHttprequest.open(requestType, requestsList[i].url, this.asynchronous);
            
            xmlHttprequest.onload = () => {
            
                if (xmlHttprequest.status >= 200 && xmlHttprequest.status < 400) {
                
                    processFinishedRequest(requestWithIndex, xmlHttprequest.responseText);
                
                } else {
                
                    processFinishedRequest(requestWithIndex, '', true, xmlHttprequest.statusText, xmlHttprequest.status);
                }
            };

            xmlHttprequest.onerror = () => {

                processFinishedRequest(requestWithIndex, '', true, xmlHttprequest.statusText, xmlHttprequest.status);
            };
            
            xmlHttprequest.ontimeout = () => {
                
                processFinishedRequest(requestWithIndex, '', true, this.timeout + HTTPManager.ERROR_TIMEOUT, 408);
            };

            xmlHttprequest.send();
        }
    }
    
    
    /**
     * Auxiliary method to generate a valid list of HTTPManagerBaseRequest instances from multiple sources
     */
    private _generateValidRequestsList(requests:string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[]){
        
        // Convert the received requests to a standarized array of HTTPManagerBaseRequest instances
        let requestsList:HTTPManagerBaseRequest[] = [];
        
        if(ArrayUtils.isArray(requests)){
            
            if((requests as Array<any>).length <= 0){
                
                throw new Error('No requests to execute');
            }
            
            requestsList = StringUtils.isString((requests as Array<any>)[0]) ?
                    (requests as string[]).map((url) => new HTTPManagerGetRequest(url)) :
                    (requests as HTTPManagerBaseRequest[]);
                        
        }else{
            
            if(StringUtils.isString(requests) && !StringUtils.isEmpty(requests as string)){
                
                requestsList = [new HTTPManagerGetRequest(requests as string)];
                
            } else if (requests instanceof HTTPManagerBaseRequest){
                
                requestsList = [requests as HTTPManagerBaseRequest]
            
            }else{
                
                throw new Error('Invalid requests value');
            }
        }
        
        return requestsList;
    }
    
    
    /**
     * Sequentially launch one or more http requests to the specified queue, one after the other.
     * Each request will start inmediately after the previous one is finished (either succesfully or with an error).
     * We can have several independent queues that run their requests at the same time. 
     * 
     * @param requests One or more requests that must be added to the specified queue. Each request can be defined as a string
     *        that will be used as a GET request url, or as an HTTPManagerBaseRequest instance in case we want to define parameters and callbacks.
     *        Requests will be sequentially executed one after the other in the same order. If the specified queue contains requests
     *        that have not finished yet, they will be executed before the ones provided here.
     * @param queueName The name for an existing queue (created with this.createQueue()) where the specified requests will be added
     * @param finishedAllCallback A method that will be executed once all the queued requests by this method have finished. Note that
     *        if the specified queue already contains running requests, the current ones will be added to be executed after and
     *        when all have finished, this method will be called.
     * 
     * @returns void
     */
    queue(requests: string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[],
          queueName: string,
          finishedCallback: (() => void) | null = null){
    
        // TODO - this method is almost finished, but some things are pending:
        // 1- FinishedCallback should work the same way as the execute method, and give us the results data and info
        // 2- There should be a progressCallback method
        // 3- Extensive tests must be written: verify requests are sequentially executed one after the other, and all
		//    the rest of the expected behaviour
        
        let requestsList = this._generateValidRequestsList(requests);

        // Validate callbacks are ok
        if((finishedCallback !== null && !(finishedCallback instanceof Function))){
        
            throw new Error('finishedCallback and progressCallback must be functions');
        }
        
        for (let queue of this._queues) {
	
            if(queue.name === queueName){
                
                // Add all the received requests to the beginning of the queue pending array
                for (var i = requestsList.length - 1; i >= 0; i--) {

                    queue.pendingRequests.unshift(requestsList[i]);
                }
                
                // Add a dummy request with a special url, containing the finished callback method
                // to be executed after all the requests are done
                if(finishedCallback !== null){
                    
                    let dummyRequest = new HTTPManagerGetRequest('FINISHED_REQUEST_CALLBACK');
                    
                    dummyRequest.finallyCallback = finishedCallback;
                    
                    queue.pendingRequests.unshift(dummyRequest);
                }
                
                // Run the queue if it is not already processing requests
                if(!this.isQueueRunning(queueName)){
                    
                    this._startQueue(queueName);
                }
                
                return;
            }
        }  
        
        throw new Error(`queue ${queueName} does not exist`);
    }
    
    
    /**
     * Auxiliary method that is used to begin executing the http requests that are pending on the specified queue.
     * A recursive operation will be used to launch the next http request once the previous has totally finished.
     * 
     * @param name The name for the queue we want to start
     * 
     * @returns void
     */
    private _startQueue(name:string){

        // Recursive method that will perform the calls to the queue requests
        let runRequests = (queue: {name: string,
                                   isRunning: boolean,
                                   pendingRequests: HTTPManagerBaseRequest[]}) => {
            
            if(queue.pendingRequests.length <= 0){
                
                queue.isRunning = false;
            
            } else {
                
                // Check if a finished queue callback must be called
                if(queue.pendingRequests[queue.pendingRequests.length - 1].url === 'FINISHED_REQUEST_CALLBACK'){
                    
                    let finallyCallback = queue.pendingRequests.pop() as HTTPManagerBaseRequest;
                    
                    if(queue.pendingRequests.length <= 0){
                    
                        queue.isRunning = false;
                    }
                    
                    finallyCallback.finallyCallback();
                }
                
                if(queue.pendingRequests.length > 0){
                                        
                    queue.isRunning = true;
    
                    this.execute(queue.pendingRequests.pop() as HTTPManagerBaseRequest, () => runRequests(queue));           
                }                    
            }
        };
        
        // Find the requested queue and start the recursive execution on it
        for (let queue of this._queues) {
            
            if(queue.name === name){
                
                runRequests(queue);
                
                return;
            }            
        }
    }
    
    
    /**
     * Given a url that contains a list of resources (files), this method will perform a request for each one of them and
     * store the whole file contents inside an array. After all the process completes, the array containing all the loaded
     * data will be available.
     * 
     * This method implements a technique that allows us to read a big list of files from an http server without needing to
     * write much code. We simply put the files on the server, create a list with all the file names, and call this method.
     * When the process succeeds, we will have all the files data loaded and ready to be used. We have also a progress callback
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
        
        if(!StringUtils.isString(urlToResourcesList) || StringUtils.isEmpty(urlToResourcesList)){
            
            throw new Error('urlToResourcesList must be a non empty string');
        }
        
        if(!StringUtils.isString(basePath) || StringUtils.isEmpty(basePath)){
            
            throw new Error('basePath must be a non empty string');
        }
        
        this.execute(urlToResourcesList, (results, anyError) => {
            
            if(results[0].isError){
                
                return errorCallback(urlToResourcesList, results[0].errorMsg, results[0].errorCode);
            }
            
            let resourcesFullUrls: string[] = [];
            let basePathWithSlash = basePath + ((basePath.charAt(basePath.length - 1) === '/') ? '' : '/');
        
            var resourcesList = StringUtils.getLines(results[0].response);
            
            for (var resource of resourcesList){
                
                resourcesFullUrls.push(StringUtils.formatPath(basePathWithSlash + resource, '/'));  
            }
            
            this.execute(resourcesFullUrls, (results, anyError) => {

                let resultsData: string[] = [];
                
                for (let result of results) {

                    if(result.isError){
                
                        return errorCallback(result.url, result.errorMsg, result.errorCode);
                    }
                    
                    resultsData.push(result.response);
                }
                    
                successCallback(resourcesList, resultsData);
                
            }, progressCallback);
        });
    }
}
