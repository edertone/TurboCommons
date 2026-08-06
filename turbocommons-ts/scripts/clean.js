const fs = require('node:fs');
const path = require('node:path');

for (const relativePath of ['dist']) {
  const target = path.resolve(__dirname, '..', relativePath);
  fs.rmSync(target, { recursive: true, force: true });
}
