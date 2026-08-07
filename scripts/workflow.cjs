const { execFileSync, spawnSync } = require('node:child_process');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const command = process.argv[2] || 'ci';
const isDevContainer = process.env.TURBOCOMMONS_DEV_CONTAINER === '1';
const nxRoot = process.env.TURBOCOMMONS_NX_ROOT || root;
const tsRoot = process.env.TURBOCOMMONS_TS_ROOT || path.join(root, 'packages', 'turbocommons-ts');

function run(executable, args, options = {}) {
  execFileSync(executable, args, {
    cwd: options.cwd || root,
    env: process.env,
    stdio: 'inherit',
    shell: process.platform === 'win32',
  });
}

function commandExists(executable) {
  const lookup = process.platform === 'win32' ? 'where' : 'command';
  const lookupArgs = process.platform === 'win32' ? [executable] : ['-v', executable];
  return spawnSync(lookup, lookupArgs, { stdio: 'ignore', shell: process.platform === 'win32' }).status === 0;
}

function dependencyStamp(...files) {
  const crypto = require('node:crypto');
  const hash = crypto.createHash('sha256');
  for (const file of files) {
    hash.update(fs.readFileSync(path.join(root, file)));
  }
  return hash.digest('hex');
}

function runDevContainerEntrypoint(args) {
  const entrypoint = path.join(root, '.devcontainer', 'devcontainer-entrypoint.sh');
  run('bash', [entrypoint, ...args]);
}

function dependencyStampFiles() {
  const rootModules = path.join(root, 'node_modules');
  const tsModules = path.join(tsRoot, 'node_modules');
  const rootMarker = path.join(rootModules, '.turbocommons-dependencies');
  const tsMarker = path.join(tsModules, '.turbocommons-dependencies');
  const rootStamp = String(dependencyStamp('package.json', 'package-lock.json'));
  const tsStamp = String(dependencyStamp(
    'packages/turbocommons-ts/package.json',
    'packages/turbocommons-ts/package-lock.json',
  ));

  if (!fs.existsSync(path.join(rootModules, '.bin', 'nx')) && !fs.existsSync(path.join(rootModules, '.bin', 'nx.cmd')) || !fs.existsSync(rootMarker) || fs.readFileSync(rootMarker, 'utf8').trim() !== rootStamp) {
    run('npm', ['ci', '--ignore-scripts'], { cwd: root });
    fs.writeFileSync(rootMarker, `${rootStamp}\n`);
  }
  if (!fs.existsSync(path.join(tsModules, '.bin', 'webpack')) && !fs.existsSync(path.join(tsModules, '.bin', 'webpack.cmd')) || !fs.existsSync(tsMarker) || fs.readFileSync(tsMarker, 'utf8').trim() !== tsStamp) {
    run('npm', ['ci', '--ignore-scripts'], { cwd: tsRoot });
    fs.writeFileSync(tsMarker, `${tsStamp}\n`);
  }
  if (!commandExists('composer')) {
    throw new Error('Composer is required for the local workflow. Install Composer 2.8.10 or reopen the repository in the Dev Container.');
  }
  if (!commandExists('php')) {
    throw new Error('PHP 8.2 is required for the local workflow. Install PHP 8.2 or reopen the repository in the Dev Container.');
  }
  run('composer', ['install', '--no-interaction', '--no-progress', '--prefer-dist'], {
    cwd: path.join(root, 'packages', 'turbocommons-php'),
  });
}

function ensureDependencies() {
  if (isDevContainer) {
    runDevContainerEntrypoint(['deps']);
    return;
  }

  dependencyStampFiles();
}

function nx(args) {
  const executable = process.platform === 'win32'
    ? path.join(nxRoot, 'node_modules', '.bin', 'nx.cmd')
    : path.join(nxRoot, 'node_modules', '.bin', 'nx');
  run(executable, args);
}

function removeGeneratedFiles() {
  const paths = [
    '.nx',
    'dist',
    ...fs.globSync('packages/turbocommons-*/dist'),
    ...fs.globSync('packages/turbocommons-*/target'),
    ...fs.globSync('packages/turbocommons-*/build'),
    ...fs.globSync('packages/turbocommons-*/bin'),
  ];

  for (const relativePath of paths) {
    fs.rmSync(path.join(root, relativePath), { recursive: true, force: true });
  }
}

