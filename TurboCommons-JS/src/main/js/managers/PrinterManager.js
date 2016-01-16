"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/* exported PrinterManager */

/**
 * Singleton Class to interact with printer and printing utilities
 * import path: 'js/libs/libEdertoneJS/managers/PrinterManager.js'
 */
var PrinterManager = {

	_printerManager : null,

	getInstance : function(){

		if(!this._printerManager){

			this._printerManager = {


				/**
				 * Sends the contents of the current page to the printer
				 * NOTE: When printing it is interesting to use the css (a)media print {} instruction to stylize or remove any elements as required
				 * 
				 * @return void
				 */
				printPage : function(){

					window.print();
				},


				/**
				 * Sends the contents of the specified element to the printer.
				 * NOTE: When printing it is interesting to use the css (a)media print {} instruction to stylize or remove any elements as required
				 * 
				 * @param element A jquery object that contains the html block we want to print
				 * 
				 * @return void
				 */
				printElement : function(element){

					// Get a reference to the actual body, and remove it from the dom
					var body = $('body');

					$('body').detach();

					// Generate a new body with the cloned contents of the element to print
					var elementCopy = $('<body>').append(element.clone().html());

					// Place the generated body inside the html doc
					$('html').append(elementCopy);

					// Print the page
					window.print();

					// Destroy the generated element copy
					elementCopy.remove();

					elementCopy = null;

					// Restore the original body
					$('html').append(body);

				}
			};
		}

		return this._printerManager;
	}
};