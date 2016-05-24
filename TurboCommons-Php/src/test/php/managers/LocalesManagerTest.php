<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace com\edertone\turboCommons\src\test\php\managers;

use Exception;
use PHPUnit_Framework_TestCase;
use com\edertone\turboCommons\src\main\php\managers\LocalesManager;


/**
 * LocalesManager tests
 *
 * @return void
 */
class LocalesManagerTest extends PHPUnit_Framework_TestCase {


	/**
	 * Test the locales manager when locales are classified in folders,
	 * each one named as the locale code in which its files are translated.
	 *
	 * @return void
	 */
	public function testLocalesAsFolders(){

		$localesManager = LocalesManager::getInstance();

		// Test EN_US
		$localesManager->locales = ['en_US', 'es_ES'];
		$localesManager->paths = [__DIR__.'/../../resources/managers/localesManager/test1'];

		$this->assertTrue($localesManager->get('PASSWORD', 'Locales') == 'Password');
		$this->assertTrue($localesManager->get('USER', 'Locales') == 'User');
		$this->assertTrue($localesManager->get('LOGIN', 'Locales') == 'Login');

		// Test ES_ES
		$localesManager->locales = ['es_ES', 'en_US'];

		$this->assertTrue($localesManager->get('PASSWORD', 'Locales') == 'Contraseña');
		$this->assertTrue($localesManager->get('USER', 'Locales') == 'Usuario');
		$this->assertTrue($localesManager->get('LOGIN', 'Locales') == 'Login');

		// Test tag that is missing on es_ES but found on en_US
		$this->assertTrue($localesManager->get('MISSING_TAG', 'Locales') == 'Missing tag');

		// Test tag that is missing everywhere
		$this->setExpectedException('Exception');
		$localesManager->get('NOT_TO_BE_FOUND', 'Locales');
	}
}

?>