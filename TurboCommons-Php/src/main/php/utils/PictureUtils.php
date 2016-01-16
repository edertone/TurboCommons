<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\main\php\utils;


/**
 * Image manipulation utils
 */
class PictureUtils{


	/**
	 * Method that generates a thumbnail from the specified picture binary data.
	 * Requires GD php extension enabled to work.
	 *
	 * @param string $source The binary string containing the source picture
	 * @param int $width The thumbnail destination width (If empty (''), resize mode will become 'fit' automatically with a fixed height)
	 * @param int $height The thumbnail destination height (If empty (''), resize mode will become 'fit' automatically with a fixed width)
	 * @param string $destination The path where thumbnail will be saved (including the filename). If not specified, thumb won't be saved to disk
	 * @param string $mode The thumb resize and scale mode:<br><br>
	 * - stretch: Generate the thumb by distorting it if necessary to fill the specified destination size.<br>
	 * - fit: Resize the source to fit the specified width and height, but mantaining aspect ratio. This may lead to a thumb with different size than the desired one.<br>
	 * - crop: (The default one) Resize the thumb to fit the full target size by cutting image sides if necessary.<br>
	 * - pad: Same as fit but painting the empty spaces of the thumb with the specified bg color to make sure the full destination size is obtained.
	 * @param number $quality The jpg quality of the generated thumb, from 0 to 100. 87 by default
	 * @param string $bgColor The thumb background color as an HEX string. Only used with the pad scale mode. 000000 by default (black)
	 * @param string $focalCenter The point of the original picture that retains the maximum interest (for example 32x100), so when cropping, the thumb will be centered as much as possible in relation to this point. (Optional and only used with crop mode).
	 *
	 * @return resource The generated thumb image GD resource, so we can manipulate it directly in memory, output it to the browser with imagejpeg($returnedResource) or store it in a binary string variable with ob_start(); imagejpeg($returnedResource); $binaryString = ob_get_contents(); ob_end_flush();
	 */
	public static function thumbnailGenerate($source, $width, $height, $destination = '', $mode = 'crop', $quality = 87, $bgColor = '000000', $focalCenter = '') {

		// GD extension must be enabled
		if(!extension_loaded('gd')) {

			trigger_error('GD library for php is not installed or enabled', E_USER_ERROR);
			die();
		}

		// By default, stretch mode: resize and distort the image if necessary to fill the full destination size
		$destX = 0;
		$destY = 0;
		$thumbWidth = $resizeWidth = $width;
		$thumbHeight = $resizeHeight = $height;
		$sourceImage = imagecreatefromstring($source);
		$sourceWidth = imagesx($sourceImage);
		$sourceHeight = imagesy($sourceImage);
		$xScale = $width / $sourceWidth;
		$yScale = $height / $sourceHeight;

		// When setting only one of the two thumb dimensions, the 'fit' resize mode is automatically used
		if($width == '' || $height == ''){

			$mode = 'fit';
		}

		// Crop mode: resize the thumb to fit the full target size by cutting image sides if necessary
		if($mode == 'crop'){

			// TODO: Cal controlar el focal center si el parametre te algun valor.

			if($xScale < $yScale){

				$resizeHeight = ceil($sourceHeight * $yScale);
				$resizeWidth = ceil($sourceWidth * $yScale) + 2;
				$destX = -1 - floor($resizeWidth / 2) + ceil($width / 2);

			} else {

				$resizeHeight = ceil($sourceHeight * $xScale) + 2;
				$resizeWidth = ceil($sourceWidth * $xScale);
				$destY = -1 - floor($resizeHeight / 2) + ceil($height / 2);
			}
		}

		// Fit and Pad mode: Resize the thumb by keeping aspect ratio and fitting inside the specified dimensions
		if($mode == 'fit' || $mode == 'pad'){

			if($height == ''){

				$thumbHeight = $resizeHeight = $sourceHeight * $xScale;

			}else{

				if($width == ''){

					$thumbWidth = $resizeWidth = $sourceWidth * $yScale;

				}else{

					if($xScale < $yScale){

						$thumbHeight = $resizeHeight = $sourceHeight * $xScale;

					}else{

						$thumbWidth = $resizeWidth = $sourceWidth * $yScale;
					}
				}
			}

			// Pad mode will fill the remaining borders with the bg color
			if($mode == 'pad'){

				if($xScale < $yScale){

					$destY = $height / 2 - $thumbHeight / 2;

				}else{

					$destX = $width / 2 - $thumbWidth / 2;
				}

				$thumbWidth = $width;
				$thumbHeight = $height;
			}
		}

		// Generate the thumbnail based on calculated dimensions
		$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

		// Paint the thumb bg if necessary. Note that default thumb bg is already black by default
		if($bgColor != '000000'){

			$thumbColor = imagecolorallocate($thumb, hexdec(substr($bgColor,0,2)), hexdec(substr($bgColor,2,2)), hexdec(substr($bgColor,4,2)));

			imagefilledrectangle($thumb, 0, 0, $thumbWidth, $thumbHeight, $thumbColor);
		}

		// Rescale and generate the thumb
		imagecopyresampled($thumb, $sourceImage, $destX, $destY, 0, 0, $resizeWidth, $resizeHeight, $sourceWidth, $sourceHeight);

		// Free source image memory
		imagedestroy($sourceImage);

		// If destination is specified, store the generated thumb to disk
		if($destination != ''){

			imagejpeg($thumb, $destination, $quality);
		}

		return $thumb;
	}



