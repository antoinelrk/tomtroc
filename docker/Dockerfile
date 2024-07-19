FROM alpine:3.19
LABEL maintainer="AntoineLRK <contact@antoinelrk.com>"
LABEL version="0.0.2"

WORKDIR /var/www

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf
COPY ./docker/nginx/vhost.conf /etc/nginx/sites-available/default.conf
COPY ./docker/php/php.ini /etc/php83/php.ini

RUN apk add --update --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/testing \
tzdata \
nginx \
nano \
nodejs \
python3 \
npm \
neovim \
supervisor \
php83-pecl-imagick \
php83 \
php83-fpm \
php83-pdo \
php83-iconv \
php83-pdo_mysql \
php83-sodium \
php83-mbstring \
php83-xml \
php83-openssl \
php83-phar \
php83-gd \
php83-zip \
php83-curl \
php83-dom \
php83-session \
php83-bcmath \
php83-pecl-redis \
php83-ctype \
php83-tokenizer \
php83-session \
php83-fileinfo \
php83-xmlwriter

RUN php83 -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php83 composer-setup.php --install-dir=/usr/bin --filename=composer && \
php83 -r "unlink('composer-setup.php');" && \
ln -s /etc/php83/php.ini /etc/php83/conf.d/php.ini

RUN mkdir -p /etc/nginx && \
mkdir -p /etc/nginx/sites-available && \
mkdir -p /etc/nginx/sites-enabled && \
mkdir -p /run/nginx && \
ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf && \
mkdir -p /var/log/supervisor

RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" \
-e "s/variables_order = \"GPCS\"/variables_order = \"EGPCS\"/g" \
/etc/php83/php.ini && \
sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" \
-e "s/;catch_workers_output\s*=\s*yes/catch_workers_output = yes/g" \
-e "s/user = nobody/user = nginx/g" \
-e "s/group = nobody/group = nginx/g" \
-e "s/;listen.mode = 0660/listen.mode = 0666/g" \
-e "s/;listen.owner = nobody/listen.owner = nginx/g" \
-e "s/;listen.group = nobody/listen.group = nginx/g" \
-e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g" \
-e "s/^;clear_env = no$/clear_env = no/" \
/etc/php83/php-fpm.d/www.conf

RUN ln -s /usr/bin/php83 /usr/bin/php

COPY ./composer.json ./composer.json

RUN chown -R nginx:nginx /var/www/

VOLUME [ "/var/log/nginx" ]

COPY . .

RUN composer install

ENV TZ Europe/Paris
EXPOSE 443 80
CMD [ "/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf" ]