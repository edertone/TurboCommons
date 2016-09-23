<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use Exception;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\managers\FilesManager;


/**
 * The most common string processing and modification utilities
 */
class StringUtils {


	/**
	 * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
	 * This method checks all these different conditions that can tell us that a string is empty.
	 *
	 * @param string $string String to check
	 * @param array $emptyChars List of strings that will be also considered as empty characters. For example, if we also want to define 'NULL' and '_' as empty string values, we can set this to ['NULL', '_']
	 *
	 * @return boolean false if the string is not empty, true if the string contains only spaces, newlines or any other characters defined as "empty" values
	 */
	public static function isEmpty($string, array $emptyChars = []){

		$aux = '';

		// Note that we are checking emptyness every time we do a replace to improve speed, avoiding unnecessary replacements.
		if($string == null || $string == ''){

			return true;
		}

		// Throw exception if non string value was received
		$validationManager = new ValidationManager();

		if(!$validationManager->isString($string)){

			throw new Exception('StringUtils->isEmpty: value is not a string');
		}

		// Replace all empty spaces.
		if(($aux = str_replace(' ', '', $string)) == ''){

			return true;
		}

		// Replace all new line characters
		if(($aux = str_replace("\n", '', $aux)) == ''){

			return true;
		}

		if(($aux = str_replace("\r", '', $aux)) == ''){

			return true;
		}

		if(($aux = str_replace("\t", '', $aux)) == ''){

			return true;
		}

		// Replace all extra empty characters
		$emptyCharsCount = count($emptyChars);

		for($i = 0; $i < $emptyCharsCount; $i++){

			if(($aux = str_replace($emptyChars[$i], '', $aux)) == ''){

				return true;
			}
		}

		return false;
	}


	/**
	 * Count the number of words that exist on the given string
	 *
	 * @param string $string The string which words will be counted
	 * @param string $wordSeparator ' ' by default. The character that is considered as the word sepparator
	 *
	 * @return int The number of words (elements divided by the wordSeparator value) that are present on the string
	 */
	public static function countWords($string, $wordSeparator = ' '){

		$count = 0;
		$lines = self::extractLines($string);
		$linesCount = count($lines);

		for ($i = 0; $i < $linesCount; $i++) {

			$words = explode($wordSeparator, $lines[$i]);
			$wordsCount = count($words);

			for ($j = 0; $j < $wordsCount; $j++) {

				if(!self::isEmpty($words[$j])){

					$count++;
				}
			}
		}

		return $count;
	}


	/**
	 * Method that limits the lenght of a string and optionally appends informative characters like ' ...'
	 * to inform that the original string was longer.
	 *
	 * @param string $string String to limit
	 * @param int $limit Max number of characters
	 * @param string $limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
	 *
	 * @return string The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
	 */
	public static function limitLen($string, $limit = 100, $limiterString = ' ...'){

		if(!is_numeric($limit)){

			throw new Exception('StringUtils->limitLen: limit must be a numeric value');
		}

		if(!is_string($string)){

			return '';
		}

		if(strlen($string) <= $limit){

			return $string;
		}

		if(strlen($limiterString) > $limit){

			return substr($limiterString, 0, $limit);

		}else{

			return substr($string, 0, $limit - strlen($limiterString)).$limiterString;
		}
	}


	/**
	 * TODO
	 */
	public static function extractDomainFromUrl($string){

		// TODO translate from JS
	}


	/**
	 * TODO
	 */
	public static function extractHostNameFromUrl($string){

		// TODO translate from JS
	}


    /**
     * Extracts all the lines from the given string and outputs an array with each line as an element.
     * It does not matter which line separator's been used (\n, \r, Windows, linux...). All source lines will be correctly extracted.
     *
     * @param string $string Text containing one or more lines that will be converted to an array with each line on a different element.
     * @param array $filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
     *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
     *
     * @return array A list with all the string lines sepparated as different array elements.
     */
    public static function extractLines($string, array $filters = ['/\s+/']){

    	$res = [];

    	// Validate we are receiving a string
    	if(!is_string($string)){

    		return $res;
    	}

    	$tmp = preg_split("/\r?\n/", $string);

    	foreach($tmp as $line){

    		// Apply specified filters
    		if(is_string($line)){

	    		if(preg_replace($filters, '', $line) != ''){

    				array_push($res, $line);
	    		}
    		}
    	}

    	return $res;
    }


