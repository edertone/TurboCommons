"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


/**
 * SINGLETON General purpose popup management class
 * import path: 'js/libs/libEdertoneJS/managers/PopUpManager.js'
 */
var PopUpManager = {

	_popUpManager : null,

	getInstance : function(){

		if(!this._popUpManager){

			this._popUpManager = {

				/**
				 * Method that generates all the behaviour for a given button and its related menu (normally some kind of floating / popup menu), so once button is clicked the menu is shown / hidden.
				 * Note1: The css property that is used to show and hide the menu element is "visibility"
				 * Note2: The menu positioning and layout is not modified by this method, so it must be defined via the respective css on the menu element.
				 * Note3: Rollover show / hide is also possible by setting the enableRollOver parameter to true
				 * 
				 * @param button A JQuery object that represents the button that will control the related menu element
				 * @param menu A JQuery object that represents the menu that will be shown and hidden by the related button element. Visibility is the css property that is used to show or hide this menu. 
				 * @param buttonOnAplha The button opacity when menu is hidden. .8 by default
				 * @param buttonOffAlpha The button opacity when menu is shown. 1 by default
				 * @param isMenuInitiallyVisible Tells if the menu will be visible at the beginning or not. False by default
				 * @param closeIfClickOutside Menu will be hidden if the user click anywhere outside it. True by default
				 * @param enableRollOver Rollover the button will also show and hide the menu. False by default
				 * @param animationIn Type of animation to use when the menu is shown. None by default. (Requires animate.css library, see its docs for + info on animation types, but some values can be: fadeInDown, bounceIn, ...)
				 * @param animationInDuration The time (in miliseconds) it will take for the animation to perform when showing the menu. 1000 by default
				 * @param animationOut Type of animation to use when the menu is hidden. None by default. (Requires animate.css library, see its docs for + info on animation types, but some values can be: fadeOutDown, bounceOut, ...)
				 * @param animationOutDuration The time (in miliseconds) it will take for the animation to perform when hiding the menu. 1000 by default
				 * @param closeIfClickInside Menu will be hidden if the user clicks inside it. False by default, cause normally the menu will load a new url, so everything will be reloaded. If our app is single page, we may want to set this to true to make sure the menu is hidden once it is clicked, as page may not be reloaded.
				 */
				attachMenuToButton : function(button, menu, buttonOnAplha, buttonOffAlpha, isMenuInitiallyVisible, closeIfClickOutside, enableRollOver, animationIn, animationInDuration, animationOut, animationOutDuration, closeIfClickInside){

					// Set optional parameters default values
					buttonOnAplha = (buttonOnAplha === undefined) ? ".8" : buttonOnAplha;
					buttonOffAlpha = (buttonOffAlpha === undefined) ? "1" : buttonOffAlpha;
					isMenuInitiallyVisible = (isMenuInitiallyVisible === undefined) ? false : isMenuInitiallyVisible;
					closeIfClickOutside = (closeIfClickOutside === undefined) ? true : closeIfClickOutside;
					enableRollOver = (enableRollOver === undefined) ? false : enableRollOver;
					animationIn = (animationIn === undefined) ? "" : animationIn;
					animationInDuration = (animationInDuration === undefined) ? 1000 : animationInDuration;
					animationOut = (animationOut === undefined) ? "" : animationOut;
					animationOutDuration = (animationOutDuration === undefined) ? 1000 : animationOutDuration;
					closeIfClickInside = (closeIfClickInside === undefined) ? false : closeIfClickInside;

					// Set the initial state for the menu and button
					button.css("opacity", (isMenuInitiallyVisible) ? buttonOnAplha : buttonOffAlpha);
					button.data("PopUpManagerButtonState", (isMenuInitiallyVisible) ? "ON" : "OFF");
					menu.css("visibility", (isMenuInitiallyVisible) ? "visible" : "hidden");

					function showMenu(){

						button.css("opacity", buttonOnAplha);

						if(animationIn != ""){

							menu.addClass('animated ' + animationIn);
							menu.css("animation-duration", animationInDuration + "ms");

							menu.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){

								menu.removeClass(animationIn);
								menu.removeClass('animated');
								menu.css("animation-duration", "");
							});
						}

						menu.css("visibility", "visible");
					}

					function hideMenu(){

						button.css("opacity", buttonOffAlpha);

						// If an animation exists, we must wait till it ends
						if(animationIn != ""){

							menu.addClass('animated ' + animationOut);
							menu.css("animation-duration", animationOutDuration + "ms");

							menu.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){

								menu.removeClass(animationOut);
								menu.removeClass('animated');
								menu.css("animation-duration", "");
								menu.css("visibility", "hidden");
							});

						}else{

							menu.css("visibility", "hidden");
						}
					}

					// Attach the click listener to the button that will show and hide the related menu
					button.click(function(){

						// Detect if the menu is selected or not
						if(button.data("PopUpManagerButtonState") == "OFF"){

							button.data("PopUpManagerButtonState", "ON");
							showMenu();

						}else{

							button.data("PopUpManagerButtonState", "OFF");
							hideMenu();
						}
					});

					// Check if menu must be hidden when mouse clicks outside
					if(closeIfClickOutside || closeIfClickInside){

						$(document).mousedown(function(e){

							if(menu.css("visibility") == "visible"){

								var container = menu;

								if(closeIfClickOutside){

									// if the target of the click isn't the container, nor a descendant of the container,
									// nor the mobile menu button, nor a descendant of the mobile menu button,
									if(!container.is(e.target) && container.has(e.target).length === 0 && !$(e.target).is(button) && button.has(e.target).length === 0){

										if(button.data("PopUpManagerButtonState") == "ON"){

											button.data("PopUpManagerButtonState", "OFF");
										}

										hideMenu();
									}
								}

								if(closeIfClickInside){

									// if the target of the click is the container, or a descendant of the container
									if(container.is(e.target) || container.has(e.target).length !== 0){

										if(button.data("PopUpManagerButtonState") == "ON"){

											button.data("PopUpManagerButtonState", "OFF");
										}

										hideMenu();
									}

								}
							}

						});
					}

					// Check if mouse roll over must show and hide the menus
					if(!enableRollOver){

						return;
					}

					function checkTimeOut(){

						setTimeout(function(){

							if(!menu.is(':hover') && !button.is(':hover') && button.data("PopUpManagerButtonState") == "OFF"){

								hideMenu();

								setTimeout(function(){

									buttonMouseEnter();

								}, animationOutDuration + 50);

							}else{

								checkTimeOut();
							}

						}, 300);
					}

					function buttonMouseEnter(){

						if(button.is(':hover') && button.data("PopUpManagerButtonState") == "OFF"){

							showMenu();
						}
					}

					button.mouseenter(buttonMouseEnter);
					button.mouseleave(checkTimeOut);
				},


				/**
				 * TODO: Crear un menu tipus google play que es pugui obrir deslliçant el dit des de fora
				 */
				attachSwipeMenuToButton : function(button, menu){

					// TODO

				},


				/**
				 * Attaches the specified tooltip to the specified element.
				 * 
				 * @param element A jquery object that will represent the element where the tooltip will be attached
				 * @param tooltip A jquery object that will represent the tooltip that will be attached to the specified element
				 * @param fadeInDelay 300ms by default, the time it will take for the tooltip to start appearing 
				 * @param fadeInDuration 300ms by default, the time it will take for the fade in effect to complete 
				 * @param fadeOutDelay 50ms by default, the time it will take for the tooltip to start disappearing
				 * @param fadeOutDuration 100ms by default, the time it will take for the fade out effect to complete
				 * @param layoutMode 'center topOut' by default, The centering mode for the tooltip. More information at: LayoutUtils.centerElementTo
				 * @param offsetX 0 by default, The horizontal centering offset for the tooltip. More information at: LayoutUtils.centerElementTo
				 * @param offsetY 5 by default, The vertical centering offset for the tooltip. More information at: LayoutUtils.centerElementTo
				 * @param keepIfMouseOver false by default. If set to true, when the mouse is over the tool tip, it won't disappear.
				 * @param showMode 0 by default. Defines the behaviour that will make the tool tip visible: 0 - on mouse over, 1 - when the user clicks on the element, 2 - on both
				 * 
				 * @see LayoutUtils.centerElementTo()
				 * 
				 * @returns void
				 */
				attachToolTipToElement : function(element, tooltip, fadeInDelay, fadeInDuration, fadeOutDelay, fadeOutDuration, layoutMode, offsetX, offsetY, keepIfMouseOver, showMode){

					// Set default values if they are not defined
					fadeInDelay = fadeInDelay === undefined ? 300 : fadeInDelay;
					fadeInDuration = fadeInDuration === undefined ? 300 : fadeInDuration;
					fadeOutDelay = fadeOutDelay === undefined ? 50 : fadeOutDelay;
					fadeOutDuration = fadeOutDuration === undefined ? 100 : fadeOutDuration;
					layoutMode = layoutMode === undefined ? 'center topOut' : layoutMode;
					offsetX = offsetX === undefined ? 0 : offsetX;
					offsetY = offsetY === undefined ? 5 : offsetY;
					keepIfMouseOver = keepIfMouseOver === undefined ? false : keepIfMouseOver;
					showMode = showMode === undefined ? 0 : showMode;

					// We must make sure that the tooltip is hidden by default
					tooltip.hide();

					// Define the timeout handlers
					var timeOutHandler = null;
					var fadeOutTimeOutHandler = null;

					// The method to handle the mose entering the element that will show the toolitp
					function onElementMouseEnter(){

						// Enable the timer
						timeOutHandler = setTimeout(function(){

							// If timer finishes, we will stop listening the click, mouse enter and leave events
							element.off('mouseenter', onElementMouseEnter);
							element.off('mouseleave', onElementMouseLeave);
							element.off('click', onElementMouseEnter);

							// Move the tooltip to front and center it
							LayoutUtils.moveToFront(tooltip);
							LayoutUtils.centerElementTo(tooltip, element, layoutMode, offsetX, offsetY);

							// Apply the specified animation to the tooltip
							tooltip.stop(true, true).fadeIn(fadeInDuration);

							// Listen to the mouse move event till the pointer is outside the element to remove the tooltip
							$(window).on('mousemove.attachToolTip' + tooltip.index(), function onToolTipMouseMove(e){

								if(LayoutUtils.isElementLocatedAt(element, e.pageX, e.pageY)){

									clearTimeout(fadeOutTimeOutHandler);
									fadeOutTimeOutHandler = null;
									return;
								}

								if(keepIfMouseOver){

									if(LayoutUtils.isElementLocatedAt(tooltip, e.pageX, e.pageY)){

										clearTimeout(fadeOutTimeOutHandler);
										fadeOutTimeOutHandler = null;
										return;
									}
								}

								if(fadeOutTimeOutHandler == null){

									fadeOutTimeOutHandler = setTimeout(function(){

										$(window).off('mousemove.attachToolTip' + tooltip.index(), onToolTipMouseMove);

										tooltip.fadeOut(fadeOutDuration, function(){

											if(showMode == 0 || showMode == 2){

												// Enable the mose enter / leave events again
												element.on('mouseenter', onElementMouseEnter);
												element.on('mouseleave', onElementMouseLeave);
											}

											if(showMode == 1 || showMode == 2){

												element.on('click', onElementMouseEnter);
											}
										});

									}, fadeOutDelay);
								}
							});

						}, fadeInDelay);
					}

					function onElementMouseLeave(){

						// Cancel the timer once mouse leaves the element
						clearTimeout(timeOutHandler);
					}

					if(showMode == 0 || showMode == 2){

						element.on('mouseenter', onElementMouseEnter);
						element.on('mouseleave', onElementMouseLeave);
					}

					if(showMode == 1 || showMode == 2){

						element.on('click', onElementMouseEnter);
					}
				},


				/**
				 * Opens the specified URL in a new standalone browser window that can have a custom defined size.
				 * Not recomended on multi device apps, cause it will have unexpected behaviours on mobile browsers.
				 * 
				 * @param url the address that will be opened on the standalone browser window 
				 * @param title The title that will be displayed on the top of the browser window that will contain the specified url
				 * @param size full by default. The browser window dimensions. We can define a WidthxHeight value or set it to 'full'. for example: 250x100, 600x400, full, ...
				 * @param showCentered True by default. Tells if the window will be shown centered on screen or not
				 *
				 * @returns void
				 */
				openBrowserWindow : function(url, title, size, showCentered){

					// Set default values if they are not defined
					title = title === undefined ? '' : title;
					size = size === undefined ? 'full' : size;
					showCentered = showCentered === undefined ? true : showCentered;

					// Detect the window dimensions
					var winW = screen.width - 20;
					var winH = screen.height - 120;

					if(size == 'full'){

						if(parseInt(navigator.appVersion) > 3){

							if(navigator.appName == 'Netscape'){

								if(winW < window.innerWidth){

									winW = window.innerWidth;
								}

								if(winH < window.window.innerHeight){

									winH = window.innerHeight;
								}
							}

							if(navigator.appName.indexOf('Microsoft') != -1){

								if(winW < document.body.offsetWidth){

									winW = document.body.offsetWidth;
								}

								if(winH < document.body.offsetHeight){

									winH = document.body.offsetHeight;
								}
							}
						}

					}else{

						winW = size.split('x')[0];
						winH = size.split('x')[1];
					}

					windowFeatures = 'width=' + winW + ',height=' + winH + ',resizable=1,scrollbars=yes';

					// Center the window if necessary
					if(size != 'full' && showCentered){

						windowFeatures += ',top=' + (screen.height / 2 - winH / 2) + ',left=' + (screen.width / 2 - winW / 2);
					}

					newwin = window.open(url, title, windowFeatures);

					if(javascript_version > 1.0){

						setTimeout(function(){

							newwin.focus();

						}, 250);
					}
				},


				/**
				 * Darkens the current screen and adds a simple busy cursor to the pointer. Note that mobile devices won't show any cursor, only the darkened screen
				 * 
				 * @param backgroundAlpha Default .4, defines the alpha for the darkened background that will be shown
				 * 
				 * @returns void
				 */
				showBusyCursor : function(backgroundAlpha){

					// Set default values if they are not defined
					backgroundAlpha = backgroundAlpha === undefined ? 0.4 : backgroundAlpha;

					// We will only add the busy cursor layer if not already present
					if($('#popUpManagerShowBusyCursorAddedCursor-kiUGft5367uh').length <= 0){

						// Define the busy cursor layer as a full screen div
						var cursor = $('<div id="popUpManagerShowBusyCursorAddedCursor-kiUGft5367uh" style="cursor:wait;position:fixed;top:0;left:0;right:0;bottom:0;opacity:' + backgroundAlpha + ';background-color:#000000"></div>');

						$("body").append(cursor);

						// Move the cursor to the front of the current document
						LayoutUtils.moveToFront(cursor);

						// Apply fade in effect			
						cursor.css("transition", "opacity " + 150 + "ms ease-out");
						cursor.css("opacity", backgroundAlpha);
					}
				},


				/**
				 * Removes a previously added busy cursor
				 * 
				 * @returns void
				 */
				removeBusyCursor : function(){

					var cursor = $('#popUpManagerShowBusyCursorAddedCursor-kiUGft5367uh');

					if(cursor.length > 0){

						// Play cursor fadeout effects
						cursor.css("transition", "opacity 100ms ease-out");
						cursor.css("opacity", "0");

						// Wait till the fade effects finish to remove the cursor layer
						setTimeout(function(){

							cursor.remove();

							cursor = null;

						}, 100);
					}
				},


				/**
				 * Shows the provided jquery element as a modal popup. 
				 * 
				 * @param element The jquery element we want to show as a modal popup. It is mandatory to set its css display value to "display: none" by default. 
				 * @param backgroundAlpha 0.8 bu default. The alpha for the background area below the popup element
				 * @param fadeInDuration 500 by default. The duration of the showing fade effect in miliseconds
				 * @param closeIfClickOutside True by default. The popup will be removed from screen if the user clicks anywhere outside it
				 * @param backgroundColor The color for the area below the popup element (that will be also affected by backgroundAlpha parameter) 
				 * @param layoutMode 'center center' by default, The centering mode for the popup. More information at: LayoutUtils.centerElementTo
				 * 
				 * @see LayoutUtils.centerElementTo()
				 * 
				 * @returns void
				 */
				showModalPopUp : function(element, backgroundAlpha, fadeInDuration, closeIfClickOutside, backgroundColor, layoutMode){

					// Set default values if they are not defined
					backgroundAlpha = backgroundAlpha === undefined ? 0.8 : backgroundAlpha;
					fadeInDuration = fadeInDuration === undefined ? 500 : fadeInDuration;
					closeIfClickOutside = closeIfClickOutside === undefined ? true : closeIfClickOutside;
					backgroundColor = backgroundColor === undefined ? '#000000' : backgroundColor;
					layoutMode = layoutMode === undefined ? 'center center' : layoutMode;

					HtmlUtils.disableScrolling();

					// Create the mask main container
					var maskId = HtmlUtils.generateUniqueId('popupMask');

					var mask = $('<div id="' + maskId + '" style="position:fixed;top:0px;left:0px;right:0px;bottom:0px;"></div>');

					// Attach the main background and the popup element wrapper inside the mask container
					mask.append($('<div style="position:fixed;top:0;left:0;right:0;bottom:0;opacity:0;background-color:' + backgroundColor + '"></div>'));
					mask.append($('<div style="position:fixed;top:0;left:0;right:0;bottom:0;opacity:0"></div>'));

					// Attach the element to the popup wrapper 
					mask.children().eq(1).append(element);

					// Force position to fixed and display to inline. Element won't be visible yet cause it is inside the popup wrapper
					element.css("position", "fixed");
					element.css("display", "inline");

					$("body").append(mask);

					// Move the mask to the front of the current document
					LayoutUtils.moveToFront(mask);

					// Define a function to layout the element on screen
					function calculatePopUpLayOut(){

						LayoutUtils.centerElementTo(element, mask, layoutMode);
					}

					// TODO: queda pendent evitar que el focus es surti dels elements del popup cap altres parts de la app

					// Relocate the elements one first time
					calculatePopUpLayOut();

					// Listen for window resize events to relocate the elements
					$(window).on("resize." + maskId, calculatePopUpLayOut);

					// Apply fade in effects to the mask bg and the element wrapper					
					mask.children().eq(0).css("transition", "opacity " + fadeInDuration / 2 + "ms ease-out");
					mask.children().eq(0).css("opacity", backgroundAlpha);
					mask.children().eq(1).css("transition", "opacity " + fadeInDuration + "ms linear");
					mask.children().eq(1).css("opacity", "1");

					// Wait till the fade effects finish to start listening popup events
					setTimeout(function(){

						// Popup will be closed if the ESC key is pressed
						$(document).on('keyup.' + maskId, function(e){

							if(e.keyCode == 27){

								// The removeModalPopUp method will take care of removing all event handlers
								PopUpManager.getInstance().removeModalPopUp(element);
							}
						});

						// Check if popup should be hidden when user clicks outside it
						if(closeIfClickOutside){

							$(document).on("mousedown." + maskId, function(e){

								if(!element.is(e.target) && element.has(e.target).length === 0){

									$(document).on("mouseup." + maskId, function(){

										PopUpManager.getInstance().removeModalPopUp(element);
									});
								}
							});
						}

						// Check if history api is enabled
						if(window.history && window.history.pushState){

							// TODO: cal testejar el funcionament dels history states i tal, que encara té alguns flecos: probar en diferents navegadors, endavant, endarrera, etc...

							// Change the current browser url to the popup id
							window.history.pushState(null, "", maskId + ".html");

							// Close the popup when the back button is pressed
							$(window).on("popstate." + maskId, function(){

								PopUpManager.getInstance().removeModalPopUp(element);
							});
						}

						element.focus();

					}, fadeInDuration);
				},


				/**
				 * Remove an existing modal popup from the screen.
				 * 
				 * @param element A jquery element that is currently being shown as a modal popup. 
				 * @param destroy false by default. If true, once the popup element is removed it will be totally destroyed. If false it will be appended again to the root of the body, so it can be reused.
				 * 
				 * @returns void
				 */
				removeModalPopUp : function(element, destroy){

					// Set default values if they are not defined
					destroy = destroy === undefined ? false : destroy;

					var mask = element.parent().parent();

					// Make sure that the modal popup parent is a mask layer, to prevent big problems if the element is not a modal popup generated by this class
					if(mask.attr('id').indexOf('popupMask') < 0){

						return;
					}

					// Remove all possible event handlers
					$(window).off("resize." + mask.attr('id'));
					$(document).off("mousedown." + mask.attr('id'));
					$(document).off("mouseup." + mask.attr('id'));
					$(document).off('keyup.' + mask.attr('id'));
					$(window).off('popstate.' + mask.attr('id'));

					// If history api is avaliable, and the current url contains the mask id, we will force a back to revert the url to the previous value
					if(window.history && window.history.pushState && window.location.href.indexOf(mask.attr('id')) >= 0){

						window.history.back();
					}

					// Play popup fadeout effects
					mask.children().eq(0).css("transition", "opacity 300ms ease-out");
					mask.children().eq(0).css("opacity", "0");
					mask.children().eq(1).css("transition", "opacity 150ms ease-out");
					mask.children().eq(1).css("opacity", "0");

					// Wait till the fade effects finish to start listening popup events
					setTimeout(function(){

						// Add the element again to the main body and hide it or destroy it
						element.css("display", "none");

						if(destroy){

							element.remove();

							element = null;

						}else{

							$("body").append(element);
						}

						// Clear and destroy the mask
						mask.remove();
						mask = null;

						HtmlUtils.enableScrolling();

					}, 300);
				},


				/**
				 * Used to show an animated general purpose notification bar
				 * 
				 * @param content  The information that will be shown on the notification bar. We can use html data or plain text.
				 * @param link '' by default. Url that will be opened by the bar once it is clicked. If we leave it empty, the bar will not respond to click event.
				 * @param newWindow True by default. Tells the bar to open its link in a new browser window. If no link is specified, this parameter will do nothing.
				 * @param cssClass '' by default. Defines the css class we want to assign to the notification bar.
				 * @param closeButton '' by default. A path to an image that will be used as a button to hide the bar, or an 'X' if we want to use a simple X close button. If not specified, the bar will only be closeable when a link is set and clicked.
				 * @param position 'top' by default. The region of the screen where the notification bar will appear: top or bottom
				 * @param animateTime 1000 by default. The time in ms that will take the show and hide bar animation.
				 * 
				 * @returns Object The created notification bar as a jquery object.
				 */
				showNotificationBar : function(content, link, newWindow, cssClass, closeButton, position, animateTime){

					// Set default values if they are not defined
					link = link === undefined ? '' : link;
					newWindow = newWindow === undefined ? true : newWindow;
					cssClass = cssClass === undefined ? '' : cssClass;
					closeButton = closeButton === undefined ? '' : closeButton;
					position = position === undefined ? 'top' : position;
					animateTime = animateTime === undefined ? 1000 : animateTime;

					// Generate the main notification bar container
					var notificationBar = $('<div style="position:fixed;overflow:hidden;left:0px;width:100%;z-index:' + (LayoutUtils.getMaxZIndex() + 1) + '"></div>');

					notificationBar.css(position, '-1000px');

					// Check if the notification bar must open a specific link
					if(link != ""){

						notificationBar.css('cursor', 'pointer');

						notificationBar.one("click", function(){

							window.open(link, newWindow ? '_blank' : '_self');

							PopUpManager.getInstance().removeNotificationBar(notificationBar);
						});
					}

					// Add the styles to the notification bar
					if(cssClass == ''){

						notificationBar.css('background-color', '#fffbac');
						notificationBar.css(position == 'bottom' ? 'border-top' : 'border-bottom', '1px solid #6d6d6d');

					}else{

						notificationBar.addClass(cssClass);
					}

					// Add the content to the notification bar
					if(content.indexOf("<") < 0){

						content = '<p ' + ((cssClass == '') ? 'style="text-align:center;margin:7px 20px 7px 20px;color:#6d6d6d"' : '') + '>' + content + '</p>';
					}

					notificationBar.append(content);

					// Add the close button if exists
					if(closeButton != ""){

						closeButton = (closeButton == 'x' || closeButton == 'X') ? $('<a>' + closeButton + '</a>') : $('<img src="' + closeButton + '">');

						closeButton.css('position', 'absolute');
						closeButton.css('cursor', 'pointer');
						closeButton.css('right', '5px');

						notificationBar.append(closeButton);

						// Vertically center the button after fading it the same time as the notification bar needs to be shown
						closeButton.fadeIn(animateTime, function(){

							closeButton.css("top", (notificationBar.height() / 2) - (closeButton.height() / 2) + "px");
						});

						// Attach the click event
						closeButton.one("click", function(event){

							event.stopPropagation();

							PopUpManager.getInstance().removeNotificationBar(notificationBar);
						});
					}

					notificationBar.appendTo("body");

					// Store the actual body css values so we can restore them later
					var bodyOriginalPosition = $('body').css('position');
					var bodyOriginalMargin = parseFloat($('body').css('margin-' + position));

					// Set the body position to relative, so all absolutely positioned elements get also displaced if the body does
					$('body').css('position', 'relative');

					// Get the list of fixed elements that must be displaced with the body
					var elementsToDisplace = $('*').filter(function(){

						if(!$(this).is($('body')) && !$(this).is(notificationBar) && $(this).css('position') === 'fixed'){

							$(this).data('showNotificationBar-originalPosition', parseFloat($(this).css(position)));

							return true;
						}

						return false;
					});

					// Define a function to perform body and fixed elements displacement to accomodate the notification bar height
					function displaceBodyElements(t){

						if(position == 'top'){

							$('body').animate({
								'margin-top' : notificationBar.outerHeight() + 'px'
							}, t);

							elementsToDisplace.each(function(){

								$(this).animate({
									top : $(this).data('showNotificationBar-originalPosition') + notificationBar.outerHeight() + 'px'
								}, t);
							});

						}else{

							$('body').css('margin-bottom', notificationBar.outerHeight() + 'px');
						}
					}

					// Once window is loaded, we will show the bar and displace the body and fixed elements
					$(window).one('load', function(){

						// Place the bar perfectly above the top of the window
						notificationBar.css(position, -notificationBar.outerHeight() + 'px');

						if(position == 'top'){

							notificationBar.animate({
								top : 0
							}, animateTime);

						}else{

							notificationBar.animate({
								bottom : 0
							}, animateTime);
						}

						displaceBodyElements(animateTime);
					});

					// Listen for window resize events to relocate the elements
					$(window).on("resize.showNotificationBar", function(){

						displaceBodyElements(0);
					});

					// Listen the remove event on the bar so we can restore the body and displaced elements original values
					notificationBar.one("remove", function(){

						$('body').css('margin-' + position, bodyOriginalMargin + 'px');
						$('body').css('position', bodyOriginalPosition);

						elementsToDisplace.each(function(){

							$(this).css(position, $(this).data('showNotificationBar-originalPosition'));

							$(this).removeData('showNotificationBar-originalPosition');
						});
					});

					return notificationBar;
				},


				/**
				 * Remove an existing notification bar
				 * 
				 * @param notificationBar A notification bar jquery object, obtained from a previous call to the showNotificationBar method
				 * 
				 * @returns void
				 */
				removeNotificationBar : function(notificationBar){

					$(window).off("resize.showNotificationBar");

					notificationBar.trigger("remove");

					notificationBar.remove();

					notificationBar = null;
				},


				/**
				 * Used to show the legal cookies policy dialog. If the user accepts it, a cookie is used to prevent it from being shown again.
				 * 
				 * @param text The legal informative text that will be shown on the dialog. Only plain text is allowed here. Note that you can use the predefined Locales on the LibEdertonePhp library: App::importLocaleBundle('Legal', '', ProjectPaths::LIBS_EDERTONE_PHP_LOCALE);	App::setVar('LOC_COOKIES_WARNING', LOC_COOKIES_WARNING);
				 * @param moreInfoLink '' by default. An url that will be opened when the more info link is clicked 
				 * @param moreInfoLinkLabel '' by default. The text that will be shown on the more info link button label
				 * @param cssClass '' by default. Defines the css class we want to assign to the notification bar.
				 * @param position 'bottom' by default. The region of the screen where the notification bar will appear: top or bottom
				 * @param animateTime 1500 by default. The time in ms that will take the show bar animation.
				 * 
				 * @returns Object The created notification bar as a jquery object, or null if it was already accepted
				 */
				showCookiesPolicyDialog : function(text, moreInfoLink, moreInfoLinkLabel, cssClass, position, animateTime){

					// Set default values if they are not defined
					moreInfoLink = moreInfoLink === undefined ? '' : moreInfoLink;
					moreInfoLinkLabel = moreInfoLinkLabel === undefined ? '' : moreInfoLinkLabel;
					cssClass = cssClass === undefined ? '' : cssClass;
					position = position === undefined ? 'bottom' : position;
					animateTime = animateTime === undefined ? 1400 : animateTime;

					if(CookiesUtils.getCookie("cookiesPolicyWarningAccepted") != 1){

						if(cssClass == ""){

							var style = ' style="text-align:center;margin:2px;color:#6d6d6d;font-size:13px;line-height:19px;" ';
						}

						// Define the legal text
						text = '<p ' + style + '>' + text;

						// Define the more info button
						if(moreInfoLink != '' && moreInfoLinkLabel != ''){

							if(cssClass == ""){

								style = 'style="cursor:pointer;margin-left:5px;margin-right:5px;color: #0000ff;font-weight:bold" ';
								style += 'onMouseOver="this.style.color=\'#f60000\'" onMouseOut="this.style.color=\'#0000ff\'" onClick="window.location.href = \'' + moreInfoLink + '\'"';
							}

							text += '<span ' + style + '>' + moreInfoLinkLabel + '</span>';
						}

						// Define the close button
						if(cssClass == ""){

							style = 'style="cursor:pointer;margin-left:10px;color: #000000;font-weight:bold" onMouseOver="this.style.color=\'#f60000\'" onMouseOut="this.style.color=\'#000000\'"';
						}

						text += '<a ' + style + '>X</a>';

						text += '</p>';

						var notificationBar = PopUpManager.getInstance().showNotificationBar(text, "", false, cssClass, "", position, animateTime);

						// Vertically center the button after fading it the same time as the notification bar needs to be shown
						var closeButton = $(notificationBar.find('a'));

						// Attach the click event
						closeButton.one("click", function(event){

							event.stopPropagation();

							CookiesUtils.setCookie('cookiesPolicyWarningAccepted', 1, 999);

							PopUpManager.getInstance().removeNotificationBar(notificationBar);
						});
					}

					return notificationBar;
				}
			};
		}

		return this._popUpManager;
	}
};