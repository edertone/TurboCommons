<?php

/**
 * TurboCommons-Php
 *
 * PHP Version 5.4
 *
 * @copyright 2015 Edertone advanced solutions (http://www.edertone.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://turbocommons.org
 */


namespace com\edertone\turboCommons\src\main\php\utils;


/** Complex object conversion utilities */
class SerializationUtils{


	/**
	 * Reads a received associative array and stores all values to the corresponding properties on the provided class instance
	 *
	 * @param array $array The array where the source data is found
	 * @param Object $class The class instance that will be filled with the source data
	 *
	 * @return the provided class instance
	 */
	public static function arrayToClass(array $array, $class){

		foreach($array as $key => $value){

			if(property_exists($class, $key)){

				$class->{$key} = $value;
			}
		}

		return $class;

	}


	/**
	 * Reads a received 2 dimensional array and generates another array but with each row containing an element of the specified type.
	 * Each source row is converted to a class instance, filled with the row values, and inserted to the result array
	 *
	 * @param array $array The associative array where the source data is found
	 * @param string $className The name for the class that will be created as an instance for each one of the source rows
	 *
	 * @return array Array of class instances
	 */
	public static function arrayToArrayOfClasses(array $array, $className){

		$res = array();

		foreach($array as $a){

			$object = new $className;

			self::arrayToClass($a, $object);

			array_push($res, $object);
		}

		return $res;
	}


	/**
	 * This is a recursive method that converts an associative array of any depth to an xml object. Each array -> key -> value pair
	 * will be converted to an xml attribute like <$rootName key="value" /> on the resulting xml. If a value of the array is
	 * another array, it will be added as a child node by using its key as the node name, and the same process will be performed recursively.
	 *
	 * @param array $array The associative array structure to be serialized
	 * @param string $rootName The name to use for the root of the generated xml
	 * @param SimpleXMLElement $xml Optional simple xml element where the data will be placed. If empty, a new one will be generated.
	 *
	 * @return SimpleXMLElement The xml converted structure
	 */
	public static function arrayToXml($array, $rootName, SimpleXMLElement $xml = null){

		if($xml == null){

			$xml = new SimpleXMLElement('<'.$rootName.'/>');
		}

		foreach($array as $key => $value){

			if(is_array($value)){

				$child = self::arrayToXml($value, $key);

				XMLUtils::simpleXmlAppend($xml, $child);

			}else{

				$xml->addAttribute($key, htmlspecialchars($value, ENT_QUOTES));
			}
		}

		return $xml;
	}


	/**
	 * Serialize a bidimensional associative array to a string encoded as an URL with GET parameters.
	 * Example: an array [a => 1, b => 2, c => 3] is converted to the following string: a=1&b=2&c=3
	 *
	 * @param array $array The associative array to be serialized
	 *
	 * @return string The encoded string
	 */
	public static function arrayToGetParameters($array){

		$result = '';

		foreach($array as $a){
			$result .= '&'.urlencode($a[0]).'='.urlencode($a[1]);
		}

		return substr($result, 1);

	}


	/**
	 * Generates a csv string from a received 2d array (array of arrays)
	 *
	 * @param array $array Array to be generated as CSV
	 * @param string $delimiter separator character to separate each line value (Excel uses ';' Unix uses ',' and it is the default one)
	 * @param string $enclosure Character that will be used to enclose and escape a value if a special char is found
	 * @param string $linefeed The character to sepparate each line (\n by default)
	 * @param array $columnNames Optionally we can set the name of each column in the array. If the array is associative, column names are found on the array keys and this is not required.
	 *
	 * @return string The encoded csv string
	 */
	public static function arrayToCsv(array $array, $delimiter = ',', $enclosure = '"', $linefeed = "\n", array $columnNames = null){

		if(count($array) <= 0){
			return '';
		}

		$csv = array();

		// Get the csv column names
		if($columnNames == null){

			$columnNames = array_keys($array[0]);
		}

		array_push($csv, self::_formatCsvLine($columnNames, $delimiter));

		foreach($array as $line){

			$l = self::_formatCsvLine($line, $delimiter, $enclosure, $linefeed);

			// Adding an empty line string can cause the decode to fail, so we verify that no empty lines are written
			if($l != ''){
				array_push($csv, $l);
			}

		}

		return implode("\n", $csv);

	}


