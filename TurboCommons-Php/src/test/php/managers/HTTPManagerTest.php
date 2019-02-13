<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\managers;

use Throwable;
use Exception;
use stdClass;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\managers\BrowserManager;
use org\turbocommons\src\main\php\managers\HTTPManager;
use org\turbocommons\src\main\php\model\HashMapObject;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\managers\httpmanager\HTTPManagerGetRequest;


/**
 * HTTPManagerTest
 *
 * @return void
 */
class HTTPManagerTest extends TestCase {

    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->browserManager = new BrowserManager();
        $this->sut = new HTTPManager();

        $this->sut->timeout = 3000;

        $this->basePath = 'https://raw.githubusercontent.com/edertone/TurboCommons/master/TurboCommons-Php/src/main/php/managers';
        $this->existantUrl = 'https://www.google.com';
        $this->nonExistantUrl = 'http://werwerwerwerwerwerwe.345345/3453453454435dgdfg.html';

        $this->exceptionMessage = '';
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


    /**
     * testConstructor
     *
     * @return void
     */
    public function testConstructor(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->sut = new HTTPManager($this->emptyValues[$i]);
                $this->exceptionMessage = 'new HTTPManager did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $this->sut = new HTTPManager(true);
        $this->assertSame($this->sut->asynchronous, true);
        $this->assertSame($this->sut->timeout, 0);

        $this->sut = new HTTPManager(false);
        $this->assertSame($this->sut->asynchronous, false);
        $this->assertSame($this->sut->timeout, 0);
        $this->assertSame($this->sut->countQueues(), 0);

        // Test wrong values
        // Already tested at empty values

        // Test exceptions
        // Already tested at empty values
    }


