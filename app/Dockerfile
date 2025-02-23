FROM debian:bookworm-slim AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    gnupg2 \
    lsb-release

RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list \
    && curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /etc/apt/trusted.gpg.d/sury-php.gpg

RUN apt-get update && apt-get install -y --no-install-recommends \
    php8.4 \
    php8.4-cli \
    php8.4-common \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-mysql \
    php8.4-zip \
    php8.4-curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM builder AS builder-dev

RUN apt-get update && apt-get install -y --no-install-recommends \
    php8.4-xdebug

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

FROM alpine/curl:8.12.0 AS frankenphp

RUN curl https://frankenphp.dev/install.sh | sh && mv frankenphp /usr/local/bin/

FROM debian:bookworm-slim AS base

WORKDIR /app

ARG UID=1000
ARG GID=1000
ENV USER=appuser
ENV HOME=/home/${USER}
ENV DOCKER_STAGE=base
ENV DEBIAN_FRONTEND=noninteractive \
    SERVER_NAME=":80" \
    XDG_CONFIG_HOME=${HOME}/.config \
    XDG_DATA_HOME=${HOME}/.local/share \
    XDG_RUNTIME_DIR=${HOME}/.local/run \
    XDG_CACHE_HOME=${HOME}/.cache

# Install runtime dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libcap2-bin \
    libxml2 \
    libcurl4 \
    libgd3 \
    libxslt1.1 \
    libedit2 \
    libonig5 \
    libargon2-1 \
    libsodium23 \
    libzip4 \
    curl \
    unzip \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=builder /usr/bin/php /usr/bin/php
COPY --from=builder /usr/lib/php /usr/lib/php
COPY --from=builder /etc/php /etc/php

COPY --from=composer:2.8.5 /usr/bin/composer /usr/local/bin/composer
COPY --from=frankenphp /usr/local/bin/frankenphp /usr/local/bin/frankenphp

# Remove default user and group
RUN deluser ${USER} || true \
    && delgroup ${USER} || true

# Create new user and group with the specified IDs, if they don't already exist
RUN if ! getent group ${GID} ; then groupadd -g ${GID} ${USER} ; else groupadd -g 9999 ${USER} ; fi \
    && if ! id -u ${UID} > /dev/null 2>&1 ; then useradd -u ${UID} -ms /bin/bash -g ${USER} ${USER} ; fi

# Run frankenphp as non-root user and add permissions
RUN mkdir -p ${XDG_CONFIG_HOME} ${XDG_DATA_HOME} ${XDG_RUNTIME_DIR} ${XDG_CACHE_HOME} && \
    mkdir -p /data/caddy /config/caddy && \
    chown -R ${USER}:${USER} /app /data/caddy /config/caddy \
        ${XDG_CONFIG_HOME} ${XDG_DATA_HOME} ${XDG_RUNTIME_DIR} ${XDG_CACHE_HOME} && \
    chmod -R 700 /app /data/caddy /config/caddy && \
    # Allow frankenphp to bind to privileged ports
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

ENV PHP_INI_DIR=/usr/local/etc/php
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1

COPY --link --chown=${UID}:${GID} ./.docker/conf.d/common $PHP_INI_DIR/app.conf.d/
COPY --link --chown=${UID}:${GID} ./.docker/caddy/Caddyfile /etc/caddy/Caddyfile

RUN mkdir -p /docker-scripts/build /docker-scripts/runtime
COPY --link --chown=${UID}:${GID} ./.docker/scripts/build/run.sh /docker-scripts/build/run.sh
COPY --link --chown=${UID}:${GID} ./.docker/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /docker-scripts/build/*.sh /docker-scripts/build/run.sh /usr/local/bin/entrypoint

ENTRYPOINT [ "entrypoint" ]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

FROM base AS dev

ENV DOCKER_STAGE=dev XDEBUG_MODE=off

USER root

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        bash \
        fish \
        acl \
        file \
        gettext \
        git

COPY --from=builder /usr/bin/php /usr/bin/php
COPY --from=builder /usr/lib/php /usr/lib/php
COPY --from=builder /etc/php /etc/php
COPY --link --chown=${UID}:${GID} ./.docker/conf.d/dev $PHP_INI_DIR/app.conf.d/

COPY --link --chown=${UID}:${GID} ./.docker/scripts/build/${DOCKER_STAGE} /docker-scripts/build/
COPY --link --chown=${UID}:${GID} ./.docker/scripts/runtime/${DOCKER_STAGE} /docker-scripts/runtime/

RUN chmod +x /docker-scripts/build/*.sh /docker-scripts/runtime/*.sh

RUN /docker-scripts/build/run.sh

USER ${USER}

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

ARG BUILD_TIME
ENV BUILD_TIME=$BUILD_TIME
ARG VERSION="N/A"
ENV VERSION=$VERSION

FROM dev AS ci

USER root

FROM base AS remote-base

ENV DOCKER_STAGE=remote-base FRANKENPHP_CONFIG="import worker.Caddyfile"

COPY --link --chown=${UID}:${GID} ./.docker/caddy/worker.Caddyfile /etc/caddy/worker.Caddyfile

FROM remote-base AS integration

ENV APP_ENV=dev DOCKER_STAGE=integration

USER root

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./

COPY --link --chown=${UID}:${GID} ./.docker/scripts/build/${DOCKER_STAGE} /docker-scripts/build/
COPY --link --chown=${UID}:${GID} ./.docker/scripts/runtime/${DOCKER_STAGE} /docker-scripts/runtime/

RUN chmod +x /docker-scripts/build/*.sh /docker-scripts/runtime/*.sh

RUN /docker-scripts/build/run.sh

USER ${USER}

ARG BUILD_TIME
ENV BUILD_TIME=$BUILD_TIME
ARG VERSION="N/A"
ENV VERSION=$VERSION

FROM remote-base AS prod

ENV APP_ENV=prod DOCKER_STAGE=prod

USER root

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./

COPY --link --chown=${UID}:${GID} ./.docker/conf.d/prod $PHP_INI_DIR/app.conf.d/
COPY --link --chown=${UID}:${GID} ./.docker/scripts/build/${DOCKER_STAGE} /docker-scripts/build/
COPY --link --chown=${UID}:${GID} ./.docker/scripts/runtime/${DOCKER_STAGE} /docker-scripts/runtime/

RUN chmod +x /docker-scripts/build/*.sh /docker-scripts/runtime/*.sh

RUN /docker-scripts/build/run.sh

USER ${USER}

ARG BUILD_TIME
ENV BUILD_TIME=$BUILD_TIME
ARG VERSION="N/A"
ENV VERSION=$VERSION