	/**
	 * Generates a string that contains csv data, but preceded by some extra values that are stored first.
	 * Normally used to give more info than the one stored on the csv string. The method output will be encoded with the following format: v1-v2-vn_CSV
	 *
	 * @param array $array Array containing the data to convert to a csv string
	 *
	 * @return string The encoded string
	 */
	public static function arrayToCsvCustom(array $array, $delimiter = ',', $enclosure = '"', $linefeed = "\n"){

		$csv = array_shift($array);

		return implode('-', $array).'_'.self::arrayToCsv($csv, $delimiter, $enclosure, $linefeed);

	}


	/**
	 * Converts an array where each row is an instance of a class, to an xml object where each child is an xml representation of the respective instance.
	 * Source array can contain instances from different classes.
	 *
	 * @param array $array An array of the same or different class instances
	 * @param string $rootName The name for the resulting xml root element, which will contain the serialized classes as children.
	 *
	 * @return SimpleXMLElement The serialized structure
	 */
	public static function arrayOfClassesToXml(array $array, $rootName){

		// Generate a list with all the details for the domains that are available for the user system
		$result = new SimpleXMLElement('<'.$rootName.'/>');

		foreach ($array as $a){

			self::classToXml($a, $result->addChild(get_class($a)));
		}

		return $result;
	}


	/**
	 * Convert a PHP array to a JSON string representation.
	 * We can pass any associative or non associative arrays, and even combinations of both types. JSON will represent the structure correctly and we will be able to convert it back in any other language like JS or PHP
	 *
	 * @param array $array A php array to convert (associative, non associative or mixed)
	 *
	 * @return string The string json representation for the given PHP array
	 */
	public static function arrayToJson(array $array){

		return json_encode($array);
	}


	/**
	 * Converts a received class object to its xml representation. It is recursive, so it will also convert contained arrays and objects.
	 *
	 * @param Object $class An instance of the class to convert
	 * @param SimpleXMLElement $xml Optional simple xml element where the data will be placed. If empty, a new one will be generated.
	 *
	 * @return SimpleXMLElement The converted data
	 */
	public static function classToXml($class, SimpleXMLElement $xml = null){

		if($xml == null){

			$xml = new SimpleXMLElement('<'.get_class($class).'/>');
		}

		$props = get_object_vars($class);

		foreach ($props as $key => $value){

			if(is_array($class->{$key})){

				$sub = $xml->addChild($key);

				foreach ($class->{$key} as $k => $c){

					$x = $sub->addChild(get_class($c));

					if(is_object($c)){

						self::classToXml($c, $x);

					}else{

						// The add attribute method will escape all necessary special chars from $c
						$x->addAttribute($k, $c);
					}
				}
				continue;
			}

			if(is_object($class->{$key})){

				// The add attribute method will escape all necessary special chars from the generated xml string
				$xml->addAttribute($key, self::classToXml($class->{$key})->asXml());
				continue;
			}

			if(!is_callable($class->{$key})){

				// The add attribute method will escape all necessary special chars from $value
				$xml->addAttribute($key, $value);
			}
		}

		return $xml;
	}


	/**
	 * Reads a class entity and generates an associative array
	 *
	 * @param Object $class		The class that will be parsed
	 *
	 * @return array
	 */
	public static function classToArray($class){

		$array = array();

		foreach($class as $property => $value){

			$array[$property] = $value;

		}

		return $array;

	}


	/**
	 * Reads a class entity and generates an array containing only the names of the class properties, in the same order as defined in the class.
	 *
	 * @param Object $class		The class that will be parsed
	 *
	 * @return array The list of class properties in the same order as the class
	 */
	public static function classToArrayOfProperties($class){

		$array = array();

		foreach($class as $property => $value){

			array_push($array, $property);
		}

		return $array;

	}


