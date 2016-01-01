"use strict";

/**
 * Static class with browser related utilities<br>
 * import path: 'js/libs/libEdertoneJS/utils/BrowserUtils.js'
 */
var BrowserUtils = {


	/**
	 * Reloads the current browser url
	 * 
	 * @return void
	 */
	reload : function(){

		location.reload();
	},


	/**
	 * Opens the specified url on the browser's current tab or in a new one.
	 * 
	 * @param url The url that will be loaded
	 * @param newWindow Setting it to true will open the url on a new browser tab. False by default
	 * @param postData If we want to send POST data to the url, we can set this parameter to an object where each property will be translated to a POST variable name, and each property value to the POST variable value
	 * 
	 * @return void
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
	 * Enables a scrolling animation to all the <a> elements that point to an anchor on the same html page 
	 * (for example: &lt;a href="#contact"&gt;... will scroll the page to the element that has an id="contact". NOTE that ony one element with the 'contact' id is expected)
	 * IMPORTANT: This method must be called before the current page has finished loading (Before the  $(window).load event)
	 * 
	 * @param time The miliseconds that the animation will last
	 * @param easingFunction The jquery easing function to use with the animation. By default jquery has only 'linear' and 'swing', but we can easily import other easing jquery libraries.
	 * @param offSet The vertical offset where the scroll will end. We can specify positive or negative values. Use this to modify the final scrolling point.
	 * @param selectedClass If a value is specified for this parameter, all the <a> elements pointing to the currently selected anchor will be set with the specified css class. Usefull to mark selected items with a special css class on a menu.
	  */
	animateScrollToInternalLinks : function(time, easingFunction, offSet, selectedClass){

		// All the code for this method is executed after the window is totally loaded
		$(window).load(function(){

			// Set default values if they are not defined
			time = time === undefined ? 1000 : time;
			easingFunction = easingFunction === undefined ? 'swing' : easingFunction;
			offSet = offSet === undefined ? 0 : offSet;
			selectedClass = selectedClass === undefined ? '' : selectedClass;

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

			// Listen for the click event on all a elements that have an internal anchor link
			$('a[href^="#"]').on('click', function(event){

				event.preventDefault();

				// Perform the scroll animation to the element that is pointed by the link hash
				if(this.hash != ''){

					// Check for duplicate ids on the current document
					HtmlUtils.findDuplicateIds();

					// Launch an error if the specified anchor link does not exist
					if(!$(this.hash).length){

						throw new Error("BrowserUtils.animateScrollToInternalLinks - Specified anchor link not found: " + this.hash);
					}

					$('html, body').stop().animate({
						'scrollTop' : $(this.hash).offset().top + offSet
					}, time, easingFunction);
				}
			});

			// Add an event listener to track the browser scroll movement and perform changes to the url hash or selected anchors if necessary
			$(document).scroll(function(){

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
			});
		});
	},


	// TODO: A partir d'aqui cal revisar els metodes ja que son fets pel sergi i poden ser bazofia


	/**
	 * Method to get an object with the browser version, name and if it uses webkit or not
	 * 
	 * @returns browser Object with the browser data. It will be defined with these keys: name, version, webkit
	 */
	getBrowserVersion : function(){

		var b = $.browser;
		var browser = {};

		if(b === undefined){
			browser.version = "unknown";
			browser.name = "unknown";
			browser.webkit = false;
			return browser;
		}

		// Get the browser version
		browser.version = parseFloat(b.version);

		// Get if browser is using webkit
		browser.webkit = b.webkit === undefined ? false : true;

		// Get the browser name
		if(!!navigator.userAgent.match(/Trident\/7\./)){
			browser.name = "msie";
			return browser;
		}

		if(b.safari){
			browser.name = "safari";
			return browser;
		}

		if(b.opera){
			browser.name = "opera";
			return browser;
		}

		if(b.msie){
			browser.name = "msie";
			return browser;
		}

		if(typeof window.chrome === "object"){
			browser.name = "chrome";
			return browser;
		}

		if(b.mozilla){
			browser.name = "mozilla";
			return browser;
		}

		return browser;

	},


	/**
	 * Method to check if the browser is older
	 * 
	 * @param name The name of the browser to check: safari, mozilla, chrome, opera, msie
	 * @param version The older allowed version of the browser which we have to check
	 * 
	 * @returns {Boolean} Returns if the browser is older or not
	 */
	isBrowserOlder : function(name, version){

		var data = BrowserUtils.getBrowserVersion();
		return (data.name === name && data.version < version);

	}
};