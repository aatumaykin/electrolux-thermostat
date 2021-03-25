FROM php:8.0-cli-alpine

RUN apk add --no-cache  git bash

COPY . /data
WORKDIR /data

COPY    --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN     install-php-extensions sockets

#RUN     curl -sS https://get.symfony.com/cli/installer | bash; \
#        mv /root/.symfony/bin/symfony /usr/bin/symfony; \
#        chmod +x /usr/bin/symfony

COPY    --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN     composer install --prefer-dist --no-dev --no-progress --no-interaction; \
        chmod +x bin/console; sync

#EXPOSE 8000

#CMD [ "symfony serve" ]

RUN ["bin/console"]