    /**
     * Generates an array with a list of common words from the specified text.
     * The list will be sorted so the words that appear more times on the string are placed first.
     *
     * @param string $string Piece of text that contains the keywords we want to extract
     * @param string $max The maxium number of keywords that will appear on the result. If set to null, all unique words on the given text will be returned
     * @param string $longerThan The minimum number of chars for the keywords to find. This is useful to filter some irrelevant words like: the, it, me, ...
     * @param string $shorterThan The maximum number of chars for the keywords to find
     * @param string $ignoreNumericWords Tells the method to skip words that represent numeric values on the result. False by default
     *
     * @return array The list of keywords that have been extracted from the given text
     */
    public static function extractKeyWords($string, $max = 25, $longerThan = 3, $shorterThan = 15, $ignoreNumericWords = false){

    	if($string == null){

    		return [];
    	}

    	// Convert all the - and _ characters to blank spaces
    	$string = str_replace('-', ' ', str_replace('_', ' ', $string));

    	// Process the received string to contain only alphanumeric lowercase values
    	$string = self::formatForFullTextSearch($string);

      	// Remove all the words that are shorter than the specified lenght
      	$string = self::removeWordsShorterThan($string, $longerThan);

      	// Remove all the words longher than specified value
      	$string = self::removeWordsLongerThan($string, $shorterThan);

      	// Count the occurences of each word
    	$words = array_count_values(explode(' ', $string));

    	// Get the max nuber of times a word is repeated.
    	$maxCount = max($words);

    	// Generate the result by adding first the most repeated words.
    	// Note that we sort in a way that the original words order is preserved when the repeat count is the same, so
    	// the text sorting does not get altered when all the words are repeated the same number of times.
    	$res = [];

    	for($i=$maxCount; $i> 0; $i--){

    		foreach($words as $key => $v){
    			if($v == $i){
					if(!is_numeric($key) || (is_numeric($key) && !$ignoreNumericWords)){
						array_push($res, $key);
					}
    			}
    		}
    	}

    	// Get the number of words to return depending on the max parameter
    	if($max == ''){

    		return $res;

    	}else{

    		return array_slice($res, 0, $max);
    	}
    }


	/**
	 * Given a filesystem path which contains some file, this method extracts the filename plus its extension.
	 * Example: "//folder/folder2/folder3/file.txt" -> results in "file.txt"
	 *
	 * @param string $path An OS system path containing some file
	 *
	 * @return string The extracted filename and extension, like: finemane.txt
	 */
	public static function extractFileNameWithExtension($path){

		$osSeparator = FilesManager::getInstance()->getDirectorySeparator();

		if(self::isEmpty($path)){

			return '';
		}

		$path = self::formatPath($path);

		if(strpos($path, $osSeparator) !== false){

			$path = substr(strrchr($path, $osSeparator), 1);
		}

		return $path;
    }


    /**
     * Given a filesystem path which contains some file, this method extracts the filename WITHOUT its extension.
     * Example: "//folder/folder2/folder3/file.txt" -> results in "file"
     *
     * @param string $path An OS system path containing some file
     *
     * @return string The extracted filename WITHOUT extension, like: finemane
     */
    public static function extractFileNameWithoutExtension($path){

    	if(self::isEmpty($path)){

    		return '';
    	}

    	$path = self::extractFileNameWithExtension($path);

		if(strpos($path, '.') !== false){

			$path = substr($path, 0, strrpos($path, '.'));
		}

		return $path;
    }


