#syntax=docker/dockerfile:1.4

# Versions
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

FROM frankenphp_upstream as frankenphp_upstream_node

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

WORKDIR /app

# Install NVM
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 20.17.0

RUN mkdir -p $NVM_DIR

RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.40.1/install.sh | bash

RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

ENV COREPACK_ENABLE_DOWNLOAD_PROMPT=0
RUN corepack enable pnpm && corepack use pnpm@9.9

SHELL ["/bin/sh", "-c"]

# Base FrankenPHP image
FROM frankenphp_upstream_node AS frankenphp_base

WORKDIR /app

VOLUME /app/var/

# persistent / runtime deps
# hadolint ignore=DL3008
RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
    gosu \
	;

RUN rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
    	pdo_pgsql \
	;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

###> recipes ###
###> doctrine/doctrine-bundle ###
###< doctrine/doctrine-bundle ###
###< recipes ###

COPY --link .docker/frankenphp/conf.d/10-app.ini $PHP_INI_DIR/app.conf.d/
COPY --link --chmod=755 .docker/frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link .docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# Dev FrankenPHP image
FROM frankenphp_base AS frankenphp_dev

ENV PHPSTAN_PRO_WEB_PORT=11112
EXPOSE 11112

COPY --link --chmod=755 .docker/frankenphp/docker-entrypoint-dev.sh /usr/local/bin/docker-entrypoint

ENV APP_ENV=dev XDEBUG_MODE=off

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

COPY --link .docker/frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

# Install fish shell
ENV XDG_CONFIG_HOME="/home/www-data/.config"
ENV XDG_DATA_HOME="/home/www-data/.local/share"

RUN mkdir -p ${XDG_CONFIG_HOME}/fish
RUN mkdir -p ${XDG_DATA_HOME}

RUN apt-get update && apt-get install -y fish

# Init non-root user
ARG USER=www-data

# Remove default user and group
RUN deluser www-data || true \
    && delgroup www-data || true

# Create new user and group with the same id as the host user
RUN groupadd -g 1000 www-data \
    && useradd -u 1000 -ms /bin/bash -g www-data www-data

RUN chown -R ${USER}:${USER} /home /tmp /app /home/${USER} ${XDG_CONFIG_HOME} ${XDG_DATA_HOME}

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

# Prod FrankenPHP image
FROM frankenphp_base AS frankenphp_prod

ARG SERVER_NAME=":80"
ENV SERVER_NAME=${SERVER_NAME}
ARG BUILD_TIME
ENV BUILD_TIME=$BUILD_TIME

ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="worker ./public/index.php"
ENV APP_RUNTIME="Runtime\FrankenPhpSymfony\Runtime"
ENV MAX_REQUESTS=200

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link .docker/frankenphp/conf.d/20-app.prod.ini $PHP_INI_DIR/app.conf.d/
COPY --link .docker/frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

# prevent the reinstallation of vendors at every changes in the source code
COPY --link ./app/composer.* ./app/symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

COPY --link ./app/pnpm-* ./app/package.json ./app/tsconfig.* ./app/vite.config.js ./app/postcss.config.cjs ./
RUN set -eux; \
    pnpm install --no-frozen-lockfile --production;

# copy sources
COPY --link ./app ./

RUN rm -Rf .docker/frankenphp/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync;

RUN set -eux; pnpm run build;