	/**
	 * Convert a string with CSV data to an array (Note that the csv string must contain the column headers on the first line!)
	 *
	 * @param string $str String containing the CSV
	 * @param string $delimiter The delimiter
	 * @param string $enclosure The enclosure
	 * @param string $linefeed The escape
	 *
	 * @return multitype:
	 */
	public static function csvToArray($str, $delimiter = ',', $enclosure = '"', $linefeed = "\n") {

		// STEP 1: Prepare the reference string replacing unwanted characters

		$found = false;
		$aux = ''; // define the string that will be used as the reference to split the data
		$strlen = strlen($str);

		for($i=0; $i<$strlen; $i++){

			// Two enclosures are always replaced when found adjacent
			if(substr($str, $i, 2) == $enclosure.$enclosure){
				$aux .= 'XX';
				$i+=2;
			}

			// finding an enclosure means the start or end of a scaped field
			if(substr($str, $i, 1) == $enclosure)
				$found = !$found;

			// if we are currently inside a scaped field, we will be replacing all the characters with X, otherwise leave them untouched
			if($found)
				$aux .= 'X';
			else
				$aux .= substr($str, $i, 1);
		}

		// STEP 2: Split the real data

		$result = array(); // to store the result
		$referenceArray = explode($linefeed, $aux); // Cut all the csv lines from the reference string
		$j = strlen($referenceArray[0]) + 1; // Point the j index to the start of the firs line afther the headers containing column names
		$keys = explode($delimiter, array_shift($referenceArray)); // Get an array with all the column headers, that are located on the first line of the string

		foreach($referenceArray as $line){

			if($line != ''){

				$resultfields = array();
				$fields = explode($delimiter, $line);
				$fieldslen = count($fields);

				// Loop throught all the reference fields and fint the real position on the real string
				for ($i=0; $i<$fieldslen; $i++){

					// Get the real field on the aux string
					$aux = substr($str, $j, strlen($fields[$i]));
					$j += (strlen($fields[$i]) + 1);

					// Check if we need to restore scaped field. If field starts with " we remove first and last chars
					// And then replace all the '' groups with simple ". This restores the field to its real value
					if(substr($aux, 0, 1) == $enclosure)
						$aux = str_replace ($enclosure.$enclosure, $enclosure, substr($aux, 1, strlen($aux) - 2));

					// store the field on the result array under it's corresponding column key
					$resultfields[$keys[$i]] = $aux;
				}

				array_push($result, $resultfields);
			}
		}

		return $result;
	}


	/**
	 * Convert a string formatted as a GET url list of parameters to an associative array.
	 * Example: a=1&b=2&c=3 is converted to the following array: [a=>1, b=>2, c=>3]
	 *
	 * @param string $parameters The parameters to be serialized
	 * @param boolean $removeHtmlTags Enable or disable the HTML code in the url keys / values
	 *
	 * @return array An associative array containing the parameters that where stored on the GET string as key - value pairs.
	 */
	public static function getParametersToArray($parameters, $removeHtmlTags = true){

		$result = array();

		$parameters = explode('&', $parameters);

		if(is_array($parameters)){

			foreach($parameters as $p){

				if(strpos($p, '=')){

					$p = explode('=', $p);

					$key = urldecode($p[0]);
					$value = urldecode($p[1]);

					// Here we remove the html tags from the parameter key and value to prevent HTML injection attacks
					if ($removeHtmlTags){
						$key = strip_tags($key);
						$value = strip_tags($value);
					}

					$result[$key] = $value;
				}
			}
		}

		return $result;
	}


	/**
	 * Read all the currently defined POST variables and store them on the respective property (a property with the same name) for the given class instance.
	 *
	 * @param Object $class The class instance that will be filled with the POST data
	 * @param boolean $removeHtmlTags Enable or disable the HTML code in the url keys / values
	 *
	 * @return array An associative array containing the parameters that where stored on the GET string as key - value pairs.
	 */
	public static function postParametersToClass($class, $removeHtmlTags = true){

		$result = [];

		foreach($_POST as $key => $value){

			// Here we remove the html tags from the parameter key and value to prevent HTML injection attacks
			if ($removeHtmlTags){

				$key = strip_tags($key);
				$value = strip_tags($value);
			}

			$result[$key] = $value;
		}

		return self::arrayToClass($result, $class);
	}


