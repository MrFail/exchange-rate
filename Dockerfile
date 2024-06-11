FROM php:8.2-fpm-alpine

# Set default path for application
WORKDIR /var/www/html/

# App files
ADD --chown=www-data:www-data . .

# ARG COMPOSER_AUTH
RUN apk update && apk upgrade

# Essentials
RUN apk add --no-cache zip unzip curl nginx redis supervisor mysql-client tzdata

# Set timezone
ENV TZ=Europe/Chisinau
RUN cp /usr/share/zoneinfo/Europe/Chisinau /etc/localtime

# Install php pdo mysql
RUN docker-php-ext-install pdo pdo_mysql opcache \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-enable opcache

# Installing bash
RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Installing composer (for remote deploy)
#RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
#RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
#RUN rm -rf composer-setup.php

# Configure supervisor
RUN mkdir -p /etc/supervisor.d/

# Configure nginx (for remote deploy)
#COPY ./.docker/config/default.conf /etc/nginx/http.d/default.conf
#COPY ./.docker/config/fastcgi-php.conf /etc/nginx/fastcgi-php.conf
#COPY ./.docker/config/php-fpm.conf /usr/local/etc/php-fpm.conf

#Copy crons from cron file (for remote deploy)
#COPY ./.docker/cron/crons /etc/crontabs/www-data

# Expose internal service port
EXPOSE 80

# Container execution
CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
