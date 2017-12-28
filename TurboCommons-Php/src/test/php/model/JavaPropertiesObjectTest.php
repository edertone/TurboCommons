<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use Throwable;
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\managers\FilesManager;


/**
 * JavaPropertiesObjectTest
 *
 * @return void
 */
class JavaPropertiesObjectTest extends TestCase {


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

        $this->exceptionMessage = '';

        $this->wrongValues = [null, [], 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, new stdclass()];
        $this->wrongValuesCount = count($this->wrongValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/javaPropertiesObject';

        $this->propertiesFiles = $this->filesManager->getDirectoryList($this->basePath);
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
     * testConstruct
     *
     * @return void
     */
    public function testConstruct(){

        // Test empty values
        $test = new JavaPropertiesObject();
        $this->assertTrue($test->length() === 0);

        $test = new JavaPropertiesObject('');
        $this->assertTrue($test->length() === 0);

        try {
            new JavaPropertiesObject('       ');
            $this->exceptionMessage = '"        " value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new JavaPropertiesObject("\n\n\n");
            $this->exceptionMessage = '"\n\n\n" value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $test = new JavaPropertiesObject('name=Stephen');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('name') === 'Stephen');

        $test = new JavaPropertiesObject('name = Stephen');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('name') === 'Stephen');

        $test = new JavaPropertiesObject('name    =    Stephen');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('name') === 'Stephen');

        $test = new JavaPropertiesObject('      name = Stephen');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('name') === 'Stephen');

