<?php

namespace TurboFramework\TurboCommons;


/**
 * Responsive html grid component
 */
class HTMLGrid{

	private $_className = '';
	private $_columns = 0;
	private $_rows = 0;
	private $_html = '';
	private $_hMargin = 0;
	private $_vMargin = 0;
	private $_columnWidth = 0;
	private $_rowHeight = 0;
	private $_cellIndex = 0;

	// TODO: aquest component ha d'extendre HTMLElementBase i funcionar amb les mateixes regles. Pot ser que acabi desapareixent ja que els elements no haurien de tenir valors css per defecte

	/**
	 * Generate a grid that you can add a X number of HTML customized cells. You can choose the number of columns and the margins between the cells.
	 * IE8 won't generate margins because of the CSS compatibility.
	 *
	 * @param int $columns The number of grid columns
	 * @param string $columnWidth The column width in px or %. Default is 200px
	 * @param string $rowHeight The row height in px or %. Default is 200px
	 * @param string $hMargin	The horizontal margin between the grid elements in px or %. Default is 10px
	 * @param string $vMargin	The vertical margin between the grid elements in px or %. Default is 10px
	 * @param string $className The grid container class name. Each generated cell will have the same class name but terminated in "Cell", and
	 * also another class terminated in "CellX" where X is the generated cell index. Each cell has an index starting from 1 to X. Default is
	 * "HTMLGrid". For example, for the 7th cell will be: "HTMLGridCell" "HTMLGridCell7"
	 *
	 * @return void
	 */
	public function HTMLGrid($columns, $columnWidth = '200px', $rowHeight = '200px', $hMargin = '10px', $vMargin = '10px', $className = 'HTMLGrid'){

		$this->_className = $className;
		$this->_columns = $columns;
		$this->_columnWidth = $columnWidth;
		$this->_rowHeight = $rowHeight;
		$this->_hMargin = $hMargin;
		$this->_vMargin = $vMargin;

		$this->_html .= '<div class="'.$className.'" style="float: left;">';

	}


	/**
	 * Add a cell to the grid
	 *
	 * @param string $htmlContent	The cell HTML content
	 *
	 * @return void
	 */
	public function addCell($htmlContent){

		// Calculate cell column index
		$columnIndex = $this->_cellIndex % $this->_columns;

		// Increase the row
		if($columnIndex == 0){
			$this->_rows++;
		}

		// Increase the item index
		$this->_cellIndex++;

		// Generate the margins
		$leftMargin = $columnIndex > 0 ? 'margin-left: '.$this->_hMargin.';' : '';
		$topMargin = $this->_rows > 1 ? 'margin-top: '.$this->_vMargin.';' : '';

		// Add TD content
		$this->_html .= '<div class="'.$this->_className.'Cell '.$this->_className.'Cell'.$this->_cellIndex.'" style="float: left; overflow: hidden; ';
		$this->_html .= 'width: '.$this->_columnWidth.'; height: '.$this->_rowHeight.'; '.$leftMargin.' '.$topMargin.'">'.$htmlContent.'</div>';

	}


	/**
	 * Get the number of added cells
	 *
	 * @return number
	 */
	public function countCells(){

		return $this->_cellIndex;
	}


	/**
	 * Get the current number of rows
	 *
	 * @return number
	 */
	public function countRows(){

		return $this->_rows;
	}


	/**
	 * Echoes the grid HTML current code
	 *
	 * @return void
	 */
	public function echoHTML(){

		echo $this->_html.'</div>';

	}
}

?>