/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { NumericUtils } from './NumericUtils';
import { ArrayUtils } from './ArrayUtils';
import { ValidationManager } from "../managers/ValidationManager";

    
/**
 * The most common string processing and modification utilities
 */  
export class StringUtils {
    
    
    /** Defines the sentence case format (Only the first character of the sentence is capitalised, except for proper nouns and other words which are required by a more specific rule to be capitalised). Generally equivalent to the baseline universal standard of formal English orthography */
    readonly FORMAT_SENTENCE_CASE = 'FORMAT_SENTENCE_CASE';


    /** Defines the start case format (The first character in all words capitalised and all the rest of the word lower case) */
    readonly FORMAT_START_CASE = 'FORMAT_START_CASE';


    /** Defines the all upper case format (All letters on a string written with Capital letters only) */
    readonly FORMAT_ALL_UPPER_CASE = 'FORMAT_ALL_UPPER_CASE';


    /** Defines the all lower case format (All letters on a string written with lower case letters only) */
    readonly FORMAT_ALL_LOWER_CASE = 'FORMAT_ALL_LOWER_CASE';


    /** Defines the CamelCase format (the practice of writing compound words or phrases such that each word or abbreviation begins with a capital letter) */
    readonly FORMAT_CAMEL_CASE = 'FORMAT_CAMEL_CASE';


    /**
     * Defines the UpperCamelCase format variation that writes first letter as upper case
     *
     * @see StringUtils.FORMAT_CAMEL_CASE
     */
    readonly FORMAT_UPPER_CAMEL_CASE = 'FORMAT_UPPER_CAMEL_CASE';


    /**
     * Defines the lowerCamelCase format variation that writes first letter as lower case
     *
     * @see StringUtils.FORMAT_CAMEL_CASE
     */
    readonly FORMAT_LOWER_CAMEL_CASE = 'FORMAT_LOWER_CAMEL_CASE';


    /** Defines the snake_case format (the practice of writing compound words or phrases in which the elements are separated with one underscore character (_) and no spaces) */
    readonly FORMAT_SNAKE_CASE = 'FORMAT_SNAKE_CASE';


    /**
     * Defines the FORMAT_UPPER_SNAKE_CASE format variation that writes all letters as upper case
     *
     * @see StringUtils.FORMAT_SNAKE_CASE
     */
    readonly FORMAT_UPPER_SNAKE_CASE = 'FORMAT_UPPER_SNAKE_CASE';


