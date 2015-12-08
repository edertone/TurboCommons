<?php

namespace TurboFramework\TurboCommons;


/**
 * HtmlMenu
 * import path: ProjectPaths::LIBS.'/libEdertonePhp/view/elements'
 */
class HTMLMenu extends HTMLElementBase{


	/** False by default. Enable this to hide the currently selected element from the menu */
	public $hideSelectedItem = false;

	/** The css class that is set with the selected item */
	public $selectedItemClass = '';

	/** The css style to apply to the selected item */
	public $selectedItemStyle = '';


	/** The list of elements in the same order as they will appear on the menu */
	protected $_elements = array();

	/** Html code to use as a sepparator for the menu elements */
	private $_sepparator = '';


	/**
	 * A customizable html menu structure.
	 *
	 * @param string $class The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($class = '', $style = ''){

		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Add a new html A anchor to the menu
	 *
	 * @param string $url The url that will be opened by the anchor
	 * @param string $label The label of the anchor
	 * @param string $icon Optionally we can render the anchor as an image. We must set it's url here. This will override the label value.
	 * @param string $class The css class to use with this anchor. None by default
	 * @param string $style	The css style to use with this anchor. None by default
	 * @param string $alsoSelectedIfUrl	Use this to specify that the menu anchor element will be marked as selected also if the current url contains the specified fragment of text
	 *
	 * @return void
	 */
	public function addAnchor($url, $label, $icon = '', $class = '', $style = '', $alsoSelectedIfUrl = ''){

		$element = array();

		$element['element'] = 'a';
		$element['url'] = $url;
		$element['label'] = $label;
		$element['icon'] = $icon;
		$element['class'] = $class;
		$element['style'] = $style;
		$element['alsoSelectedIfUrl'] = $alsoSelectedIfUrl;

		array_push($this->_elements, $element);
	}


	/**
	 * Use this to place raw html code after the currently written elements
	 *
	 * @param string $htmlCode The raw html code to place, for example: '<p>-</p>'
	 *
	 * @return void
	 */
	public function addHtmlCode($htmlCode){

		$element = array();

		$element['htmlCode'] = $htmlCode;

		array_push($this->_elements, $element);
	}


	/**
	 * Use this to place raw html code as the sepparator of all the menu elements
	 *
	 * @param string $htmlCode The raw html code to place, for example: '<p>-</p>'
	 *
	 * @return void
	 */
	public function addHtmlSepparator($htmlCode){

		$this->_sepparator = $htmlCode;
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

			if(isset($this->_elements[$i]['htmlCode'])){

				array_push($generatedHtml, $this->_elements[$i]['htmlCode']);

				continue;
			}

			if($this->_elements[$i]['url']){

				$currentItemIsSelected = strpos($currentUrl, $this->_elements[$i]['url']) !== false;

				if($this->_elements[$i]['alsoSelectedIfUrl'] != ''){

					$currentItemIsSelected = $currentItemIsSelected & strpos($currentUrl, $this->_elements[$i]['alsoSelectedIfUrl']) !== false;
				}

			}else{

				$currentItemIsSelected = false;
			}

			if(!$this->hideSelectedItem || ($this->hideSelectedItem && !$currentItemIsSelected)){

				$defaultClass = ($this->_elements[$i]['class'] != '') ? ' class="'.$this->_elements[$i]['class'].'" ' : '';
				$defaultStyle = ($this->_elements[$i]['style'] != '') ? ' style="'.$this->_elements[$i]['style'].'" ' : '';

				$selectedItemClass = ($this->selectedItemClass != '') ? ' class="'.$this->selectedItemClass.' '.$this->_elements[$i]['class'].'" ' : $defaultClass;
				$selectedItemStyle = ($this->selectedItemStyle != '') ? ' style="'.$this->selectedItemStyle.' '.$this->_elements[$i]['style'].'" ' : $defaultStyle;

				$elementCss = $currentItemIsSelected ? $selectedItemClass.$selectedItemStyle : $defaultClass.$defaultStyle;

				$elementHref = (!$currentItemIsSelected && $this->_elements[$i]['url'] != '') ? ' href="'.$this->_elements[$i]['url'].'"' : '';

				$menuElement  = '<a'.$elementCss.$elementHref.'>';

				if($this->_elements[$i]['icon'] != ''){

					$menuElement .= '<img alt="" src="'.$this->_elements[$i]['icon'].'"></a>';

				}else{

					$menuElement .= $this->_elements[$i]['label'].'</a>';
				}

				array_push($generatedHtml, $menuElement);
			}
		}

		$this->_html = implode($this->_sepparator, $generatedHtml);

		return '<div '.$this->createHtmlAttributes().'>'.$this->_html.'</div>';
	}
}

?>