<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;


use org\turbocommons\src\main\php\managers\FilesManager;
/**
 * PDF documents manipulation methods
 */
class PdfUtils {


	/**
	 * Count the number of pages on a PDF document.
	 *
	 * This method requires an external command line tool called pdfinfo, that we should place on our project storage/binary folder
	 * Its free and can be downloaded from: http://www.foolabs.com/xpdf/download.html
	 *
	 * VERY IMPORTANT:
	 * 1. pdfinfo execute permission MUST be enabled at least for the file owner
	 * 2. Make sure you are using the binary executable of pdfinfo that fits your OS (centos, windows...) and processor (32bit / 64bit) or it may not work
	 *
	 * @param string $pdfInfoPath Full executable path to the pdfInfo tool. For example: $fileStorageManager->binaryGetAppPath('pdfinfo')
	 * @param string $pdfPath Full path to the pdf source file. Example: ProjectPaths::RESOURCES.'/pdf/mypdf.pdf'
	 *
	 * @return int The total number of calculated pages
	 */
	public static function getPagesCount($pdfInfoPath, $pdfPath){

		// Check that the specified pdf file exists
		if(!is_file($pdfPath)){

			trigger_error('PdfUtils::getPagesCount Error: Specified PDF file ('.$pdfPath.') does not exist', E_USER_WARNING);

			return 0;
		}

		// Check that the cmd pdfinfo tool exists and is executable
		if(!is_executable($pdfInfoPath)){

			trigger_error('PdfUtils::getPagesCount Error: Specified pdfinfo CMD binary ('.$pdfInfoPath.') does not exist or execute permisions are disabled', E_USER_WARNING);

			return 0;
		}

		// Execute the pdfinfo tool that gives us the information we need
		exec($pdfInfoPath.' "'.$pdfPath.'"', $output, $return);

		// Check any problem on jpegtran execution
		if ($return != 0) {

			trigger_error('PdfUtils::getPagesCount pdfinfo failed :'.implode("\n", $output), E_USER_WARNING);

			return 0;
		}

		// Iterate through lines
		$pageCount = 0;

		// We will get the number of pages from the pdinfo command line output.
		// It is extracted by using a regular expression
		foreach($output as $op){

			if(preg_match('/Pages:\s*(\d+)/i', $op, $matches) === 1){

				return intval($matches[1]);
			}
		}

		return $pageCount;
	}


	/**
	 * Given a PDF document, this method will generate a picture for the specified page.
	 *
	 * Requires GhostScript, that is an open source library to manipulate PS and PDF files, that normally comes bundled with linux distributions.
	 * If not available, we must install it on our machine, or better download (http://www.ghostscript.com/download/gsdnld.html) the pre compiled binaries and place it on storage/binary
	 *
	 * @param string $ghostScriptPath  Full executable path to the ghostscript tool. For example: 'gs' if we are using the version installed on our machine or  $fileStorageManager->binaryGetAppPath('gs') if we have placed it on our storage binary folder. It is recommended to user always the latest version, so we better download it and place it on storage/binary
	 * @param string $pdfPath  Full path to the pdf source file. Example: ProjectPaths::RESOURCES.'/pdf/mypdf.pdf'
	 * @param string $page The number of the page we want to convert to a picture. FIRST PAGE STARTS AT 0
	 * @param number $jpgQuality 90 by default. Specifies the jpg quality for the generated picture
	 * @param string $dpi 200 by default, defines the pixel density for the generated picture. This will in fact affect the final resolution of the image.
	 *
	 * @return string A binary string containing the generated picture, or null if some problem happened
	 */
	public static function generatePageJpgPicture($ghostScriptPath, $pdfPath, $page, $jpgQuality = 90, $dpi = '200'){

		// Check that ghostscript is enabled on the current machine
		if($ghostScriptPath == 'gs'){

			system('which gs > /dev/null', $checkGhostScript);

			if($checkGhostScript != 0) {

				trigger_error('PdfUtils::generatePageJpgPicture Error: Ghostscript is not enabled on the system.', E_USER_WARNING);

				return null;
			}
		}else{

			if(!is_executable($ghostScriptPath)){

				trigger_error('PdfUtils::generatePageJpgPicture Error: Specified '.$ghostScriptPath.' application binary ('.$ghostScriptPath.') does not exist or execute permisions are disabled', E_USER_WARNING);

				return null;
			}
		}

		// Check that page value is ok
		if(!is_numeric($page) || $page < 0){

			trigger_error('PdfUtils::generatePageJpgPicture Error: Specified page must be a positive integer > 0', E_USER_WARNING);

			return null;
		}

		// Check that the specified pdf file exists
		if(!is_file($pdfPath)){

			trigger_error('PdfUtils::generatePageJpgPicture Error: Specified PDF file ('.$pdfPath.') does not exist', E_USER_WARNING);

			return null;
		}

		// Generate the ghostscript command line call from the received parameters
		$gsQuery  = $ghostScriptPath.' -dNOPAUSE -sDEVICE=jpeg -dUseCIEColor -dDOINTERPOLATE -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=- ';
		$gsQuery .= '-dFirstPage='.($page + 1).' -dLastPage='.($page + 1).' ';
		$gsQuery .= '-r'.$dpi.' ';
		$gsQuery .= '-dJPEGQ='.$jpgQuality.' ';
		$gsQuery .= '-q '.$pdfPath;

		// Get the processed image directly to stdOut
		ob_start();

		passthru($gsQuery, $return);

		$image = ob_get_contents();

		ob_end_clean();

		// Check any problem on ghostscript execution
		if ($return != 0) {

			trigger_error('PdfUtils::generatePageJpgPicture Ghostscript failed :'.$image, E_USER_WARNING);

			return null;
		}

		return $image;
	}


