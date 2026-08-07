const { spawnSync } = require('node:child_process');
const path = require('node:path');

const workspaceRoot = path.resolve(__dirname, '..');
const command = process.argv[2] || 'ci';
const args = process.argv.slice(3);
const entrypoint = path.join(workspaceRoot, '.devcontainer', 'devcontainer-entrypoint.sh');

const result = spawnSync('bash', [entrypoint, command, ...args], {
    cwd: workspaceRoot,
    stdio: 'inherit',
    shell: false,
});

if (result.error) {
    console.error(`Unable to run ${command}: ${result.error.message}`);
    process.exit(1);
}

process.exit(result.status ?? 1);
