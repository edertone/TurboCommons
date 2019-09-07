/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { HTTPManagerBaseRequest } from './HTTPManagerBaseRequest';
import { HashMapObject } from '../../model/HashMapObject';

 
/**
 * Class that defines a POST http request, to be used by HttpManager
 */ 
export class HTTPManagerPostRequest extends HTTPManagerBaseRequest{


    /**
     * A list of key / value pairs that will be sent as POST parameters for the request.
     * 
     * String is the default format for these values, so if any of the parameters specified here is not a string, it will be
     * passed through a JSON encoder to obtaion the string that will be sent to the request.
     */
    parameters:{[s:string]:any} | HashMapObject = {};
}