        $test = new JavaPropertiesObject('name=Stephen      ');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('name') === 'Stephen      ');

        $test = new JavaPropertiesObject('path=c:\\\\docs\\\\doc1');
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('path') === 'c:\docs\doc1');

        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
            $test = new JavaPropertiesObject($fileData);

            switch ($file) {

                case '1KeyWithValue.properties':
                    $this->assertTrue($test->length() === 1);
                    $this->assertTrue($test->get('keyname') === 'value');
                    break;

                case '2KeysWithValue.properties':
                    $this->assertTrue($test->length() === 2);
                    $this->assertTrue($test->get('keyname') === 'value');
                    $this->assertTrue($test->get('keyname2') === 'value2');
                    break;

                case 'CommentsSlashesAndSpecialChars.properties':
                    $this->assertTrue($test->length() === 5);
                    $this->assertTrue($test->get('website') === 'http://en.wikipedia.org/');
                    $this->assertTrue($test->get('language') === 'English');
                    $this->assertTrue($test->get('message') === 'Welcome to Wikipedia!');
                    $this->assertTrue($test->get('key with spaces') === 'This is the value that could be looked up with the key "key with spaces".');
                    $this->assertTrue($test->get('tab') === "\t");
                    break;

                case 'LotsOfEmptySpacesEveryWhere.properties':
                    $this->assertEquals(12, $test->length());
                    $this->assertTrue($test->get('k1') === '');
                    $this->assertTrue($test->get('k2') === ' ');
                    $this->assertTrue($test->get('k3') === '   ');
                    $this->assertTrue($test->get('k4') === '   test');
                    $this->assertTrue($test->get('k5') === '    test   ');
                    $this->assertTrue($test->get('k6') === "   test  \r\ngo");
                    $this->assertTrue($test->get('k7') === '  ');
                    $this->assertTrue($test->get(' k8') === '8');
                    $this->assertTrue($test->get('  k9') === '9');
                    $this->assertTrue($test->get('  k10 ') === '10');
                    $this->assertTrue($test->get('  k11  ') === '11');
                    $this->assertTrue($test->get('  k12  ') === '12');
                    break;

                case 'LotsOfLatinKeysAndValues.properties':
                    $this->assertTrue($test->length() === 45);
                    $this->assertTrue($test->get('period.maintenance.InMillis') === '86400000');
                    $this->assertTrue($test->get('decontamination.frequency.inDays') === '30');
                    $this->assertTrue($test->get('decontamination.warningBeforehand.inDays') === '1');
                    $this->assertTrue($test->get('technicalServiceInspection.frequency.inMonths') === '12');
                    $this->assertTrue($test->get('technicalServiceInspection.warningBeforehand.inDays') === '7');
                    $this->assertTrue($test->get('instrument.restartFrequency.inDays') === '7');
                    $this->assertTrue($test->get('start.purgeFinishedTestsPriorToTheLast.inDays') === '7');
                    $this->assertTrue($test->get('max.error.count') === '-1');
                    $this->assertTrue($test->get('error.delimeter') === '(!)');
                    $this->assertTrue($test->get('log.stdout') === 'N');
                    $this->assertTrue($test->get('portalrolemembership.bb.controlled.fields') === '');
                    $this->assertTrue($test->get('membership.datasource.key') === '');
                    $this->assertTrue($test->get('reconcile') === 'Y');
                    break;

                case 'LotsOfScapedCharacters.properties':
                    $this->assertTrue($test->length() === 6);
                    $this->assertTrue($test->get('escaped key!=:# is __good') === 'And must work as "escaped key!=:# is __good"');
                    $this->assertTrue($test->get('key with spaces') === "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
                    $this->assertTrue($test->get('key\\with\\slashes') === 'value');
                    $this->assertTrue($test->get('multiline.values') === "line 1\nline 2\nline 3\nline 4\\");
                    $this->assertTrue($test->get('multiplebackslashes') === '\\\\\\value\\\\');
                    $this->assertTrue($test->get('multiline.backslashes') === "value\n\n\\value");
                    break;

                case 'MidSizeInternationalizedFile7KeysLotsOfText.properties':
                    $this->assertTrue($test->length() === 7);
                    $this->assertTrue($test->get('featureName') === 'Spring Dashboard (optional)');
                    $this->assertTrue($test->get('providerName') === 'Pivotal Software, Inc.');
                    $this->assertTrue($test->get('updateSiteName') === 'Eclipse Integration Commons Updates');
                    $this->assertTrue($test->get('description') === 'This feature provides the STS dashboard for displaying RSS feeds and the extensions page');
                    $this->assertTrue($test->get('copyright') === 'Copyright (c) 2015, 2016 Pivotal Software, Inc.');
                    $this->assertTrue($test->get('licenseUrl') === 'open_source_licenses.txt');
                    break;

                case 'MultipleKeysWithDifferentSpaces.properties':
                    $this->assertTrue($test->length() === 4);
                    $this->assertTrue($test->get('keyname') === 'value');
                    $this->assertTrue($test->get('keyname2') === 'value2');
                    $this->assertTrue($test->get('key3') === 'value3');
                    $this->assertTrue($test->get('key4') === 'value4');
                    break;

                case 'VietnameseAndJapaneseCharacters.properties':
                    $this->assertTrue($test->length() === 11);
                    $this->assertTrue($test->get('Currency_Converter') === 'Chuyen doi tien te  ');
                    $this->assertTrue($test->get('Enter_Amount') === 'Nhập vào số lượng  ');
                    $this->assertTrue($test->get('Target_Currency') === 'Đơn vị chuyển  ');
                    $this->assertTrue($test->get('Alert_Mess') === 'Vui lòng nhập một số hợp lệ  ');
                    $this->assertTrue($test->get('Alert_Title') === 'Thong bao ');
                    $this->assertTrue($test->get('SOME_CHINESE_TEXT') === '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
                    $this->assertTrue($test->get('SOME_JAPANESE_TEXT') === '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
                    $this->assertTrue($test->get('SOME_JAPANESE_TEXT_WITH_MULTILINES') === "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");
                    break;

                case 'BigFile-5000Lines.properties':
                    $this->assertTrue($test->length() === 5000);
                    $this->assertTrue($test->get('0') === 'value-0');
                    $this->assertTrue($test->get('789') === 'value-789');
                    $this->assertTrue($test->get('1240') === 'value-1240');
                    $this->assertTrue($test->get('3450') === 'value-3450');
                    $this->assertTrue($test->get('4999') === 'value-4999');
                    break;

                case 'BigFile-15000Lines.properties':
                    $this->assertTrue($test->length() === 15000);
                    $this->assertTrue($test->get('0') === 'value-0');
                    $this->assertTrue($test->get('1789') === 'value-1789');
                    $this->assertTrue($test->get('5240') === 'value-5240');
                    $this->assertTrue($test->get('10450') === 'value-10450');
                    $this->assertTrue($test->get('14999') === 'value-14999');
                    break;

                default:
                    $this->assertTrue(false, $file.' Was not tested');
                    break;
            }
        }

        // Test exceptions
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                new JavaPropertiesObject($this->wrongValues[$i]);
                $this->exceptionMessage = 'wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testIsJavaProperties
     *
     * @return void
     */
    public function testIsJavaProperties(){

        $this->assertFalse(JavaPropertiesObject::isJavaProperties(null));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties(''));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties([]));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties(new stdClass()));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties('     '));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties("\n\n\n"));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties(0));

        $this->assertTrue(JavaPropertiesObject::isJavaProperties(new JavaPropertiesObject()));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties(new JavaPropertiesObject('')));

        // Test ok values
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key='));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key:'));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key=value'));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key:value'));

        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
            $test = new JavaPropertiesObject($fileData);
            $this->assertTrue(JavaPropertiesObject::isJavaProperties($fileData));
            $this->assertTrue(JavaPropertiesObject::isJavaProperties($test));
        }

        // Test wrong values
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            $this->assertFalse(JavaPropertiesObject::isJavaProperties($this->wrongValues[$i]));
        }

        // Test exceptions
        // Already tested at wrong values
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        // Test empty values
        $properties = new JavaPropertiesObject();

        $this->assertTrue($properties->isEqualTo(''));
        $this->assertTrue($properties->isEqualTo(new JavaPropertiesObject()));

        try {
            $properties->isEqualTo(null);
            $this->exceptionMessage = 'null value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $properties->isEqualTo([]);
            $this->exceptionMessage = '[] value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $properties->isEqualTo(new stdClass());
            $this->exceptionMessage = 'new stdClass() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $properties->isEqualTo(0);
            $this->exceptionMessage = '0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
            $properties = new JavaPropertiesObject($fileData);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($properties->length() < 1000){

                $this->assertTrue($properties->isEqualTo($fileData));
                $this->assertTrue($properties->isEqualTo($properties));
            }
        }

        // Test wrong values
        $properties = new JavaPropertiesObject();

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $properties->isEqualTo($this->wrongValues[$i]);
                $this->exceptionMessage = $this->wrongValues[$i].' wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $properties = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($properties->isEqualTo('key2=v1'));

        $properties = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($properties->isEqualTo('key1=v2'));

        $properties = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($properties->isEqualTo("key1=v1\nkey2=v2"));

        // Test exceptions
        // Already tested at wrong values
    }


    /**
     * testToString
     *
     * @return void
     */
    public function testToString(){

        // Test empty values
        $test = new JavaPropertiesObject();
        $this->assertTrue($test->toString() === '');

        $test = new JavaPropertiesObject('');
        $this->assertTrue($test->toString() === '');

        // Test ok values
        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);

            $test = new JavaPropertiesObject($fileData);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($test->length() < 1000){

                $this->assertTrue($test->isEqualTo($test->toString(), true), $file.' has a problem');
            }
        }

        // Test wrong values
        // Already tested at constructor test

        // Test exceptions
        // Already tested at constructor test
    }
}

?>