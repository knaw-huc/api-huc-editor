FROM php:7.4-apache
EXPOSE 80 443

RUN apt-get update && apt-get install -y libc-client-dev libfreetype6-dev libmcrypt-dev libpng-dev libjpeg-dev libldap2-dev zlib1g-dev libkrb5-dev libtidy-dev libzip-dev libsodium-dev libpq-dev libxml2-dev libxslt1-dev  && rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install gd mysqli pdo pdo_mysql pdo_pgsql opcache zip iconv tidy xml xsl \
    && docker-php-ext-configure ldap --with-libdir=lib/$(gcc -dumpmachine)/ \
    && docker-php-ext-install ldap \
    && docker-php-ext-configure imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-install imap \
    && docker-php-ext-install sodium \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install exif

RUN a2enmod rewrite