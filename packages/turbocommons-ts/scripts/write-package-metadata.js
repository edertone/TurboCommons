const fs = require('node:fs');
const path = require('node:path');

const projectRoot = path.resolve(__dirname, '..');
const versions = JSON.parse(fs.readFileSync(path.join(projectRoot, 'bundle-versions.json'), 'utf8'));

for (const [name, version] of Object.entries(versions)) {
  const target = path.join(projectRoot, 'dist', name.replace('turbocommons-', ''));
  fs.mkdirSync(target, { recursive: true });
  fs.writeFileSync(path.join(target, 'package.json'), `${JSON.stringify({
    name,
    version,
    description: 'TurboCommons browser bundle',
    license: 'Apache-2.0',
    main: `${name}.js`,
    files: [`${name}.js`, `${name}.js.map`]
  }, null, 2)}\n`);
}
