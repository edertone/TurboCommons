<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\utils;

use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;
use stdClass;
use Exception;
use Throwable;


/**
 * ObjectUtils tests
 *
 * @return void
 */
class ObjectUtilsTest extends TestCase {


    /**
     * testIsObject
     *
     * @return void
     */
    public function testIsObject(){

        // test empty values
        $this->assertTrue(!ObjectUtils::isObject(null));
        $this->assertTrue(!ObjectUtils::isObject(''));
        $this->assertTrue(!ObjectUtils::isObject([]));
        $this->assertTrue(!ObjectUtils::isObject(0));
        $this->assertTrue(ObjectUtils::isObject(new stdClass()));

        // Test valid values
        $this->assertTrue(ObjectUtils::isObject(new Exception()));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            '1' => 1
        ])));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            'a' => 'hello'
        ])));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            'a' => 1,
            'b' => 2,
            'c' => 3
        ])));

        // Test invalid values
        $this->assertTrue(!ObjectUtils::isObject(874));
        $this->assertTrue(!ObjectUtils::isObject('hello'));
        $this->assertTrue(!ObjectUtils::isObject([123]));
        $this->assertTrue(!ObjectUtils::isObject([1, 'aaa']));
    }


	/**
	 * testGetKeys
	 *
	 * @return void
	 */
	public function testGetKeys(){

		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(new stdClass()), []));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'1' => 1
		])), ['1']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
			'a' => 1
		])), ['a']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'a' => 1,
				'b' => 2,
				'c' => 3
		])), ['a', 'b', 'c']));
		$this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
				'a' => 1,
				'b' => (object) [
						'a' => 1,
						'x' => 0
					],
				'c' => 3
		])), ['a', 'b', 'c']));

		// Test exceptions
		$exceptionMessage = '';

		try {
			ObjectUtils::getKeys(null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ObjectUtils::getKeys([]);
			$exceptionMessage = '[] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ObjectUtils::getKeys([1, 2, 3]);
			$exceptionMessage = '[1, 2, 3] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testIsEqualTo
	 *
	 * @return void
	 */
	public function testIsEqualTo(){

		// Test identic values
		$this->assertTrue(ObjectUtils::isEqualTo(new stdClass(), new stdClass()));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home'
		]), ((object) [
				'hello' => 'home'
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
			'1' => 1
		]), ((object) [
			'1' => 1
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1
		]), ((object) [
				'hello' => 'home',
				'number' => 1
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		]), ((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'array' => ((object) [
						'hello' => 'home',
						'number' => 1
				])
		]), ((object) [
				'hello' => 'home',
				'array' => ((object) [
						'hello' => 'home',
						'number' => 1
				])
		])));

		// Test same values but with different key order
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
		    'number' => 1,
		    'hello' => 'home',
		    'array' => [1, 2, 3]
		]), ((object) [
		    'hello' => 'home',
		    'number' => 1,
		    'array' => [1, 2, 3]
		])));
		$this->assertTrue(ObjectUtils::isEqualTo(((object) [
		    'hello' => 'home',
		    'array' => ((object) [
		        'hello' => 'home',
		        'number' => 1
		    ])
		]), ((object) [
		    'array' => ((object) [
		        'number' => 1,
		        'hello' => 'home'
		    ]),
		    'hello' => 'home'
		])));

		// Test different values
		$this->assertTrue(!ObjectUtils::isEqualTo(new stdClass(), ((object) [
				'1' => 1
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'1' => 1
		]), ((object) [
				'1' => 2
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'guys'
		]), ((object) [
				'1' => 2
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'guys'
		]), ((object) [
				'hell' => 'guys'
		])));
		$this->assertTrue(!ObjectUtils::isEqualTo(((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 3]
		]), ((object) [
				'hello' => 'home',
				'number' => 1,
				'array' => [1, 2, 3]
		])));

		// Test exceptions with non objects
		$exceptionMessage = '';

		try {
			ObjectUtils::isEqualTo(null, null);
			$exceptionMessage = 'null did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ObjectUtils::isEqualTo([], []);
			$exceptionMessage = '[] did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		try {
			ObjectUtils::isEqualTo('hello', 'hello');
			$exceptionMessage = 'hello did not cause exception';
		} catch (Throwable $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}


	/**
	 * testMerge
	 *
	 * @return void
	 */
	public function testMerge(){

	    // Test empty values
	    // TODO - translate from ts

	    // Test ok values
	    // TODO - translate from ts

	    // Test wrong values
	    // TODO - translate from ts

	    // Test exceptions
	    // TODO - translate from ts
	    $this->markTestIncomplete('This test has not been implemented yet.');
	}


	/**
	 * testClone
	 *
	 * @return void
	 */
	public function testClone(){

	    // Test empty values
	    // TODO - translate from ts

	    // Test ok values
	    // TODO - translate from ts

	    // Test wrong values
	    // TODO - translate from ts

	    // Test exceptions
	    // TODO - translate from ts
	    $this->markTestIncomplete('This test has not been implemented yet.');
	}
}

?>