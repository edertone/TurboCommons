const fs = require('node:fs');
const path = require('node:path');
const { spawnSync } = require('node:child_process');

const root = path.resolve(__dirname, '..');
const files = fs.readdirSync(path.join(root, 'ubuntu')).filter((file) => file.endsWith('.sh'));
const shellcheck = process.platform === 'win32' ? 'shellcheck.exe' : 'shellcheck';
const available = spawnSync(shellcheck, ['--version'], { stdio: 'ignore' }).status === 0;

if (!available) {
  console.warn('ShellCheck is not installed; shell lint was skipped. Install ShellCheck to enable this check.');
  process.exit(0);
}

const result = spawnSync(shellcheck, files.map((file) => path.join(root, 'ubuntu', file)), { stdio: 'inherit' });
process.exit(result.status ?? 1);