	/**
	 * Convert a JSON string to the respective PHP object
 	 *
 	 * @param string $json The JSON string that contains the defined object.
	 *
	 * @return Object A Php object representation for the received json string
	 */
	public static function jsonToObject($json){

		return json_decode($json, false);
	}


	/**
	 * Convert a PHP generical object (stdClass) to the respective JSON Object
	 *
	 * @param stClass $object The Php object that will be converted to json
	 *
	 * @return string The string json representation for the given PHP object
	 */
	public static function objectToJson($object){

		return json_encode(get_object_vars($object));
	}


	/**
	 * Convert a received xml structure to the related class or structure of classes.
	 * Class properties are read from the xml attributes on the root node. Each child node will be parsed to an array
	 * of entities, and will be stored on the class property that has the same name as the node. Each entity on the array
	 * will be processed the same way as the root one.
	 *
	 * @param mixed $xml An xml string or a SimpleXmlElement that will be converted to the structure of classes
	 *
	 * @return object The resulting serialized class
	 */
	public static function xmlToClass($xml){

		if(is_string($xml)){
			$xml = simplexml_load_string($xml);
		}

		$className = $xml->getName();
		$class = new $className;

		// Loop the entity attributes and fill the class with the values
		foreach($xml->attributes() as $name => $value){

			if(property_exists($class, $name)){

				$class->{$name} = (string)$value;
			}
		}

		// Loop any possible class child nodes and generate the related entities
		foreach($xml as $item){

			$itemName = $item->getName();

			if(property_exists($class, $itemName)){

				foreach ($item as $i){
					array_push($class->{$itemName}, self::xmlToClass($i));
				}
			}
		}

		return $class;
	}


	/**
	 * Convert a received xml structure to an associative array.
	 * Array keys are read from the xml attributes on the root node. Each child node will be parsed to an array and will be stored on
	 * the key that has the same name as that node. Process will repeat recursively
	 *
	 * @param mixed $xml An xml string or a SimpleXmlElement that will be converted to an associative array
	 *
	 * @return array The resulting serialized array
	 */
	public static function xmlToArray($xml){

		if(is_string($xml)){
			$xml = simplexml_load_string($xml);
		}

		$array = array();

		// Loop the entity attributes and fill the class with the values
		foreach($xml->attributes() as $name => $value){
			$array[$name] = (string)$value;
		}

		// Loop any possible class child nodes and generate the related entities
		foreach($xml as $item){
			$itemName = $item->getName();

			if (!isset($array[$itemName])){
				$array[$itemName] = array();
			}

			array_push($array[$itemName], self::xmlToArray($item));
		}

		return $array;
	}


    /**
     * Auxiliary method that is used to format a single csv line string
     *
     * @param array $l The array containing the line to format as csv
     * @param string $delimiter character to separate each line value (Excel uses ';' Unix uses ',' and it is the default one)
     *
     * @return string The formatted csv line
     */
    private static function _formatCsvLine($l, $delimiter = ',', $enclosure = '"', $linefeed = "\n"){

    	$aux = array();

    	foreach($l as $v){

    		// In case we find special chars on the value, we must enclose it with the specified enclosure character
    		if(strpos($v, $delimiter) !== false || strpos($v, $enclosure) !== false || strpos($v, $linefeed) !== false){

    			$v = $enclosure.str_replace($enclosure, $enclosure.$enclosure, $v).$enclosure;
    		}

    		array_push($aux, $v);

    	}

    	$res = implode($delimiter, $aux);

    	// If the result contains only empty fields, we will return an empty string
    	if(strlen($res) == count($aux) - 1)
    		return '';
    	else
    		return $res;

    }
}

?>