# How to use TurboCommons with PHP

The PHP distribution is available as a PHAR file:

```php
require '..../turbocommons-php-X.X.X.phar';

use org\turbocommons\src\main\php\utils\StringUtils;

$n = StringUtils::countWords("word1 word2 word3");
```

Download the latest PHAR from [turboframework.org](https://turboframework.org/en/libs/turbocommons).
 PHP 8.2 or later is the configured platform version for the current package.
