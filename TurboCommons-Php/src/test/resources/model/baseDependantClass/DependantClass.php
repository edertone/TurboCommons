<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\resources\model\baseDependantClass;

use org\turbocommons\src\main\php\model\BaseDependantClass;


/**
 * Class to test dependency management
 */
class DependantClass extends BaseDependantClass {


    public $publicProp = null;


    private $_property1 = null;


    private $_property2 = null;
}

?>