FROM alpine:latest

RUN apk update && apk upgrade && \
    apk add --no-cache \
    nano \
    curl \
    npm \
    php7 \
    apache2 \
    php7-xsl \
    php7-xml \
    php7-json \
    php7-phar \
    php7-pear \
    php7-soap \
    php7-iconv \
    php7-xdebug \
    php7-apache2 \
    php7-openssl \
    php7-session \
    php7-mbstring \
    php7-xmlwriter \
    php7-tokenizer \
    php7-pecl-redis \
    php7-pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY config/httpd.conf /etc/apache2/httpd.conf

RUN set -x ; \
  addgroup -g 82 -S www-data ; \
  adduser -u 82 -D -S -G www-data www-data ; \
    chown -R www-data:www-data /var/www/localhost/htdocs
    
RUN apk add --no-cache libc6-compat

WORKDIR /var/www/localhost/htdocs/

CMD httpd && tail -f /var/log/apache2/*.log