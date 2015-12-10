<?php

namespace com\edertone\turboCommons\src\main\php\managers;


/**
 * A class to manage the shopping cart data
 */
class ShoppingCartManager extends BaseSingletonClass{


	/**
	 * Defines the name of the cookie that will store the cart data. It can be changed if necessary, but normally not required
	 * Note that if this needs to be changed, it must be done before any operation with this class
	 */
	public $cookieName = 'ShoppingCartManager_Data';


	/**
	 * Defines the decimal precision for the values of the cart
	 */
	public $decimalPlaces = 2;


	/**
	 * True by default. If set to true, when the same item is added two times, the units will be added to the same cart row. If set to false, adding the same item two times will generate two different cart rows.
	 */
	public $mergeItems = true;


	/**
	 * Stores mapping between a specified item id and the respective price and vat
	 */
	private $_pricesMap = array();


	/**
	 * Add an item to the cart.
	 * WARNING: Shopping cart stores the items on a cookie, so we must avoid unnecessary data to prevent the cookie from getting full. Ideally we will only define item id and units.
	 *
	 * @param Object $item An object containing the item we want to add to the cart. It may have any property we like (id, units, price, vat, name...), but the following are mandatory:<br><br>
	 * 		  id: Which must contain the item unique identifier<br>
	 * 		  units: The number of units we are adding to the cart for the specified item<br>
	 *
	 * @return Boolean True if the item was correctly added, and false if a problem happened.
	 */
	public function addItem($item){

		// Check that the received item object has all the mandatory properties
		if(!property_exists($item, 'id')){

			trigger_error('ShoppingCartManager->addItem: Item id property is mandatory', E_USER_WARNING);
			return false;
		}

		if(!property_exists($item, 'units')){

			trigger_error('ShoppingCartManager->addItem: Item units property is mandatory', E_USER_WARNING);
			return false;
		}

		// Check that the received item object properties have correct values
		if($item->id == '' || $item->id == null){

			trigger_error('ShoppingCartManager->addItem: Item id value is not correct', E_USER_WARNING);
			return false;
		}

		if(!is_numeric($item->units)){

			trigger_error('ShoppingCartManager->addItem: Item units value is not correct', E_USER_WARNING);
			return false;
		}

		// Convert the item to an associative array
		$item = SerializationUtils::classToArray($item);

		// Get the current list of cart products and add the new one
		$list = $this->getItems();

		// Define the resulting array
		$res = array();

		// Check if we are merging items or not
		if($this->mergeItems){

			$itemFound = false;

			foreach($list as $l){

				if($l['id'] == $item['id']){

					$itemFound = true;

					$item['units'] += $l['units'];

					$l = $item;
				}

				array_push($res, $l);
			}

			if(!$itemFound){

				array_push($res, $item);
			}

		}else{

			foreach($list as $l){

				array_push($res, $l);
			}

			array_push($res, $item);
		}

		// Override the current cart cookie with the new data
		CookiesUtils::setCookie($this->cookieName, SerializationUtils::arrayToJson($res));

		return true;
	}


	/**
	 * Get the data for an item existing on the shopping cart
	 *
	 * @param int $row The row of the cart where the item is located. First row starts with 0.
	 *
	 * @return Object The requested item, or null if not found
	 */
	public function getItemByRow($row){

		$list = $this->getItems();

		if(count($list) > $row){

			return $list[$row];
		}

		return null;
	}


	/**
	 * Get all the cart items list as an associative array
	 *
	 * @return array The list of elements
	 */
	public function getItems(){

		// Get the cart item list from the cookie
		$list = CookiesUtils::getCookie($this->cookieName);

		// If the list is null, create an empty JSON object
		$list = ($list == null) ? '{}' : $list;

		// Serialize the JSON list object to a PHP object and return it
		return json_decode($list, true);
	}


	/**
	 * Delete from the cart the item that is located at the specified row
	 *
	 * @param int $row The row number where the item we want to delete is located. First row starts at 0
	 *
	 * @return boolean True if the item was correctly removed or false instead
	 */
	public function removeItemByRow($row){

		if(!is_numeric($row)){

			throw new Exception('ShoppingCartManager::removeItemByRow Specified row ('.$row.') is not valid');

			return false;
		}

		// Get the list of items and remove the specified row
		$list = $this->getItems();

		$listCount = count($list);

		$res = array();

		for($i = 0; $i<$listCount; $i++){

			if($i != $row){

				array_push($res, $list[$i]);
			}
		}

		// Override the current cart cookie with the new data
		CookiesUtils::setCookie($this->cookieName, SerializationUtils::arrayToJson($res));

		return true;
	}


	/**
	 * Delete from the cart all the rows that contain the item specified by the given id. If multiple rows contain the same item, all of them will be deleted.
	 *
	 * @param mixed $id A value representing the identifier for the item to remove
	 *
	 * @return boolean True if the item was correctly removed or false instead
	 */
	public function removeItemById($id){

		// TODO

	}


