<?php

namespace com\edertone\turboCommons\src\main\php\view\elements;


/** Html Social share */
class HTMLSocialShare extends HTMLElementBase {


	/** The url to share with this control */
	private $_shareUrl = '';

	/** The locale for the share elements (if appliable) */
	private $_locale = '';

	/** The language for the share elements (if appliable) */
	private $_language = '';

	/** The alignment of the component buttons : vertical or horizontal */
	private $_align;

	/** Stores the code for the JS scripts that may be required for the generated buttons */
	private $_scripts = '';


	/**
	 * Component that shows a block of the specified social networks share buttons
	 *
	 * @param string $shareUrl The url that will be shared by all the buttons on this component
	 * @param string $locale The language for the buttons as a standard locale string like 'en_US' (if appliable)
	 * @param string $align The alignment of the component buttons: horizontal or vertical.
	 * @param string $class The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($shareUrl, $locale, $align = 'horizontal', $class = '', $style = ''){

		$this->_shareUrl = $shareUrl;

		// Define locale and language
		$this->_locale = $locale;
		$this->_language = substr($locale, 0, 2);

		$this->_align = $align;
		$this->class = $class;
		$this->style = $style;
	}


	/**
	 * Add a Facebook Like button to the container
	 *
	 * @param string $type The button type: standard, box_count, button_count, button
	 * @param boolean $includeShare	Include a share button or not. Not by default.
	 * @param string $class	The css class to use with this control. None by default
	 * @param string $style	The css style to use with this control. None by default
	 *
	 * @return void
	 */
	public function addFacebookButton($type = 'standard', $includeShare = false, $class = '', $style = ''){

		// Set the button HTML
		$this->_html .= '<div style="'.($this->_align == 'vertical' ? 'display: block;' : 'float:left;').$style.'" class="fb-like '.$class.'" data-href="'.$this->_shareUrl.'" data-layout="'.$type.'" data-action="like" data-show-faces="true" data-share="'.($includeShare ? 'true' : 'false').'"></div>';

		// Set the button necessary script
		$this->_scripts .= '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/'.$this->_locale.'/all.js#xfbml=1&appId=173166156062509";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>';
	}


	/**
	 * Add a Twitter share button to the container
	 *
	 * @param boolean $showCount Show or hide the share counter. False by default
	 * @param boolean $largeButton Show a large button or the normal one. False by default
	 * @param string $class	The css class to use with this button div container. None by default
	 * @param string $style	The css style to use with this button div container. None by default
	 *
	 * @return void
	 */
	public function addTwitterButton($showCount = false, $largeButton = false, $class = '', $style = ''){

		$dataCount = (!$showCount) ? 'data-count="none"' : '';

		$dataSize = ($largeButton) ? 'data-size="large"' : '';

		$this->_html .= '<div class="'.$class.'" style="'.($this->_align == 'vertical' ? 'display: block;' : 'float:left;').$style.'"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$this->_shareUrl.'" data-lang="'.$this->_language.'" '.$dataSize.' '.$dataCount.'>Tweet</a></div>';

		$this->_scripts .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
	}


	/**
	 * Add a Google plus share button to the container
	 *
	 * @param string $type The button type: none for a simple button, bubble for a button + counter, vertical-bubble for a horizontal button + counter, integrated for a button + a lot of text
	 * @param string $height The size of the button: small, medium or large
	 * @param string $class	The css class to use with this button div container. None by default
	 * @param string $style	The css style to use with this button div container. None by default
	 *
	 * @return void
	 */
	public function addGooglePlusButton($type = 'bubble', $height = 'medium', $class = '', $style = ''){

		$dataHeight = '';

		if($height == 'small'){
			$dataHeight = 'data-height="15"';
		}

		if($height == 'large'){
			$dataHeight = 'data-height="24"';
		}

		$anno = ($type == 'integrated') ? '' : 'data-annotation="'.$type.'"';

		$this->_html .= '<div style="'.($this->_align == 'vertical' ? 'display: block;' : 'float:left;').$style.'" class="g-plus '.$class.'" data-action="share" '.$anno.' '.$dataHeight.'></div>';

		$this->_scripts .= "<script type=\"text/javascript\">window.___gcfg = {lang: '".$this->_language."'};(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/platform.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>";
	}


	/**
	 * Get the component's HTML code
	 *
	 * @return void
	 */
	public function getHTML(){

		return '<div '.$this->createHtmlAttributes().'>'.$this->_html.'</div>'.$this->_scripts;
	}
}

?>