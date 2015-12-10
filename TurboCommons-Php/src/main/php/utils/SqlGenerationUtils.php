<?php

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Utilities for easier sql code generation
 */
class SqlGenerationUtils{


	/**
	 * Generate SQL Insert Query
	 * @param string $table Target table name
	 * @param array $data SQL Data  (ColumnName => ColumnValue) or multiple column data: (Array[] => (ColumnName => ColumnValue))
	 *
	 * @return string
	 **/
	public static function insert($table, array $data) {

		if (is_string($data)) {
			return 'INSERT INTO '.$table.' '.$data.';';
		}

		$field = '';
		$col = '';
		$cols = array();

		foreach ($data as $k => $v) {
			if(!is_array($v)){
				$field .= '`' . $k . '`,';
				$col .= self::_quote($v) . ',';
			}
			else{
				$field = '';
				$col = '';
				foreach($v as $vk => $vv){
					$field .= '`' . $vk . '`,';
					$col .= self::_quote($vv) . ',';
				}

				$field = self::_trim($field , ',');
				array_push($cols, self::_trim($col , ','));
			}
		}

		$colsCount = count($cols);

		if($colsCount == 0){
			$field = self::_trim($field , ',');
			array_push($cols, self::_trim($col , ','));
			$colsCount++;
		}

		$result = 'INSERT INTO '.$table.' ('.$field.') VALUES ';

		for($i = 0; $i < $colsCount; $i++){
			$result .= $i > 0 ? ',' : '';
			$result .= '('.$cols[$i].')';
		}

		return $result;
	}


	/**
	 * Generate SQL Update Query
	 * @param string $table Target table name
	 * @param array $data SQL Data  (ColumnName => ColumnValue)
	 * @param string $cond SQL Condition
	 *
	 * @return string
	 **/
	public static function update($table, array $data, $cond=''){

		$sql = 'UPDATE '.$table.' SET ';
		if (is_string($data)) {
			$sql .= $data;
		} else {
			foreach ($data as $k => $v) {
				$sql .= '`'.$k.'` = '.self::_quote($v).',';
			}
			$sql = self::_trim($sql , ',');
		}
		if ($cond != '') $sql .= ' WHERE '.$cond;
			$sql .= ';';

		return $sql;
	}


	/**
	 * Generate an INSERT or an UPDATE query that will be used to store the data given by a specified class object on DB.
	 *
	 * @param Object $class The class instance we want to store on database
	 * @param string $primaryKeyField The name for the class property that is used as the primary key field on the DB table.
	 * @param string $table The table that contains the data related to the specified entity
	 * @param mixed $propertiesFilter A list of entity properties (array or a string separed by comas) that we want to store from the given object to the table. Leaving this value as empty means all the class properties will be stored on the table.
	 * @param array $extraFields Associative array containing extra data to store in case it is not available on the class properties. For example: array('thisFieldIsNotOntheInstance' => 'theValue')
	 *
	 * @return string The generated query to INSERT or UPDATE the specified instance, depending on the primary key field value. If empty, an INSERT will be generated, otherwise an UPDATE.
	 */
	public static function insertUpdateFromClass($class, $primaryKeyField, $table, $propertiesFilter = '', array $extraFields = array()){

		$fields = array();

		$filter = (is_string($propertiesFilter)) ? explode(',', $propertiesFilter) : $propertiesFilter;

		$props = get_object_vars($class);

		// Generate an array with the properties we want to use on the insert / update
		foreach ($props as $key => $value){

			if(count($filter) <= 0 || in_array($key, $filter)){

				if(!is_array($class->{$key}) && !is_callable($class->{$key})){

					$fields[$key] = $value;
				}
			}
		}

		// Append the extra fields if specified
		foreach ($extraFields as $key => $value){

			$fields[$key] = $value;
		}

		if($class->{$primaryKeyField} != ''){

			return self::update($table, $fields, $primaryKeyField.' = '.$class->{$primaryKeyField});

		}else{

			return self::insert($table, $fields);
		}
	}


	/**
	 * Generate SQL Delete Query
	 * @param string $table Target table name
	 * @param string $cond SQL Condition
	 *
	 * @return string
	 **/
	public static function delete($table,$cond=''){

		$sql = 'DELETE FROM '.$table;
		if ($cond != '') $sql .= ' WHERE '.$cond;
		$sql .= ';';
		return $sql;
	}


	/**
	 * Generate a LIMIT sql fragment that can be used to paginate a database query with the specified parameters
	 *
	 * @param array $paging It must have two elements: The first one is the page we want to retrieve and the second the number of items we want per page
	 *
	 * @return string The sql fragment to paginate a query
	 */
	public static function limitPaginated($paging){

		// Empty value on the paging variable means nothing to do
		if($paging == ''){

			return '';
		}

		// Empty value for the number of items means nothing to do
		if($paging[1] == '' || $paging[1] <= 0){

			return '';
		}

		// empty page value must be set to the first page
		if($paging[0] == ''){

			$paging[0] = 0;
		}

		return 'LIMIT '.($paging[1] * $paging[0]).','.$paging[1];

    }


    /**
     * Generate an ORDER BY sql fragment that can be used to sort the result of a query
     *
     * @param array $fields List of strings with the following format: "fieldName ASC" or "fieldName DESC"
     *
     * @return string The sql fragment to sort a query
     */
    public static function orderBy(array $fields){

    	if(count($fields) <= 0){

    		return '';
    	}

    	return 'ORDER BY '.implode(',', $fields);

    }


