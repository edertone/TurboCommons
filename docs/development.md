# Development

TurboCommons is a polyglot monorepo. Each implementation keeps its native
build tool, while the Dev Container provides the complete and reproducible
development environment.

## Requirements

For the container workflow, install Docker Desktop or another Docker Engine and
the VS Code Dev Containers extension. The Dev Container contains:

- Node.js and npm
- Nx
- PHP, Composer, and PHPUnit
- Java and Gradle
- Bash and ShellCheck

## Dev Container workflow

Open the repository in VS Code and run **Dev Containers: Reopen in Container**.
The configuration is in [`.devcontainer/devcontainer.json`](../.devcontainer/devcontainer.json)
and builds [`.devcontainer/Dockerfile`](../.devcontainer/Dockerfile). The
container uses `/workspace` so the workflow scripts and distribution staging
paths are the same in every environment.

The container is persistent and remains available for interactive terminals.
Dependencies are checked automatically after creation and can be refreshed at
any time with:

npm run setup
```

The root npm scripts are the canonical commands. Run them in a terminal in the
Dev Container:

```text
npm run clean
npm run build
npm run lint
npm test
npm run test:java
npm run test:php
npm run test:shell
npm run test:ts
npm run ci
npm run package
npm run dist
```

Rebuild the Dev Container after changing `.devcontainer/Dockerfile`, the
pinned runtime or system package versions, or other image-level configuration.
Routine scripts do not rebuild the image.

To run only one library's tests from the repository root, use the corresponding
script:

```text
npm run test:java
npm run test:php
npm run test:shell
npm run test:ts
```

The root `npm test` command runs the test target for every registered project.

Nx runs inside the Dev Container. The workflow entrypoint at
`.devcontainer/devcontainer-entrypoint.sh` keeps dependency setup and
distribution staging consistent with the container environment.

The repository is mounted into `/workspace`. Named Docker volumes configured by
the Dev Container preserve Node.js dependencies, Composer dependencies, npm and
Composer caches, and the Gradle cache between rebuilds.

## Dev Container CLI workflow

The same Dev Container can be launched without VS Code by using the Dev
Container CLI. Install Docker and the CLI on the host, then run these commands
from the repository root:

```bash
npm install --global @devcontainers/cli
devcontainer up --workspace-folder .
devcontainer exec --workspace-folder . bash
```

The final command opens an interactive Bash shell inside the container. From
that shell, run the usual project commands:

```bash
npm run build
npm test
npm run lint
```

To execute a command without opening an interactive shell, use `exec` directly:

```bash
devcontainer exec --workspace-folder . npm run ci
```

Stop the container when it is no longer needed:

```bash
devcontainer down --workspace-folder .
```

## Cleaning generated files

Remove generated project output and Nx cache without removing dependency volumes:

```text
npm run clean
```

This removes generated `dist`, `target`, `build`, `bin`, and `.nx` directories.
It does not remove the Node.js dependency volumes, PHP `vendor`, npm cache,
Composer cache, or Gradle cache.

## Local environment workflow

The same root scripts also work outside the Dev Container when the required
toolchain is installed locally: Node.js `22.23.2`, npm `10.9.8`, PHP `8.2`,
Composer `2.8.10`, Java 17 with Gradle, Bash, and ShellCheck. From the repository
root, run `npm install` and `npm --prefix packages/turbocommons-ts install`, then
`composer install` in `packages/turbocommons-php`. After that, use the same
`npm run build`, `npm test`, `npm run lint`, and release commands.

The local workflow does not use Docker or Docker Compose. `npm run setup` is
available as a convenience for refreshing local dependencies, but it expects
the native tools to be on `PATH`.

## Root distribution artifacts

Build and stage all final artifacts under the root `dist/` directory:

```text
npm run dist
```

The `dist` and `ci` commands stage final artifacts after their normal
workflows complete.

```text
dist/
├── turbocommons-php/
│   └── turbocommons-php-X.X.X.phar
├── turbocommons-ts/
│   ├── turbocommons-ts-X.X.X.tgz
│   ├── turbocommons-es5-X.X.X.tgz
│   └── turbocommons-es6-X.X.X.tgz
├── turbocommons-java/
│   └── turbocommons-java-X.X.X.jar
└── turbocommons-shell/
    └── turbocommons-shell-X.X.X.tar.gz
```

Project-local output directories remain available to native tools. The root
`dist/` directory is a release staging area and is ignored by Git.

## Independent versions

Versions are owned by each project:

- PHP: `packages/turbocommons-php/version.json`
- TypeScript and browser bundles: project `package.json` files
- Java: `packages/turbocommons-java/version.properties`
- Shell: `packages/turbocommons-shell/package.json`

This allows each package to be released independently from the same repository.

## CI considerations

The same Docker image can be used locally and in CI. Browser tests that require
real Chrome or Firefox instances remain separate from the Node/JSDOM test target.
