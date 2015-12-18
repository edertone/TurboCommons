<?php

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * The most common string processing and modification utilities
 */
class StringUtils {


	/**
	 * Tells if a specified string is empty. The string may contain empty spaces, and new line characters but have some lenght, and therefore be EMPTY.
	 * This method checks all these different conditions that can tell us that a string is empty.
	 *
	 * @param string $string String to check
	 * @param array $otherEmptyKeys Optional array containing a list of string values that will be considered as empty for the given string. This can be useful in some cases when we want to consider a string like 'NULL' as an empty string.
	 *
	 * @return boolean false if the string is not empty, true if the string contains only spaces, newlines or any other "empty" character
	 */
	public static function isEmpty($string, array $otherEmptyKeys = null){

		$aux = '';

		// Note that we are checking emptyness every time we do a replace to improve speed, avoiding unnecessary replacements.
		if($string == null || $string == ''){

			return true;
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

		// Check if the empty keys array is specified
		if(count($otherEmptyKeys) > 0){

			if(in_array($aux, $otherEmptyKeys)){

				return true;
			}
		}

		return false;
	}


	/**
	 * Method that limits the lenght of a given string, appending 3 dots, meaning the string is longer.
	 *
	 * @param string $string String to limit
	 * @param int $limit Max number of characters
	 * @param string $limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
	 *
	 * @return string The specified string but limited by the specified lenght
	 */
	public static function limitLen($string, $limit = 100, $limiterString = ' ...'){

		if(strlen($string) <= $limit){

			return $string;
		}

		return mb_strcut($string, 0, $limit, mb_detect_encoding($string)).$limiterString;
    }


    /**
     * Clean latin and strange accents from a string. String encoding is utf8 by default
     *
     * @param string $str String to remove accents
     * @param string $charset Sort of charset
     *
     * @return mixed
     */
    public static function removeAccents($str, $charset='utf-8'){

    	$str = htmlentities($str, ENT_NOQUOTES, $charset);

	    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
	    $str = preg_replace('#&[^;]+;#', '', $str);

	    return $str;

    }


	/**
	 * Full text search is the official name for the process of searching on a big text content based on a string containing text to find.
	 * This method will process a text so it removes all the accents and non alphanumerical characters that are not usefull for searching on strings,
	 * and converts everything to lower case.
	 * To perform the search it is important that both search and searched strings are standarized the same way, to maximize possible matches.
	 *
	 * @param string $string String to process
	 * @param string $wordSepparator The character that will be used as the word sepparator. By default it is the empty space character ' '
	 *
	 * @return string The resulting string
	 */
    public static function processForFullTextSearch($string, $wordSepparator = ' '){

    	// Remove accents
    	$res = self::removeAccents($string);

    	// make all lowercase
    	$res = strtolower($res);

    	// Take only alphanumerical characters, but keep the spaces
    	$res = preg_replace('/[^a-z0-9 ]/', '', $res);

    	if($wordSepparator != ' '){

    		$res = str_replace(' ', $wordSepparator, $res);
    	}

    	return $res;
    }


    /**
     * Extracts all the lines from the given string. It does not matter which line sepparator's been used (Windows, linux...)
     *
     * Any empty line will be discarded
     *
     * @param string $string String to extract its lines
     * @param strign $limit If specified, then only lines up to limit are returned with the rest of the string being placed in the last line.
     *
     * @return array A list of all the string lines sepparated on each array element.
     */
    public static function extractLines($string, $limit = null){

    	$res = array();

    	$tmp = preg_split("/((\r?\n)|(\r\n?))/", $string, $limit);

    	foreach($tmp as $line){

    		if(!self::isEmpty($line)){

    			array_push($res, $line);
    		}
    	}

    	return $res;
    }


    /**
     * Generates an array containing the words that are more common on the text until the count reaches the max specified value
     *
     * @param string $string String to extract its keywords
     * @param string $max The maxium of keywords which will be extracted. If no specified, all keywords will be returned
     * @param string $longerThan The minimum number of chars for the keywords to find
     * @param string $shorterThan The maximum number of chars for the keywords to find
     * @param string $removeNumericWords Flag that will tell the method to skip words that represent numeric values
     *
     * @return array The list of keywords that have been extracted
     */
    public static function extractKeywords($string, $max = '', $longerThan = 3, $shorterThan = 15, $removeNumericWords = false){

    	// Convert all the - and _ characters to blank spaces
    	$string = str_replace('-', ' ', str_replace('_', ' ', $string));

    	// Process the received string to contain only alphanumeric lowercase values
    	$string = self::processForFullTextSearch($string);

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
    	$res = array();

    	for($i=$maxCount; $i> 0; $i--){

    		foreach($words as $key => $v){
    			if($v == $i){
					if(!is_numeric($key) || (is_numeric($key) && !$removeNumericWords)){
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
	* Method that gives the file name from a path, without its extension. Example: "//folder/pepe/tete/file.txt" ---> results in "file"
	*
	* @param string $path The path of the file
	*
	* @return File name
	*/
	public static function getFilenameNoextension($path){

		$aux = str_replace('\\', '/', $path);

		if(strpos($aux, '/') !== false){

			$aux = substr(strrchr($aux, '/'), 1);
		}

		if(strpos($aux, '.') !== false){

			$aux = substr($aux, 0, strrpos($aux, '.'));
		}

		return $aux;
    }


	/**
	* Method that gives the file name from a path, with its extension. Example: "//folder/pepe/tete/file.txt" ---> results in "file.txt"
	*
	* @param string $path The file path
	*
	* @return File name + extension
	*/
	public static function getFilenameWithExtension($path){

		$aux = str_replace('\\', '/', $path);

		if(strpos($aux, '/') !== false)
			$aux = substr(strrchr($aux, '/'), 1);

		return $aux;

    }


	/**
	* Method that gives the file extension for a given path (The dot symbol is NOT included)
	*
	* @param string $path The path of the file
	* @param boolean $lowercase Specify if the return value will be converted to lower case or not
	*
	* @return file extension WITHOUT dot character. For example: jpg, png, js, exe ...
	*/
	public static function getFileExtension($path, $lowercase = false){

		// Find the extension by getting the last position of the dot character
		$extension = substr($path, strrpos($path, '.') + 1);

		return $lowercase ? strtolower($extension) : $extension;
    }


    /**
     * Gives correct format to a file path so all the directory separators are correct for the current OS, and makes sure that no directory separator is present at the end.
     * The method basically standarizes the given file path format.
     *
     * @param string $path The path that must be formated
     *
     * @return string The correctly formated path
     */
    public static function formatFilePath($path){

    	// Replace all slashes on the path to the os default
    	$res = str_replace('/', DIRECTORY_SEPARATOR, $path);
    	$res = str_replace('\\', DIRECTORY_SEPARATOR, $res);

    	// Remove the last slash only if it exists, to prevent duplicate directory separator
    	if(substr($res, strlen($res) - 1) == DIRECTORY_SEPARATOR){

    		$res = substr($res, 0, strlen($res) - 1);
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
	 * Deletes from a string all the words that are shorter than the specified length
	 *
	 * @param string $string   The string to process
	 * @param int $shorterThan The minimum length for the words to be preserved
	 * @param int $startWithLen The strings that are shorter than the specified length wont be processed.
	 *
	 * @return string The string without the removed words
	 */
	public static function removeWordsShorterThan($string, $shorterThan = 3, $startWithLen = 45){

		if(strlen($string) <= $startWithLen){
			return $string;
		}

		// Array where the result will be stored
		$res = array();

		// Generate an array with the received string words
		$words = explode(' ', $string);

		foreach($words as $value){

			if(strlen($value) >= $shorterThan){
				array_push($res, $value);
			}
		}

		return implode(' ', $res);

	}


	/**
	 * Deletes from a string all the words that are longer than the specified length
	 *
	 * @param string $string   The string to process
	 * @param int $longerThan The maximum length for the words to be preserved
	 * @param int $startWithLen The strings that are shorter than the specified length wont be processed.
	 *
	 * @return string The string without the removed words
	 */
	public static function removeWordsLongerThan($string, $longerThan = 3, $startWithLen = 45){

		if(strlen($string) <= $startWithLen){
			return $string;
		}

		// Array where the result will be stored
		$res = array();

		// Generate an array with the received string words
		$words = explode(' ', $string);

		foreach($words as $value){

			if(strlen($value) <= $longerThan){
				array_push($res, $value);
			}
		}

		return implode(' ', $res);

	}


	/**
	 * If we have a string that contains some sort of prefix that is appended by a sepparator, and we want to remove it, we can use this method.
	 *
	 * @param string $string The string to process
	 * @param string $sepparator The character or characters that are used to sepparate the string prefix from the string itself
	 *
	 * @return string The string without the prefix, based on the given sepparator. For example: pre-string woud become string if we specify - as the sepparator.
	 */
	public static function removePrefix($string, $sepparator) {

		$tmp = explode('*', $string, 2);

		return array_pop($tmp);
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