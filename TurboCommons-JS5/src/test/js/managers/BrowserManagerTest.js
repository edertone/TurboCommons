"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

//Import namespaces
var utils = org_turbocommons_src_main_js_utils;
var managers = org_turbocommons_src_main_js_managers;

QUnit.module("BrowserManagerTest");


/**
 * isLoaded
 */
QUnit.test("isLoaded", function(assert){

	var browserManager = managers.BrowserManager.getInstance();

	assert.ok(browserManager.isLoaded() === true);
});


/**
 * reloadPage
 * 
 * Tests are not required for this method
 */


/**
 * getPreferredLanguage
 */
QUnit.test("getPreferredLanguage", function(assert){

	var browserManager = managers.BrowserManager.getInstance();
	var validationManager = new managers.ValidationManager();

	assert.ok(validationManager.isString(browserManager.getPreferredLanguage()));
	assert.ok(browserManager.getPreferredLanguage().length == 2);
	assert.ok(validationManager.isFilledIn(browserManager.getPreferredLanguage()));
});


/**
 * goToUrl
 */
QUnit.test("goToUrl", function(assert){

	// TODO
	assert.ok(true);

	// TODO: Not much tests are possible on this method, but try thinking in something
});


/**
 * disableScroll
 */
QUnit.test("disableScroll", function(assert){

	// TODO
	assert.ok(true);
});


/**
 * enableScroll
 */
QUnit.test("enableScroll", function(assert){

	// TODO
	assert.ok(true);
});


/**
 * getScrollPosition
 */
QUnit.test("getScrollPosition", function(assert){

	var browserManager = managers.BrowserManager.getInstance();

	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 0]));
});


/**
 * scrollTo
 */
QUnit.test("scrollTo", function(assert){

	var browserManager = managers.BrowserManager.getInstance();

	// Test wrong values
	assert.throws(function(){

		browserManager.scrollTo(-1, 2);
	});

	assert.throws(function(){

		browserManager.scrollTo(10, []);
	});

	assert.ok(browserManager.scrollTo(null, null) === false);
	assert.ok(browserManager.scrollTo(null, null, 2000) === false);

	// Add a big div that exceeds the display height with some internal links and targets
	var div = $('<div style="position:absolute;z-index:999999;top:0px;left:0px;width:5300px;height:10000px"></div>');
	$("body").append(div);

	// Test the scroll movements
	browserManager.scrollTo(0, 0, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 0]));

	browserManager.scrollTo(0, 2000, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 2000]));

	browserManager.scrollTo(0, 0, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 0]));

	browserManager.scrollTo(3000, 5000, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [3000, 5000]));

	browserManager.scrollTo(null, 8000, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [3000, 8000]));

	browserManager.scrollTo(0, 0, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 0]));

	// Remove the test div
	div.remove();
});


/**
 * setAnimatedScroll
 */
QUnit.test("setAnimatedScroll", function(assert){

	var browserManager = managers.BrowserManager.getInstance();

	// Test wrong values
	assert.throws(function(){

		browserManager.setAnimatedScroll([]);
	});

	assert.throws(function(){

		browserManager.setAnimatedScroll(true, 'hello');
	});

	assert.throws(function(){

		browserManager.setAnimatedScroll(true, 10, []);
	});

	assert.throws(function(){

		browserManager.setAnimatedScroll(true, 1, 1, []);
	});

	assert.throws(function(){

		browserManager.setAnimatedScroll(true, 1, 1, [], 9);
	});

	browserManager.setAnimatedScroll(true, 0);

	// Add a big div that exceeds the display height with some internal links and targets
	var div = $('<div style="position:absolute;z-index:999999;top:0px;left:0px;width:300px;height:10000px"></div>');

	var anchorTestLink1 = $('<a href="#" >undefined link</a>');
	var anchorTestLink2 = $('<a href="#anchorTestTarget1" >link1</a>');
	var anchorTestLink3 = $('<a href="#anchorTestTarget2" >link2</a>');

	var anchorTestTarget1 = $('<div id="anchorTestTarget1" style="position:absolute;top:500px;width:50px;height:50px;background-color:#ff0000"></div>');
	var anchorTestTarget2 = $('<div id="anchorTestTarget2" style="position:absolute;top:7500px;width:50px;height:50px;background-color:#00ff00"></div>');

	$("body").append(div);
	div.append(anchorTestLink1);
	div.append(anchorTestLink2);
	div.append(anchorTestLink3);
	div.append(anchorTestTarget1);
	div.append(anchorTestTarget2);

	// Test animations work as expected
	anchorTestLink2.trigger("click");
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 500]));

	anchorTestLink3.trigger("click");
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 7500]));

	browserManager.setAnimatedScroll(false);

	browserManager.scrollTo(0, 0, 0);
	assert.ok(utils.ArrayUtils.isEqualTo(browserManager.getScrollPosition(), [0, 0]));

	// Remove the test div
	div.remove();
});