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
     * @param value A value to check
     *
     * @return true if the given value is numeric or represents a numeric value, false otherwise
     */
    public static isNumeric(value:any) {
        
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
     * @return true if the given value is a numeric integer or represents a a numeric integer value, false otherwise
     */
    public static isInteger(value:any) {
        
        if(!NumericUtils.isNumeric(value)){

            return false;
        }

        return String(value).indexOf('.') < 0;
    }


    /**
     * Strictly check that the provided value is numeric or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forceNumeric(value:any, valueName = '', errorMessage = 'must be numeric'){

        if(!this.isNumeric(value)){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }


    /**
     * Strictly check that the provided value is a positive integer or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forcePositiveInteger(value:any, valueName = '', errorMessage = 'must be a positive integer'){

        if(!this.isInteger(value) || value <= 0){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }
    
    
    /**
     * Get the number represented by the given value
     *
     * @param value A value to convert to a number
     *
     * @return number The numeric type representation from the given value. For example, a string '0001' will return 1
     */
    public static getNumeric(value:any) {
    
        if(NumericUtils.isNumeric(value)){

            return Number(value);
        }

        throw new Error('value is not numeric');
    }
    
    
    /**
     * Generate a random integer between the specified range (both extremes are included).
     *
     * @param min lowest possible value (negative values are allowed)
     * @param max highest possible value (negative values are allowed)
     * 
     * @throws Exception if max is equal or less than min.
     *
     * @return A random integer value between min and max
     */
    public static generateRandomInteger(min:number, max:number) {
        
        if(!NumericUtils.isInteger(max) || !NumericUtils.isInteger(min)){

            throw new Error('max and min must be integers');
        }
        
        if(max <= min){

            throw new Error('max must be higher than min');
        }
        
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }        
}
