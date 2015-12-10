<?php

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Video Utilities
 * TODO: Aquesta classe es un desastre. Cal convertirla en dos components HTML diferents:
 * Un HTMLVideoYoutube que encapsuli totes les funcionalitats relatives al youtube de forma elegant
 * Un HTMLVideoVimeo que encapsuli totes les funcionalitats relatives al vimeo de forma elegant
 *
 * Els dos components hauran de seguir els conceptes del nostre sistema de components, extenent el basic, etc...
 */
class VideoUtils{


	/**
	 * Get the YouTube embed URL from a normal Youtube url
	 *
	 * @param string $url	a valid YouTube url
	 * @param string $autoPlay	auto play the video when loading the url
	 *
	 * @return string the YouTube embed url like http://www.youtube.com/embed/code
	 */
	public static function youTubeGetEmbedUrlFromUrl($url, $autoPlay = true){

		if(!self::youTubeIsValidUrl($url)){
			return '';
		}

		$videoId = self::youTubeGetVideoIdFromUrl($url);

		return 'http://www.youtube.com/embed/'.$videoId.($autoPlay ? '?autoplay=1' : '');
	}


	/**
	 * Given the youtube video identifier code (normally something like m7sCUaPchFM), the method will return a valid youtube url to load the video
	 *
	 * @param string $videoId	The youtube video id
	 *
	 * @return string
	 */
	public static function youTubeGetUrlFromVideoId($videoId){

		if(!isset($videoId)) {
			return '';
		}

		if($videoId == '') {
			return '';
		}

		return 'http://www.youtube.com/watch?v='.$videoId;

	}


	/**
	 * Get the video identifier code (normally something like m7sCUaPchFM) from a specified youtube valid url
	 *
	 * @param string $url		A valid youtube url
	 *
	 * @return string
	 */
	public static function youTubeGetVideoIdFromUrl($url){

		if(!self::youTubeIsValidUrl($url)){
			return '';
		}

		if(strstr($url, 'youtube.com/watch?v=') !== false) {

			$a = explode('youtube.com/watch?v=', $url);

			if(count($a) > 1){
				return $a[1];
			}
		}

		if(strstr($url, 'youtu.be/') !== false) {

			$a = explode('youtu.be/', $url);

			if(count($a) > 1){
				return $a[1];
			}
		}

		return '';

	}



	/**
	 * Generate a YouTube video embed code as an HTML iframe from a YouTube video URL
	 *
	 * @param string $url		YouTube video URL. Example:	http://youtu.be/wiMVJfAwUAs | http://www.youtube.com/watch?v=wiMVJfAwUAs
	 * @param string $width		The iframe width. 420 by default
	 * @param string $height	The iframe height. 315 by default
	 * @param string $frameBorder	The iframe border size in pixels. 0 by default
	 * @param boolean $allowFullScreen	Allow full screen option. True by default
	 * @param string $customCode Optional custom code to insert on the iframe tag. Normally some style or other customized attributes. Nothing by default
	 *
	 * @return string The html generated code for the iframe that will contain the embedded video
	 */
	public static function youTubeGetEmbedCodeFromUrl($url, $width = '420', $height = '315', $frameBorder = '0', $allowFullScreen = true, $customCode = ''){

		// TODO: això no és responsive.
		// Pot tenir tot el sentit del mon convertir aquesta classe en un component HTML que es digui 'HTMLVideo' i treballar més amb l'estil
		// modern que fem servir ara

		if(!self::youTubeIsValidUrl($url)){
			return '';
		}

		$videoId = self::youTubeGetVideoIdFromUrl($url);

		if($width != ''){

			$width = ' width="'.$width.'" ';
		}

		if($height != ''){

			$height = ' height="'.$height.'" ';
		}

		$result = '<iframe '.$customCode.' src="//www.youtube.com/embed/'.$videoId.'"'.$width.$height.'" ';
		$result .= 'frameborder="'.$frameBorder.'" ';
		$result .= $allowFullScreen ? 'webkitallowfullscreen mozallowfullscreen allowfullscreen ' : '';
		$result .= '></iframe>';

		return $result;

	}


	/**
	 * Verify if a given url is a valid youtube video url
	 *
	 * @param string $url	a youtube video url
	 *
	 * @return boolean
	 */
	public static function youTubeIsValidUrl($url){

		if(strstr($url, 'youtube.com/watch?v=') === false){

			if( strstr($url, 'youtu.be') === false){

				return false;
			}
		}

		return true;

	}


	/**
	 * Given a valid youtube embed code (normally an HTML fragment containing an iframe), the method will give a valid youtube url to load the video
	 *
	 * @param unknown $embedCode A valid youtube embed code where the video url will be extracted
	 *
	 * @return string The video url that is extracted from the specified embed code
	 */
	public static function youTubeGetUrlFromEmbedCode($embedCode){

		// TODO: traduir de la versio php

	}


	/**
	 * Get an URL that loads the video thumbnail for a specified youtube url.
	 *
	 * @param string $url A valid youtube video url
	 *
	 * @return string An url where the youtube thumb can be found
	 */
	public static function youTubeGetThumbFromUrl($url) {

		// TODO: traduir de la versio php
	}


	/**
	 * Get the Vimeo embed URL from a normal Vimeo  url
	 *
	 * @param string $url	a valid YouTube url
	 * @param string $autoPlay	auto play the video when loading the url
	 *
	 * @return string the Vimeo embed url like http://player.vimeo.com/video/code
	 */
	public static function vimeoGetEmbedUrlFromUrl($url, $autoPlay = true){

		if(!self::vimeoIsValidUrl($url)){
			return '';
		}

		$videoId = self::vimeoGetVideoIdFromUrl($url);

		return 'http://player.vimeo.com/video/'.$videoId.($autoPlay ? '?autoplay=1' : '');
	}