	/**
	 * Totally clears the shopping cart contents
	 *
	 * @return boolean True if the items were correctly removed or false instead
	 */
	public function removeAll(){

		CookiesUtils::deleteCookie($this->cookieName);

		return true;

	}


	/**
	 * Define the price for a specific cart id.
	 * This is very important, cause the shopping cart does not store the products prices, or in case the prices are stored, we cannot rely on these values.
	 * This is cause the cart data is stored on a cookie, so prices may have been modified by malicious user.
	 * Knowing this, before using the cart price manipulation methods, we must associate each cart stored product id with its real price and or vat.
	 * The cookie that contains the cart data will not be modified by this method. Values will be only stored on memory.
	 *
	 * @param mixed $id Identifier for the shopping cart item to which we are associating a price and or vat
	 * @param float $price The unit price for the specified item, without any vat or taxes
	 * @param float $vat The vat that is applied to the specified item (if any)
	 *
	 * @return void
	*/
	public function setPriceMapping($id, $price, $vat = 0){

		if(!is_numeric($price)){

			return;
		}

		if($vat != 0 && !is_numeric($vat)){

			return;
		}

		// Get the current list of cart products
		$list = $this->getItems();

		foreach($list as $item){

			if($item['id'] == $id){

				$this->_pricesMap[$id]['price'] = $price;
				$this->_pricesMap[$id]['vat'] = $vat;

				return;
			}
		}

		trigger_error('ShoppingCartManager::setPriceMapping Specified id not found on shopping cart', E_USER_WARNING);
	}


	/**
	 * Get the total row price, taking into consideration all the item units on the specified row
	 *
	 * @param int $row The row where the totals we want to calculate are located
	 *
	 * @return float The calculated price, rounded to the decimals specified by $this->decimalPlaces
	 */
	public function getRowTotalPrice($row){

		$item = $this->getItemByRow($row);
		$id = $item['id'];

		if(!isset($this->_pricesMap[$id])){

			trigger_error('ShoppingCartManager::getItemTotalPrice : PriceMap not defined for item with id: '.$id.'. Use ShoppingCartManager::setPriceMapping method', E_USER_ERROR);
		}

		if(isset($this->_pricesMap[$id]['price'])){

			$amount = $item['units'] * $this->_pricesMap[$id]['price'];

			return round($amount, $this->decimalPlaces);
		}

		return 0;
	}


	/**
	 * Get the cost of the taxes for the specified cart row
	 *
	 * @param int $row The row where the totals we want to calculate are located
	 *
	 * @return float The calculated price
	 */
	public function getRowTotalTaxes($row){

		$item = $this->getItemByRow($row);
		$id = $item['id'];

		if(!isset($this->_pricesMap[$id])){

			trigger_error('ShoppingCartManager::getItemTotalTaxes : PriceMap not defined for item with id: '.$id.'. Use ShoppingCartManager::setPriceMapping method', E_USER_ERROR);
		}

		if(isset($this->_pricesMap[$id]['price']) && isset($this->_pricesMap[$id]['vat'])){

			$amount = $item['units'] * ($this->_pricesMap[$id]['price'] * ($this->_pricesMap[$id]['vat'] / 100));

			return round($amount, $this->decimalPlaces);
		}
	}


	/**
	 * Get the total row price INCLUDING TAXES, taking into consideration all the item units currently on the specified row
	*
	* @param int $row The row where the totals we want to calculate are located
	*
	* @return float The calculated price
	*/
	public function getRowTotalPriceWithTaxes($row){

		$amount = $this->getRowTotalPrice($row) + $this->getRowTotalTaxes($row);

		return round($amount, $this->decimalPlaces);
	}


	/**
	 * Calculate the total price for the currently existing cart items, WTHOUT applied taxes
	 *
	 * @return float The calculated price
	 */
	public function getCartTotalPrice(){

		$list = $this->getItems();

		$listCount = count($list);

		$amount = 0;

		for($i = 0; $i < $listCount; $i++){

			$amount += $this->getRowTotalPrice($i);
		}

		return max(array(0, round($amount, $this->decimalPlaces)));
	}


	/**
	 * Calculate the total taxes for the currently existing cart items
	*
	* @return float The calculated taxes
	*/
	public function getCartTotalTaxes(){

		$list = $this->getItems();

		$listCount = count($list);

		$amount = 0;

		for($i = 0; $i < $listCount; $i++){

			$amount += $this->getRowTotalTaxes($i);
		}

		// Return the total amount
		return max(array(0, round($amount, $this->decimalPlaces)));
	}


	/**
	 * Calculate the total price for the currently existing cart items, INCLUDING taxes
	*
	* @return float The calculated total price
	*/
	public function getCartTotalPriceWithTaxes(){

		// Calculate the total price
		$amount = $this->getCartTotalPrice() + $this->getCartTotalTaxes();

		return max(array(0, round($amount, $this->decimalPlaces)));
	}
}

?>