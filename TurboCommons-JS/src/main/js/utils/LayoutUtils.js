"use strict";

/* exported LayoutUtils */

/**
 * Static Class that gives screen layout utilities
 * 
 * import path: 'js/libs/libEdertoneJS/utils/LayoutUtils.js'
 */
var LayoutUtils = {


	/**
	 * Calculates the maximum z-index value for the elements specified by the selector parameter. 
	 * If no selector specified, the maximum z-index will be calculated for all the elements of the current page.
	 * IMPORTANT: this method may be a bit heavy, as it loops on a lot of elements, so use it carefully
	 * NOTE: Only elements that have a position != static are taken into consideration.<br><br>
	 * Soultion taken from http://stackoverflow.com/questions/1118198/how-can-you-figure-out-the-highest-z-index-in-your-document
	 * 
	 * @param selector '*' by default, a jquery selector string that will be used to select the elements for which the maximum z-index will be calculated. 
	 * 
	 * @returns The maximum z-index value found. 
	 */
	getMaxZIndex : function(selector){

		// Set default values if they are not defined
		selector = selector === undefined ? '*' : selector;

		var highest = Math.max.apply(null, $.map($(selector), function(e){

			if($(e).css('position') != 'static'){

				return parseInt($(e).css('z-index')) || 1;
			}
		}));

		return highest;
	},


	/**
	 * Moves the specified element to the front by updating its z-index if necessary. Note that z-index only works with "position:absolute", "position:relative" and "position:fixed"
	 * IMPORTANT: this method may be a bit heavy, as it loops on a lot of elements, so use it carefully
	 * 
	 * @param element A jquery object representing the element that must be moved to the front.
	 * 
	 * @returns The element z-index after being moved to the front
	 */
	moveToFront : function(element){

		var zIndex = LayoutUtils.getMaxZIndex() + 1;

		element.css("z-index", zIndex);

		return zIndex;
	},


	/**
	 * Check if the specified global coordinates fall inside the specified element
	 * 
	 * @param element A jquery element that will be used to check if the specified coordinates fall inside it, for its current position
	 * @param x The global X coordinate relative to the left corner of the browser window
	 * @param y The global Y coordinate relative to the top corner of the browser window
	 * 
	 * @returns {Boolean} True if the global x and y coordinates specified fall inside the specified element or false otherwise
	 */
	isElementLocatedAt : function(element, x, y){

		// Get the element size
		var elementWidth = element.outerWidth();
		var elementHeight = element.outerHeight();

		// Get the element left and top coordinates
		var elementLeft = element.offset().left;
		var elementTop = element.offset().top;

		if(x >= elementLeft && x <= elementLeft + elementWidth){

			if(y >= elementTop && y <= elementTop + elementHeight){

				return true;
			}
		}

		return false;
	},


	/**
	 * Sets the width for the specified element relatively to the specified reference.
	 * 
	 * @param element A jquery object that represents the element that we want resize (in width)
	 * @param reference A jquery object that will be measured to resize the width of the specified element (Note that full width will be calculated, including paddings).
	 * @param percent 100 by default. A value that will represent the percentual value relative to the reference element. By default the same as the reference (100)  		
	 *
	 * @return void
	 */
	setWidthRelativeTo : function(element, reference, percent){

		// Set default values if they are not defined
		percent = percent === undefined ? 100 : percent;

		element.width(percent * reference.outerWidth() / 100);
	},


	/**
	 * Sets the height for the specified element relatively to the specified reference.
	 * 
	 * @param element A jquery object that represents the element that we want resize (in height)
	 * @param reference A jquery object that will be measured to resize the height of the specified element (Note that full height will be calculated, including paddings).
	 * @param percent 100 by default. A value that will represent the percentual value relative to the reference element. By default the same as the reference (100)  		
	 *
	 * @return void
	 */
	setHeightRelativeTo : function(element, reference, percent){

		// Set default values if they are not defined
		percent = percent === undefined ? 100 : percent;

		element.height(percent * reference.outerHeight() / 100);
	},


	/**
	 * Centers the given element based on the specified settings: Referred to the window, to another element, to a coordinate, etc... 
	 * Multiple settings can be used to define how the element must be centered 
	 * 
	 * @param element A jquery object that represents the element that we want to center. Note that its position value must be "absolute" or "fixed"
	 * @param reference '' by default. The given element will be centered differently, depending on this value:<br>
	 * 		&emsp;- Empty '' value: The element will be centered relative to the main browser window<br>
	 * 		&emsp;- Jquery object: The element will be centered relative to the specified jquery object<br>
	 * 		&emsp;- Array with 4 values: The element will be centered relative to an imaginary object with coordinates and size: [x, y, width, height]
	 * @param mode 'center center' by default. A string 'N M' with the following possible values:<br>
	 * 		&emsp;- N: Defines the centering on the X axis, and must have one of the following values:<br>
	 * 		&emsp;&emsp;none: To avoid modifying the horizontal layout of the element<br>
	 * 		&emsp;&emsp;leftOut: To layout the element outside the left border of the reference<br>
	 * 		&emsp;&emsp;left: To layout the element attached to the left border of the reference, but from the inside<br>
	 * 		&emsp;&emsp;center: To layout the element at the horizontal center of the reference<br>
	 * 		&emsp;&emsp;right: To layout the element attached to the right border of the reference, but from the inside<br>
	 * 		&emsp;&emsp;rightOut: To layout the element outside the right border of the reference<br>
	 * 		&emsp;- M: Defines the centering on the Y axis, and must have one of the following values:<br>
	 * 		&emsp;&emsp;none: To avoid modifying the vertical layout of the element<br>
	 * 		&emsp;&emsp;topOut: To layout the element above the top border of the reference<br>
	 * 		&emsp;&emsp;top: To layout the element attached to the top border of the reference, but from the inside<br>
	 * 		&emsp;&emsp;center: To layout the element at the vertical center of the reference<br>
	 * 		&emsp;&emsp;bottom: To layout the element attached to the lower border of the reference, but from the inside<br>
	 * 		&emsp;&emsp;bottomOut: To layout the element below the lower border of the reference<br>
	 * 		Examples: 'left top' 'center center' 'rightOut bottom' ...
	 * @param offsetX 0 by default Used to displace the horizontal center by the specified amount
	 * @param offsetY 0 by default. Used to displace the vertical center by the specified amount
	 * @param keepInsideViewPort True by default. Is used to force the element inside the main browser window area in case the centering process makes it partially invisible outside the browser window.
	 * 
	 * @return void
	 */
	centerElementTo : function(element, reference, mode, offsetX, offsetY, keepInsideViewPort){

		// Set default values if they are not defined
		reference = (reference instanceof jQuery || $.isArray(reference)) ? reference : undefined;
		mode = mode === undefined ? 'center center' : mode;
		offsetX = offsetX === undefined ? 0 : offsetX;
		offsetY = offsetY === undefined ? 0 : offsetY;
		keepInsideViewPort = keepInsideViewPort === undefined ? true : keepInsideViewPort;

		// Check if the element to be centered exists.
		if(element.length != 1){

			throw new Error("LayoutUtils.centerElement - Element to center does not exist or is not a unique object");
		}

		// Element to center must be absolte or fixed positioned, otherwise centering it is nonsense
		if(element.css('position') != 'absolute' && element.css('position') != 'fixed'){

			throw new Error("LayoutUtils.centerElement - Element to center must have absolute or fixed value for css position property, but is " + element.css('position') + ". Element contents: " + element.html());
		}

		// Check if the reference element exists.
		if(reference instanceof jQuery){

			if(reference.length != 1){

				throw new Error("LayoutUtils.centerElement - Reference element does not exist or is not a unique object");
			}
		}

		// Margins must be removed on the element to center
		element.css("margin", 0);

		// Get the element size
		var elementWidth = element.outerWidth();
		var elementHeight = element.outerHeight();

		// If the element position is fixed, window references are simply 0
		var windowLeftForFixed = (element.css('position') == 'fixed') ? 0 : $(window).scrollLeft();
		var windowTopForFixed = (element.css('position') == 'fixed') ? 0 : $(window).scrollTop();

		// Get the reference element size (or the main window if no reference element specified)
		var referenceWidth = $.isArray(reference) ? reference[2] : (reference === undefined) ? $(window).width() : reference.outerWidth();
		var referenceHeight = $.isArray(reference) ? reference[3] : (reference === undefined) ? $(window).height() : reference.outerHeight();

		// Get the reference element coordinates (or the main window ones)
		var referenceLeft = $.isArray(reference) ? reference[0] : (reference === undefined) ? windowLeftForFixed : reference.offset().left;
		var referenceTop = $.isArray(reference) ? reference[1] : (reference === undefined) ? windowTopForFixed : reference.offset().top;

		// These variables will store the final calculated element coordiantes
		var elementLeft = 0;
		var elementTop = 0;

		// Perform the horizontal centering of the element
		switch(mode.split(' ')[0]){

			case 'none':
				elementLeft = 'none';
				break;

			case 'leftOut':
				elementLeft = offsetX + referenceLeft - elementWidth;
				break;

			case 'left':
				elementLeft = offsetX + referenceLeft;
				break;

			case 'center':
				elementLeft = offsetX + referenceLeft + (referenceWidth - elementWidth) / 2;
				break;

			case 'right':
				elementLeft = offsetX + referenceLeft + referenceWidth - elementWidth;
				break;

			case 'rightOut':
				elementLeft = offsetX + referenceLeft + referenceWidth;
				break;

			default:
				throw new Error("LayoutUtils.centerElement - mode parameter value is wrong");
		}

		// Perform the vertical centering of the element
		switch(mode.split(' ')[1]){

			case 'none':
				elementTop = 'none';
				break;

			case 'topOut':
				elementTop = offsetY + referenceTop - elementHeight;
				break;

			case 'top':
				elementTop = offsetY + referenceTop;
				break;

			case 'center':
				elementTop = offsetY + referenceTop + (referenceHeight - elementHeight) / 2;
				break;

			case 'bottom':
				elementTop = offsetY + referenceTop + referenceHeight - elementHeight;
				break;

			case 'bottomOut':
				elementTop = offsetY + referenceTop + referenceHeight;
				break;

			default:
				throw new Error("LayoutUtils.centerElement - mode parameter value is wrong");
		}

		// Check that the element does not fall outside the screen
		if(keepInsideViewPort){

			if(elementLeft < windowLeftForFixed){

				elementLeft = windowLeftForFixed;
			}

			if(elementLeft + elementWidth > windowLeftForFixed + $(window).width()){

				elementLeft = windowLeftForFixed + $(window).width() - elementWidth;
			}

			if(elementTop < windowTopForFixed){

				elementTop = windowTopForFixed;
			}

			if(elementTop + elementHeight > windowTopForFixed + $(window).height()){

				elementTop = windowTopForFixed + $(window).height() - elementHeight;
			}
		}

		// Position the element where it's been calculated
		if(elementLeft !== 'none'){

			element.css("left", elementLeft + "px");
		}

		if(elementTop !== 'none'){

			element.css("top", elementTop + "px");
		}
	}
};