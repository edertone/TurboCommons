<?php

namespace TurboFramework\TurboCommons;


/**
 * HTMLMenuDropDown
 */
class HTMLMenuDropDown extends HTMLElementBase{


	/** The list of elements in the same order as they will appear on the menu */
	protected $_elements = array();


	/**
	 * A customizable dropdown menu that is normally used with mobile devices to select one from a list of urls and open it in the browser.
	 *
	 * @param string $id Id is mandatory for this control, as it requires some javascript code to work (that is also generated)
	 * @param string $class The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($id, $class = '', $style = ''){

		$this->id = $id;
		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Add a new html option item to the menu
	 *
	 * @param string $url The url that will be opened by the anchor
	 * @param string $label The label of the anchor
	 * @param string $alsoSelectedIfUrl	Use this to specify that the menu anchor element will be marked as selected also if the current url contains the specified fragment of text
	 *
	 * @return void
	 */
	public function addOption($url, $label, $alsoSelectedIfUrl = ''){

		$element = array();

		$element['url'] = $url;
		$element['label'] = $label;
		$element['alsoSelectedIfUrl'] = $alsoSelectedIfUrl;

		array_push($this->_elements, $element);
	}


	/**
	 * Get the component's HTML code
	 *
	 * @return void
	 */
	public function getHTML(){

		$elementsCount = count($this->_elements);

		$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$generatedHtml = array();

		for($i = 0; $i < $elementsCount; $i++){

			if($this->_elements[$i]['url']){

				$currentItemIsSelected = strpos($currentUrl, $this->_elements[$i]['url']) !== false;

				if($this->_elements[$i]['alsoSelectedIfUrl'] != ''){

					$currentItemIsSelected = $currentItemIsSelected || strpos($currentUrl, $this->_elements[$i]['alsoSelectedIfUrl']) !== false;
				}

			}else{

				$currentItemIsSelected = false;
			}

			$elementHref = (!$currentItemIsSelected && $this->_elements[$i]['url'] != '') ? ' value="'.$this->_elements[$i]['url'].'"' : '';

			$menuElement  = '<option'.$elementHref.(($currentItemIsSelected) ? ' selected' : '').'>';

			$menuElement .= $this->_elements[$i]['label'].'</option>';

			array_push($generatedHtml, $menuElement);
		}

		$this->_html = implode('', $generatedHtml);

		$jsCode = '<script>$(document).ready(function(){
						$("#'.$this->id.'").change(function(){
							window.location.href = $(this).val();
						});
					});</script>';

		return '<select '.$this->createHtmlAttributes().'>'.$this->_html.'</select>'."\n".$jsCode;
	}
}

?>