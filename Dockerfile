FROM php:8.3-alpine as base

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

RUN mkdir /app

WORKDIR /app/

ADD composer.json composer.lock /app/

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader --prefer-dist


FROM base as prod

COPY . /app

RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize

RUN composer dump-autoload --optimize

ENTRYPOINT /app/docker/docker-entrypoint

CMD jukebox