    /**
     * Generate an SQL condition to be placed on a WHERE part, that will be used to validate that one or more date fields are found between two specified values.
     *
     * @param array $dateFields List with all the table fields (including any applicable prefix) that will be compared to be inside the given range
     * @param string $datesRange Array containing two values: 1 - The lower date, 2 - The higher date to use for comparison (both in mysql format: yyyy-mm-dd). If the range lower or higher value is empty, there will be no lower or higher limit.
     * @param string $comparisonOperand The boolean operand to use between all the compared fields. By default is OR
     * @param string $sqlOperation The sql operation that will be placed before the generated code
	 *
     * @return string The sql fragment to place on the where part
     */
    public static function expressionBetweenDates(array $dateFields, array $datesRange = null, $comparisonOperand = 'OR',  $sqlOperation = ' AND '){

    	if($datesRange == null || count($datesRange) <= 0 || implode('', $datesRange) == ''){
    		return '';
    	}

    	$res = array();

    	// Format the date values to use for comparison
    	$d1 = "DATE_FORMAT('".$datesRange[0]."', '%Y-%m-%d')";
    	$d2 = "DATE_FORMAT('".$datesRange[1]."', '%Y-%m-%d')";

    	// Loop all the specified date fields
    	foreach($dateFields as $date){

    		$d = 'DATE_FORMAT('.$date.", '%Y-%m-%d')";

    		$ranges = array();

    		if($datesRange[0] != ''){
    			array_push($ranges, $d.' >= '.$d1);
    		}

    		if($datesRange[1] != ''){
    			array_push($ranges, $d.' <= '.$d2);
    		}

    		array_push($res, '('.$date.' IS NOT NULL AND '.implode(' AND ', $ranges).')');
    	}

    	return $sqlOperation.'('.implode(' '.$comparisonOperand.' ', $res).')';

    }


    /**
     * Generate an SQL condition to be placed on a WHERE part, that will be used to validate that one or more numeric fields are found between two specified values.
     *
     * @param array $numericFields List with all the table fields (including any applicable prefix) that will be compared to be inside the given range
     * @param string $numbersRange Array containing two values: 1 - The lower number 2 - The higher number to use for comparison. If the range lower or higher value is empty, there will be no lower or higher limit.
     * @param string $comparisonOperand The boolean operand to use between all the compared fields. By default is OR
     * @param string $sqlOperation The sql operation that will be placed before the generated code
     *
     * @return string The sql fragment to place on the where part
     */
    public static function expressionBetweenNumericValues(array $numericFields, array $numbersRange = null, $comparisonOperand = 'OR',  $sqlOperation = ' AND '){

    	if($numbersRange == null || count($numbersRange) <= 0 || implode('', $numbersRange) == ''){
    		return '';
    	}

    	$res = array();

    	// Loop all the specified number fields
    	foreach($numericFields as $number){

    		$ranges = array();

    		if($numbersRange[0] != ''){
    			array_push($ranges, $number.' >= '.$numbersRange[0]);
    		}

    		if($numbersRange[1] != ''){
    			array_push($ranges, $number.' <= '.$numbersRange[1]);
    		}

    		array_push($res, '('.implode(' AND ', $ranges).')');
    	}

    	return $sqlOperation.'('.implode(' '.$comparisonOperand.' ', $res).')';

    }


    /**
     * Generate a generic WHERE boolean expression from an array of values. For example:
     * [1,2,3] can be converted to: "(c.id = 1 OR c.id = 2 OR c.id = 3)"
     *
     * @param array $array The array containing the values that will be placed on the result. For example : [1, 4, 5]
     * @param string $expressionStart The string that will be placed before each value. For example: 'id'
     * @param string $expressionEnd The string that will be placed after each value. Normally not used
     * @param string $operator The operator that will join all the array values. Default is OR
     *
     * @return string The generated sql expression enclosed between open and close parenthesis. For example: (id=1 OR id=4 OR id=5).
     */
    public static function expressionFromArray(array $array, $expressionStart, $expressionEnd = '', $operator = 'OR'){

    	$arrayCount = count($array);

    	if($arrayCount <= 0){
    		return '';
    	}

    	$res = $expressionStart.$array[0].$expressionEnd;

    	for ($i = 1; $i < $arrayCount; $i++){
    		$res .= ' '.$operator.' '.$expressionStart.$array[$i].$expressionEnd;
    	}

    	return '('.$res.')';
    }


	/**
	 * Auxiliary Remove unnecessary string in a sql query
	 *
	 * @param string $s String 1
	 * @param string $s2 String need to be removed
	 *
	 * @return string
	 * @access private
	 **/
	private static function _trim($s, $s2) {
		if (substr($s , strlen($s) - strlen($s2)) == $s2) $s = substr($s , 0 , strlen($s) - strlen($s2));
		return $s;
	}


	/**
	 * auxiliary Quote an SQL value
	 *
	 * @param string $s String that needs to be quoted
	 *
	 * @return string
	 **/
	private static function _quote($s) {

		if (!is_numeric($s)){
			$s = addslashes($s);
		}

		return "'".$s."'";

	}

}

?>