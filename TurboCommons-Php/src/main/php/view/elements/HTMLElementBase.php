<?php

namespace com\edertone\turboCommons\src\main\php\view\elements;


/**
 * The base class for all HTML elements.
 * This is used to define html elements with php, so all the data can be passed in an encapsulated way
 */
class HTMLElementBase{

	/** The element html identifier */
	public $id = '';


	/** The control html name */
	public $name = '';


	/** The css class that is set to this element */
	public $class = '';


	/** The css style to apply to the element, if any */
	public $style = '';


	/** Used to store the generated html code */
	protected $_html = '';


	/**
	 * @param string $class Css class
	 * @param string $style Css style
	 */
	public function __construct($class = '', $style = ''){

		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Get the element HTML code
	 *
	 * @return void
	 */
	public function getHTML(){

		// This method is normally overriden
		return $this->_html;
	}


	/**
	 * Echo the element HTML code
	 *
	 * @return void
	 */
	public function echoHTML(){

		echo $this->getHTML();
	}


	/**
	 * Auxiliary method that generates all the attributes to place on the html element for this element
	 *
	 * @return string The generated html attributes
	 */
	protected function createHtmlAttributes(){

		$atts = '';

		if($this->id != ''){

			$atts .= 'id="'.$this->id.'" ';
		}

		if($this->name != ''){

			$atts .= 'name="'.$this->name.'" ';
		}

		if($this->style != ''){

			$atts .= 'style="'.$this->style.'" ';
		}

		if($this->class != ''){

			$atts .= 'class="'.$this->class.'" ';
		}

		return $atts;
	}
}

?>