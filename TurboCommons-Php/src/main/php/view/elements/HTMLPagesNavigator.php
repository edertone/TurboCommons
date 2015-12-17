<?php

namespace com\edertone\turboCommons\src\main\php\view\elements;


/** Pages navigator */
class HTMLPagesNavigator extends HTMLElementBase{

	/** The page that is currently selected on the control */
	public $currentPage = 0;

	/** The total amount of pages that exist */
	public $totalPages = 0;

	/** 10 by default. Defines the amount of pages that will be displayed by the control. If the total amout of pages exceeds this value, the control will be visually compressed */
	public $maxDisplayedPages = 10;

	/** Stores the html content that will be shown on each page button (which is created as <a><div>$pageHtml</div></a>). Button won't be shown if this value is empty '' */
	public $pageHtml = '<p>%REPLACE_PAGE_NUMBER%</p>';

	/** The css class that will be assigned to the div of each (unselected) page buttons */
	public $pageClass = '';

	/** The css class that will be assigned to the div of the selected page */
	public $selectedPageClass = '';

	/** Stores the html content that will be shown on the 'previous page' button (which is created as <a><div>$prevPageHtml</div></a>). Button won't be shown if this value is empty '' */
	public $prevPageHtml = '<p>&lt;</p>';

	/** The css class that will be assigned to the div of the prev page button */
	public $prevPageClass = '';

	/** Stores the html content that will be shown on the 'next page' button (which is created as <a><div>$prevPageHtml</div></a>). Button won't be shown if this value is empty '' */
	public $nextPageHtml = '<p>&gt;</p>';

	/** The css class that will be assigned to the div of the next page button */
	public $nextPageClass = '';

	/** Stores the html content that will be shown on the 'first page' button (which is created as <a><div>$firstPageHtml</div></a>). Button won't be shown if this value is empty '' */
	public $firstPageHtml = '<p>&lt;&lt;</p>';

	/** The css class that will be assigned to the div of  the first page button */
	public $firstPageClass = '';

	/** Stores the html content that will be shown on the 'last page' button (which is created as <a><div>$lastPageHtml</div></a>). Button won't be shown if this value is empty '' */
	public $lastPageHtml = '<p>&gt;&gt;</p>';

	/** The css class that will be assigned to the div of  the last page button */
	public $lastPageClass = '';

	/** Stores the html content that will be shown on the 'page sepparator' button (which is created as <a><div>$pageSepparatorHtml</div></a>). Button won't be shown if this value is empty '' */
	public $pageSepparatorHtml = '<p>..</p>';

	/** The css class that will be assigned to the div of  the page sepparator element that will appear when number of total pages does not fit the max allowed */
	public $pageSepparatorClass = '';

	/**
	 * This is used to allow us showing a different page number on the control that the real one by adding the specified increment
	 * to the real value. This is used to fix differences between how pages are represented visually by the control and their real internal values.
	 * It is very common to use pagination from 0 to N pages, but showing them to the user as 1 to N+1. This property will let us fix this easily. (The value by default is 1)
	 */
	public $displayedPageIncrement = 1;


	/** See this property description on the class constructor */
	private $_urlTemplate = '';


	/**
	 * Control that generates a navigator for paginated views. Note that it has no style applied by default.
	 *
	 * TODO: cal posar aqui una serie d'estils basics d'exemple sobre els que sigui facil construir l'aspecte visal de component
	 *
	 * @param int $currentPage The page that is currently set as selected on the control
	 * @param int $totalPages The total number of pages that are available
	 * @param int $urlTemplate  Stores a generic url that will be used to generate all the valid page urls. The string '%REPLACE_PAGE_NUMBER%' must exist on the template url,
	 * as it will be replaced by the correct value on each page. Another optional string can be used with this url template, but normally not required: '%REPLACE_TOTAL_PAGES%' for the total number of pages.<br><br>
	 * examples: App::getUrlToView(App::getCurrentView(), '%REPLACE_PAGE_NUMBER%')  or more elaborated:  App::getUrlToView(App::getCurrentView(), array(App::getParam(1), '%REPLACE_PAGE_NUMBER%', App::getParam(3)))
	 * @param string $class	The css class to use with this control.
	 * @param string $style	The css style to use with this control. None by default
	 */
	public function __construct($currentPage, $totalPages, $urlTemplate, $class, $style = ''){

		$this->class = $class;
		$this->style = $style;
		$this->currentPage = ($currentPage == '' || $currentPage < 0) ? 0 : $currentPage;
		$this->totalPages = $totalPages;
		$this->_urlTemplate = $urlTemplate;
	}


