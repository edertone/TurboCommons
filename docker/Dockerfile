# syntax=docker/dockerfile:1.7

FROM node:22-bookworm

ARG COMPOSER_VERSION=2.8.10
ARG SHELLCHECK_VERSION=0.10.0

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/opt/composer \
    GRADLE_USER_HOME=/opt/gradle \
    npm_config_cache=/opt/npm-cache \
    NX_DAEMON=false \
    PATH="/opt/composer/vendor/bin:/workspace/node_modules/.bin:${PATH}"

RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
    bash \
    ca-certificates \
    curl \
    git \
    openjdk-17-jdk-headless \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP CLI separately so the image remains based on the official Node image.
RUN apt-get update \
    && apt-get install --yes --no-install-recommends \
    php-cli \
    php-curl \
    php-dom \
    php-mbstring \
    php-phar \
    php-xml \
    php-zip \
    && rm -rf /var/lib/apt/lists/*

RUN printf '%s\n' 'phar.readonly=0' > /etc/php/8.2/cli/conf.d/99-turbocommons.ini

RUN curl --fail --silent --show-error --location https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} \
    && composer --version

RUN curl --fail --silent --show-error --location \
    "https://github.com/koalaman/shellcheck/releases/download/v${SHELLCHECK_VERSION}/shellcheck-v${SHELLCHECK_VERSION}.linux.x86_64.tar.xz" \
    --output /tmp/shellcheck.tar.xz \
    && tar -xJf /tmp/shellcheck.tar.xz -C /tmp \
    && install "/tmp/shellcheck-v${SHELLCHECK_VERSION}/shellcheck" /usr/local/bin/shellcheck \
    && rm -rf /tmp/shellcheck* \
    && shellcheck --version

WORKDIR /workspace

COPY package.json package-lock.json nx.json ./

RUN npm ci --ignore-scripts

COPY . .

COPY docker/docker-entrypoint.sh /usr/local/bin/turbocommons-entrypoint
RUN chmod +x turbocommons-java/gradlew /usr/local/bin/turbocommons-entrypoint

ENTRYPOINT ["/usr/local/bin/turbocommons-entrypoint"]
CMD ["ci"]