	/**
	 * Given a PDF document, this method will generate a picture for each one of the document pages.
	 *
	 * Requires GhostScript, that is an open source library to manipulate PS and PDF files, that normally comes bundled with linux distributions.
	 * If not available, we must install it on our machine, or better download (http://www.ghostscript.com/download/gsdnld.html) the pre compiled binaries and place it on storage/binary
	 *
	 * @param string $ghostScriptPath  Full executable path to the ghostscript tool. For example: 'gs' if we are using the version installed on our machine or  $fileStorageManager->binaryGetAppPath('gs') if we have placed it on our storage binary folder. It is recommended to user always the latest version, so we better download it and place it on storage/binary
	 * @param string $pdfPath  Full path to the pdf source file. Example: ProjectPaths::RESOURCES.'/pdf/mypdf.pdf'
	 * @param string $outputPath Full path to a file system EMPTY folder where all the generated pictures will be stored. If the specified folder is not empty or does not exist, an exception will happen.
	 * @param number $jpgQuality 90 by default. Specifies the jpg quality for all the generated pictures
	 * @param string $dpi 200 by default, defines the pixel density for all the generated pictures. This will in fact affect the final resolution of the images.
	 * @param string $outFileMask '/%d.jpg' by default. Allows us to define a pattern for the generated file names (%d will be replaced by the page number). Example: '%05d.jpg' will generate a jpg file with 5 digits, like '00012.jpg'. More info on the GostScript manual for the option -o
	 *
	 * @return number The total number of generated pages or -1 if an error happened
	 */
	public static function generateDocumentJpgPictures($ghostScriptPath, $pdfPath, $outputPath, $jpgQuality = 90, $dpi = '200', $outFileMask = '/%d.jpg'){

		// Check that ghostscript is enabled on the current machine
		if($ghostScriptPath == 'gs'){

			system('which gs > /dev/null', $checkGhostScript);

			if($checkGhostScript != 0) {

				trigger_error('PdfUtils::generateDocumentJpgPictures Error: Ghostscript is not enabled on the system.', E_USER_WARNING);

				return -1;
			}
		}else{

			if(!is_executable($ghostScriptPath)){

				trigger_error('PdfUtils::generateDocumentJpgPictures Error: Specified '.$ghostScriptPath.' application binary ('.$ghostScriptPath.') does not exist or execute permisions are disabled', E_USER_WARNING);

				return -1;
			}
		}

		// Check that the specified pdf file exists
		if(!is_file($pdfPath)){

			trigger_error('PdfUtils::generateDocumentJpgPictures Error: Specified PDF file ('.$pdfPath.') does not exist', E_USER_WARNING);

			return -1;
		}

		// Check that the specified output folder exists
		if(!is_dir($outputPath)){

			trigger_error('PdfUtils::generateDocumentJpgPictures Error: Specified output folder ('.$outputPath.') does not exist', E_USER_WARNING);

			return -1;
		}

		// Make sure that the output folder is empty
		$filesManager = new FilesManager();

		if(count($filesManager->getDirectoryList($outputPath)) > 0){

			trigger_error('PdfUtils::generateDocumentJpgPictures Error: Specified output folder ('.$outputPath.') must be empty', E_USER_WARNING);

			return -1;
		}

		// Push the script time limit to 20 minutes, as this operation may be cpu intensive
		$timeLimit = ini_get('max_execution_time');

		set_time_limit(1200);

		// Generate the ghostscript command line call from the received parameters
		$gsQuery  = $ghostScriptPath.' -dNOPAUSE -sDEVICE=jpeg -dUseCIEColor -dDOINTERPOLATE -dTextAlphaBits=4 -dGraphicsAlphaBits=4 ';
		$gsQuery .= '-o'.$outputPath.$outFileMask.' ';
		$gsQuery .= '-r'.$dpi.' ';
		$gsQuery .= '-dJPEGQ='.$jpgQuality.' ';
		$gsQuery .= "'".$pdfPath."'";

		exec($gsQuery, $output, $return);

		// Restore the previous time limit value as we have finished processing
		set_time_limit($timeLimit);

		// Check any problem on ghostscript execution
		if ($return != 0) {

			trigger_error('PdfUtils::generateDocumentJpgPictures Ghostscript failed :'.implode("\n", $output), E_USER_WARNING);

			return -1;
		}

		// Verify that the output folder contains the generated pictures, and count their number
		return count($filesManager->getDirectoryList($outputPath));
	}


