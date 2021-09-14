FROM ubuntu:18.04

MAINTAINER Julien Tant <julien@craftyx.fr>
MAINTAINER Esteban Zeller <esteban.zeller@gmail.com>

RUN apt-get update \
    && apt-get install --no-install-recommends -y software-properties-common locales supervisor \
    && apt-get update \
    && locale-gen en_US.UTF-8

ENV LANG=en_US.UTF-8
ENV LANGUAGE=en_US:en
ENV LC_ALL=en_US.UTF-8

RUN add-apt-repository ppa:nginx/stable \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update
RUN apt-get install --no-install-recommends -y \
        nginx \
        php7.3-fpm \
        php7.3-cli \
        php7.3-xdebug \
        php7.3-pdo \
        php7.3-pdo-mysql \
        php7.3-pdo-pgsql \
        php7.3-sqlite3 \
        php7.3-xml \
        php7.3-mbstring \
        php7.3-tokenizer \
        php7.3-zip \
        php7.3-mcrypt \
        php7.3-gd \
        php7.3-curl \
        php7.3-soap \
        php7.3-redis \
        curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get remove -y --purge software-properties-common curl
RUN apt-get clean
RUN apt-get autoremove -y && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY default /etc/nginx/sites-enabled/default
COPY php.ini /etc/php/7.3/fpm/php.ini
COPY php-fpm.conf /etc/php/7.3/fpm/php-fpm.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY laravel.ini /etc/php/7.3/fpm/conf.d/laravel.ini
#COPY disabled-xdebug.ini /etc/php/7.3/mods-available/xdebug.ini
COPY enabled-xdebug.ini /etc/php/7.3/mods-available/xdebug.ini

RUN /etc/init.d/php7.3-fpm restart

RUN mkdir /tmp/certgen
WORKDIR /tmp/certgen
RUN openssl genrsa -des3 -passout pass:gsahdg -out server.pass.key 2048
RUN openssl rsa -passin pass:gsahdg -in server.pass.key -out server.key
RUN rm server.pass.key
RUN openssl req -new -key server.key -out server.csr -subj "/CN=asgardcms.com"
RUN openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt
RUN cp server.crt /etc/ssl/certs/
RUN cp server.key /etc/ssl/private/
RUN rm -rf /tmp/certgen

EXPOSE 80
EXPOSE 443

WORKDIR /var/www/html

CMD ["supervisord"]