	/**
	 * Given a filesystem path which contains some file, this method extracts only the file extension
	 * Example: "//folder/folder2/folder3/file.txt" -> results in "txt"
	 *
	 * @param string $path An OS system path containing some file
	 *
	 * @return string The file extension WITHOUT the dot character. For example: jpg, png, js, exe ...
	 */
	public static function extractFileExtension($path){

		if(self::isEmpty($path)){

			return '';
		}

		// Find the extension by getting the last position of the dot character
		return substr($path, strrpos($path, '.') + 1);
    }


    /**
     * TODO - translate from js
     */
    public static function extractSchemeFromUrl(){

    	// TODO - translate from js
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
     * @param string $path The path that must be formatted
     *
     * @return string The correctly formatted path without any trailing directory separator
     */
    public static function formatPath($path){

    	$osSeparator = FilesManager::getInstance()->getDirectorySeparator();

    	if($path == null){

    		return '';
    	}

    	if(!is_string($path)){

    		throw new Exception('StringUtils->formatPath: Specified path must be a string');
    	}

    	// Replace all slashes on the path with the os default
    	$path = str_replace('/', $osSeparator, $path);
    	$path = str_replace('\\', $osSeparator, $path);

    	// Remove duplicate path separator characters
    	while(strpos($path, $osSeparator.$osSeparator) !== false) {

    		$path = str_replace($osSeparator.$osSeparator, $osSeparator, $path);
    	}

    	// Remove the last slash only if it exists, to prevent duplicate directory separator
    	if(substr($path, strlen($path) - 1) == $osSeparator){

    		$path = substr($path, 0, strlen($path) - 1);
    	}

    	return $path;
    }


    /**
     * TODO - copy from js
     */
    public static function formatUrl(){

    	// TODO - copy from js
    }


    /**
     * Full text search is the official name for the process of searching on a big text content based on a string containing some text to find.
     * This method will process a text so it removes all the accents and non alphanumerical characters that are not usefull for searching on strings,
     * and convert everything to lower case.
     * To perform the search it is important that both search and searched strings are standarized the same way, to maximize possible matches.
     *
     * @param string $string String to process
     * @param string $wordSeparator The character that will be used as the word separator. By default it is the empty space character ' '
     *
     * @return string The resulting string
     */
    public static function formatForFullTextSearch($string, $wordSeparator = ' '){

    	// Remove accents
    	$res = self::removeAccents($string);

    	// make all lowercase
    	$res = strtolower($res);

    	// Take only alphanumerical characters, but keep the spaces
    	$res = preg_replace('/[^a-z0-9 ]/', '', $res);

    	if($wordSeparator != ' '){

    		$res = str_replace(' ', $wordSeparator, $res);
    	}

    	return $res;
    }


    /**
     * Method to generate a random string with the specified lenght
     *
     * @param int $lenght Specify the lengh of the password
     * @param boolean $useuppercase Specify if it's an upper case or not
     *
     * @return string
     */
	public static function generateRandomPassword($lenght = 5, $useuppercase = true){

		// Set the characters to use in the random password
		$chars = 'abcdefghijkmnopqrstuvwxyz023456789';

		// With the upper case option, also upper case letters will be used
		if($useuppercase)
			$chars = 'ABCDEFGHIJKMNOPQRSTUVWXYZ'.$chars;

		// Get the lenght for the chars string to use in random generation process
		$charslen = strlen($chars) - 1;

		// Initialize the used vars: (srand defines the random seed)
	    srand((double)microtime()*1000000);
	    $pass = '' ;

	    // loop throught all the password defined lenght
	    for($i=0; $i<$lenght; $i++){
	    	$num = rand() % $charslen; // get an integer between 0 and charslen.
	        $pass = $pass.substr($chars, $num, 1); // append the random character to the password.
	    }

	    return $pass;

	}


	/**
	 * Converts all accent characters to ASCII characters on a given string.<br>
	 * This method is based on the WordPress implementation called remove_Accents
	 *
	 * @param string $string Text from which accents must be cleaned
	 *
	 * @see https://core.trac.wordpress.org/browser/tags/3.9/src/wp-includes/formatting.php#L682
	 *
	 * @return string The given string with all accent and diacritics replaced by the respective ASCII characters.
	 */
	public static function removeAccents($string){

		if($string == null){

			return '';
		}

		if(!preg_match('/[\x80-\xff]/', $string)){

			return $string;
		}

	    $chars = array(
		    // Decompositions for Latin-1 Supplement
		    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
		    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
		    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
		    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
		    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
		    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
		    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
		    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
		    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
		    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
		    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
		    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
		    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
		    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
		    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
		    chr(195).chr(191) => 'y',
		    // Decompositions for Latin Extended-A
		    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
	    );

	    return strtr($string, $chars);
	}


	/**
	 * Deletes from a string all the words that are shorter than the specified length
	 *
	 * @param string $string The string to process
	 * @param int $shorterThan The minimum length for the words to be preserved. So any word that is shorther than the specified value will be removed.
	 * @param string $wordSeparator The character that will be used as the word separator. By default it is the empty space character ' '
     *
	 * @return string The string without the removed words
	 */
	public static function removeWordsShorterThan($string, $shorterThan = 3, $wordSeparator = ' '){

		if($string == null){

			return '';
		}

		// Generate an array with the received string words
		$words = explode($wordSeparator, $string);
		$wordsCount = count($words);

		for ($i = 0; $i < $wordsCount; $i++) {

			if(strlen($words[$i]) < $shorterThan){

				$words[$i] = '';
			}
		}

		return implode($wordSeparator, $words);
	}


	/**
	 * Deletes from a string all the words that are longer than the specified length
	 *
	 * @param string $string The string to process
	 * @param int $longerThan The maximum length for the words to be preserved. Any word that exceeds the specified length will be removed from the string.
	 * @param string $wordSeparator The character that will be used as the word separator. By default it is the empty space character ' '
     *
	 * @return string The string without the removed words
	 */
	public static function removeWordsLongerThan($string, $longerThan = 3, $wordSeparator = ' '){

		if($string == null){

			return '';
		}

		// Generate an array with the received string words
		$words = explode($wordSeparator, $string);
		$wordsCount = count($words);

		for ($i = 0; $i < $wordsCount; $i++) {

			if(strlen($words[$i]) > $longerThan){

				$words[$i] = '';
			}
		}

		return implode($wordSeparator, $words);
	}


	/**
	 * Remove all urls from The string to process
	 *
	 * @param string $string The string to process
	 * @param string $replacement The replacement string that will be shown when some url is removed
	 *
	 * @return string The string without the urls
	 */
	public static function removeUrls($string, $replacement = 'xxxx') {

		return preg_replace('/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i', $replacement, $string);
	}



	/**
	 * Remove all emails from The string to process
	 *
	 * @param string $string The string to process
	 * @param string $replacement The replacement string that will be shown when some email is removed
	 *
	 * @return string The string without any text that represents an email inside it.
	 */
	public static function removeEmails($string, $replacement = 'xxxx'){

		return preg_replace('/[^@\s]*@[^@\s]*\.[^@\s]*/', $replacement, $string);
	}


	/**
	 * Remove all html code and tags from the specified text, so it gets converted to plain text.
	 *
	 * @param string $string The string to process
	 * @param string $allowedTags You can use this optional second parameter to specify tags which should not be stripped. Example: '&lt;p&gt;&lt;a&gt;&lt;b&gt;&lt;li&gt;&lt;br&gt;&lt;u&gt;' To preserve the specified tags
	 *
	 * @return string The string without the html code
	 */
	public static function removeHtmlCode($string, $allowedTags = ''){

		return strip_tags($string , $allowedTags);
	}


	/**
	 * Removes all multiple spaces from the given string, including tabulators, leaving a single space instead.
	 *
	 * @param string $string The string to process
	 *
	 * @return string The string with a maximum of one consecutive space
	 */
	public static function removeMultipleSpaces($string){

		// Remove line breaks and tabs
		$res = preg_replace('/\t/', ' ', $string);

		// Remove more than one spaces on the string
		return preg_replace('/ +/', ' ', $res);
	}

}

?>