<?php

/**
 * TurboCommons-Php
 *
 * PHP Version 5.4
 *
 * @copyright 2015 Edertone advanced solutions (http://www.edertone.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://turbocommons.org
 */


namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * RSS feed generic utilities class
 */
class RssUtils{


	/**
	 * Generate a valid XML RSS feed string from the specified array data.
	 *
	 * @param string $url			The url where this feed can be found
	 * @param string $title			The rss feed title
	 * @param string $description	The rss feed contents resume
	 * @param string $locale		The rss feed language in a standard locales notation: en_US, es_ES, fr_FR, etc..
	 * @param string $array			The feed data. Must be an array of arrays, with each element containing: [title],[link],[description],[pubdate]
	 * @param string $encoding		The type of charset used with the generated data: utf-8 is defauls
	 *
	 * @return string The xml rss feed string ready to use on a site
	 */
	public static function generateRssFeedFromArray($url, $title, $description, $locale, $array, $encoding='utf-8'){

		// Define the xml header
		$xml  = '<?xml version="1.0" encoding="'.$encoding.'"?>';
		$xml .= '<rss version="2.0">';
		$xml .= '<channel>';

		// Define the rss doc header
		$xml .= '<title>'.htmlspecialchars($title).'</title>';
		$xml .= '<link>'.htmlspecialchars($url).'</link>';
		$xml .= '<description>'.htmlspecialchars($description).'</description>';
		$xml .= '<language>'.htmlspecialchars(strtolower(str_replace('_', '-', $locale))).'</language>';

		// Count the total feed elements
		$arrayLen = count($array);

		// Loop the received data and generate the rss feed
		for($i=0; $i<$arrayLen; $i++){

			$xml .= '<item>';
			$xml .= '<title>'.htmlspecialchars($array[$i]['title']).'</title>';
			$xml .= '<link>'.htmlspecialchars($array[$i]['link']).'</link>';
			$xml .= '<description>'.htmlspecialchars($array[$i]['description']).'</description>';
			$xml .= '<pubDate>'.htmlspecialchars(date('r', strtotime($array[$i]['pubDate']))).'</pubDate>';
			$xml .= '</item>';

		}

		$xml .= '</channel>';
		$xml .= '</rss>';

		return $xml;

	}

}

?>