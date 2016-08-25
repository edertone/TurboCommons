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
});


/**
 * goToUrl
 */
QUnit.test("goToUrl", function(assert){

	// TODO
	assert.ok(true);

	// TODO: Not much tests are possible on this method, but try to add as much as possible
});


/**
 * setAnimatedScroll
 */
QUnit.test("setAnimatedScroll", function(assert){

	var browserManager = managers.BrowserManager.getInstance();

	assert.ok(browserManager.isLoaded() === true);

	// Make sure no exception happens when enabling and disabling animated scroll
	browserManager.setAnimatedScroll(true);

	browserManager.setAnimatedScroll(false);

	// TODO: Not much tests are possible on this method, but try to add as much as possible

});