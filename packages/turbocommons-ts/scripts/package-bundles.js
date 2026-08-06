const { execFileSync } = require('node:child_process');
const path = require('node:path');

const projectRoot = path.resolve(__dirname, '..');
const packageDestination = path.join(projectRoot, 'dist', 'packages');

for (const bundle of ['es5', 'es6']) {
  execFileSync(process.platform === 'win32' ? 'npm.cmd' : 'npm', [
    'pack',
    path.join(projectRoot, 'dist', bundle),
    '--pack-destination',
    packageDestination
  ], { stdio: 'inherit' });
}