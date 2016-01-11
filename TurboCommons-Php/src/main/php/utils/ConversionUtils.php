<?php


namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * The most common conversion utilities to change the data from a simple type to another one.
 * To convert complex classes or structures, use SerializationUtils class.
 */
class ConversionUtils {


	/**
	 * Converts a Mysql date to a dd/mm/yyyy format
	 *
	 * @param string $date Date to format
	 * @param string $seppar String sepparator for the date
	 * @param boolean $showTimeValue Specify if show time value or not
	 *
	 * @return string
	 */
	public static function dateMysqlToDMY($date, $seppar = '/', $showTimeValue = true){

		if($date == '' || $date == '0000-00-00'){
			return '';
		}

		// Split time and date
		$aux = explode(' ', $date);

		$d = explode('-', $aux[0]);

		$time = isset($aux[1]) ? $aux[1] : '';

		// Generate the correct format to the res variable
		$res = $d[2].$seppar.$d[1].$seppar.$d[0];

		return ($showTimeValue) ? $res.' '.$time : $res;

    }


    /**
     * Converts a date to a string format, using day names and full year numbers like '8 may 2009'.
	 * Optionally we can get the day of the week name as 'monday 8 may 2009'
     *
     * @param string $date Date to format
     * @param boolean $showDayName Specify if show day name
     *
     * @return string
     */
	public static function dateMysqlDMYToLocalized($date, $showDayName){

    	$res = self::dateMysqlToDMY($date, '/', false);
    	$res = explode('/', $res);

    	$day = $res[0];
    	$month = $res[1];
    	$year = $res[2];

	   	if($showDayName){
    		return DateUtils::getDayName($day).' '.DateUtils::getMonthName($month).' '.$year;
	   	}else{
	   		return $day.' '.DateUtils::getMonthName($month).' '.$year;
	   	}
    }


    /**
     * Converts a color in hex format to the respective RGB numeric values.
     *
     * @param string $hexColor A 6 digit color in HEX format, like #ffffff, 000000, #ff09A7 ...
     *
     * @return array Array with three values, corresponding to the rgb codes. For example [255, 255, 255] if #ffffff is received.
     */
    public static function colorHexToRgb($hexColor){

    	$formattedValue = ltrim($hexColor, '#');

    	// Check that received color code format is ok
    	if(strlen($formattedValue) != 6){

    		trigger_error('ConversionUtils::colorHexToRgb : Invalid color HEX format received: '.$hexColor, E_USER_WARNING);
    	}

    	return array_map('hexdec', str_split($formattedValue, 2));
    }

}

?>