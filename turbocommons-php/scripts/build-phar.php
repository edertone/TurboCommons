<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$versionFile = $projectRoot . DIRECTORY_SEPARATOR . 'version.json';
$versionData = json_decode((string) file_get_contents($versionFile), true, 512, JSON_THROW_ON_ERROR);
$version = $versionData['version'];
$outputDirectory = $projectRoot . DIRECTORY_SEPARATOR . 'dist';
$outputFile = $outputDirectory . DIRECTORY_SEPARATOR . 'turbocommons-php-' . $version . '.phar';
$sourceDirectory = $projectRoot . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'main' . DIRECTORY_SEPARATOR . 'php';

if (!class_exists(Phar::class)) {
    fwrite(STDERR, "The Phar extension is required to build the PHP artifact.\n");
    exit(1);
}

if (!is_dir($outputDirectory)) {
    mkdir($outputDirectory, 0777, true);
}

if (file_exists($outputFile)) {
    unlink($outputFile);
}

$phar = new Phar($outputFile);
$phar->startBuffering();
$phar->buildFromDirectory($sourceDirectory);
$phar->setStub($phar->createDefaultStub('autoloader.php'));
$phar->setMetadata([
    'name' => 'turbocommons-php',
    'version' => $version,
    'license' => 'Apache-2.0'
]);
$phar->stopBuffering();

fwrite(STDOUT, "Created {$outputFile}\n");
