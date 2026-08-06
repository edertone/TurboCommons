# How to use TurboCommons with JavaScript

Install the TypeScript package from npm and consume its compiled CommonJS
output from Node.js:

```bash
npm install turbocommons-ts
```

```javascript
const { StringUtils } = require('turbocommons-ts');

const n = StringUtils.countWords('word1 word2 word3');
```

The package also produces browser bundles. Build the package locally with the
 instructions in the [TypeScript usage guide](how-to-use-typescript.md).
