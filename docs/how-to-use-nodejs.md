# How to use TurboCommons with Node.js

Install the TypeScript package from npm and consume its compiled CommonJS
output:

```bash
npm install turbocommons-ts
```

```javascript
const { StringUtils } = require('turbocommons-ts');

const n = StringUtils.countWords('word1 word2 word3');
```

The package source and build instructions are available in the
[`packages/turbocommons-ts`](../packages/turbocommons-ts/) directory.
