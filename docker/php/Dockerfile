FROM mileschou/phalcon:7.2-fpm

ENV TZ=Asia/Shanghai

COPY ./docker/sources.list /opt

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone \
    && cat /opt/sources.list > /etc/apt/sources.list \
    && apt-get update \
    && apt-get install -y git wget \
    && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip \
    && pecl install redis-4.1.1 \
    && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer config -g repo.packagist composer https://packagist.phpcomposer.com

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

VOLUME ["/var/log/php-fpm", "/var/www/html"]

EXPOSE 9000