function stageDistribution() {
  const distributionRoot = path.join(root, 'dist');
  const phpRoot = path.join(root, 'packages', 'turbocommons-php');
  const tsRootPath = path.join(root, 'packages', 'turbocommons-ts');
  const javaRoot = path.join(root, 'packages', 'turbocommons-java');
  const shellRoot = path.join(root, 'packages', 'turbocommons-shell');

  fs.rmSync(distributionRoot, { recursive: true, force: true });
  for (const packageName of ['turbocommons-php', 'turbocommons-ts', 'turbocommons-java', 'turbocommons-shell']) {
    fs.mkdirSync(path.join(distributionRoot, packageName), { recursive: true });
  }

  const phpVersion = JSON.parse(fs.readFileSync(path.join(phpRoot, 'version.json'), 'utf8')).version;
  const phpArtifact = path.join(phpRoot, 'dist', `turbocommons-php-${phpVersion}.phar`);
  fs.copyFileSync(phpArtifact, path.join(distributionRoot, 'turbocommons-php', path.basename(phpArtifact)));

  for (const artifact of fs.globSync(path.join(tsRootPath, 'dist', 'packages', '*.tgz'))) {
    fs.copyFileSync(artifact, path.join(distributionRoot, 'turbocommons-ts', path.basename(artifact)));
  }

  const javaVersion = fs.readFileSync(path.join(javaRoot, 'version.properties'), 'utf8')
    .split(/\r?\n/)
    .find((line) => line.startsWith('version='))
    .slice('version='.length);
  for (const artifact of fs.globSync(path.join(javaRoot, 'build', 'libs', '*.jar'))) {
    fs.copyFileSync(artifact, path.join(distributionRoot, 'turbocommons-java', path.basename(artifact)));
  }
  fs.writeFileSync(path.join(distributionRoot, 'turbocommons-java', 'VERSION'), `${javaVersion}\n`);

  const shellVersion = JSON.parse(fs.readFileSync(path.join(shellRoot, 'package.json'), 'utf8')).version;
  const shellArchive = path.join(distributionRoot, 'turbocommons-shell', `turbocommons-shell-${shellVersion}.tar.gz`);
  const tarResult = spawnSync('tar', [
    '-czf', shellArchive,
    '-C', shellRoot,
    'ubuntu',
    'win-powershell',
    'README.md',
    'package.json',
  ], { cwd: root, env: process.env, stdio: 'inherit', shell: false });
  if (tarResult.error || tarResult.status !== 0) {
    throw tarResult.error || new Error('tar failed while staging the shell distribution');
  }
}

if (command === 'clean') {
  removeGeneratedFiles();
  process.exit(0);
}

ensureDependencies();

switch (command) {
  case 'deps':
    break;
  case 'build':
    nx(['run-many', '-t', 'build', '--all', '--parallel=3']);
    break;
  case 'test':
    nx(['run-many', '-t', 'test', '--all', '--parallel=3']);
    break;
  case 'test:java':
    nx(['run', 'turbocommons-java:test']);
    break;
  case 'test:php':
    nx(['run', 'turbocommons-php:test']);
    break;
  case 'test:shell':
    nx(['run', 'turbocommons-shell:test']);
    break;
  case 'test:ts':
    nx(['run', 'turbocommons-ts:test']);
    break;
  case 'lint':
    nx(['run-many', '-t', 'lint', '--all', '--parallel=3']);
    break;
  case 'package':
  case 'dist':
    nx(['run-many', '-t', 'package', '--all', '--parallel=3']);
    stageDistribution();
    break;
  case 'ci':
    nx(['run-many', '-t', 'lint', '--all', '--parallel=3']);
    nx(['run-many', '-t', 'test', '--all', '--parallel=3']);
    nx(['run-many', '-t', 'package', '--all', '--parallel=3']);
    stageDistribution();
    break;
  case 'shell':
    if (isDevContainer) {
      run('bash', [], { cwd: root });
    } else {
      console.log('Use a terminal in the Dev Container for an interactive shell.');
    }
    break;
  default:
    throw new Error(`Unknown workflow command: ${command}`);
}