    /**
     * Defines the lower_snake_case format variation that writes all letters as lower case
     *
     * @see StringUtils.FORMAT_SNAKE_CASE
     */
    readonly FORMAT_LOWER_SNAKE_CASE = 'FORMAT_LOWER_SNAKE_CASE';
    
    
    /**
     * Tells if the given value is a string or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is a string, false otherwise
     */
    public static isString(value:any):boolean {
        
        return (typeof value === 'string' || value instanceof String);
    }
    
    
    /**
     * Tells if the given string is a valid url or not
     *
     * @param value The value to check
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    public static isUrl(value:any) {
        
        var res = false;
    
        if(!StringUtils.isEmpty(value) && StringUtils.isString(value)){
    
            // This amazingly good solution's been found at http://stackoverflow.com/questions/3809401/what-is-a-good-regular-expression-to-match-a-url
            var urlRegex = '^(?!mailto:)(?:(?:http|https|ftp)://)(?:\\S+(?::\\S*)?@)?(?:(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[0-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-z\\u00a1-\\uffff]{2,})))|localhost)(?::\\d{2,5})?(?:(/|\\?|#)[^\\s]*)?$';
    
            res = !(value.length < 2083 && (new RegExp(urlRegex, 'i')).test(value)) ? false : true;
        }
    
        return res;
    }
    
    
    /**
     * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
     * This method checks all these different conditions that can tell us that a string is empty.
     * 
     * @param string String to check
     * @param emptyChars List of strings that will be also considered as empty characters. For example, if we also want to define 'NULL' and '_' as empty string values, we can set this to ['NULL', '_']
     *
     * @returns false if the string is not empty, true if the string contains only spaces, newlines or any other characters defined as "empty" values
     */
    public static isEmpty(string:string, emptyChars:string[] = []) {
    
        // Empty or null value is considered empty
        if(string == null || string == ""){

            return true;
        }

        // Throw exception if non string value was received
        if(!StringUtils.isString(string)){

            throw new Error("value is not a string");
        }

        let aux = '';

        // Replace all empty spaces and new line characters
        if((aux = StringUtils.replace(string, [' ', "\n", "\r", "\t"], '')) == ''){

            return true;
        }

        // Replace all extra empty characters
        for(var i = 0; i < emptyChars.length; i++){

            if((aux = StringUtils.replace(aux, emptyChars[i], '')) == ''){

                return true;
            }
        }

        return false;
    }
    
    
    public static isCamelCase() {
        
        // TODO - translate from php
    }
    
    
    public static isSnakeCase() {
        
        // TODO - translate from php
    }
    
    
    /**
     * Replace all occurrences of the search string with the replacement string
     *  
     * @param string The string or array being searched and replaced on, otherwise known as the haystack.
     * @param search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles. 
     * @param replacement The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles. 
     * @param count [optional] If passed and > 0, this will define the maximum number of replacements to perform
     * 
     * @returns The string with all the replaced values
     */
    public static replace(string: string, search: string|string[], replacement: string|string[], count: number = -1) {
        
        if(!StringUtils.isString(string)){
            
            throw new Error("string is not valid");
        }
        
        if(!StringUtils.isString(search) && !ArrayUtils.isArray(search)){
            
            throw new Error("search is not a string or array");
        }
        
        if(!StringUtils.isString(replacement) && !ArrayUtils.isArray(replacement)){
            
            throw new Error("replacement is not a string or array");
        }
        
        if(!NumericUtils.isInteger(count) || count === 0){
            
            throw new Error("count must be a positive integer");
        }
        
        let result = string;
        let replaceCount = 0;
        let searchArray = StringUtils.isString(search) ? [String(search)] : search;
        let replacementArray = StringUtils.isString(replacement) ? [String(replacement)] : replacement;
        
        if(replacementArray.length > 1 && searchArray.length !== replacementArray.length){
            
            throw new Error("search and replacement arrays must have the same length");
        }
        
        for (let i = 0; i < searchArray.length; i++) {
            
            if(searchArray[i] !== ''){
                
                let r = (replacementArray.length === 1) ? replacementArray[0] : replacementArray[i];
                
                if(r === undefined || r === null){
                    
                    r = '';
                }
                
                let occurences = StringUtils.countStringOccurences(result, searchArray[i]);
                
                for (let j = 0; j < occurences; j++) {
                
                    result = result.replace(searchArray[i], r.replace(/\$/g, "$$$$"));
                    
                    replaceCount ++;
                    
                    if(count > 0 && replaceCount >= count){
                        
                        return result;
                    }
                }
            }
        }
        
        return result;
    }

    
    /**
     * Remove whitespaces (or any custom set of characters) from both sides of a string
     * 
     * @param string A string to process
     * 
     * @param characters A set of characters that will be trimmed from both string sides. By default,
     * empty space and new line characters are defined : " \n\r"
     * 
     * @example: StringUtils.trim("abcXXabc", "abc") outputs "XX"
     * 
     * @returns The trimmed string
     */
    public static trim(string: string, characters = " \n\r") {
        
        return StringUtils.trimLeft(StringUtils.trimRight(string, characters), characters);
    }
    
    
    /**
     * Remove whitespaces (or any custom set of characters) from a string left side
     * 
     * @param string A string to process
     * 
     * @param characters A set of characters that will be trimmed from string left side. By default,
     * empty space and new line characters are defined : " \n\r"
     * 
     * @example: StringUtils.trimLeft("abcXXabc", "abc") outputs "XXabc"
     *  
     * @returns The trimmed string
     */
    public static trimLeft(string: string, characters = " \n\r") {
    
        if(!StringUtils.isString(string)){
            
            throw new Error('value is not a string');
        }

        return string.replace(new RegExp("^[" + characters + "]+"), "");
    }
    
    
    /**
     * Remove whitespaces (or any custom set of characters) from a string right side
     * 
     * @param string A string to process
     * 
     * @param characters A set of characters that will be trimmed from string right side. By default,
     * empty space and new line characters are defined : " \n\r"
     *
     * @example: StringUtils.trimRight("abcXXabc", "abc") outputs "abcXX"
     * 
     * @returns The trimmed string
     */
    public static trimRight(string: string, characters = " \n\r") {
        
        if(!StringUtils.isString(string)){
            
            throw new Error('value is not a string');
        }

        return string.replace(new RegExp("[" + characters + "]+$"), "");
    }
    
    
    /**
     * Count the number of times a string is found inside another string
     *
     * @param string The string where we want to search
     * @param findMe The string that we want to look for
     *
     * @returns The number of times that findMe appears on string
     */
    public static countStringOccurences(string: string, findMe: string) {
        
        if(!StringUtils.isString(string) || !StringUtils.isString(findMe)){
            
            throw new Error('value is not a string');
        }
        
        if(findMe === ''){
            
            throw new Error('cannot count occurences for an empty string');
        }

        return string.split(findMe).length - 1;
    }
    
    
    public static countCapitalLetters() {
        
        // TODO - translate from php
    }
    
    
    /**
     * Count the number of words that exist on the given string
     *
     * @param string The string which words will be counted
     * @param wordSeparator ' ' by default. The character that is considered as the word sepparator
     *
     * @returns The number of words (elements divided by the wordSeparator value) that are present on the string
     */
    public static countWords(string:string, wordSeparator:string = ' ') {
        
        var count:number = 0;
        var lines:string[] = StringUtils.getLines(string);

        for(var i = 0; i < lines.length; i++){

            var words:string[] = lines[i].split(wordSeparator);

            for(var j = 0; j < words.length; j++){

                if(!StringUtils.isEmpty(words[j])){

                    count++;
                }
            }
        }

        return count;
    }
    
    
    /**
     * Method that limits the lenght of a string and optionally appends informative characters like ' ...'
     * to inform that the original string was longer.
     * 
     * @param string String to limit
     * @param limit Max number of characters
     * @param limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
     *
     * @returns The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
     */
    public static limitLen(string:string, limit:number = 100, limiterString:string = ' ...') {
        
        if(limit <= 0 || !NumericUtils.isNumeric(limit)){

            throw new Error('limit must be a positive numeric value');
        }

        if(!StringUtils.isString(string)){

            return '';
        }

        if(string.length <= limit){

            return string;
        }

        if(limiterString.length > limit){

            return limiterString.substring(0, limit);

        }else{

            return string.substring(0, limit - limiterString.length) + limiterString;
        }
    }
            
            
    /**
     * Extracts the domain name from a given url (excluding subdomain). 
     * For example: http://subdomain.google.com/test/ will result in 'google.com'
     * 
     * @param url A string containing an URL
     * 
     * @returns The domain from the given string (excluding the subdomain if exists)
     */
    public static getDomainFromUrl(url:string) {
        
        var hostName:any = StringUtils.getHostNameFromUrl(url);

        hostName = hostName.split('.');

        if(hostName.length > 2){

            hostName.shift();
        }

        return hostName.join('.');
    }
    
    
    /**
     * Extracts the hostname (domain + subdomain) from a given url.
     * For example: http://subdomain.google.com/test/ will result in 'subdomain.google.com'
     * 
     * @param url A string containing an URL
     * 
     * @returns The domain and subdomain from the given string (subdomain.domain.com)
     */
    public static getHostNameFromUrl(url:string) {
        
        if(StringUtils.isEmpty(url) || !StringUtils.isUrl(url)){

            return '';
        }

        // TODO - This should be improved by avoiding the use of an anchor element,
        // cause it will only work on browsers and Explorer / Edge generate wrong results
        var tmp:HTMLAnchorElement = document.createElement('a');

        tmp.href = url;

        // Validate domain contains a valid number of dots
        var dotsCount:number = (tmp.host.match(/\./g) || []).length;

        if(dotsCount <= 0 || dotsCount > 2){

            return '';
        }

        return tmp.host;
    }
    
    
    /**
     * Extracts all the lines from the given string and outputs an array with each line as an element.
     * It does not matter which line separator's been used (\n, \r, Windows, linux...). All source lines will be correctly extracted.
     * 
     * @param string Text containing one or more lines that will be converted to an array with each line on a different element.
     * @param filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
     *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
     *
     * @returns A list with all the string lines sepparated as different array elements.
     */
    public static getLines(string:string, filters:RegExp[] = [/\s+/g]) {
    
        var res:string[] = [];

        // Validate we are receiving a string
        if(!StringUtils.isString(string)){

            return res;
        }

        var tmp:string[] = string.split(/\r?\n|\n|\r/);

        for(var i = 0; i < tmp.length; i++){

            // Apply specified filters
            if(StringUtils.isString(tmp[i])){

                // TODO: this is not exactly the same behaviour as the php version.
                // In the php version, we can define an array of filters and if any of the filters matches the current line,
                // it will not be added to the result. This version only accepts the first element of the filters array, it must be fixed!
                if(tmp[i].replace(filters[0], '') != ''){

                    res.push(tmp[i]);
                }
            }
        }

        return res;
    }
    
            
    public static getKeyWords() {
    
        // TODO: translate from php
    }
    
    
    /**
     * Given a filesystem path which contains some file, this method extracts the filename plus its extension.
     * Example: "//folder/folder2/folder3/file.txt" -> results in "file.txt"
     * 
     * @param path An OS system path containing some file
     *
     * @returns The extracted filename and extension, like: finemane.txt
     */
    public static getFileNameWithExtension(path:string){
    
        var osSeparator:string = '/';

        if(StringUtils.isEmpty(path)){

            return '';
        }

        path = StringUtils.formatPath(path);

        if(path.indexOf(osSeparator) >= 0){

            path = path.substr(path.lastIndexOf(osSeparator) + 1);
        }

        return path;
    }
    
    
    /**
     * Given a filesystem path which contains some file, this method extracts the filename WITHOUT its extension.
     * Example: "//folder/folder2/folder3/file.txt" -> results in "file"
     *
     * @param path An OS system path containing some file
     *
     * @returns The extracted filename WITHOUT extension, like: finemane
     */
    public static getFileNameWithoutExtension(path:string) {
    
        if(StringUtils.isEmpty(path)){

            return '';
        }

        path = this.getFileNameWithExtension(path);

        if(path.indexOf('.') >= 0){

            path = path.substr(0, path.lastIndexOf('.'));
        }

        return path;
    }
    
    
    /**
     * Given a filesystem path which contains some file, this method extracts only the file extension
     * Example: "//folder/folder2/folder3/file.txt" -> results in "txt"
     * 
     * @param path An OS system path containing some file
     *
     * @returns The file extension WITHOUT the dot character. For example: jpg, png, js, exe ...
     */
    public static getFileExtension(path:string) {
    
        if(StringUtils.isEmpty(path)){

            return '';
        }

        // Find the extension by getting the last position of the dot character
        return path.substr(path.lastIndexOf('.') + 1);
    }
    
    
    /**
     * Given an internet URL, this method extracts only the scheme part.
     * Example: "http://google.com" -> results in "http"
     * 
     * @see StringUtils.formatUrl, ValidationManager.isUrl
     * 
     * @param url A valid internet url
     *
     * @returns ('ftp', 'http', ...) if the url is valid or '' if the url is invalid
     */
    public static getSchemeFromUrl(url:string) {
    
        if(url == null || url == undefined){

            return '';
        }

        if(!StringUtils.isString(url)){

            throw new Error("Specified value must be a string");
        }

        if(!StringUtils.isUrl(url)){

            return '';
        }

        var res:string[] = url.split('://');

        return (res.length === 2) ? res[0] : '';
    }
    
    
    public static formatCase() {
    
        // TODO: translate from php
    }
    
    
    /**
     * Given a raw string containing a file system path, this method will process it to obtain a path that
     * is 100% format valid for the current operating system.
     * Directory separators will be converted to the OS valid ones, no directory separator will be present
     * at the end and duplicate separators will be removed.
     * This method basically standarizes the given path so it does not fail for the current OS.
     * 
     * NOTE: This method will not check if the path is a real path on the current file system; it will only fix formatting problems
     * 
     * @param path The path that must be formatted
     *
     * @returns The correctly formatted path without any trailing directory separator
     */
    public static formatPath(path:string):string {
    
        var osSeparator:string = '/';

        if(path == null || path == undefined){

            return '';
        }

        if(!StringUtils.isString(path)){

            throw new Error("Specified path must be a string");
        }

        // Replace all slashes on the path with the os default
        path = path.replace(/\//g, osSeparator);
        path = path.replace(/\\/g, osSeparator);

        // Remove duplicate path separator characters
        while(path.indexOf(osSeparator + osSeparator) >= 0) {

            path = path.replace(osSeparator + osSeparator, osSeparator);
        }

        // Remove the last slash only if it exists, to prevent duplicate directory separator
        if(path.substr(path.length - 1) == osSeparator){

            path = path.substr(0, path.length - 1);
        }

        return path;
    }
    
    
    /**
     * Given a raw string containing an internet URL, this method will process it to obtain a URL that is 100% format valid.
     * 
     * A Uniform Resource Locator (URL), commonly informally termed a web address is a reference to a web resource that specifies 
     * its location on a computer network and a mechanism for retrieving it. URLs occur most commonly to reference web pages (http), 
     * but are also used for file transfer (ftp), email (mailto), database access (JDBC), and many other applications.
     * 
     * Every HTTP URL conforms to the syntax of a generic URI. A generic URI is of the form: scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]
     * 
     * @see https://en.wikipedia.org/wiki/Uniform_Resource_Locator#Syntax
     * 
     * @returns The formated url string or the original string if it was not a valid url
     */
    public static formatUrl(url:string) {
        
        var urlSeparator:string = '/';
        
        if(url == null || url == undefined || url == ''){

            return '';
        }

        if(!StringUtils.isString(url)){

            throw new Error("Specified value must be a string");
        }

        if(StringUtils.isEmpty(url)){

            return url;
        }

        // Trim and replace all slashes on the url with the correct url slash
        url = url.trim();
        url = url.replace(/\//g, urlSeparator);
        url = url.replace(/\\/g, urlSeparator);
        
        // Remove duplicate path separator characters. We replace :// with @@
        // to prevent the first two // from being replaced with a single one
        url = url.replace(/\:\/\//g, '@@@');
        
        while(url.indexOf(urlSeparator + urlSeparator) >= 0) {

            url = url.replace(urlSeparator + urlSeparator, urlSeparator);
        }

        url = url.replace(/@@@/g, '://');
        
        // get the url scheme
        var scheme:string = this.getSchemeFromUrl(url);

        if(scheme === ''){

            if(StringUtils.isUrl('http://' + url)){

                return 'http://' + url;
            }
        }

        return url;
    }
    
    
    public static formatForFullTextSearch() {
    
        // TODO: translate from php
    }
    
    
    /**
     * Generates a random string with the specified lenght and options
     *
     * @param lenght Specify the lengh of the password
     * @param useUpperCase Specify if upper case letters will be also included in the generated string
     * @param useNumbers Specify if numeric digits will be also included in the generated string
     *
     * @return A randomly generated string that can be used as a password
     */
    public static generateRandomPassword(lenght = 5, useUpperCase = true, useNumbers = true) {
    
        if(lenght < 0 || !NumericUtils.isInteger(lenght)){

            throw new Error('length must be a positive number');
        }
        
        // Set the characters to use in the random password
        let chars = 'abcdefghijkmnopqrstuvwxyz023456789';

        if(useUpperCase){

            chars = 'ABCDEFGHIJKMNOPQRSTUVWXYZ' + chars;
        }
        
        if(useNumbers){

            chars = '0123456789' + chars;
        }

        // Get the lenght for the chars string to use in random generation process
        let charsLen = chars.length - 1;

        let result = '' ;

        // loop throught all the password defined lenght
        for(let i=0; i<lenght; i++){
        
            // get an integer between 0 and charslen.
            let num = Math.floor(Math.random() * charsLen);
        
            // append the random character to the password.
            result = result + chars.substr(num, 1);
        }

        return result;
    }
    
    
    public static removeNewLineCharacters() {
        
        // TODO: translate from php
    }
    
    
    /**
     * Converts all accent characters to ASCII characters on a given string.<br>
     * This method is based on a stack overflow implementation called removeDiacritics
     *
     * @see http://stackoverflow.com/questions/990904/remove-accents-diacritics-in-a-string-in-javascript
     * 
     * @param string Text from which accents must be cleaned
     *
     * @returns The given string with all accent and diacritics replaced by the respective ASCII characters.
     */
    public static removeAccents(string:string) {
    
        if(string == null){

            return '';
        }

        var defaultDiacriticsRemovalMap:any[] = [{
            'b' : 'A',
            'l' : '\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'
        }, {
            'b' : 'AA',
            'l' : '\uA732'
        }, {
            'b' : 'AE',
            'l' : '\u00C6\u01FC\u01E2'
        }, {
            'b' : 'AO',
            'l' : '\uA734'
        }, {
            'b' : 'AU',
            'l' : '\uA736'
        }, {
            'b' : 'AV',
            'l' : '\uA738\uA73A'
        }, {
            'b' : 'AY',
            'l' : '\uA73C'
        }, {
            'b' : 'B',
            'l' : '\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'
        }, {
            'b' : 'C',
            'l' : '\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'
        }, {
            'b' : 'D',
            'l' : '\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779'
        }, {
            'b' : 'DZ',
            'l' : '\u01F1\u01C4'
        }, {
            'b' : 'Dz',
            'l' : '\u01F2\u01C5'
        }, {
            'b' : 'E',
            'l' : '\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'
        }, {
            'b' : 'F',
            'l' : '\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'
        }, {
            'b' : 'G',
            'l' : '\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'
        }, {
            'b' : 'H',
            'l' : '\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'
        }, {
            'b' : 'I',
            'l' : '\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'
        }, {
            'b' : 'J',
            'l' : '\u004A\u24BF\uFF2A\u0134\u0248'
        }, {
            'b' : 'K',
            'l' : '\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'
        }, {
            'b' : 'L',
            'l' : '\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'
        }, {
            'b' : 'LJ',
            'l' : '\u01C7'
        }, {
            'b' : 'Lj',
            'l' : '\u01C8'
        }, {
            'b' : 'M',
            'l' : '\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'
        }, {
            'b' : 'N',
            'l' : '\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'
        }, {
            'b' : 'NJ',
            'l' : '\u01CA'
        }, {
            'b' : 'Nj',
            'l' : '\u01CB'
        }, {
            'b' : 'O',
            'l' : '\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'
        }, {
            'b' : 'OI',
            'l' : '\u01A2'
        }, {
            'b' : 'OO',
            'l' : '\uA74E'
        }, {
            'b' : 'OU',
            'l' : '\u0222'
        }, {
            'b' : 'OE',
            'l' : '\u008C\u0152'
        }, {
            'b' : 'oe',
            'l' : '\u009C\u0153'
        }, {
            'b' : 'P',
            'l' : '\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'
        }, {
            'b' : 'Q',
            'l' : '\u0051\u24C6\uFF31\uA756\uA758\u024A'
        }, {
            'b' : 'R',
            'l' : '\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'
        }, {
            'b' : 'S',
            'l' : '\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'
        }, {
            'b' : 'T',
            'l' : '\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'
        }, {
            'b' : 'TZ',
            'l' : '\uA728'
        }, {
            'b' : 'U',
            'l' : '\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'
        }, {
            'b' : 'V',
            'l' : '\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'
        }, {
            'b' : 'VY',
            'l' : '\uA760'
        }, {
            'b' : 'W',
            'l' : '\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'
        }, {
            'b' : 'X',
            'l' : '\u0058\u24CD\uFF38\u1E8A\u1E8C'
        }, {
            'b' : 'Y',
            'l' : '\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'
        }, {
            'b' : 'Z',
            'l' : '\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'
        }, {
            'b' : 'a',
            'l' : '\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'
        }, {
            'b' : 'aa',
            'l' : '\uA733'
        }, {
            'b' : 'ae',
            'l' : '\u00E6\u01FD\u01E3'
        }, {
            'b' : 'ao',
            'l' : '\uA735'
        }, {
            'b' : 'au',
            'l' : '\uA737'
        }, {
            'b' : 'av',
            'l' : '\uA739\uA73B'
        }, {
            'b' : 'ay',
            'l' : '\uA73D'
        }, {
            'b' : 'b',
            'l' : '\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'
        }, {
            'b' : 'c',
            'l' : '\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'
        }, {
            'b' : 'd',
            'l' : '\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'
        }, {
            'b' : 'dz',
            'l' : '\u01F3\u01C6'
        }, {
            'b' : 'e',
            'l' : '\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'
        }, {
            'b' : 'f',
            'l' : '\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'
        }, {
            'b' : 'g',
            'l' : '\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'
        }, {
            'b' : 'h',
            'l' : '\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'
        }, {
            'b' : 'hv',
            'l' : '\u0195'
        }, {
            'b' : 'i',
            'l' : '\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'
        }, {
            'b' : 'j',
            'l' : '\u006A\u24D9\uFF4A\u0135\u01F0\u0249'
        }, {
            'b' : 'k',
            'l' : '\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'
        }, {
            'b' : 'l',
            'l' : '\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'
        }, {
            'b' : 'lj',
            'l' : '\u01C9'
        }, {
            'b' : 'm',
            'l' : '\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'
        }, {
            'b' : 'n',
            'l' : '\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'
        }, {
            'b' : 'nj',
            'l' : '\u01CC'
        }, {
            'b' : 'o',
            'l' : '\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'
        }, {
            'b' : 'oi',
            'l' : '\u01A3'
        }, {
            'b' : 'ou',
            'l' : '\u0223'
        }, {
            'b' : 'oo',
            'l' : '\uA74F'
        }, {
            'b' : 'p',
            'l' : '\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'
        }, {
            'b' : 'q',
            'l' : '\u0071\u24E0\uFF51\u024B\uA757\uA759'
        }, {
            'b' : 'r',
            'l' : '\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'
        }, {
            'b' : 's',
            'l' : '\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'
        }, {
            'b' : 't',
            'l' : '\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'
        }, {
            'b' : 'tz',
            'l' : '\uA729'
        }, {
            'b' : 'u',
            'l' : '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'
        }, {
            'b' : 'v',
            'l' : '\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'
        }, {
            'b' : 'vy',
            'l' : '\uA761'
        }, {
            'b' : 'w',
            'l' : '\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'
        }, {
            'b' : 'x',
            'l' : '\u0078\u24E7\uFF58\u1E8B\u1E8D'
        }, {
            'b' : 'y',
            'l' : '\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'
        }, {
            'b' : 'z',
            'l' : '\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'
        }];

        var diacriticsMap:any = {};

        for(var i:number = 0; i < defaultDiacriticsRemovalMap.length; i++){

            var letters:string = defaultDiacriticsRemovalMap[i].l;

            for(var j:number = 0; j < letters.length; j++){

                diacriticsMap[letters[j]] = defaultDiacriticsRemovalMap[i].b;
            }
        }

        return string.replace(/[^\u0000-\u007E]/g, function(a) {

            return diacriticsMap[a] || a;
        });
    }
    
    
    public static removeWordsShorterThan() {
        
        // TODO: translate from php
    }
    
    
    public static removeWordsLongerThan() {
        
        // TODO: translate from php
    }
    
    
    public static removeUrls() {
        
        // TODO: translate from php
    }
    
    
    public static removeHtmlCode() {
        
        // TODO: translate from php
    }
    
    
    public static removeMultipleSpaces() {
        
        // TODO: translate from php
    }
}