<?php

require_once __DIR__.'/../main/AutoLoader.php';
require_once __DIR__.'/php/libs/phpunit.phar';


$phpunit = new PHPUnit_TextUI_TestRunner();

// Run all the tests inside the current folder or subfolders for all the files ending with Test.php
$phpunit->dorun($phpunit->getTest(__DIR__, '', 'Test.php'));

?>