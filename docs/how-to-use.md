# How to use TurboCommons

TurboCommons provides the same common development operations across multiple
programming languages.

## PHP

The PHP distribution is available as a PHAR file:

```php
require '..../turbocommons-php-X.X.X.phar';

use org\turbocommons\src\main\php\utils\StringUtils;

$n = StringUtils::countWords("word1 word2 word3");
```

Download the latest PHAR from [turbocommons.org](https://turbocommons.org).

## TypeScript

```text
npm install turbocommons-ts
```

```typescript
import { StringUtils } from 'turbocommons-ts';

const n = StringUtils.countWords('word1 word2 word3');
```

## JavaScript 6

```text
npm install turbocommons-es6
```

```html
<script src="turbocommons-es6/turbocommons-es6.js"></script>
<script>
  const StringUtils = org_turbocommons.StringUtils;
  const n = StringUtils.countWords('word1 word2 word3');
</script>
```

## Node.js

```text
npm install turbocommons-ts
```

```javascript
const { StringUtils } = require('turbocommons-ts');
const n = StringUtils.countWords('word1 word2 word3');
```

## Ubuntu shell scripts

Import the shell utilities from a fixed Git commit:

```bash
source <(curl -fsSL "https://raw.githubusercontent.com/edertone/turbocommons/<sha>/packages/turbocommons-shell/ubuntu/script-common-tools.sh")
sct_user_must_exist "username" "username must exist!"
```

## Supported languages

- PHP (7 or later recommended)
- TypeScript
- JavaScript
- Java
- Shell script

Python and C# placeholders are available for future implementations.