	/**
	 * Get the component's HTML code
	 *
	 * @return void
	 */
	public function getHTML(){

		if(!is_numeric($this->totalPages)|| $this->totalPages < 0 || $this->totalPages === ''){

			trigger_error('HTMLPagesNavigator total pages value is not correct.', E_USER_WARNING);
			return;
		}

		// If there's only one or less pages, the control won't be shown
		if ($this->totalPages <= 1){

			return '<!-- HTMLPagesNavigator: NO PAGES TO SHOW -->';
		}

		// add the first page button if necessary
		if($this->firstPageHtml != ''){

			$firstPageClass = ($this->currentPage > 0) ? $this->firstPageClass : $this->selectedPageClass;
			$firstPageHref = ($this->currentPage > 0) ? ' href="'.$this->_generatePageUrl($this->_urlTemplate, 0).'"' : '';

			$this->_html .= '<a'.$firstPageHref.'><div '.($firstPageClass != '' ? ' class="'.$firstPageClass.'"' : '').'>'.$this->firstPageHtml.'</div></a>';
		}

		// add the prev page button if necessary
		if($this->prevPageHtml != ''){

			$prevPageClass = ($this->currentPage > 0) ? $this->prevPageClass : $this->selectedPageClass;
			$prevPageHref = ($this->currentPage > 0) ? ' href="'.$this->_generatePageUrl($this->_urlTemplate, $this->currentPage - 1).'"' : '';

			$this->_html .= '<a'.$prevPageHref.'><div '.($prevPageClass != '' ? ' class="'.$prevPageClass.'"' : '').'>'.$this->prevPageHtml.'</div></a>';
		}

		// Generate the page buttons
		if($this->totalPages <= $this->maxDisplayedPages){

			for($i = 0; $i < $this->totalPages; $i++){

				$this->_html .= $this->_generatePageHtml($i);
			}

		}else{

			// We must generate the 3 different blocks to show the first, middle and last pages.
			// Note that first and last blocks will get 1 less page each, as a .. symbol will appear.
			$blocks = $this->maxDisplayedPages / 3;

			$firstsBlock = floor($blocks)- 1;
			$lastsBlock = floor($blocks) - 1;
			$middleBlock = $this->maxDisplayedPages - $firstsBlock - $lastsBlock - 2;

			// Generate the firsts pages block
			for($i = 0; $i < $firstsBlock; $i++){

				$this->_html .= $this->_generatePageHtml($i);
			}

			// Generate the middle pages block
			$middleReachesEnd = false;
			$lastsStart = $this->totalPages - $lastsBlock;

			if($this->currentPage >= $firstsBlock && $this->currentPage < $this->totalPages - $lastsBlock){

				$middleStart = max(array($firstsBlock, ceil($this->currentPage - $middleBlock / 2)));

				if($middleStart + $middleBlock >= $this->totalPages - $lastsBlock - 1){

					$middleReachesEnd = true;
					$lastsStart --;

					$middleStart = $this->totalPages - $middleBlock - $lastsBlock - 1;
				}

			}else{

				$middleStart = ceil($this->totalPages / 2 - $middleBlock / 2);
			}

			if($middleStart == $firstsBlock){

				$middleBlock ++;

			}else{

				$this->_html .= '<a><div'.($this->pageSepparatorClass != '' ? ' class="'.$this->pageSepparatorClass.'"' : '').'>'.$this->pageSepparatorHtml.'</div></a>';
			}

			for($i = $middleStart; $i < $middleStart + $middleBlock; $i++){

				$this->_html .= $this->_generatePageHtml($i);
			}

			// Generate the lasts pages block
			if(!$middleReachesEnd){

				$this->_html .= '<a><div'.($this->pageSepparatorClass != '' ? ' class="'.$this->pageSepparatorClass.'"' : '').'>'.$this->pageSepparatorHtml.'</div></a>';
			}

			for($i = $lastsStart; $i < $this->totalPages; $i++){

				$this->_html .= $this->_generatePageHtml($i);
			}

		}

		// add the next page button if necessary
		if($this->nextPageHtml != ''){

			$nextPageClass = ($this->currentPage < $this->totalPages - 1) ? $this->nextPageClass : $this->selectedPageClass;
			$nextPageHref = ($this->currentPage < $this->totalPages - 1) ? ' href="'.$this->_generatePageUrl($this->_urlTemplate, $this->currentPage + 1).'"' : '';

			$this->_html .= '<a'.$nextPageHref.'><div '.($nextPageClass != '' ?  ' class="'.$nextPageClass.'"' : '').'>'.$this->nextPageHtml.'</div></a>';
		}

		// add the last page button if necessary
		if($this->lastPageHtml != ''){

			$lastPageClass = ($this->currentPage < $this->totalPages - 1) ? $this->lastPageClass : $this->selectedPageClass;
			$lastPageHref = ($this->currentPage < $this->totalPages - 1) ? ' href="'.$this->_generatePageUrl($this->_urlTemplate, $this->totalPages - 1).'"' : '';

			$this->_html .= '<a'.$lastPageHref.'><div '.($lastPageClass != '' ?  ' class="'.$lastPageClass.'"' : '').'>'.$this->lastPageHtml.'</div></a>';
		}

		return '<div '.$this->createHtmlAttributes().'>'.$this->_html.'</div>';
	}


	/**
	 * Auxiliary method to generate the html for a page button
	 *
	 * @param int $i The page index to generate its button
	 *
	 * @return string The generated HTML for the page
	 */
	private function _generatePageHtml($i){

		$detectedPageClass = ($i == $this->currentPage) ? $this->selectedPageClass : $this->pageClass;
		$detectedPageHref = ($i == $this->currentPage) ? '' : ' href="'.$this->_generatePageUrl($this->_urlTemplate, $i).'"';

		return '<a'.$detectedPageHref.'><div'.($detectedPageClass != '' ? ' class="'.$detectedPageClass.'"' : '').'>'.str_replace('%REPLACE_PAGE_NUMBER%', $i + $this->displayedPageIncrement, $this->pageHtml).'</div></a>';
	}


	/**
	 * Auxiliary method that is used to generate a url that points to a correct page number, by replacing it on the providen template url.
	 *
	 * @param string $url The template url that contains the string %REPLACE_PAGE_NUMBER% or %REPLACE_TOTAL_PAGES% to be replaced by the correct value
	 * @param int $page The page where the url will point to
	 * @param int $totalPages The total number of pages. Normally not used, default value is -1
	 *
	 * @return string The url that points to the specified page
	 */
	private function _generatePageUrl($url, $page, $totalPages = -1){

		$url = str_replace('%REPLACE_PAGE_NUMBER%', $page, $url);

		if($totalPages > 0){

			$url = str_replace('%REPLACE_TOTAL_PAGES%', $page, $url);
		}

		return$url;
	}
}

?>