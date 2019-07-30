/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/**
 * Class that defines the base http request to be used with http manager
 */ 
export abstract class HTTPManagerBaseRequest{

    
    /**
     * The url that will be called as part of this request
     */
    url:string;


    /**
     * Defines how much miliseconds will the http requests wait before failing with a timeout.
     * If set to 0, no value will be specifically defined, so the httpmanager default will be used.
     */
    timeout:number;

    
    constructor(url: string, timeout = 0) {

        this.url = url;        
        this.timeout = timeout;
    }
    

    /**
     * A method to be executed inmediately after the request execution finishes successfully (200 ok code).
     * The callback function must have the following signature:
     * (response: string) => void
     */
    successCallback: (response: string) => void = () => {};
    
    
    /**
     * A method to be executed if an error happens to the request execution.
     * The callback function must have the following signature:
     * (errorMsg:string, errorCode:number, response: string) => void
     *
     * errorMsg will contain the error text, errorCode will contain the numeric error http value and response will contain
     * the main request response body
     */
    errorCallback: (errorMsg:string, errorCode:number, response: string) => void = () => {};
    
    
    /**
     * A method to be executed always when the request finishes, even successfully or with an error.
     * (This will be the very last method to be executed, allways after success or error callbacks).
     *
     * The callback function must have the following signature:
     * () => void
     */
    finallyCallback: () => void = () => {};  
}
