"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

/** @namespace */
var org_turbocommons_src_main_js_managers = org_turbocommons_src_main_js_managers || {};


/**
 * SINGLETON class that lets us interact with the current browser
 * 
 * <pre><code>
 * Usage example:
 * 
 * var b = org_turbocommons_src_main_js_managers.BrowserManager.getInstance();
 * 
 * b.reloadPage();
 * var l = b.getPreferredLanguage();
 * ...
 * </code></pre>
 * 
 * @class
 */
org_turbocommons_src_main_js_managers.BrowserManager = {

	_browserManager : null,

	/**
	 * Get the global singleton class instance
	 * 
	 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager
	 * 
	 * @returns {org_turbocommons_src_main_js_managers.BrowserManager} The singleton instance
	 */
	getInstance : function(){

		if(!this._browserManager){

			this._browserManager = {

				/**
				 * Tells if the current html document is fully loaded or not.
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 *  
				 * @returns {boolean} True if the current html document is fully loaded (including all frames, objects and images) or false otherwise. 
				 */
				isLoaded : function(){

					return (document.readyState === "complete");
				},


				/**
				 * Reloads the current url
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 *  
				 * @returns void
				 */
				reloadPage : function(){

					location.reload();
				},


				/**
				 * Tries to detect the language that is set as preferred by the user on the current browser.
				 * NOTE: Getting browser language with JS is not accurate. It is always better to use server side language detection
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 *  
				 * @returns {string} A two digits string containing the detected browser language. For example 'es', 'en', ...
				 */
				getPreferredLanguage : function(){

					var lan = window.navigator.userLanguage || window.navigator.language;

					lan = lan.split(',');

					lan = lan[0].trim().substr(0, 2).toLowerCase();

					return lan;
				},


				/**
				 * Opens the specified url on the browser's current tab or in a new one.
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 * 
				 * @param {string} url The url that will be loaded
				 * @param {string} newWindow Setting it to true will open the url on a new browser tab. False by default
				 * @param {object} postData If we want to send POST data to the url, we can set this parameter to an object where each property will be translated to a POST variable name, and each property value to the POST variable value
				 * 
				 * @returns void
				 */
				goToUrl : function(url, newWindow, postData){

					// Init default vars values
					newWindow = (newWindow === undefined) ? false : newWindow;
					postData = (postData === undefined) ? null : postData;

					// Check if POST data needs to be sent
					if(postData == null){

						// Check if same or new window is required
						if(newWindow){

							window.open(url, '_blank');

						}else{

							window.location.href = url;
						}

					}else{

						// Create a dummy html form element to use it as the method to call the url with post data
						var formHtml = '<form action="' + url + '" method="POST" ' + (newWindow ? 'target="_blank"' : '') + ' style="display:none;">';

						// Convert the postData object to the different POST vars
						var props = Object.getOwnPropertyNames(postData);

						for(var i = 0; i < props.length; i++){

							formHtml += '<input type="hidden" name="' + props[i] + '" value="' + postData[props[i]] + '">';
						}

						formHtml += '</form>';

						var form = $(formHtml);

						$('body').append(form);

						form.submit();
					}
				},


				/**
				 * Gives the current position for the browser scroll
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 * 
				 * @returns {array} The current x,y position based on the top left corner of the current document
				 */
				getScrollPosition : function(){

					return [$(document).scrollLeft(), $(document).scrollTop()];
				},


				/**
				 * Moves the browser scroll to the specified X-Y axis position.
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 * 
				 * @param {int} x The horizontal position where the scroll will go. It is based on the left of the current page, so we can only specify positive values. Set it to null to avoid x scroll movement
				 * @param {int} y The vertical position where the scroll will go. It is based on the top of the current page, so we can only specify positive values. Set it to null to avoid y scroll movement
				 * @param {int} time The animation duration in miliseconds. Set it to 0 to perform a direct scroll change.
				 * @param {string} easingFunction The jquery easing function to use with the animation. By default jquery has only 'linear' and 'swing', but we may import other jQuery easing libraries if required
				 * 
				 * @returns {boolean} True if scroll position changed after the execution of this method or false if no scroll change happened.
				  */
				scrollTo : function(x, y, time, easingFunction){

					// Set default values if they are not defined
					time = time === undefined ? 1000 : time;
					easingFunction = easingFunction === undefined ? 'swing' : easingFunction;

					var validationManager = new org_turbocommons_src_main_js_managers.ValidationManager();

					if(x !== null && (!validationManager.isNumeric(x) || x < 0)){

						throw new Error("BrowserUtils.scrollTo - x coordinate must be a positive number");
					}

					if(y !== null && (!validationManager.isNumeric(y) || y < 0)){

						throw new Error("BrowserUtils.scrollTo - y coordinate must be a positive number");
					}

					// Perform scrolling
					var res = false;
					var animateObj = {};

					if(x !== null){

						res = true;
						animateObj.scrollLeft = x;
					}

					if(y !== null){

						res = true;
						animateObj.scrollTop = y;
					}

					if(res){

						$('html, body').stop().animate(animateObj, time, easingFunction);
					}

					return res;
				},


				/**
				 * Enables or disables a smooth scrolling animation when the user clicks on any internal page link.
				 * Example: &lt;a href="#contact"&gt; will scroll the page to the element that has id="contact". NOTE that ony one element with the 'contact' id is expected)
				 * 
				 * @memberOf org_turbocommons_src_main_js_managers.BrowserManager.prototype
				 * 
				 * @param {boolean} enabled True to enable the animated scroll, false to disable it. If disabled, all the rest arguments of this method are useless
				 * @param {int} time The animation duration in miliseconds
				 * @param {int} offSet The vertical offset where the scroll will end. We can specify positive or negative values. Use this to modify the final scrolling point.
				 * @param {string} easingFunction The jquery easing function to use with the animation. By default jquery has only 'linear' and 'swing', but we may import other jQuery easing libraries if required
				 * @param {string} selectedClass If a value is specified for this parameter, all the <a> elements pointing to the currently selected anchor will be set with the specified css class. Usefull to mark selected items with a special css class on a menu.
				 * 
				 * @returns void
				  */
				setAnimatedScroll : function(enabled, time, offSet, easingFunction, selectedClass){

					// Set default values if they are not defined
					time = time === undefined ? 1000 : time;
					easingFunction = easingFunction === undefined ? 'swing' : easingFunction;
					offSet = offSet === undefined ? 0 : offSet;
					selectedClass = selectedClass === undefined ? '' : selectedClass;

					// Alias namespaces
					var ns = org_turbocommons_src_main_js_managers;
					var eventsNs = 'org_turbocommons_src_main_js_managers.BrowserManager.setAnimatedScroll';

					// Validate method parameters
					var validationManager = new ns.ValidationManager();

					validationManager.isBoolean(enabled, "enabled parameter must be a boolean value");
					validationManager.isNumeric(time, "time parameter must be a numeric value");
					validationManager.isNumeric(offSet, "offSet parameter must be a numeric value");
					validationManager.isString(easingFunction, "easingFunction parameter must be a string value");
					validationManager.isString(selectedClass, "selectedClass parameter must be a string value");

					if(validationManager.validationStatus !== ns.ValidationManager.VALIDATION_OK){

						throw new Error("BrowserUtils.scrollTo - " + validationManager.lastMessage);
					}

					// Method that performs scroll animation to a clicked element
					function onElementMouseClick(event){

						// Detect that the click target is an anchor element with hash (a href = "#")
						if(event.target.nodeName.toLowerCase() === 'a'){

							if(event.target.hash != ''){

								// Perform the scroll animation to the element that is pointed by the link hash
								event.preventDefault();

								// Alias namespace
								var ut = org_turbocommons_src_main_js_utils;

								// Check for duplicate ids on the current document
								ut.HtmlUtils.findDuplicateIds();

								// Launch an error if the specified anchor link does not exist
								if(!$(event.target.hash).length){

									throw new Error("BrowserUtils.animateScrollToInternalLinks - Specified anchor link not found: " + event.target.hash);
								}

								$('html, body').stop().animate({
									'scrollTop' : $(event.target.hash).offset().top + offSet
								}, time, easingFunction);
							}
						}
					}

					// Method that racks the browser scroll movement and performs changes to the url hash or selected anchors if necessary
					function onBrowserScroll(){

						// Get an array with all the anchor elements that have set a hash value (href="#...")
						var anchors = $('a[href^="#"]').toArray();

						var elements = [];

						// For all the anchors, we must find the target element and its distance relative to the top of the page
						for(var i = 0; i < anchors.length; i++){

							var elementId = $(anchors[i]).attr('href').replace("#", "");

							// Note that non existent or non visible elements won't be taken into consideration
							if(elementId != ''){

								if($('#' + elementId).length){

									if($('#' + elementId).is(":visible")){

										elements.push({
											id : elementId,
											distance : Math.abs(window.pageYOffset - $('#' + elementId).offset().top)
										});
									}
								}
							}
						}

						// No elements found, nothing to do
						if(elements.length > 0){

							// Sort the elements ascending by distance. We are looking for the one that is closer to the top of the page (the lowest distance)
							var result = elements.sort(function(a, b){

								return (a.distance - b.distance);
							});

							// This is a tricky hack. To prevent the scroll from jumping when we change the url hash, we will locate the element that is 
							// tagged with this hash and temporarily remove its id, then change the url hash and restore the element id.
							var element = document.getElementById(result.shift().id);
							var tmpId = element.id;

							element.removeAttribute('id');
							window.location.hash = '#' + tmpId;
							element.setAttribute('id', tmpId);

							// Set the selected class to the first of the sorted elements
							if(selectedClass != ''){

								$('a[href^="#"]').removeClass(selectedClass);
								$('a[href^="' + window.location.hash + '"]').addClass(selectedClass);
							}
						}
					}

					// Function that contains all the code to initialize the animated scroll
					function initScrollListeners(){

						// Perform a scroll to the url hash on window load, to make sure the ofset is applied
						if(window.location.hash != ''){

							if($(window.location.hash).length){

								// Perform the scroll animation to the element that is pointed by the link hash
								$('html, body').stop().animate({
									'scrollTop' : $(window.location.hash).offset().top + offSet
								}, time, easingFunction);

							}else{

								window.location.hash = '';
							}
						}

						// Listen for the click event to launch the animation
						$(document).on('click.' + eventsNs, onElementMouseClick);

						// Listen for the main browser scroll event
						$(document).on('scroll.' + eventsNs, onBrowserScroll);
					}

					// Check if we need to enable or disable the scrolling animations
					if(enabled){

						// All the code must be executed after the full HTML document is loaded
						if(ns.BrowserManager.getInstance().isLoaded()){

							initScrollListeners();

						}else{

							$(window).one('load', initScrollListeners);
						}

					}else{

						// Remove any listeners that may have been created by this method
						$(document).off('click.' + eventsNs);
						$(document).off('scroll.' + eventsNs);
					}
				},
			};
		}

		return this._browserManager;
	}
};