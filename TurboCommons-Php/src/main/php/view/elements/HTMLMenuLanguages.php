<?php

namespace com\edertone\turboCommons\src\main\php\view\elements;


/**
 * HTMLMenuLanguages
 */
class HTMLMenuLanguages extends HTMLMenu{

	/**
	 * An extension for the html menu specifically defined for language selection
	 *
	 * @param string $class The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($class = '', $style = ''){

		$this->hideSelectedItem = true;

		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Add a new element to the menu
	 *
	 * @param string $locale The locale represented by this element
	 * @param string $label The label of the element. If not specified, the language code with upper case will be used
	 * @param string $icon Optionally we can render the element as an image. We must set it's url here. This will override the label value.
	 * @param string $class The css class to use with this element. None by default
	 * @param string $style	The css style to use with this element. None by default
	 * @param string $alsoSelectedIfUrl	Use this to specify that the menu anchor element will be marked as selected also if the current url contains the specified fragment of text
	 *
	 * @return void
	 */
	public function addAnchor($locale, $label = '', $icon = '', $class = '', $style = '', $alsoSelectedIfUrl = ''){

		if($label == ''){

			$label = strtoupper(substr($locale, 0, 2));
		}

		parent::addAnchor(App::getUrlToChangeLocale($locale), $label, $icon, $class, $style, $alsoSelectedIfUrl);
	}
}

?>