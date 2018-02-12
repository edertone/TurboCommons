/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
 
import { StringUtils } from './StringUtils';

    
/**
 * Common operations and tools related with numbers
 */ 
export class NumericUtils {
    
    
    /**
     * Tells if the given value is numeric or not
     *
     * @param {any} value A value to check
     *
     * @returns {boolean} true if the given value is numeric or represents a numeric value, false otherwise
     */
    public static isNumeric(value:any):boolean {
        
        if(StringUtils.isString(value)){
            
            value = String(value).trim();
        }
        
        return !isNaN(parseFloat(value)) && isFinite(value);
    }
    
    
    /**
     * Tells if the given value is a numeric integer or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is a numeric integer or represents a a numeric integer value, false otherwise
     */
    public static isInteger(value:any):boolean {
        
        if(!NumericUtils.isNumeric(value)){

            return false;
        }

        return String(value).indexOf('.') < 0;
    }
    
    
    /**
     * Get the number represented by the given value
     *
     * @param any $value A value to convert to a number
     *
     * @returns number The numeric type representation from the given value. For example, a string '0001' will return 1
     */
    public static getNumeric(value:any):number {
    
        if(NumericUtils.isNumeric(value)){

            return Number(value);
        }

        throw new Error('NumericUtils.getNumeric : Provided value is not numeric');
    }
    
    
    /**
     * Generate a random integer
     *
     * @param max highest value to be returned
     * @param min lowest value to be returned (default: 0)
     *
     * @return A random integer value between min (or 0) and max
     * @throws Exception if max is equal or less than min.
     */
    public static generateRandomInteger(max:number, min:number = 0):number {
        
        if(!NumericUtils.isInteger(max) || max < 0 || !NumericUtils.isInteger(min) || min < 0){

            throw new Error('NumericUtils.generateRandomInteger : Provided max and min must be positive integers');
        }
        
        if(max <= min){

            throw new Error('NumericUtils.generateRandomInteger : Provided max must be higher than min');
        }
        
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }        
}