	/**
	 * Performs maximum jpg optimization possible by calling the jpegtran command line tool.
	 * jpegtran is an open source library tool to compress jpg images as much as possible, that normally comes bundled with linux distributions. If not available, we must install it on our machine.
	 *
	 * @param string $imagePath Full file system path to the image file we want to compress
	 * @param string $outputPath Leave it empty (default value) to override the source image or specify a full system path (including the destination filename) where the compressed image will be stored. Warning: Do not set the output path to the same value as the source path or an error will happen. To override the source, simply leave this parameter empty.
	 *
	 * @return boolean True if compression was performed or false if something failed
	 */
	public static function compressJpgPicture($imagePath, $outputPath = ''){

		// Check that jpegtran is enabled on the current machine
		system('which jpegtran > /dev/null', $checkJpegTran);

		if($checkJpegTran != 0) {

			trigger_error('PictureUtils::compressJpgPicture Error: jpegtran is not enabled on the system.', E_USER_WARNING);

			return false;
		}

		// Check that source image exists
		if(!is_file($imagePath)){

			trigger_error('PictureUtils::compressJpgPicture Error: Specified image file ('.$imagePath.') does not exist', E_USER_WARNING);

			return false;
		}

		// Check that source and output paths are not the same!
		if($imagePath == $outputPath) {

			trigger_error('PictureUtils::compressJpgPicture Error: source and output paths cannot be the same.', E_USER_WARNING);

			return false;
		}

		if($outputPath == ''){

			exec('jpegtran -copy none -optimize -progressive -outfile '.$imagePath.' '.$imagePath, $output, $return);

		}else{

			exec('jpegtran -copy none -optimize -progressive '.$imagePath.' > '.$outputPath, $output, $return);
		}

		// Check any problem on jpegtran execution
		if ($return != 0) {

			trigger_error('PictureUtils::compressJpgPicture jpegtran failed :'.implode("\n", $output), E_USER_WARNING);

			return false;
		}

		return true;
	}


	/**
	 * Performs maximum jpg optimization possible by calling the jpegtran command line tool for all files on the specified folder.
	 * This method will apply the compression to all files, so the folder shall contain only images to prevent errors.
	 * IMPORTANT: Files are replaced by the optimized version.
	 *
	 * @param string $imagesPath Full file system path to a folder containing images that will be replaced by a compressed version
	 *
	 * @return boolean True if processing was performed or false if something failed
	 */
	public static function compressJpgPictureFolder($imagesPath){

		// Verify that path exists and is a real directory
		if(!is_dir($imagesPath)){

			trigger_error('PictureUtils::compressJpgPictureFolder : Specified path ('.$imagesPath.') is not a folder', E_USER_WARNING);

			return false;
		}

		$images = FileSystemUtils::getDirectoryList($imagesPath);

		// Remove folder trailing sepparator if exists
		if(substr($imagesPath, -1) == DIRECTORY_SEPARATOR){

			$imagesPath = substr($imagesPath, 0, -1);
		}

		foreach($images as $im){

			self::compressJpgPicture($imagesPath.'/'.$im);
		}

		return true;
	}

}

?>