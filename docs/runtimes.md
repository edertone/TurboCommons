# Development tool versions

Build and test the monorepo with the Docker toolbox defined in
[`docker/Dockerfile`](../docker/Dockerfile). The host only needs Docker
Desktop.

## Fixed toolchain

| Tool | Version |
| --- | --- |
| Node.js | `22.23.2` |
| npm | `10.9.8` |
| PHP | `8.2.32` |
| Composer | `2.8.10` |
| OpenJDK | `17.0.20` |
| Gradle | `9.1.0` |
| ShellCheck | `0.10.0` |

## Locked dependencies

- npm: Nx is defined in [`package.json`](../package.json), resolved by
  [`package-lock.json`](../package-lock.json). TypeScript tooling is defined
  in [`packages/turbocommons-ts/package.json`](../packages/turbocommons-ts/package.json),
  resolved by its [`package-lock.json`](../packages/turbocommons-ts/package-lock.json).
- PHP: PHPUnit `8.5.53`, resolved by
  [`composer.lock`](../packages/turbocommons-php/composer.lock).
- Java: JUnit `4.13.2` and Hamcrest `1.3`, resolved by
  [`gradle.lockfile`](../packages/turbocommons-java/gradle.lockfile).

Dependencies are installed with `npm ci`, `composer install`, and the
committed Gradle wrapper.
