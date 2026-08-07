# syntax=docker/dockerfile:1.7

FROM node:22-bookworm@sha256:0557ac14e0d45d02ed563067b82856ca5e7aa3437fa28d98d4350ea9c3d9494a

ARG NODE_VERSION=22.23.2
ARG NPM_VERSION=10.9.8
ARG COMPOSER_VERSION=2.8.10
ARG COMPOSER_SHA256=28dbb6bd8bef31479c7985b774c130a8bda37dbe63c35b56f6cb6bc377427573
ARG PHP_PACKAGE_VERSION=8.2.32-1~deb12u1
ARG JDK_PACKAGE_VERSION=17.0.20+8-1~deb12u1
ARG SHELLCHECK_VERSION=0.10.0
ARG SHELLCHECK_SHA256=6c881ab0698e4e6ea235245f22832860544f17ba386442fe7e9d629f8cbedf87

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/opt/composer \
    GRADLE_USER_HOME=/opt/gradle \
    npm_config_cache=/opt/npm-cache \
    NX_DAEMON=false \
    NODE_PATH=/opt/turbocommons/node_modules:/opt/turbocommons-ts/node_modules \
    PATH="/opt/composer/vendor/bin:/opt/turbocommons/node_modules/.bin:/opt/turbocommons-ts/node_modules/.bin:${PATH}"

RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
    bash=5.2.15-2+b13 \
    ca-certificates=20230311+deb12u1 \
    curl=7.88.1-10+deb12u15 \
    git=1:2.39.5-0+deb12u3 \
    openjdk-17-jdk-headless=${JDK_PACKAGE_VERSION} \
    unzip=6.0-28 \
    zip=3.0-13 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP CLI separately so the image remains based on the official Node image.
RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
    php8.2-cli=${PHP_PACKAGE_VERSION} \
    php8.2-common=${PHP_PACKAGE_VERSION} \
    php8.2-curl=${PHP_PACKAGE_VERSION} \
    php8.2-mbstring=${PHP_PACKAGE_VERSION} \
    php8.2-xml=${PHP_PACKAGE_VERSION} \
    php8.2-zip=${PHP_PACKAGE_VERSION} \
    && rm -rf /var/lib/apt/lists/*

RUN printf '%s\n' 'phar.readonly=0' > /etc/php/8.2/cli/conf.d/99-turbocommons.ini

RUN curl --fail --silent --show-error --location \
    "https://getcomposer.org/download/${COMPOSER_VERSION}/composer.phar" \
    --output /usr/local/bin/composer \
    && printf '%s  %s\n' "${COMPOSER_SHA256}" /usr/local/bin/composer | sha256sum --check --status - \
    && chmod +x /usr/local/bin/composer \
    && composer --version

RUN curl --fail --silent --show-error --location \
    "https://github.com/koalaman/shellcheck/releases/download/v${SHELLCHECK_VERSION}/shellcheck-v${SHELLCHECK_VERSION}.linux.x86_64.tar.xz" \
    --output /tmp/shellcheck.tar.xz \
    && printf '%s  %s\n' "${SHELLCHECK_SHA256}" /tmp/shellcheck.tar.xz | sha256sum --check --status - \
    && tar -xJf /tmp/shellcheck.tar.xz -C /tmp \
    && install "/tmp/shellcheck-v${SHELLCHECK_VERSION}/shellcheck" /usr/local/bin/shellcheck \
    && rm -rf /tmp/shellcheck* \
    && shellcheck --version

RUN test "$(node --version)" = "v${NODE_VERSION}" \
    && test "$(npm --version)" = "${NPM_VERSION}" \
    && test "$(php -r 'echo PHP_VERSION;')" = "${PHP_PACKAGE_VERSION%%-*}" \
    && test "$(java -version 2>&1 | sed -n 's/.*version \"\([0-9.]*\).*/\1/p')" = "${JDK_PACKAGE_VERSION%%+*}"

WORKDIR /workspace

COPY package.json package-lock.json nx.json ./
COPY packages/turbocommons-ts/package.json packages/turbocommons-ts/package-lock.json /opt/turbocommons-ts/

RUN mkdir -p /opt/turbocommons \
    && cp package.json package-lock.json /opt/turbocommons/ \
    && npm ci --prefix /opt/turbocommons --ignore-scripts \
    && npm ci --prefix /opt/turbocommons-ts --ignore-scripts

COPY . .

COPY docker/docker-entrypoint.sh /usr/local/bin/turbocommons-entrypoint
RUN chmod +x packages/turbocommons-java/gradlew /usr/local/bin/turbocommons-entrypoint

ENTRYPOINT ["/usr/local/bin/turbocommons-entrypoint"]
CMD ["ci"]