	/**
	 * Performs maximum possible optimization to a specified pdf document, by appliyng the pdftk command line tool. We should place this tool on our project storage/binary folder
	 * pdftk is free and can be downloaded from: https://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/
	 *
	 * VERY IMPORTANT:
	 * 1. pdftk execute permission MUST be enabled at least for the file owner
	 * 2. Make sure you are using the binary executable of pdftk that fits your OS (centos, windows...) and processor (32bit / 64bit) or it may not work
	 *
	 * @param string $pdftkPath Full executable path to the pdftk tool. For example:  $fileStorageManager->binaryGetAppPath('pdftk')
	 * @param string $pdfPath Full path to the pdf source file. Example: ProjectPaths::RESOURCES.'/pdf/mypdf.pdf'
	 * @param string $outputPath Leave it empty (default value) to override the source pdf document or specify a full system path (including the destination filename) where the compressed result will be stored.
	 *
	 * @return boolean True if compression was performed or false if something failed
	 */
	public static function compressDocument($pdftkPath, $pdfPath, $outputPath = ''){

		// Check that the specified pdf file exists
		if(!is_file($pdfPath)){

			trigger_error('PdfUtils::compressDocument Error: Specified PDF file ('.$pdfPath.') does not exist', E_USER_WARNING);

			return false;
		}

		// Check that the cmd pdftk tool exists and is executable
		if(!is_executable($pdftkPath)){

			trigger_error('PdfUtils::compressDocument Error: Specified pdftk CMD binary ('.$pdftkPath.') does not exist or execute permisions are disabled', E_USER_WARNING);

			return false;
		}

		// Process the received pdf with pdftk
		ob_start();

		// We are using output - so the result of the pdftk command is shown directly on stdout.
		// We then capture it with the php passthru method
		passthru($pdftkPath.' '.$pdfPath.' output - compress');

		$processedPdf = ob_get_contents();

		ob_end_clean();

		// Check that we have gained size improvements by applying the pdftk app
		$originalSize = filesize($pdfPath);
		$processedSize = strlen($processedPdf);

		if($originalSize > $processedSize && $processedSize > 0){

			// Store the compressed file
			if($outputPath == ''){

				file_put_contents($pdfPath, $processedPdf);

			}else{

				file_put_contents($outputPath, $processedPdf);
			}

		}else{

			if($outputPath != ''){

				copy($pdfPath, $outputPath);
			}
		}

		return true;
	}


	/**
	 * Extract all the possible text from the given pdf document
	 *
	 * @param string $pdfPath Full path to the pdf source file. Example: ProjectPaths::RESOURCES.'/pdf/mypdf.pdf'
	 *
	 * @return string All the text that could be extracted from the pdf
	 */
	public static function extractDocumentText($pdfPath) {

		// TODO: fer aixo
		return 'TODO';
	}
}

?>