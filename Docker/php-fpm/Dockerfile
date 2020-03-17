FROM php:7.3-fpm

RUN apt-get update

RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

RUN usermod -u 1000 www-data

#RUN apt-get install -y tor
#RUN apt-get install -y privoxy

WORKDIR /var/www/coupons-parser

#COPY ./privoxy/config /etc/privoxy
#COPY ./tor/torrc /etc/tor
#COPY ./tor/torsocks.conf /etc/tor

##ENTRYPOINT ["/tmp/entrypoint.sh"]
#service privoxy restart; service tor restart;
CMD composer install ;  php artisan migrate:refresh ; php-fpm

#FROM ubuntu:bionic
#ENV TZ=Europe/Moscow
#RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
#RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/* \
#    && localedef -i en_US -c -f UTF-8 -A /usr/share/locale/locale.alias en_US.UTF-8
#ENV LANG en_US.utf8
#RUN apt-get update
##RUN apt-get install -y php-fpm
#RUN apt install -y software-properties-common
#
#RUN add-apt-repository ppa:ondrej/php && apt-get update
#
#RUN apt install -y php7.3 php7.3-cli php7.3-common curl
#RUN apt install -y php7.3-xml php7.3-fpm php7.3-intl php7.3-pdo php-pgsql
#RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev
#RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
#
##RUN usermod -u 1000 www-data
#
#RUN apt-get install -y tor
#RUN apt-get install -y privoxy
#
#WORKDIR /var/www/coupons-parser
#
##COPY ./privoxy /etc
##COPY ./tor/torrc /etc/tor
##COPY ./tor/torsocks.conf /etc/tor
#
###ENTRYPOINT ["/tmp/entrypoint.sh"]
## ; service privoxy restart; service tor restart; php-fpm
#RUN sed -i 's/.*listen.*/listen=127.0.0.1:9000/g' /etc/php/7.3/fpm/pool.d/www.conf
#CMD composer install
#CMD php-fpm
