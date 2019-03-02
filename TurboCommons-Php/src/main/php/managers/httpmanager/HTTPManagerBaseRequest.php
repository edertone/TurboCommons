<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers\httpmanager;

use org\turbocommons\src\main\php\model\BaseStrictClass;


/**
 * Class that defines the base http request to be used with http manager
 */
class HTTPManagerBaseRequest extends BaseStrictClass {


    /**
     * The url that will be called as part of this request
     */
    public $url;


    /**
     * Defines how much miliseconds will the http requests wait before failing with a timeout.
     * If set to 0, no value will be specifically defined, so the httpmanager default will be used.
     */
    public $timeout;


    public function __construct(string $url, int $timeout = 0){

        $this->url = $url;
        $this->timeout = $timeout;

        $this->successCallback = function() {};
        $this->errorCallback = function() {};
        $this->finallyCallback = function() {};
    }


    /**
     * A method to be executed inmediately after the request execution finishes successfully.
     * The callback function must have the following signature:
     * (response: string) => void
     */
    public $successCallback = null;


    /**
     * A method to be executed if an error happens to the request execution.
     * The callback function must have the following signature:
     * (errorMsg:string, errorCode:number) => void
     *
     * errorMsg will contain the error text and errorCode will contain the numeric error http value
     */
    public $errorCallback = null;


    /**
     * A method to be executed always when the request finishes, even successfully or with an error.
     * The callback function must have the following signature:
     * () => void
     */
    public $finallyCallback = null;
}