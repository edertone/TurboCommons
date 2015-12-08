<?php

// php.ini must have: phar.readonly = Off

$p = new Phar('../target/my.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'my.phar');

//issue the Phar::startBuffering() method call to buffer changes made to the archive until you issue the Phar::stopBuffering() command
$p->startBuffering();

//set the Phar file stub
//the file stub is merely a small segment of code that gets run initially when the Phar file is loaded,
//and it always ends with a __HALT_COMPILER()
$p->setStub('<?php Phar::mapPhar(); include "phar://my.phar/AutoLoader.php"; __HALT_COMPILER(); ?>');

//adds all of the PHP files in the stated directory to the Phar archive
$p->buildFromDirectory('../src/main/', '$(.*)\.php$');

//Stop buffering write requests to the Phar archive, and save changes to disk
$p->stopBuffering();







?>
