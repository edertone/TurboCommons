/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { HashMapObject } from '../model/HashMapObject';
import { HTTPManagerBaseRequest } from './HTTPManagerBaseRequest';

 
/**
 * Class that defines a POST http request, to be used by HttpManager
 */ 
export class HTTPManagerPostRequest extends HTTPManagerBaseRequest{


    /**
     * A list of key / value pairs that will be used as parameters for this request 
     */
    parameters:{[s:string]:string} | HashMapObject = {};
}

