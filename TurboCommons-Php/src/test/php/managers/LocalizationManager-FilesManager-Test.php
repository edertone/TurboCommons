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

use stdClass;
use Throwable;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\managers\LocalizationManager;
use org\turbocommons\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\utils\ArrayUtils;


/**
 * LocalizationManager tests
 *
 * @return void
 */
class LocalizationManagerTest extends TestCase {


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
        $this->basePath = __DIR__.'/../resources/managers/localizationManager';

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->sut = new LocalizationManager();
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
     * testIsLocaleLoaded
     *
     * @return void
     */
    public function testIsLocaleLoaded(){

        $this->assertFalse($this->sut->isLocaleLoaded('en_US'));
        $this->assertFalse($this->sut->isLocaleLoaded('es_ES'));
        $this->assertFalse($this->sut->isLocaleLoaded('fr_FR'));
        $this->assertFalse($this->sut->isLocaleLoaded('en_GB'));

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertTrue($this->sut->isLocaleLoaded('en_US'));
            $this->assertTrue($this->sut->isLocaleLoaded('es_ES'));
            $this->assertTrue($this->sut->isLocaleLoaded('fr_FR'));
        });
    }


    /**
     * testIsLanguageLoaded
     *
     * @return void
     */
    public function testIsLanguageLoaded(){

        // Test invalid values
        try {
            $this->sut->isLanguageLoaded('en_US');
            $this->exceptionMessage = 'en_US did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->isLanguageLoaded('s');
            $this->exceptionMessage = 's did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->isLanguageLoaded('somestring');
            $this->exceptionMessage = 'somestring did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->assertFalse($this->sut->isLanguageLoaded('en'));
        $this->assertFalse($this->sut->isLanguageLoaded('es'));
        $this->assertFalse($this->sut->isLanguageLoaded('fr'));
        $this->assertFalse($this->sut->isLanguageLoaded('en'));

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertTrue($this->sut->isLanguageLoaded('en'));
            $this->assertTrue($this->sut->isLanguageLoaded('es'));
            $this->assertTrue($this->sut->isLanguageLoaded('fr'));
        });
    }


    /**
     * initialize
     *
     * @return void
     */
    public function testInitialize_empty_values(){

        // Test empty values
        $this->assertSame(count($this->sut->locales()), 0);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->sut->initialize(new FilesManager(), $this->emptyValues[$i], [['path' => 'p', 'bundles' => ['b']]]);
                $this->exceptionMessage = 'path => p did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->initialize(new FilesManager(), ['es_ES'], $this->emptyValues[$i]);
                $this->exceptionMessage = '[es_ES] did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $this->assertSame(count($this->sut->locales()), 0);
    }


    /**
     * initialize
     *
     * @return void
     */
    public function testInitialize_without_bundles(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => []
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame(count($this->sut->locales()), 3);
            $this->assertSame(count($this->sut->languages()), 3);
        });
    }


    /**
     * initialize
     *
     * @return void
     */
    public function testInitialize_without_finish_callback(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US'], $bundles);

        $this->assertSame(count($this->sut->locales()), 2);
        $this->assertSame($this->sut->get('PASSWORD'), 'Contraseña');
        $this->assertSame($this->sut->get('USER'), 'Usuario');
    }


    /**
     * initialize
     *
     * @return void
     */
    public function testInitialize_secondth_time_resets_state(){

        $this->completedUrlsCount = 0;

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame(count($this->sut->locales()), 1);
            $this->assertSame($this->completedUrlsCount, 1);

            $bundles = [[
                'path' => $this->basePath.'/test-json/$locale/$bundle.json',
                'bundles' => ['Locales']
            ]];

            $this->completedUrlsCount = 0;

            $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US'], $bundles, function($errors){

                $this->assertSame(count($errors), 0);
                $this->assertSame(count($this->sut->locales()), 2);
                $this->assertSame($this->completedUrlsCount, 2);

            }, function($completedUrl, $totalUrls){

                $this->completedUrlsCount ++;
                $this->assertSame($totalUrls, 2);
            });

            $this->assertSame(count($this->sut->locales()), 2);

        }, function($completedUrl, $totalUrls){

            $this->completedUrlsCount ++;
            $this->assertSame($totalUrls, 1);
        });
    }


    /**
     * testInitialize_wrong_values
     *
     * @return void
     */
    public function testInitialize_wrong_values(){

        try {
            $this->sut->initialize(new FilesManager(), "Locales");
            $this->exceptionMessage = 'Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->initialize(new FilesManager(), ['es_ES'], 123);
            $this->exceptionMessage = '[es_ES] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Dummy assert to avoid phpunit warnings
        $this->assertTrue(true);
    }


    /**
     * testInitialize_exceptions
     *
     * @return void
     */
    public function testInitialize_exceptions(){

        // Test exceptions
        try {
            $this->sut->initialize(new FilesManager(), [1,2,3,4]);
            $this->exceptionMessage = 'Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->initialize(new FilesManager(), 150);
            $this->exceptionMessage = 'Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Dummy assert to avoid phpunit warnings
        $this->assertTrue(true);
    }


    /**
     * testInitialize_non_existing_bundle
     *
     * @return void
     */
    public function testInitialize_non_existing_bundle(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['nonexistingbundle']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 1);
            $this->assertSame(count($this->sut->locales()), 1);
        });
    }


    /**
     * testInitialize_non_existing_path
     *
     * @return void
     */
    public function testInitialize_non_existing_path(){

        $bundles = [[
            'path' => $this->basePath.'/thispathdoesnotexist/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US', 'es_ES'], $bundles, function($errors){

            $this->assertSame(count($errors), 2);
            $this->assertSame(count($this->sut->locales()), 2);
            $this->assertSame(count($this->sut->languages()), 2);
        });
    }


    /**
     * testLoadLocales_empty_values
     *
     * @return void
     */
    public function testLoadLocales_empty_values(){

        try {
            $this->sut->loadLocales(['en_US']);
            $this->exceptionMessage = 'en_US did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame(count($this->sut->locales()), 1);
            $this->assertSame($this->sut->locales()[0], 'en_US');

            // Test empty values
            for($i=0; $i < $this->emptyValuesCount; $i++){

                try {
                    $this->sut->loadLocales($emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValue did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        });
    }


    /**
     * testLoadLocales_ok_values
     *
     * @return void
     */
    public function testLoadLocales_ok_values(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);

            // Test ok values
            $this->sut->loadLocales(['es_ES'], function($errors){

                $this->assertSame(count($errors), 0);
                $this->assertSame(count($this->sut->locales()), 2);
                $this->assertSame($this->sut->locales()[0], 'en_US');
                $this->assertSame($this->sut->locales()[1], 'es_ES');
            });
        });
    }


    /**
     * testLoadLocales_without_finished_callback
     *
     * @return void
     */
    public function testLoadLocales_without_finished_callback(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles);

        $this->assertSame(count($this->sut->locales()), 1);

        $this->sut->loadLocales(['es_ES']);

        $this->assertSame(count($this->sut->locales()), 2);
        $this->assertSame($this->sut->locales()[0], 'en_US');
        $this->assertSame($this->sut->locales()[1], 'es_ES');
    }


    /**
     * testLoadLocales_wrong_values
     *
     * @return void
     */
    public function testLoadLocales_wrong_values(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame(count($this->sut->locales()), 1);

            // Test missing locale
            $this->sut->loadLocales(['fr_FR'], function($errors){

                $this->assertSame(count($errors), 1);
                $this->assertSame(count($this->sut->locales()), 2);
            });
        });
    }


    /**
     * testLoadLocales_duplicate_locales
     *
     * @return void
     */
    public function testLoadLocales_duplicate_locales(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame($this->sut->get("LOGIN"), "Login");

            $this->sut->loadLocales(['en_US'], function($errors){

                $this->assertSame(count($errors), 0);
                $this->assertSame(count($this->sut->locales()), 1);
                $this->assertSame($this->sut->locales()[0], 'en_US');
                $this->assertSame($this->sut->get("LOGIN"), "Login");

                $this->sut->loadLocales(['en_US'], function($errors){

                    $this->assertSame(count($errors), 0);
                    $this->assertSame(count($this->sut->locales()), 1);
                    $this->assertSame($this->sut->locales()[0], 'en_US');
                    $this->assertSame($this->sut->get("LOGIN"), "Login");
                });
            });
        });
    }


    /**
     * testLoadBundles_empty_values
     *
     * @return void
     */
    public function testLoadBundles_empty_values(){

        try {
            $this->sut->loadBundles('/test-loadBundles/$locale/$bundle.json', []);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        try {
            $this->sut->loadBundles($bundles[0]['path'], $bundles[0]['bundles']);
            $this->exceptionMessage = '$bundles.path did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);
            $this->assertSame(count($this->sut->locales()), 1);
            $this->assertSame($this->sut->locales()[0], 'en_US');

            // Test empty values
            for($i=0; $i < $this->emptyValuesCount; $i++){

                try {
                    $this->sut->loadBundles($this->emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValues did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        });
    }


    /**
     * testLoadBundles_ok_values
     *
     * @return void
     */
    public function testLoadBundles_ok_values(){

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->sut->loadBundles($this->basePath.'/test-loadBundles/$locale/$bundle.json',
                ['MoreLocales'], function($errors){

                $this->assertSame(count($errors), 0);
                $this->assertSame(count($this->sut->locales()), 1);
                $this->assertSame($this->sut->locales()[0], 'en_US');
            });
        });
    }


    /**
     * testLoadBundles_without_finished_callback
     *
     * @return void
     */
    public function testLoadBundles_without_finished_callback(){

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles);

        $this->assertSame(count($this->sut->locales()), 1);
        $this->assertSame($this->sut->locales()[0], 'en_US');

        $this->sut->loadBundles($this->basePath.'/test-loadBundles/$locale/$bundle.json', ['MoreLocales']);

        $this->assertSame(count($this->sut->locales()), 1);
        $this->assertSame($this->sut->locales()[0], 'en_US');
    }


    /**
     * testLoadBundles_nonexistant_bundles_or_pahts
     *
     * @return void
     */
    public function testLoadBundles_nonexistant_bundles_or_pahts(){

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->sut->loadBundles($this->basePath.'/test-loadBundles/$locale/$bundle.json',
                ['nonexistant'], function($errors){

                $this->assertSame(count($errors), 1);
            });

            $this->sut->loadBundles($this->basePath.'/test-nonexistant/$locale/$bundle.json',
                ['MoreLocales'], function($errors){

                $this->assertSame(count($errors), 1);
            });
        });
    }


    /**
     * testGet_non_initialized
     *
     * @return void
     */
    public function testGet_non_initialized(){

        $this->assertSame('$exception', $this->sut->missingKeyFormat);

        // Test empty values
        for($i=0; $i < $this->emptyValuesCount; $i++){

            try {
                $this->sut->get($this->emptyValues[$i]);
                $this->exceptionMessage = 'emptyValues did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        try {
            $this->sut->get("KEY");
            $this->exceptionMessage = 'KEY did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales");
            $this->exceptionMessage = 'KEY Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales", "Some/path");
            $this->exceptionMessage = 'KEY Locales Some/path did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->sut->missingKeyFormat = '';
        try {
            $this->sut->get("KEY");
            $this->exceptionMessage = 'KEY did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales");
            $this->exceptionMessage = 'KEY Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales", "Some/path");
            $this->exceptionMessage = 'KEY Locales Some/path did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->sut->missingKeyFormat = '--$key--';
        try {
            $this->sut->get("KEY");
            $this->exceptionMessage = 'KEY did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales");
            $this->exceptionMessage = 'KEY Locales did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("KEY", "Locales", "Some/path");
            $this->exceptionMessage = 'KEY Locales Some/path did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->sut->missingKeyFormat = '<$key>';
        try {
            $this->sut->get("NON_EXISTANT");
            $this->exceptionMessage = 'NON_EXISTANT did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("NON_EXISTANT", "Nonexistant");
            $this->exceptionMessage = 'NON_EXISTANT Nonexistant did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->sut->get("NON_EXISTANT", "Nonexistant", "Nonexistant/path");
            $this->exceptionMessage = 'NON_EXISTANT Nonexistant Nonexistant/path did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGet_initialized_missing_values
     *
     * @return void
     */
    public function testGet_initialized_missing_values(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test missingKeyFormat with $exception wildcard
            $this->assertSame('$exception', $this->sut->missingKeyFormat);

            try {
                $this->sut->get("MISSINGKEY");
                $this->exceptionMessage = 'MISSINGKEY did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales");
                $this->exceptionMessage = 'MISSINGKEY Locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "MissingBundle");
                $this->exceptionMessage = 'MISSINGKEY MissingBundle did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales", "Some/path");
                $this->exceptionMessage = 'MISSINGKEY Locales Some/path did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            // Test empty missingKeyFormat
            $this->sut->missingKeyFormat = '';
            $this->assertSame($this->sut->get("MISSINGKEY"), '');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales"), '');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales", $this->basePath.'/test-json/$locale/$bundle.json'), '');

            try {
                $this->sut->get("MISSINGKEY", "MissingBundle");
                $this->exceptionMessage = 'MISSINGKEY MissingBundle did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales", "Some/path");
                $this->exceptionMessage = 'MISSINGKEY Locales Some/path did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            // Test missingKeyFormat with some text
            $this->sut->missingKeyFormat = 'sometext';
            $this->assertSame($this->sut->get("MISSINGKEY"), 'sometext');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales"), 'sometext');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales", $this->basePath.'/test-json/$locale/$bundle.json'), 'sometext');

            try {
                $this->sut->get("MISSINGKEY", "MissingBundle");
                $this->exceptionMessage = 'MISSINGKEY MissingBundle did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales", "Some/path");
                $this->exceptionMessage = 'MISSINGKEY Locales Some/path did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            // Test missingKeyFormat with $key wildcard
            $this->sut->missingKeyFormat = '--$key--';
            $this->assertSame($this->sut->get("MISSINGKEY"), '--MISSINGKEY--');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales"), '--MISSINGKEY--');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales", $this->basePath.'/test-json/$locale/$bundle.json'), '--MISSINGKEY--');

            try {
                $this->sut->get("MISSINGKEY", "MissingBundle");
                $this->exceptionMessage = 'MISSINGKEY MissingBundle did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales", "Some/path");
                $this->exceptionMessage = 'MISSINGKEY Locales Some/path did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            $this->sut->missingKeyFormat = '<$key>';
            $this->assertSame($this->sut->get("MISSINGKEY"), '<MISSINGKEY>');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales"), '<MISSINGKEY>');
            $this->assertSame($this->sut->get("MISSINGKEY", "Locales", $this->basePath.'/test-json/$locale/$bundle.json'), '<MISSINGKEY>');

            try {
                $this->sut->get("MISSINGKEY", "MissingBundle");
                $this->exceptionMessage = 'MISSINGKEY MissingBundle did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("MISSINGKEY", "Locales", "Some/path");
                $this->exceptionMessage = 'MISSINGKEY Locales Some/path did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });
    }


    /**
     * testGet_initialized_correct_values_with_single_locale_loaded
     *
     * @return void
     */
    public function testGet_initialized_correct_values_with_single_locale_loaded(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
            $this->assertSame($this->sut->get('PASSWORD'), 'Password');
            $this->assertSame($this->sut->get('USER'), 'User');

            $bundles = [[
                'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
                'bundles' => ['Locales', 'MoreLocales']
            ]];

            $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

                $this->assertSame($this->sut->get('LOGIN', 'Locales'), 'Login');
                $this->assertSame($this->sut->get('PASSWORD'), 'Password');
                $this->assertSame($this->sut->get('USER'), 'User');

                $this->assertSame($this->sut->get('SOME_LOCALE', 'MoreLocales'), 'Some locale');
                $this->assertSame($this->sut->get('SOME_OTHER'), 'Some other');
            });
        });
    }


    /**
     * testGet_initialized_keys_from_another_bundle_fail
     *
     * @return void
     */
    public function testGet_initialized_keys_from_another_bundle_fail(){

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales', 'MoreLocales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            try {
                $this->sut->get("LOGIN", "MoreLocales");
                $this->exceptionMessage = 'LOGIN MoreLocales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get("SOME_OTHER", "Locales");
                $this->exceptionMessage = 'SOME_OTHER Locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });

        // Dummy assert to avoid phpunit warnings
        $this->assertTrue(true);
    }


    /**
     * testGet_initialized_values_for_multiple_locales
     *
     * @return void
     */
    public function testGet_initialized_values_for_multiple_locales(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US'], $bundles, function($errors){

            $this->assertSame($this->sut->get('PASSWORD'), 'Contraseña');
            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
        });
    }


    /**
     * testGet_initialized_keys_from_multiple_paths_bundles_and_locales
     *
     * @return void
     */
    public function testGet_initialized_keys_from_multiple_paths_bundles_and_locales(){

        $bundles = [[
            'path' => $this->basePath.'/test-multiple-paths/path-1/$locale/$bundle.properties',
            'bundles' => ['bundle1']
        ],[
            'path' => $this->basePath.'/test-multiple-paths/path-2/$locale/$bundle.properties',
            'bundles' => ['bundle1']
        ],[
            'path' => $this->basePath.'/test-multiple-paths/path-3/$locale/$bundle.properties',
            'bundles' => ['bundle1']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US'], $bundles, function($errors){

            $this->assertSame($this->sut->get('PATH_NAME'), 'ruta3');
            $this->assertSame($this->sut->get('PATH_NAME', 'bundle1'), 'ruta3');
            $this->assertSame($this->sut->get('PATH_NAME', '', $this->basePath.'/test-multiple-paths/path-2/$locale/$bundle.properties'), 'ruta2');
            $this->assertSame($this->sut->get('PATH_NAME', 'bundle1', $this->basePath.'/test-multiple-paths/path-2/$locale/$bundle.properties'), 'ruta2');
            $this->assertSame($this->sut->get('PATH_NAME'), 'ruta2');

            $this->assertSame($this->sut->get('NOT_ON_ES'), 'not on es 2');
            $this->assertSame($this->sut->get('NOT_ON_ES', 'bundle1'), 'not on es 2');
            $this->assertSame($this->sut->get('NOT_ON_ES', '', $this->basePath.'/test-multiple-paths/path-1/$locale/$bundle.properties'), 'not on es 1');
            $this->assertSame($this->sut->get('NOT_ON_ES', 'bundle1'), 'not on es 1');
        });
    }


    /**
     * testLocales
     *
     * @return void
     */
    public function testLocales(){

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US', 'fr_FR']));

            $this->sut->setLocalesOrder(['en_US', 'fr_FR', 'es_ES']);

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'fr_FR', 'es_ES']));
        });
    }


    /**
     * testLanguages
     *
     * @return void
     */
    public function testLanguages(){

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles);

        $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'fr']));

        $this->sut->setLocalesOrder(['en_US', 'fr_FR', 'es_ES']);

        $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'fr', 'es']));
    }


    /**
     * testActiveBundle
     *
     * @return void
     */
    public function testActiveBundle(){

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales', 'MoreLocales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            $this->assertSame($this->sut->activeBundle(), 'MoreLocales');

            $this->sut->setActiveBundle('Locales');
            $this->assertSame($this->sut->activeBundle(), 'Locales');
        });
    }


    /**
     * testPrimaryLocale
     *
     * @return void
     */
    public function testPrimaryLocale(){

        try {
            $this->sut->primaryLocale();
            $this->exceptionMessage = 'primaryLocale did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertSame($this->sut->primaryLocale(), 'es_ES');

            $this->sut->setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);

            $this->assertSame($this->sut->primaryLocale(), 'en_US');
        });
    }


    /**
     * testPrimaryLanguage
     *
     * @return void
     */
    public function testPrimaryLanguage(){

        try {
            $this->sut->primaryLanguage();
            $this->exceptionMessage = 'primaryLanguage did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles);

        $this->assertSame($this->sut->primaryLanguage(), 'es');

        $this->sut->setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);

        $this->assertSame($this->sut->primaryLanguage(), 'en');
    }


    /**
     * testSetActiveBundle
     *
     * @return void
     */
    public function testSetActiveBundle(){

        // Test empty values
        for($i=0; $i < $this->emptyValuesCount; $i++){

            try {
                $this->sut->setActiveBundle($this->emptyValues[$i]);
                $this->exceptionMessage = 'emptyValues did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $bundles = [[
            'path' => $this->basePath.'/test-loadBundles/$locale/$bundle.json',
            'bundles' => ['Locales', 'MoreLocales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test ok values
            $this->assertSame($this->sut->activeBundle(), 'MoreLocales');
            $this->assertSame($this->sut->get('SOME_LOCALE'), 'Some locale');

            $this->sut->setActiveBundle('Locales');
            $this->assertSame($this->sut->activeBundle(), 'Locales');
            $this->assertSame($this->sut->get('LOGIN'), 'Login');

            // Test wrong values
            try {
                $this->sut->setActiveBundle('nonexisting');
                $this->exceptionMessage = 'nonexisting did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });
    }


    /**
     * testSetPrimaryLocale
     *
     * @return void
     */
    public function testSetPrimaryLocale(){

        try {
            $this->sut->setPrimaryLocale('en_US');
            $this->exceptionMessage = 'setPrimaryLocale did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'fr']));

            $this->sut->setPrimaryLocale('en_US');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'es_ES', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'es', 'fr']));

            $this->sut->setPrimaryLocale('fr_FR');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['fr_FR', 'en_US', 'es_ES']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['fr', 'en', 'es']));

            $this->sut->setPrimaryLocale('es_ES');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'fr_FR', 'en_US']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'fr', 'en']));

            // Test exceptions
            try {
                $this->sut->setPrimaryLocale(123);
                $this->exceptionMessage = '123 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setPrimaryLocale(["LOGIN"]);
                $this->exceptionMessage = 'LOGIN did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setPrimaryLocale(new stdClass());
                $this->exceptionMessage = 'stdClass did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });
    }


    /**
     * testSetPrimaryLanguage
     *
     * @return void
     */
    public function testSetPrimaryLanguage(){

        try {
            $this->sut->setPrimaryLanguage('en');
            $this->exceptionMessage = 'setPrimaryLanguage did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'fr']));

            $this->sut->setPrimaryLanguage('en');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'es_ES', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'es', 'fr']));

            $this->sut->setPrimaryLanguage('fr');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['fr_FR', 'en_US', 'es_ES']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['fr', 'en', 'es']));

            $this->sut->setPrimaryLanguage('es');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'fr_FR', 'en_US']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'fr', 'en']));

            // Test exceptions
            try {
                $this->sut->setPrimaryLanguage(123);
                $this->exceptionMessage = '123 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setPrimaryLanguage(["LOGIN"]);
                $this->exceptionMessage = 'LOGIN did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setPrimaryLanguage(new stdClass());
                $this->exceptionMessage = 'stdClass did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });
    }


    /**
     * testSetPrimaryLanguage_repeated_languages
     *
     * @return void
     */
    public function testSetPrimaryLanguage_repeated_languages(){

        $bundles = [[
            'path' => $this->basePath.'/test-duplicate-languages/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_GB', 'en_US'], $bundles, function($errors){

            $this->assertSame(count($errors), 0);

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_GB', 'en_US']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'en']));
            $this->assertSame($this->sut->get('LOGIN'), 'acceder');

            $this->sut->setPrimaryLanguage('en');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_GB', 'es_ES', 'en_US']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'es', 'en']));
            $this->assertSame($this->sut->get('LOGIN'), 'login GB');

            $this->sut->setPrimaryLocale('en_US');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'en_GB', 'es_ES']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'en', 'es']));
            $this->assertSame($this->sut->get('LOGIN'), 'login US');

            $this->sut->setPrimaryLanguage('es');
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US', 'en_GB']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'en']));
            $this->assertSame($this->sut->get('LOGIN'), 'acceder');
        });
    }


    /**
     * testSetLocalesOrder
     *
     * @return void
     */
    public function testSetLocalesOrder(){

        $bundles = [[
            'path' => $this->basePath.'/test-locales/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['es_ES', 'en_US', 'fr_FR'], $bundles, function($errors){

            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['es', 'en', 'fr']));
            $this->assertSame($this->sut->get('LOGIN'), 'acceder');

            $this->sut->setLocalesOrder(['en_US', 'es_ES', 'fr_FR']);
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'es_ES', 'fr_FR']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['en', 'es', 'fr']));
            $this->assertSame($this->sut->get('LOGIN'), 'Login');

            $this->sut->setLocalesOrder(['fr_FR', 'en_US', 'es_ES']);
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['fr_FR', 'en_US', 'es_ES']));
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->languages(), ['fr', 'en', 'es']));
            $this->assertSame($this->sut->get('LOGIN'), 'loguele');

            // Test exceptions
            try {
                $this->sut->setLocalesOrder(['fr_FR']);
                $this->exceptionMessage = 'fr_FR did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setLocalesOrder(['fr_FR', 'en_US', 'es_ES', 'en_GB']);
                $this->exceptionMessage = 'locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setLocalesOrder(['fr_FR', 'en_US', 'en_GB']);
                $this->exceptionMessage = 'locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setLocalesOrder(123);
                $this->exceptionMessage = '123 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setLocalesOrder(["LOGIN"]);
                $this->exceptionMessage = 'LOGIN did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->setLocalesOrder(new stdClass());
                $this->exceptionMessage = 'stdClass did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        });
    }


    /**
     * testGetStartCase
     *
     * @return void
     */
    public function testGetStartCase(){

        $bundles = [[
            'path' => $this->basePath.'/test-cases/$locale/$bundle.properties',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test empty values
            for ($i = 0; $i < $this->emptyValuesCount; $i++) {

                try {
                    $this->sut->getStartCase($this->emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValues did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }

            // Test ok values
            $this->assertSame($this->sut->getStartCase('H'), 'H');
            $this->assertSame($this->sut->getStartCase('HELLO'), 'Hello');
            $this->assertSame($this->sut->getStartCase('HELLO_UNDER'), 'Helló. Únder Ü??');
            $this->assertSame($this->sut->getStartCase('MIXED_CASE'), 'Hello People');
            $this->assertSame($this->sut->getStartCase('MULTIPLE_WORDS'), 'Word1 Word2 Word3 Word4 Word5');
            $this->assertSame($this->sut->getStartCase('SOME_ACCENTS'), 'Óyeà!!! Üst??');
        });
    }


    /**
     * testGetAllUpperCase
     *
     * @return void
     */
    public function testGetAllUpperCase(){

        $bundles = [[
            'path' => $this->basePath.'/test-cases/$locale/$bundle.properties',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test empty values
            for ($i = 0; $i < $this->emptyValuesCount; $i++) {

                try {
                    $this->sut->getAllUpperCase($this->emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValues did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }

            // Test ok values
            $this->assertSame($this->sut->getAllUpperCase('H'), 'H');
            $this->assertSame($this->sut->getAllUpperCase('HELLO'), 'HELLO');
            $this->assertSame($this->sut->getAllUpperCase('HELLO_UNDER'), 'HELLÓ. ÚNDER Ü??');
            $this->assertSame($this->sut->getAllUpperCase('MIXED_CASE'), 'HELLO PEOPLE');
            $this->assertSame($this->sut->getAllUpperCase('MULTIPLE_WORDS'), 'WORD1 WORD2 WORD3 WORD4 WORD5');
            $this->assertSame($this->sut->getAllUpperCase('SOME_ACCENTS'), 'ÓYEÀ!!! ÜST??');
        });
    }


    /**
     * testGetAllLowerCase
     *
     * @return void
     */
    public function testGetAllLowerCase(){

        $bundles = [[
            'path' => $this->basePath.'/test-cases/$locale/$bundle.properties',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test empty values
            for ($i = 0; $i < $this->emptyValuesCount; $i++) {

                try {
                    $this->sut->getAllLowerCase($this->emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValues did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }

            // Test ok values
            $this->assertSame($this->sut->getAllLowerCase('H'), 'h');
            $this->assertSame($this->sut->getAllLowerCase('HELLO'), 'hello');
            $this->assertSame($this->sut->getAllLowerCase('HELLO_UNDER'), 'helló. únder ü??');
            $this->assertSame($this->sut->getAllLowerCase('MIXED_CASE'), 'hello people');
            $this->assertSame($this->sut->getAllLowerCase('MULTIPLE_WORDS'), 'word1 word2 word3 word4 word5');
            $this->assertSame($this->sut->getAllLowerCase('SOME_ACCENTS'), 'óyeà!!! üst??');
        });
    }


    /**
     * testGetFirstUpperRestLower
     *
     * @return void
     */
    public function testGetFirstUpperRestLower(){

        $bundles = [[
            'path' => $this->basePath.'/test-cases/$locale/$bundle.properties',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US'], $bundles, function($errors){

            // Test empty values
            for ($i = 0; $i < $this->emptyValuesCount; $i++) {

                try {
                    $this->sut->getFirstUpperRestLower($this->emptyValues[$i]);
                    $this->exceptionMessage = 'emptyValues did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }

            // Test ok values
            $this->assertSame($this->sut->getFirstUpperRestLower('H'), 'H');
            $this->assertSame($this->sut->getFirstUpperRestLower('HELLO'), 'Hello');
            $this->assertSame($this->sut->getFirstUpperRestLower('HELLO_UNDER'), 'Helló. únder ü??');
            $this->assertSame($this->sut->getFirstUpperRestLower('MIXED_CASE'), 'Hello people');
            $this->assertSame($this->sut->getFirstUpperRestLower('MULTIPLE_WORDS'), 'Word1 word2 word3 word4 word5');
            $this->assertSame($this->sut->getFirstUpperRestLower('SOME_ACCENTS'), 'Óyeà!!! üst??');
        });
    }


    /**
     * test_json
     *
     * @return void
     */
    public function test_json(){

        $bundles = [[
            'path' => $this->basePath.'/test-json/$locale/$bundle.json',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US', 'es_ES'], $bundles, function($errors){

            // Test EN_US
            $this->assertSame($this->sut->get('PASSWORD'), 'Password');
            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
            $this->assertSame($this->sut->get('USER', 'Locales'), 'User');
            $this->assertSame($this->sut->get('LOGIN', 'Locales'), 'Login');

            // Verify defined attributes are still the same
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'es_ES']));

            // Test ES_ES
            $this->sut->setLocalesOrder(['es_ES', 'en_US']);

            $this->assertSame($this->sut->get('PASSWORD'), 'Contraseña');
            $this->assertSame($this->sut->get('USER'), 'Usuario');
            $this->assertSame($this->sut->get('LOGIN', 'Locales'), 'Login');

            // Test tag that is missing on es_ES but found on en_US
            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');

            // Verify defined attributes are still the same
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US']));

            // Test tag that is missing everywhere
            try {
                $this->sut->get('NOT_TO_BE_FOUND');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get('NOT_TO_BE_FOUND', 'Locales');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND Locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-json/$locale/$bundle.json');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND Locales basepath did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            $this->sut->missingKeyFormat = '--$key--';
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-json/$locale/$bundle.json'), '--NOT_TO_BE_FOUND--');

            $this->sut->missingKeyFormat = '';
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND'), '');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales'), '');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-json/$locale/$bundle.json'), '');
        });
    }


    /**
     * test_properties
     *
     * @return void
     */
    public function test_properties(){

        $bundles = [[
            'path' => $this->basePath.'/test-properties/$locale/$bundle.properties',
            'bundles' => ['Locales']
        ]];

        $this->sut->initialize(new FilesManager(), ['en_US', 'es_ES'], $bundles, function($errors){

            // Test EN_US
            $this->assertSame($this->sut->get('PASSWORD'), 'Password');
            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');
            $this->assertSame($this->sut->get('USER', 'Locales'), 'User');
            $this->assertSame($this->sut->get('LOGIN', 'Locales'), 'Login');

            // Verify defined attributes are still the same
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['en_US', 'es_ES']));

            // Test ES_ES
            $this->sut->setLocalesOrder(['es_ES', 'en_US']);

            $this->assertSame($this->sut->get('PASSWORD'), 'Contraseña');
            $this->assertSame($this->sut->get('USER'), 'Usuario');
            $this->assertSame($this->sut->get('LOGIN', 'Locales'), 'Login');

            // Test tag that is missing on es_ES but found on en_US
            $this->assertSame($this->sut->get('TAG_NOT_EXISTING_ON_ES_ES'), 'Missing tag');

            // Verify defined attributes are still the same
            $this->assertTrue(ArrayUtils::isEqualTo($this->sut->locales(), ['es_ES', 'en_US']));

            // Test tag that is missing everywhere
            try {
                $this->sut->get('NOT_TO_BE_FOUND');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get('NOT_TO_BE_FOUND', 'Locales');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND Locales did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-properties/$locale/$bundle.properties');
                $this->exceptionMessage = 'NOT_TO_BE_FOUND Locales basePath did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            $this->sut->missingKeyFormat = '--$key--';
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND'), '--NOT_TO_BE_FOUND--');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales'), '--NOT_TO_BE_FOUND--');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-properties/$locale/$bundle.properties'), '--NOT_TO_BE_FOUND--');

            $this->sut->missingKeyFormat = '';
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND'), '');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales'), '');
            $this->assertSame($this->sut->get('NOT_TO_BE_FOUND', 'Locales', $this->basePath.'/test-properties/$locale/$bundle.properties'), '');
        });
    }
}

?>