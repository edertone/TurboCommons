# Development

TurboCommons is a polyglot monorepo. Each implementation keeps its native
build tool, while Docker provides the complete and reproducible development
environment.

## Requirements

Only Docker Desktop is required on the host. The toolbox image contains:

- Node.js and npm
- Nx
- PHP, Composer, and PHPUnit
- Java and Gradle
- Bash and ShellCheck

## Docker workflow

Build the toolbox image once (or after changing the Docker environment):

```text
npm run docker:build
```

The normal source code workflow does not rebuild the image. The repository is
mounted into the container, so changes under the repository are available
immediately. The entrypoint compares the dependency lockfiles with stamps in
the named dependency volumes and only runs `npm ci` or `composer install` when
the dependencies are missing or the lockfiles have changed.

To check or refresh dependencies explicitly, use:

```text
npm run docker:deps
```

The root npm scripts are the canonical commands. They run entirely inside the
Docker container:

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

Open a shell in the toolbox when running several commands interactively:

```text
npm run docker:shell
```

Rebuild the image with `npm run docker:build` when changing `docker/Dockerfile`,
the pinned runtime or system package versions, or other image-level
configuration. `--build` is intentionally not used by the routine scripts.

To run only one library's tests from the repository root, use the corresponding
script:

```text
npm run test:java
npm run test:php
npm run test:shell
npm run test:ts
```

The root `npm test` command runs the test target for every registered project.

There are no host-side Nx commands. Nx runs only inside the Docker entrypoint
at `docker/docker-entrypoint.sh`.

The repository is mounted into `/workspace`. Named Docker volumes preserve
Node.js dependencies, Composer dependencies, npm and Composer caches, and the
Gradle cache between runs.

## Cleaning generated files

Remove generated project output and Nx cache without removing dependency volumes:

```text
npm run clean
```

This removes generated `dist`, `target`, `build`, `bin`, and `.nx` directories.
It does not remove `node_modules`, PHP `vendor`, npm cache, Composer cache, or
Gradle cache.

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
