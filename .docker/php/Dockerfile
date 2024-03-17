FROM dunglas/frankenphp:1-php8.3.3 as frankenphp

WORKDIR /app
USER root

RUN apt-get update && apt-get install -y \
    bash \
    nano \
    git \
    acl \
    file \
    gettext \
    openssh-client \
    yarn

# Install PHP extensions
RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        zip \
        pdo_mysql \
        excimer \
    ;

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --link ./.docker/php/Caddyfile /etc/caddy/Caddyfile
COPY --link ./.docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

FROM frankenphp as dev-base

ENV APP_ENV=dev

COPY ./.docker/php/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/

# Install fish shell
ARG XDG_CONFIG_HOME=/home/www-data/.config
ENV XDG_CONFIG_HOME=${XDG_CONFIG_HOME}

ARG XDG_DATA_HOME=/home/www-data/.local/share
ENV XDG_DATA_HOME=${XDG_DATA_HOME}

RUN mkdir -p ${XDG_CONFIG_HOME}/fish
RUN mkdir -p ${XDG_DATA_HOME}

RUN apt-get update && apt-get install -y fish

# Install Xdebug
RUN set -eux; \
    install-php-extensions \
        xdebug \
        pcov \
    ;

RUN echo "xdebug.mode=debug,develop,profile" >> $PHP_INI_DIR/conf.d/.docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> $PHP_INI_DIR/conf.d/.docker-php-ext-xdebug.ini \
    && echo "xdebug.output_dir=/tmp/xdebug" >> $PHP_INI_DIR/conf.d/.docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> $PHP_INI_DIR/conf.d/.docker-php-ext-xdebug.ini

# Config pcov
#RUN echo "pcov.enabled=1" >> $PHP_INI_DIR/conf.d/pcov.ini \
#    && echo "pcov.directory=/app" >> $PHP_INI_DIR/conf.d/pcov.ini \
#    && echo "pcov.exclude=vendor" >> $PHP_INI_DIR/conf.d/pcov.ini


# Init non-root user
ARG USER=www-data

# Remove default user and group
RUN deluser www-data || true \
    && delgroup www-data || true

# Create new user and group with the same id as the host user
RUN groupadd -g 1000 www-data \
    && useradd -u 1000 -ms /bin/bash -g www-data www-data

RUN chown -R ${USER}:${USER} /home /tmp /app /home/${USER} ${XDG_CONFIG_HOME} ${XDG_DATA_HOME}

# Install castor
RUN curl "https://github.com/jolicode/castor/releases/latest/download/castor.linux-amd64.phar" -L -o castor.phar && \
    chmod +x castor.phar && \
    mv castor.phar /usr/local/bin/castor

FROM dev-base as worker-dev

FROM dev-base as dev

COPY --link --chmod=755 ./.docker/php/dev-entrypoint.sh /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

USER root

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

FROM frankenphp as prod

ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link ./.docker/php/app.prod.ini $PHP_INI_DIR/conf.d/
COPY --link ./.docker/php/worker.Caddyfile /etc/caddy/worker.Caddyfile

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
    composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./
RUN rm -Rf frankenphp/

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer dump-env prod; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync;