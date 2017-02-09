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

use Exception;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\utils\SerializationUtils;
use PHPUnit_Framework_TestCase;
use org\turbocommons\src\main\php\utils\XmlUtils;


/**
 * SerializationUtils tests
 *
 * @return void
 */
class SerializationUtilsTest extends PHPUnit_Framework_TestCase {


	/**
	 * testArrayToObject
	 *
	 * @return void
	 */
	public function testArrayToObject(){

		// TODO
	}


	/**
	 * testHashMapToClass
	 *
	 * @return void
	 */
	public function testHashMapToClass(){

		$basePath = __DIR__.'/../resources/utils/serializationUtils/hashMapToClass';

		$validationManager = new ValidationManager();

		// TODO $this->assertTrue(false);
		//$res = SerializationUtils::hashMapToClass([], new Customer());
		//$this->assertTrue($validationManager->isEqualTo($res, new CustomerExpected()));


		// Test exceptions
		// TODO $this->assertTrue(SerializationUtils::hashMapToClass(null, null));

	}


	/**
	 * testJavaPropertiesToArray
	 *
	 * @return void
	 */
	public function testJavaPropertiesToArray(){

		// TODO - rename this method to propertiesToHashMap ???

		$basePath = __DIR__.'/../resources/utils/serializationUtils/javaPropertiesToArray';

		// Test the properties file 1
		$test = FilesManager::getInstance()->readFile($basePath.'/Test1.properties');
		$test = SerializationUtils::javaPropertiesToArray($test);

		$this->assertTrue(count($test) == 45);
		$this->assertTrue($test['period.maintenance.InMillis'] == '86400000');
		$this->assertTrue($test['decontamination.frequency.inDays'] == '30');
		$this->assertTrue($test['decontamination.warningBeforehand.inDays'] == '1');
		$this->assertTrue($test['technicalServiceInspection.frequency.inMonths'] == '12');
		$this->assertTrue($test['technicalServiceInspection.warningBeforehand.inDays'] == '7');
		$this->assertTrue($test['instrument.restartFrequency.inDays'] == '7');
		$this->assertTrue($test['start.purgeFinishedTestsPriorToTheLast.inDays'] == '7');
		$this->assertTrue($test['max.error.count'] == '-1');
		$this->assertTrue($test['error.delimeter'] == '(!)');
		$this->assertTrue($test['log.stdout'] == 'N');
		$this->assertTrue($test['portalrolemembership.bb.controlled.fields'] == '');
		$this->assertTrue($test['membership.datasource.key'] == '');
		$this->assertTrue($test['reconcile'] == 'Y');

		// Test the properties file 2
		$test = FilesManager::getInstance()->readFile($basePath.'/Test2.properties');
		$test = SerializationUtils::javaPropertiesToArray($test);

		$this->assertTrue(count($test) == 11);
		$this->assertTrue($test['Currency_Converter'] == 'Chuyen doi tien te  ');
		$this->assertTrue($test['Enter_Amount'] == 'Nhập vào số lượng  ');
		$this->assertTrue($test['Target_Currency'] == 'Đơn vị chuyển  ');
		$this->assertTrue($test['Alert_Mess'] == 'Vui lòng nhập một số hợp lệ  ');
		$this->assertTrue($test['Alert_Title'] == 'Thong bao ');
		$this->assertTrue($test['SOME_CHINESE_TEXT'] == '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
		$this->assertTrue($test['SOME_JAPANESE_TEXT'] == '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
		$this->assertTrue($test['SOME_JAPANESE_TEXT_WITH_MILTILINES'] == "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");

		// Test the properties file 3
		$test = FilesManager::getInstance()->readFile($basePath.'/Test3.properties');
		$test = SerializationUtils::javaPropertiesToArray($test);

		$this->assertTrue(count($test) == 5);
		$this->assertTrue($test['website'] == 'http://en.wikipedia.org/');
		$this->assertTrue($test['language'] == 'English');
		$this->assertTrue($test['message'] == 'Welcome to Wikipedia!');
		$this->assertTrue($test['key with spaces'] == 'This is the value that could be looked up with the key "key with spaces".');
		$this->assertTrue($test['tab'] == "\t");

		// Test the properties file 4
		$test = FilesManager::getInstance()->readFile($basePath.'/Test4.properties');
		$test = SerializationUtils::javaPropertiesToArray($test);

		$this->assertTrue(count($test) == 6);
		$this->assertTrue($test['key with spaces'] == "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
		$this->assertTrue($test['escaped key!=:# is __good'] == 'And must work as "escaped key!=:# is __good"');
		$this->assertTrue($test['multiline.values'] == "line 1\nline 2\nline 3\nline 4\\");
		$this->assertTrue($test['key\with\slashes'] == 'value');
		$this->assertTrue($test['multiplebackslashes'] == '\\\\\\value\\\\');
		$this->assertTrue($test['multiline.backslashes'] == "value\n\n\\value");
	}


	/**
	 * testJavaPropertiesToObject
	 *
	 * @return void
	 */
	public function testJavaPropertiesToObject(){

		// TODO
	}


	/**
	 * testStringToXml
	 *
	 * @return void
	 */
	public function testStringToXml(){

		$basePath = __DIR__.'/../resources/utils/serializationUtils/stringToXml';

		// test empty cases
		$this->assertTrue(SerializationUtils::stringToXml(null) === null);
		$this->assertTrue(SerializationUtils::stringToXml('') === null);
		$this->assertTrue(SerializationUtils::stringToXml('     ') === null);

		// test incorrect cases
		$exceptionMessage = '';

		try {
			SerializationUtils::stringToXml(1);
			$exceptionMessage = '1 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::stringToXml('sdafgsdt4567');
			$exceptionMessage = 'sdafgsdt4567 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::stringToXml('<caca>');
			$exceptionMessage = '<caca> did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::stringToXml([1,2,3,4]);
			$exceptionMessage = '[1,2,3,4] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}

		// Test correct cases
		$this->assertTrue(get_class(SerializationUtils::stringToXml('<document><from>Joe</from></document>')) == 'SimpleXMLElement');
		$this->assertTrue(get_class(SerializationUtils::stringToXml("<?xml version='1.0'?><document><from>Joe</from></document>")) == 'SimpleXMLElement');

		$filesManager = FilesManager::getInstance();

		$xmlData1 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test1.xml');
		$xmlData2 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test2.xml');
		$xmlData3 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test3.xml');
		$xmlData4 = $filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test4.xml');

		$this->assertTrue(get_class(SerializationUtils::stringToXml($xmlData1)) == 'SimpleXMLElement');
		$this->assertTrue(get_class(SerializationUtils::stringToXml($xmlData2)) == 'SimpleXMLElement');
		$this->assertTrue(get_class(SerializationUtils::stringToXml($xmlData3)) == 'SimpleXMLElement');
		$this->assertTrue(get_class(SerializationUtils::stringToXml($xmlData4)) == 'SimpleXMLElement');

		// This case deliverately adds empty spaces at the beginning of the xml string. It is not well formed,
		// but the stringToXml method handles it by trimming the received string
		$xmlData5 = '      '.$filesManager->readFile($basePath.$filesManager->getDirectorySeparator().'Test2.xml').'       ';
		$this->assertTrue(get_class(SerializationUtils::stringToXml($xmlData5)) == 'SimpleXMLElement');

	}


	/**
	 * testXmlToString
	 *
	 * @return void
	 */
	public function testXmlToString(){

		// Test empty cases
		$this->assertTrue(SerializationUtils::xmlToString(null) === '');
		$this->assertTrue(SerializationUtils::xmlToString('') === '');
		$this->assertTrue(SerializationUtils::xmlToString('     ') === '');

		// Test correct cases
		$this->assertTrue(XmlUtils::isEqualTo(SerializationUtils::xmlToString('<root t="1"><a>1</a></root>'), '<root t="1"><a>1</a></root>'));
		$this->assertTrue(XmlUtils::isEqualTo(SerializationUtils::xmlToString('<root><a>1</a><b>1</b></root>'), "<?xml version='1.0'?><root><a>1</a><b>1</b></root>"));

		// Test Test exceptions
		$exceptionMessage = '';

		try {
			SerializationUtils::xmlToString(1);
			$exceptionMessage = '1 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::xmlToString('sdafgsdt4567');
			$exceptionMessage = 'sdafgsdt4567 did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::xmlToString('<caca>');
			$exceptionMessage = '<caca> did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		try {
			SerializationUtils::xmlToString([1,2,3,4]);
			$exceptionMessage = '[1,2,3,4] did not cause exception';
		} catch (Exception $e) {
			// We expect an exception to happen
		}

		if($exceptionMessage != ''){

			$this->fail($exceptionMessage);
		}
	}
}

?>