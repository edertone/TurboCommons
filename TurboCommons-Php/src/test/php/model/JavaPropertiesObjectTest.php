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

use Exception;
use PHPUnit_Framework_TestCase;
use stdClass;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\utils\JavaPropertiesUtils;


/**
 * JavaPropertiesObjectTest
 *
 * @return void
 */
class JavaPropertiesObjectTest extends PHPUnit_Framework_TestCase {


    /**
     * @see PHPUnit_Framework_TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see PHPUnit_Framework_TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->wrongValues = [null, '', 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, new stdclass()];
        $this->wrongValuesCount = count($this->wrongValues);

        $this->filesManager = new FilesManager();

        $this->basePath = __DIR__.'/../resources/model/javaPropertiesObject';

        $this->propertiesFiles = $this->filesManager->getDirectoryList($this->basePath);
    }


    /**
     * @see PHPUnit_Framework_TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Nothing necessary here
    }


    /**
     * @see PHPUnit_Framework_TestCase::tearDownAfterClass()
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

        // Test empty object
        $test = new JavaPropertiesObject();
        $this->assertTrue($test->length() === 0);

        $test = new JavaPropertiesObject('');
        $this->assertTrue($test->length() === 0);

        $test = new JavaPropertiesObject('       ');
        $this->assertTrue($test->length() === 0);

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

        // Test 1KeyWithValue
        $fileData = $this->filesManager->readFile($this->basePath.'/1KeyWithValue.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('keyname') === 'value');

        // Test 2KeysWithValue
        $fileData = $this->filesManager->readFile($this->basePath.'/2KeysWithValue.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 2);
        $this->assertTrue($test->get('keyname') === 'value');
        $this->assertTrue($test->get('keyname2') === 'value2');

        // Test CommentsSlashesAndSpecialChars
        $fileData = $this->filesManager->readFile($this->basePath.'/CommentsSlashesAndSpecialChars.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 5);
        $this->assertTrue($test->get('website') === 'http://en.wikipedia.org/');
        $this->assertTrue($test->get('language') === 'English');
        $this->assertTrue($test->get('message') === 'Welcome to Wikipedia!');
        $this->assertTrue($test->get('key with spaces') === 'This is the value that could be looked up with the key "key with spaces".');
        $this->assertTrue($test->get('tab') === "\t");

        // Test LotsOfLatinKeysAndValues
        $fileData = $this->filesManager->readFile($this->basePath.'/LotsOfLatinKeysAndValues.properties');
        $test = new JavaPropertiesObject($fileData);

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

        // Test LotsOfScapedCharacters
        $fileData = $this->filesManager->readFile($this->basePath.'/LotsOfScapedCharacters.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 6);
        $this->assertTrue($test->get('key with spaces') === "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
        $this->assertTrue($test->get('escaped key!=:# is __good') === 'And must work as "escaped key!=:# is __good"');
        $this->assertTrue($test->get('multiline.values') === "line 1\nline 2\nline 3\nline 4\\");
        $this->assertTrue($test->get('key\with\slashes') === 'value');
        $this->assertTrue($test->get('multiplebackslashes') === '\\\\\\value\\\\');
        $this->assertTrue($test->get('multiline.backslashes') === "value\n\n\\value");

        // Test MultipleKeysWithDifferentSpaces
        $fileData = $this->filesManager->readFile($this->basePath.'/MultipleKeysWithDifferentSpaces.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 4);
        $this->assertTrue($test->get('keyname') === 'value');
        $this->assertTrue($test->get('keyname2') === 'value2');
        $this->assertTrue($test->get('key3') === 'value3');
        $this->assertTrue($test->get('key4') === 'value4');

        // Test VietnameseAndJapaneseCharacters
        $fileData = $this->filesManager->readFile($this->basePath.'/VietnameseAndJapaneseCharacters.properties');
        $test = new JavaPropertiesObject($fileData);

        $this->assertTrue($test->length() === 11);
        $this->assertTrue($test->get('Currency_Converter') === 'Chuyen doi tien te  ');
        $this->assertTrue($test->get('Enter_Amount') === 'Nhập vào số lượng  ');
        $this->assertTrue($test->get('Target_Currency') === 'Đơn vị chuyển  ');
        $this->assertTrue($test->get('Alert_Mess') === 'Vui lòng nhập một số hợp lệ  ');
        $this->assertTrue($test->get('Alert_Title') === 'Thong bao ');
        $this->assertTrue($test->get('SOME_CHINESE_TEXT') === '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
        $this->assertTrue($test->get('SOME_JAPANESE_TEXT') === '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
        $this->assertTrue($test->get('SOME_JAPANESE_TEXT_WITH_MILTILINES') === "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");

        // Test exceptions
        $exceptionMessage = '';

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                new JavaPropertiesObject($this->wrongValues[$i]);
            } catch (Exception $e) {
                // We expect an exception to happen
            }
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }
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

        $test = new JavaPropertiesObject('    ');
        $this->assertTrue($test->toString() === '');

        // Test ok values
        foreach ($this->propertiesFiles as $file) {

            $fileData = $this->filesManager->readFile($this->basePath.'/'.$file);
            $test = new JavaPropertiesObject($fileData);
            $this->assertTrue(JavaPropertiesUtils::isEqualTo($test->toString(), $fileData, true));
        }

        // Test wrong values
        // Already tested at constructor test

        // Test exceptions
        // Already tested at constructor test
    }
}

?>