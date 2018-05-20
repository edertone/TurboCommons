"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("FilesManagerTest", {
    beforeEach : function() {

        window.StringUtils = org_turbocommons.StringUtils;
        window.FilesManager = org_turbocommons.FilesManager;
    },

    afterEach : function() {

        delete window.StringUtils;
        delete window.FilesManager;
    }
});


/**
 * testIsFile
 */
QUnit.todo("testIsFile", function(assert){
    
    // TODO
});


// TODO - write all missing tests
// TODO - this class is really difficult cause it requires server side functionalities
// TODO - So the FilesManager typescript version is only being tested manually.