	/**
	 * Given the vimeo video identifier code (normally something like m7sCUaPchFM), the method will return a valid vimeo url to load the video
	 *
	 * @param string $videoId	The youtube video id
	 *
	 * @return string
	 */
	public static function vimeoGetUrlFromVideoId($videoId){

		if(!isset($videoId)) {
			return '';
		}

		return 'http://vimeo.com/'.$videoId;

	}


	/**
	 * Get the video identifier code (normally something like 74645125) from a specified youtube valid url
	 *
	 * @param string $url	A valid vimeo url
	 *
	 * @return string
	 */
	public static function vimeoGetVideoIdFromUrl($url){

		if(!self::vimeoIsValidUrl($url)) {
			return '';
		}

		$a = explode('imeo.com/', $url);

		if(count($a) > 1){
			return $a[1];
		}else{
			return '';
		}

	}


	/**
	 * Generate a Vimeo video embed code as an HTML iframe from a Vimeo video URL
	 *
	 * @param string $url		Vimeo video URL. Example: http://vimeo.com/74015908
	 * @param string $width 	The iframe width. 420 by default
	 * @param string $height	The iframe height. 315 by default
	 * @param string $frameBorder	The iframe border size in pixels. 0 by default
	 * @param boolean $allowFullScreen	Allow full screen option. True by default
	 * @param string $customCode Optional custom code to define on the iframe
	 *
	 * @return string
	 */
	public static function vimeoGetEmbedCodeFromUrl($url, $width = '', $height = '', $frameBorder = '', $allowFullScreen = true, $customCode = ''){
		// TODO: Aquest metode cal revisarlo amb el seu equivalent de youtube que esta més evolucionat
		if(!self::vimeoIsValidUrl($url)){
			return '';
		}

		$videoId = self::vimeoGetVideoIdFromUrl($url);

		$result = '<iframe '.$customCode.' src="//player.vimeo.com/video/'.$videoId.'?badge=0" width="'.$width.'" height="'.$height.'" ';
		$result .= 'frameborder="'.$frameBorder.'" ';
		$result .= $allowFullScreen ? 'webkitallowfullscreen mozallowfullscreen allowfullscreen ' : '';
		$result .= '></iframe>';

		return $result;

	}


	/**
	 * Verify if a given url is a valid vimeo video
	 *
	 * @param string $url	a vimeo video url
	 *
	 * @return boolean
	 */
	public static function vimeoIsValidUrl($url){

		if(strstr($url, 'vimeo.com/') === false) {

			return false;
		}

		return true;

	}


	/**
	 * Get an URL that loads the video thumbnail for a specified youtube url.
	 *
	 * @param string $url A valid vimeo url
	 * @return string The link to the specified video thumb
	 */
	public static function vimeoGetThumbFromUrl($url) {

		// TODO traduir de la versio flex

		return '';

	}


	/**
	 * Try to detect the type of specified video and get their related thumbnail
	 *
	 * @param unknown $url A valid vimeo url
	 * @return string The link to the specified video thumb
	 */
	public static function getThumbFromUrl($url) {

		// TODO traduir de la versio flex

		return '';

	}


	/**
	 * Get a YouTube or Vimeo embed code from a YouTube or a Vimeo video URL
	 *
	 * @param string $url		Youtube or Vimeo video URL. Example: http://youtu.be/wiMVJfAwUAs | http://vimeo.com/74015908
	 * @param string $width		The iframe width. 420 by default
	 * @param string $height	The iframe height. 315 by default
	 * @param string $frameBorder	The iframe border size in pixels. 0 by default
	 * @param boolean $allowFullScreen	Allow full screen option. True by default
	 * @param string $customCode Optional custom code to define on the iframe
	 *
	 * @return string
	 */
	public static function getEmbedCodeFromUrl($url, $width = '', $height = '', $frameBorder = '', $allowFullScreen = true, $customCode = ''){

		if($url == null){
			error_log('The URL is not defined.');
			return '';
		}

		if(self::youTubeIsValidUrl($url)){
			return self::youTubeGetEmbedCodeFromUrl($url, $width, $height, $frameBorder, $allowFullScreen, $customCode);
		}

		if(self::vimeoIsValidUrl($url)){
			return self::vimeoGetEmbedCodeFromUrl($url, $width, $height, $frameBorder, $allowFullScreen, $customCode);
		}

		error_log('The URL is not valid.');
		return '';

	}


	/**
	 * Get a YouTube or Vimeo embed url
	 *
	 * @param string $url Youtube or Vimeo video URL. Example: http://youtu.be/wiMVJfAwUAs | http://vimeo.com/74015908
	 * @param string $autoPlay	auto play the video when loading the url
	 *
	 * @return string	the Vimeo or Youtube embed url like http://player.vimeo.com/video/code or http://www.youtube.com/embed/code
	 */
	public static function getEmbedUrlFromUrl($url, $autoPlay = true){

		if($url == null){
			error_log('The URL is not defined.');
			return '';
		}

		if(self::youTubeIsValidUrl($url)){
			return self::youTubeGetEmbedUrlFromUrl($url, $autoPlay);
		}

		if(self::vimeoIsValidUrl($url)){
			return self::vimeoGetEmbedUrlFromUrl($url, $autoPlay);
		}

		error_log('The URL is not valid.');
		return '';

	}

}

?>