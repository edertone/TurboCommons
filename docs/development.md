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

Build the toolbox image explicitly:

```text
docker compose -f docker-compose.yaml build toolbox
```

The root npm scripts are the canonical commands. They run entirely inside the
Docker container:

```text
npm run build
npm test
npm run lint
npm run package
npm run clean
npm run dist
npm run ci
```

There are no host-side Nx commands. Nx runs only inside the Docker entrypoint
at `docker/docker-entrypoint.sh`.

The repository is mounted into `/workspace`. Named Docker volumes preserve
Node.js dependencies, Composer dependencies, npm and Composer caches, and the
Gradle cache between runs.

## Build and test targets

The Docker entrypoint orchestrates the registered Nx projects:

- `turbocommons-php`
- `turbocommons-ts`
- `turbocommons-java`
- `turbocommons-shell`

The implementations are stored under the root `packages/` directory.

Use the standard commands for the corresponding operation:

```text
npm run build
npm test
npm run lint
npm run package
```

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

The `package` and `ci` commands also stage final artifacts after their normal
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