    /**
     * testCreateQueue
     *
     * @return void
     */
    public function testCreateQueue(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->sut->createQueue($this->emptyValues[$i]);
                $this->exceptionMessage = '$this->emptyValues did not cause exception';
            } catch (Throwable $e) {
                $this->assertRegExp('/name must be a non empty string|value is not a string/', $e->getMessage());
            }
        }

        // Test ok values
        $this->assertSame($this->sut->countQueues(), 0);
        $this->sut->createQueue("first queue");
        $this->assertSame($this->sut->countQueues(), 1);
        $this->assertSame($this->sut->isQueueRunning("first queue"), false);

        $this->sut->createQueue("second queue");
        $this->assertSame($this->sut->countQueues(), 2);
        $this->assertSame($this->sut->isQueueRunning("second queue"), false);

        $this->sut->createQueue("third queue");
        $this->assertSame($this->sut->countQueues(), 3);
        $this->assertSame($this->sut->isQueueRunning("third queue"), false);

        // Test wrong values
        try {
            $this->sut->createQueue("first queue");
            $this->exceptionMessage = '"first queue" did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/queue first queue already exists/', $e->getMessage());
        }

        try {
            $this->sut->createQueue("second queue");
            $this->exceptionMessage = '"second queue" did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/queue second queue already exists/', $e->getMessage());
        }

        try {
            $this->sut->createQueue(13435);
            $this->exceptionMessage = '13435 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            $this->sut->createQueue(['hello' => 1]);
            $this->exceptionMessage = '"hello" => 1 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            $this->sut->createQueue([1, 2, 3]);
            $this->exceptionMessage = '[1, 2, 3] did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }
    }


    /**
     * testCountQueues
     *
     * @return void
     */
    public function testCountQueues(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertSame($this->sut->countQueues(), 0);

        for ($i = 0; $i < 20; $i++) {

            $this->sut->createQueue("queue ".$i);
            $this->assertSame($this->sut->countQueues(), $i + 1);
            $this->assertSame($this->sut->isQueueRunning("queue ".$i), false);
        }

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }


    /**
     * testIsQueueRunning
     *
     * @return void
     */
    public function testIsQueueRunning(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testDeleteQueue
     *
     * @return void
     */
    public function testDeleteQueue(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->sut->deleteQueue($this->emptyValues[$i]);
                $this->exceptionMessage = '$this->emptyValues did not cause exception';
            } catch (Throwable $e) {
                $this->assertRegExp('/name must be a non empty string|value is not a string/', $e->getMessage());
            }
        }

        // Test ok values
        $this->sut->createQueue("queue1");
        $this->sut->createQueue("queue2");
        $this->sut->createQueue("queue3");
        $this->assertSame($this->sut->countQueues(), 3);
        $this->sut->deleteQueue("queue1");
        $this->assertSame($this->sut->countQueues(), 2);

        try {
            $this->sut->isQueueRunning("queue1");
            $this->exceptionMessage = 'isQueueRunning queue1 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/queue queue1 does not exist/', $e->getMessage());
        }

        $this->assertSame($this->sut->isQueueRunning("queue2"), false);
        $this->sut->deleteQueue("queue2");
        $this->assertSame($this->sut->countQueues(), 1);

        try {
            $this->sut->isQueueRunning("queue2");
            $this->exceptionMessage = 'isQueueRunning queue2 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/queue queue2 does not exist/', $e->getMessage());
        }

        // Test wrong values
        try {
            $this->sut->deleteQueue("non existant queue");
            $this->exceptionMessage = 'deleteQueue non existant queue did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/queue non existant queue does not exist/', $e->getMessage());
        }

        // Test exceptions
        try {
            $this->sut->deleteQueue(13435);
            $this->exceptionMessage = 'deleteQueue 13435 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            $this->sut->deleteQueue(['hello' => 1]);
            $this->exceptionMessage = 'deleteQueue ["hello" => 1] did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            $this->sut->deleteQueue([1, 2, 3]);
            $this->exceptionMessage = 'deleteQueue [1, 2, 3] did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }
    }


    /**
     * testGenerateUrlQueryString
     *
     * @return void
     */
    public function testGenerateUrlQueryString(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->sut->generateUrlQueryString($this->emptyValues[$i]);
                $this->exceptionMessage = '$this->emptyValues did not cause exception';
            } catch (Throwable $e) {
                $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
            }
        }

        // Test ok values with objects
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1]), 'a=1');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => 2]), 'a=1&b=2');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => 2, 'c' => 3]), 'a=1&b=2&c=3');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => "h&b", 'b' => '-_.*=']), 'a=h%26b&b=-_.*%3D');
        $this->assertSame($this->sut->generateUrlQueryString(['/&%$·#&=' => "1"]), '%2F%26%25%24%C2%B7%23%26%3D=1');
        $this->assertSame($this->sut->generateUrlQueryString(["%" => "%"]), '%25=%25');

        // Test ok values with HashMapObjects
        $hashMapObject = new HashMapObject();
        $hashMapObject->set('/&%$·#&=', 1);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1');

        $hashMapObject->set('b', 2);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2');

        $hashMapObject->set('c', 3);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3');

        $hashMapObject->set('d', 'he/&%$·#&=llo');
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3&d=he%2F%26%25%24%C2%B7%23%26%3Dllo');

        // Test wrong values
        // Tested with exceptions

        // Test exceptions
        try {
            $this->sut->generateUrlQueryString("hello");
            $this->exceptionMessage = '"hello" did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
        }

        try {
            $this->sut->generateUrlQueryString([1,2,3,4]);
            $this->exceptionMessage = '[1,2,3,4] did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
        }

        try {
            $this->sut->generateUrlQueryString(new Exception());
            $this->exceptionMessage = 'new Exception() did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
        }

        try {
            $this->sut->generateUrlQueryString(10);
            $this->exceptionMessage = '10 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
        }

        try {
            $this->sut->generateUrlQueryString(true);
            $this->exceptionMessage = 'true did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/keyValuePairs must be a HashMapObject or a non empty associative array/', $e->getMessage());
        }
    }


    /**
     * testIsInternetAvailable
     *
     * @return void
     */
    public function testIsInternetAvailable(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testUrlExists
     *
     * @return void
     */
    public function testUrlExists(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            if(!StringUtils::isString($this->emptyValues[$i])){

                try {
                    $this->sut->urlExists($this->emptyValues[$i], function(){}, function(){});
                    $this->exceptionMessage = '$this->emptyValues did not cause exception';
                } catch (Throwable $e) {
                    $this->assertRegExp('/url must be a string/', $e->getMessage());
                }
            }

            try {
                $this->sut->urlExists('https://www.google.com', $this->emptyValues[$i]);
                $this->exceptionMessage = '$this->emptyValues 2 did not cause exception';
            } catch (Throwable $e) {
                $this->assertRegExp('/params must be functions|Too few arguments to function/', $e->getMessage());
            }

            try {
                $this->sut->urlExists('https://www.google.com', function(){}, $this->emptyValues[$i]);
                $this->exceptionMessage = '$this->emptyValues 3 did not cause exception';
            } catch (Throwable $e) {
                $this->assertRegExp('/params must be functions/', $e->getMessage());
            }
        }

        // Test ok values
        $this->sut->urlExists($this->existantUrl, function(){

            $this->assertTrue(true);

        }, function(){

            $this->assertTrue(false);
        });

        // Test wrong values
        $this->sut->urlExists($this->nonExistantUrl, function(){

            $this->assertTrue(false);

        }, function(){

            $this->assertTrue(true);
        });

        // Test exceptions
        // Already tested by empty values
    }


    /**
     * testGetUrlHeaders
     *
     * @return void
     */
    public function testGetUrlHeaders(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testExecuteRequestsWithStringUrls
     *
     * @return void
     */
    public function testExecuteRequestsWithStringUrls(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            $expectedError = 'Invalid requests value';

            if(ArrayUtils::isArray($this->emptyValues[$i]) && count($this->emptyValues[$i]) === 0){

                $expectedError = 'No requests to execute';
            }

            try {
                $this->sut->execute($this->emptyValues[$i]);
                $this->exceptionMessage = 'expected error : '.$expectedError;
            } catch (Throwable $e) {
                $this->assertRegExp('/'.$expectedError.'/', $e->getMessage());
            }
        }

        // Test ok values

        // Single url with error
        $this->sut->execute('some invalid url', function($results, $anyError){

            $this->assertSame($anyError, true);
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
        });

        // Single url without error
            $this->sut->execute($this->existantUrl, function($results, $anyError){

            $this->assertTrue(!StringUtils::isEmpty($results[0]['response']));
            $this->assertTrue(strlen($results[0]['response']) > 5);

            $this->assertSame($anyError, false);
            $this->assertSame($results[0]['isError'], false);
        });

        // Multiple urls with errors
        $multiErrProgressCount = 0;

        $this->sut->execute(['invalidUrl1', 'invalidUrl2', 'invalidUrl3'], function($results, $anyError) use (&$multiErrProgressCount) {

            $this->assertSame($multiErrProgressCount, 3);
            $this->assertSame($anyError, true);
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
            $this->assertSame($results[1]['isError'], true);
            $this->assertTrue(strlen($results[1]['errorMsg']) > 3);
            $this->assertSame($results[2]['isError'], true);
            $this->assertTrue(strlen($results[2]['errorMsg']) > 3);

        }, function($completedUrl, $totalRequests) use (&$multiErrProgressCount) {

            $this->assertTrue(strlen($completedUrl) > 3);
            $this->assertSame($totalRequests, 3);
            $multiErrProgressCount ++;
        });

            $this->sut->execute([$this->existantUrl, 'invalidUrl2', $this->existantUrl], function($results, $anyError){

            $this->assertSame($anyError, true);
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[1]['isError'], true);
            $this->assertTrue(strlen($results[1]['errorMsg']) > 3);
            $this->assertSame($results[2]['isError'], false);
            $this->assertSame($results[2]['errorMsg'], '');
        });

        // Multiple urls without errors
        $multiProgressCount = 0;

        $this->sut->execute([$this->basePath.'/BrowserManager.php', $this->basePath.'/HTTPManager.php', $this->basePath.'/LocalizationManager.php'], function($results, $anyError) use (&$multiProgressCount){

            $this->assertSame($multiProgressCount, 3);
            $this->assertSame($anyError, false);
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[1]['isError'], false);
            $this->assertSame($results[1]['errorMsg'], '');
            $this->assertSame($results[2]['isError'], false);
            $this->assertSame($results[2]['errorMsg'], '');

        }, function($completedUrl, $totalRequests) use (&$multiProgressCount){

            $this->assertTrue(strlen($completedUrl) > 3);
            $this->assertSame($totalRequests, 3);
            $multiProgressCount ++;
        });

        // Test wrong values
        // Not necessary

        // Test exceptions
        try {
            $this->sut->execute($this->basePath.'/BrowserManager.php', ['hello'], function () {});
            $this->exceptionMessage = 'BrowserManager.php expected error';
        } catch (Throwable $e) {
            $this->assertRegExp('/finishedCallback and progressCallback must be functions/', $e->getMessage());
        }

        try {
            $this->sut->execute($this->basePath.'/BrowserManager.php', function () {}, ['hello']);
            $this->exceptionMessage = 'BrowserManager.php expected error';
        } catch (Throwable $e) {
            $this->assertRegExp('/finishedCallback and progressCallback must be functions/', $e->getMessage());
        }

        try {
            $this->sut->execute([1, 2], function () {}, function () {});
            $this->exceptionMessage = '[1, 2] expected error';
        } catch (Throwable $e) {
            $this->assertRegExp('/url 0 must be a non empty string/', $e->getMessage());
        }

        try {
            $this->sut->execute(["1", 2], function () {}, function () {});
            $this->exceptionMessage = '["1", 2] expected error';
        } catch (Throwable $e) {
            $this->assertRegExp('/url 1 must be a non empty string/', $e->getMessage());
        }
    }


    /**
     * testExecuteSingleHTTPManagerGetRequestWithErrors
     *
     * @return void
     */
    public function testExecuteSingleHTTPManagerGetRequestWithErrors(){

        $progressCount = 0;
        $successCalled = false;
        $errorCalled = false;
        $finallyCalled = false;

        $request = new HTTPManagerGetRequest('some invalid url');

        $request->successCallback = function ($response) use(&$successCalled) { $successCalled = true; };

        $request->errorCallback = function ($errorMsg, $errorCode) use(&$errorCalled) {
            $this->assertTrue(strlen($errorMsg) > 3);
            $this->assertSame($errorCode, 0);
            $errorCalled = true;
        };

        $request->finallyCallback = function () use(&$finallyCalled) { $finallyCalled = true; };

        $this->sut->execute($request, function($results, $anyError) use(&$progressCount, &$successCalled, &$errorCalled, &$finallyCalled) {

            $this->assertSame($progressCount, 1);
            $this->assertSame($successCalled, false);
            $this->assertSame($errorCalled, true);
            $this->assertSame($finallyCalled, true);

            $this->assertSame($anyError, true);
            $this->assertSame($results[0]['url'], 'some invalid url');
            $this->assertSame($results[0]['response'], '');
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
            $this->assertTrue($results[0]['errorCode'] === 0);

        }, function($completedUrl, $totalRequests) use(&$progressCount) {

            $this->assertSame($completedUrl, 'some invalid url');
            $this->assertSame($totalRequests, 1);
            $progressCount ++;
        });
    }


    /**
     * testExecuteSingleHTTPManagerGetRequestWithoutErrors
     *
     * @return void
     */
    public function testExecuteSingleHTTPManagerGetRequestWithoutErrors(){

        $progressCount = 0;
        $successCalled = false;
        $errorCalled = false;
        $finallyCalled = false;

        $request = new HTTPManagerGetRequest($this->basePath.'/HTTPManager.php');

        $request->successCallback = function ($response) use (&$successCalled) {
            $this->assertContains('class HTTPManager extends BaseStrictClass', $response);
            $successCalled = true;
        };

        $request->errorCallback = function ($errorMsg, $errorCode) use (&$errorCalled) { $errorCalled = true; };

        $request->finallyCallback = function () use (&$finallyCalled) { $finallyCalled = true; };

        $this->sut->execute($request, function($results, $anyError) use (&$progressCount, &$successCalled, &$errorCalled, &$finallyCalled) {

            $this->assertSame($progressCount, 1);
            $this->assertSame($successCalled, true);
            $this->assertSame($errorCalled, false);
            $this->assertSame($finallyCalled, true);

            $this->assertSame($anyError, false);
            $this->assertSame($results[0]['url'], $this->basePath.'/HTTPManager.php');
            $this->assertContains('class HTTPManager extends BaseStrictClass', $results[0]['response']);
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[0]['errorCode'], -1);

        }, function($completedUrl, $totalRequests) use (&$progressCount) {

            $this->assertSame($completedUrl, $this->basePath.'/HTTPManager.php');
            $this->assertSame($totalRequests, 1);
            $progressCount ++;
        });
    }


    /**
     * testTODO
     *
     * @return void
     */
    public function testTODOImplementAllMissingTests(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>