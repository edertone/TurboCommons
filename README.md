### General purpose cross-language development library

Devs around the world do the same things every day with different languages. They perform string modifications, validations, data conversions, file operations and more.

Why must they learn new ways of doing the same thing every time they move to a new language?

TurboCommons tries to standardize all those common operations so they are performed the same way across all possible languages. It is a library that uses the same methods and classes across all the implemented languages.

### How to use it

- Php:
```
Currently available only as a .phar file (download it from https://turbocommons.org)
require '..../turbocommons-php-X.X.X.phar';
use org\turbocommons\src\main\php\utils\StringUtils;
$n = StringUtils::countWords("word1 word2 word3");
```
- Typescript:
```
npm install turbocommons-ts
import { StringUtils } from 'turbocommons-ts';
let n = StringUtils.countWords("word1 word2 word3");
```
- Javascript 6:
```
npm install turbocommons-es6
<script src="turbocommons-es6/turbocommons-es6.js"></script>
var StringUtils = org_turbocommons.StringUtils;
var n = StringUtils.countWords("word1 word2 word3");
```
- NodeJS projects:
```
npm install turbocommons-ts
const {StringUtils} = require('turbocommons-ts');
var n = StringUtils.countWords("word1 word2 word3");
```
- Ubuntu shell scripts (.sh):
```
# Load turbocommons common tools from github
source <(curl -fsSL "https://raw.githubusercontent.com/edertone/turbocommons/<sha>/turbocommons-shell/ubuntu/script-common-tools.sh")
sct_user_must_exist "username" "username must exist!"
```

### Language support

- Php (7 or more recommended)
- Typescript
- Javascript
- Java
- Shell script

We want to increase this list. So! if you want to translate the library to your language of choice, please contact us! We need your help to port this library to as many languages as possible, and more important, we need to code the SAME unit tests across all the implemented languages. This is the only way to guarantee that the library delivers exactly the same behavior everywhere.

### Dependencies

The main goal for this library is to have zero dependencies. We are building a true standalone general purpose library.

### Building the monorepo with Docker

The complete build environment is provided by one Docker image. Docker Desktop
is the only runtime that needs to be installed on the host; Node.js, Nx, PHP,
Composer, PHPUnit, Java, Gradle, Bash, and ShellCheck run inside the toolbox
container.

Build the image once:

```text
docker compose -f docker-compose.yaml build toolbox
```

The root npm scripts are the canonical commands. Each one automatically runs
the corresponding operation inside the Docker toolbox:

```text
npm run build
npm test
npm run lint
npm run package
npm run clean
```

The scripts rebuild the image when required. There are no `docker:*`, `*:local`,
or host-side Nx npm scripts. The entrypoint in `docker/docker-entrypoint.sh`
maps each operation to Nx
inside the container.

The repository is mounted into `/workspace`. Named Docker volumes preserve
Node.js dependencies, Composer dependencies, npm and Composer caches, and the
Gradle cache between runs. Build artifacts are written into the mounted project
directories and remain visible on the host.

To remove generated build output and the Nx cache from all projects without
removing dependency volumes, run:

```text
npm run clean
```

This removes generated `dist`, `target`, `build`, `bin`, and `.nx` directories.
It does not remove `node_modules`, Composer `vendor`, or Docker caches.

Inside the container, Nx orchestrates the native tools. PHP uses Composer and
PHPUnit, TypeScript uses `tsc` and webpack, Java uses Gradle, and shell scripts
use ShellCheck. Versions are owned by each project (`version.json`, `package.json`,
and `version.properties`) so releases can be published independently.

The image is intentionally single and polyglot for consistent local and CI
execution. Browser tests that require a real Chrome or Firefox instance remain
separate from the Node/JSDOM test target.

### Contribute

Turbo Commons is 100% free and open source, but we will be really pleased to receive any help, support, comments or donations to help us improve this library. If you like it, spread the word!
