"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

// Import namespaces
var utils = org_turbocommons_src_main_js_utils;


QUnit.module("HtmlUtilsTest");


/**
 * idExists
 */
QUnit.test("idExists", function(assert){

	// Test for random ids that must not exist on the page
	assert.ok(utils.HtmlUtils.idExists('asdfasdfaer3245345cxg') === false);
	assert.ok(utils.HtmlUtils.idExists('1234123434ertret45fbvvn') === false);
	assert.ok(utils.HtmlUtils.idExists('vcbf4gqxsef4ryghvhyj7t') === false);

	// Add an element to the page and test that it exists
	var div1 = $('<div id="HtmlUtilsTest-div1"></div>');
	$("body").append(div1);

	assert.ok(utils.HtmlUtils.idExists('HtmlUtilsTest-div1') === true);

	div1.remove();

	assert.ok(utils.HtmlUtils.idExists('HtmlUtilsTest-div1') === false);
});


/**
 * findDuplicateIds
 */
QUnit.test("findDuplicateIds", function(assert){

	// Test that no dupes exist on the page
	assert.ok(utils.HtmlUtils.findDuplicateIds() === false);

	assert.ok(utils.HtmlUtils.idExists('HtmlUtilsTest-div1') === false);

	// Create two divs with same id and add them to the page
	var div1 = $('<div id="HtmlUtilsTest-div1"></div>');
	var div2 = $('<div id="HtmlUtilsTest-div1"></div>');

	$("body").append(div1);
	$("body").append(div2);

	// Test that exception happens when calling the findDuplicateIds method
	assert.throws(function(){

		utils.HtmlUtils.findDuplicateIds();
	});

	// Remove the generated divs and verify the id is not found anymore
	div1.remove();
	div2.remove();
	assert.ok(utils.HtmlUtils.idExists('HtmlUtilsTest-div1') === false);
});


/**
 * generateUniqueId
 */
QUnit.test("generateUniqueId", function(assert){

	// Generate multiple unique ids and add them to the document
	var ids = [];

	for(var i = 0; i < 20; i++){

		// We pass a prefix value every two loop cycles
		var idPrefix = i % 2 == 0 ? 'generatedId' : undefined;
		var idAssert = (idPrefix === undefined) ? 'id-' : (idPrefix + '-');

		ids.push(utils.HtmlUtils.generateUniqueId(idPrefix));

		var id = ids[ids.length - 1];

		assert.ok(id.indexOf(idAssert) === 0);
		assert.ok(utils.HtmlUtils.idExists(id) === false);

		$("body").append($('<div id="' + id + '"></div>'));
	}

	// Remove all previously generated divs
	for(i = 0; i < ids.length; i++){

		assert.ok(utils.HtmlUtils.idExists(ids[i]) === true);

		$("#" + ids[i]).remove();

		assert.ok(utils.HtmlUtils.idExists(ids[i]) === false);
	}
});


// TODO: add missing tests