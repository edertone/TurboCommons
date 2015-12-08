<?php

namespace TurboFramework\TurboCommons;


/**
 * Control that shows a dropdown with a list of all the Country names in the world, localized with the current locale
 */
class HTMLDropDownCountries extends HTMLElementBase{


	/** Enable it to automatically select the country based on the specified locale */
	public $autoSelectDefault = true;


	/** The locale in which the country names will be shown */
	private $_locale = '';


	/** The list of existing ISO country codes */
	private $_countryCodes = array();


	/**
	 * Create a countries drop down
	 *
	 * @param string $locale The locale in which the country names will be shown
	 * @param string $class The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($locale, $class = '', $style = ''){

		$this->_locale = $locale;
		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Get the component's HTML code
	 *
	 * @return void
	 */
	public function getHTML(){

		$this->_html = '';

		// Get all the currently defined php constants
		$constants = get_defined_constants(true);
		$constants = $constants['user'];
		asort($constants);

		// Get the country code from the current locale
		$localeCode = substr($this->_locale, 3);

		// Generate all the different countries of the drop down by finding all the constants that start with 'LOC_COUNTRY_ISO_'
		foreach(array_keys($constants) as $l){

			if (substr($l, 0, 16) == 'LOC_COUNTRY_ISO_'){

				$countryCode = substr($l, 16);

				$selected = ($localeCode == $countryCode)? 'selected="selected"' : '';

				$this->_html .= '<option value = "'.substr($l, 16).'" '.$selected.'>'.constant($l).'</option>';
			}
		}

		// Check if locales are found
		if($this->_html == ''){

			trigger_error('No countries found on HTMLDropDownCountries. Please make sure that countries bundles are loaded (CountriesList)', E_USER_WARNING);
		}

		return '<select '.$this->createHtmlAttributes().'>'.$this->_html.'</select>';
	}
}

?>