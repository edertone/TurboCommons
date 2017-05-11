/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
namespace org_turbocommons_utils {
 
    
    /**
     * The most common string processing and modification utilities.
     * 
     * <pre><code> 
     * This is a static class, so no instance needs to be created.
     * Usage example:
     * 
     * var ns = org_turbocommons_utils;
     * 
     * var result1 = ns.StringUtils.isEmpty('   ');
     * var result2 = ns.StringUtils.countWords('hello');
     * ...
     * </code></pre>
     * 
     * @class
     */   
    export class StringUtils {
        
        
        /**
         * Tells if the given value is a string or not
         *
         * @param {any} value A value to check
         *
         * @returns {boolean} true if the given value is a string, false otherwise
         */
        public static isString(value:any):boolean {
            
            return (typeof value === 'string' || value instanceof String);
        }
        
        
        /**
         * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
         * This method checks all these different conditions that can tell us that a string is empty.
         * 
         * @static
         * 
         * @param {string} string String to check
         * @param {array} emptyChars List of strings that will be also considered as empty characters. For example, if we also want to define 'NULL' and '_' as empty string values, we can set this to ['NULL', '_']
         *
         * @returns {boolean} false if the string is not empty, true if the string contains only spaces, newlines or any other characters defined as "empty" values
         */
        public static isEmpty(string:string, emptyChars:string[] = []) {
        
            var aux = '';
    
            // Empty or null value is considered empty
            if(string == null || string == ""){
    
                return true;
            }
    
            // Throw exception if non string value was received
            if(!StringUtils.isString(string)){
    
                throw new Error("StringUtils.isEmpty: value is not a string");
            }
    
            // Replace all empty spaces
            if((aux = string.replace(/ /g, '')) == ''){
    
                return true;
            }
    
            // Replace all new line characters
            if((aux = aux.replace(/\n/g, '')) == ''){
    
                return true;
            }
    
            if((aux = aux.replace(/\r/g, '')) == ''){
    
                return true;
            }
    
            if((aux = aux.replace(/\t/g, '')) == ''){
    
                return true;
            }
    
            // Replace all extra empty characters
            for(var i = 0; i < emptyChars.length; i++){
    
                if((aux = aux.replace(new RegExp(emptyChars[i], 'g'), '')) == ''){
    
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
        
        
        public static countStringOccurences() {
            
            // TODO - translate from php
        }
        
        
        public static countCapitalLetters() {
            
            // TODO - translate from php
        }
        
        
        /**
         * Count the number of words that exist on the given string
         *
         * @static
         * 
         * @param string The string which words will be counted
         * @param wordSeparator ' ' by default. The character that is considered as the word sepparator
         *
         * @returns int The number of words (elements divided by the wordSeparator value) that are present on the string
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
         * @static
         * 
         * @param string String to limit
         * @param limit Max number of characters
         * @param limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
         *
         * @returns string The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
         */
        public static limitLen(string:string, limit:number = 100, limiterString:string = ' ...') {
            
            if(limit <= 0 || !(!isNaN(Number(limit)) && isFinite(limit))){
    
                throw new Error("StringUtils.limitLen: limit must be a positive numeric value");
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
         * @static
         * 
         * @param {string} url A string containing an URL
         * 
         * @returns {string} The domain from the given string (excluding the subdomain if exists)
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
         * @static
         * 
         * @param {string} url A string containing an URL
         * 
         * @returns {string} The domain and subdomain from the given string (subdomain.domain.com)
         */
        public static getHostNameFromUrl(url:string) {
            
            var validationManager = new org_turbocommons_managers.ValidationManager();
    
            if(!validationManager.isFilledIn(url) || !validationManager.isUrl(url)){
    
                return '';
            }
    
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
         * @static
         * 
         * @param {string} string Text containing one or more lines that will be converted to an array with each line on a different element.
         * @param {array} filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
         *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
         *
         * @returns {array} A list with all the string lines sepparated as different array elements.
         */
        public static getLines(string:string, filters:RegExp[] = [/\s+/g]) {
        
            var res:string[] = [];
    
            // Validate we are receiving a string
            if(!StringUtils.isString(string)){
    
                return res;
            }
    
            var tmp:string[] = string.split(/\r?\n/);
    
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
         * @static
         * 
         * @param {string} path An OS system path containing some file
         *
         * @returns {string} The extracted filename and extension, like: finemane.txt
         */
        public static getFileNameWithExtension(path:string){
        
            var osSeparator:string = (new org_turbocommons_managers.FilesManager()).getDirectorySeparator();
    
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
         * @static
         * 
         * @param {string} path An OS system path containing some file
         *
         * @returns {string} The extracted filename WITHOUT extension, like: finemane
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
         * @static
         * 
         * @param {string} path An OS system path containing some file
         *
         * @returns {string} The file extension WITHOUT the dot character. For example: jpg, png, js, exe ...
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
         * @static
         * 
         * @see StringUtils.formatUrl, ValidationManager.isUrl
         * 
         * @param {string} url A valid internet url
         *
         * @returns {string} ('ftp', 'http', ...) if the url is valid or '' if the url is invalid
         */
        public static getSchemeFromUrl(url:string) {
        
            if(url == null || url == undefined){
    
                return '';
            }
    
            var validationManager = new org_turbocommons_managers.ValidationManager();
    
            if(!validationManager.isString(url)){
    
                throw new Error("StringUtils.getSchemeFromUrl: Specified value must be a string");
            }
    
            if(!validationManager.isUrl(url)){
    
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
         * @static
         * 
         * @param path The path that must be formatted
         *
         * @returns string The correctly formatted path without any trailing directory separator
         */
        public static formatPath(path:string):string {
        
           var osSeparator:string = (new org_turbocommons_managers.FilesManager()).getDirectorySeparator();
    
            if(path == null || path == undefined){
    
                return '';
            }
    
            if(!StringUtils.isString(path)){
    
                throw new Error("StringUtils.formatPath: Specified path must be a string");
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
         * @static
         *
         * @returns {string} The formated url string or the original string if it was not a valid url
         */
        public static formatUrl(url:string) {
            
            var validationManager = new org_turbocommons_managers.ValidationManager();
    
            if(url == null || url == undefined || url == ''){
    
                return '';
            }
    
            if(!validationManager.isString(url)){
    
                throw new Error("StringUtils.formatUrl: Specified value must be a string");
            }
    
            if(!validationManager.isFilledIn(url)){
    
                return url;
            }
    
            // Trim and replace all slashes on the url with the correct url slash
            url = url.trim();
            url = url.replace(/\//g, '/');
            url = url.replace(/\\/g, '/');
    
            // get the url scheme
            var scheme:string = this.getSchemeFromUrl(url);
    
            if(scheme === ''){
    
                if(validationManager.isUrl('http://' + url)){
    
                    return 'http://' + url;
                }
            }
    
            return url;
        }
        
        
        public static formatForFullTextSearch() {
        
            // TODO: translate from php
        }
        
        
        public static generateRandomPassword() {
        
            // TODO: translate from php
        }
        
        
        /**
         * Converts all accent characters to ASCII characters on a given string.<br>
         * This method is based on a stack overflow implementation called removeDiacritics
         *
         * @see http://stackoverflow.com/questions/990904/remove-accents-diacritics-in-a-string-in-javascript
         * 
         * @static
         * 
         * @param {string} string Text from which accents must be cleaned
         *
         * @returns {string} The given string with all accent and diacritics replaced by the respective ASCII characters.
         */
        public static removeAccents(string:string) {
        
            if(string == null){
    
                return '';
            }
    
            var defaultDiacriticsRemovalMap:any[] = [{
                'base' : 'A',
                'letters' : '\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'
            }, {
                'base' : 'AA',
                'letters' : '\uA732'
            }, {
                'base' : 'AE',
                'letters' : '\u00C6\u01FC\u01E2'
            }, {
                'base' : 'AO',
                'letters' : '\uA734'
            }, {
                'base' : 'AU',
                'letters' : '\uA736'
            }, {
                'base' : 'AV',
                'letters' : '\uA738\uA73A'
            }, {
                'base' : 'AY',
                'letters' : '\uA73C'
            }, {
                'base' : 'B',
                'letters' : '\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'
            }, {
                'base' : 'C',
                'letters' : '\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'
            }, {
                'base' : 'D',
                'letters' : '\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779'
            }, {
                'base' : 'DZ',
                'letters' : '\u01F1\u01C4'
            }, {
                'base' : 'Dz',
                'letters' : '\u01F2\u01C5'
            }, {
                'base' : 'E',
                'letters' : '\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'
            }, {
                'base' : 'F',
                'letters' : '\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'
            }, {
                'base' : 'G',
                'letters' : '\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'
            }, {
                'base' : 'H',
                'letters' : '\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'
            }, {
                'base' : 'I',
                'letters' : '\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'
            }, {
                'base' : 'J',
                'letters' : '\u004A\u24BF\uFF2A\u0134\u0248'
            }, {
                'base' : 'K',
                'letters' : '\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'
            }, {
                'base' : 'L',
                'letters' : '\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'
            }, {
                'base' : 'LJ',
                'letters' : '\u01C7'
            }, {
                'base' : 'Lj',
                'letters' : '\u01C8'
            }, {
                'base' : 'M',
                'letters' : '\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'
            }, {
                'base' : 'N',
                'letters' : '\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'
            }, {
                'base' : 'NJ',
                'letters' : '\u01CA'
            }, {
                'base' : 'Nj',
                'letters' : '\u01CB'
            }, {
                'base' : 'O',
                'letters' : '\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'
            }, {
                'base' : 'OI',
                'letters' : '\u01A2'
            }, {
                'base' : 'OO',
                'letters' : '\uA74E'
            }, {
                'base' : 'OU',
                'letters' : '\u0222'
            }, {
                'base' : 'OE',
                'letters' : '\u008C\u0152'
            }, {
                'base' : 'oe',
                'letters' : '\u009C\u0153'
            }, {
                'base' : 'P',
                'letters' : '\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'
            }, {
                'base' : 'Q',
                'letters' : '\u0051\u24C6\uFF31\uA756\uA758\u024A'
            }, {
                'base' : 'R',
                'letters' : '\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'
            }, {
                'base' : 'S',
                'letters' : '\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'
            }, {
                'base' : 'T',
                'letters' : '\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'
            }, {
                'base' : 'TZ',
                'letters' : '\uA728'
            }, {
                'base' : 'U',
                'letters' : '\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'
            }, {
                'base' : 'V',
                'letters' : '\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'
            }, {
                'base' : 'VY',
                'letters' : '\uA760'
            }, {
                'base' : 'W',
                'letters' : '\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'
            }, {
                'base' : 'X',
                'letters' : '\u0058\u24CD\uFF38\u1E8A\u1E8C'
            }, {
                'base' : 'Y',
                'letters' : '\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'
            }, {
                'base' : 'Z',
                'letters' : '\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'
            }, {
                'base' : 'a',
                'letters' : '\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'
            }, {
                'base' : 'aa',
                'letters' : '\uA733'
            }, {
                'base' : 'ae',
                'letters' : '\u00E6\u01FD\u01E3'
            }, {
                'base' : 'ao',
                'letters' : '\uA735'
            }, {
                'base' : 'au',
                'letters' : '\uA737'
            }, {
                'base' : 'av',
                'letters' : '\uA739\uA73B'
            }, {
                'base' : 'ay',
                'letters' : '\uA73D'
            }, {
                'base' : 'b',
                'letters' : '\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'
            }, {
                'base' : 'c',
                'letters' : '\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'
            }, {
                'base' : 'd',
                'letters' : '\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'
            }, {
                'base' : 'dz',
                'letters' : '\u01F3\u01C6'
            }, {
                'base' : 'e',
                'letters' : '\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'
            }, {
                'base' : 'f',
                'letters' : '\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'
            }, {
                'base' : 'g',
                'letters' : '\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'
            }, {
                'base' : 'h',
                'letters' : '\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'
            }, {
                'base' : 'hv',
                'letters' : '\u0195'
            }, {
                'base' : 'i',
                'letters' : '\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'
            }, {
                'base' : 'j',
                'letters' : '\u006A\u24D9\uFF4A\u0135\u01F0\u0249'
            }, {
                'base' : 'k',
                'letters' : '\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'
            }, {
                'base' : 'l',
                'letters' : '\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'
            }, {
                'base' : 'lj',
                'letters' : '\u01C9'
            }, {
                'base' : 'm',
                'letters' : '\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'
            }, {
                'base' : 'n',
                'letters' : '\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'
            }, {
                'base' : 'nj',
                'letters' : '\u01CC'
            }, {
                'base' : 'o',
                'letters' : '\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'
            }, {
                'base' : 'oi',
                'letters' : '\u01A3'
            }, {
                'base' : 'ou',
                'letters' : '\u0223'
            }, {
                'base' : 'oo',
                'letters' : '\uA74F'
            }, {
                'base' : 'p',
                'letters' : '\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'
            }, {
                'base' : 'q',
                'letters' : '\u0071\u24E0\uFF51\u024B\uA757\uA759'
            }, {
                'base' : 'r',
                'letters' : '\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'
            }, {
                'base' : 's',
                'letters' : '\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'
            }, {
                'base' : 't',
                'letters' : '\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'
            }, {
                'base' : 'tz',
                'letters' : '\uA729'
            }, {
                'base' : 'u',
                'letters' : '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'
            }, {
                'base' : 'v',
                'letters' : '\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'
            }, {
                'base' : 'vy',
                'letters' : '\uA761'
            }, {
                'base' : 'w',
                'letters' : '\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'
            }, {
                'base' : 'x',
                'letters' : '\u0078\u24E7\uFF58\u1E8B\u1E8D'
            }, {
                'base' : 'y',
                'letters' : '\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'
            }, {
                'base' : 'z',
                'letters' : '\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'
            }];
    
            var diacriticsMap:any = {};
    
            for(var i:number = 0; i < defaultDiacriticsRemovalMap.length; i++){
    
                var letters:string = defaultDiacriticsRemovalMap[i].letters;
    
                for(var j:number = 0; j < letters.length; j++){
    
                    diacriticsMap[letters[j]] = defaultDiacriticsRemovalMap[i].base;
                }
            }
    
            return string.replace(/[^\u0000-\u007E]/g, function(a) {
    
                return diacriticsMap[a] || a;
            });
        }
    }
}
