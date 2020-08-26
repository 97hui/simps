FROM phpswoole/swoole:4.4.18-php7.4
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/ \
    && rm -rf /var/www/* \
    && rm -rf /etc/supervisor/service.d/*.conf
COPY ./inotify-tools-3.14.tar.gz /usr/local/src/inotify-tools-3.14.tar.gz
COPY ./redis-5.3.1.tgz /usr/local/src/redis-5.3.1.tgz
WORKDIR /usr/local/src
RUN tar -zxvf inotify-tools-3.14.tar.gz && \
    cd inotify-tools-3.14 && \
    ./configure && \
    make && \
    make install && \
    ln -sv /usr/local/lib/libinotify* /usr/lib/ && \
    cd /usr/local/src && \
    tar -zxvf redis-5.3.1.tgz && \
    cd redis-5.3.1 && \
    /usr/local/bin/phpize && \
    ./configure --with-php-config=/usr/local/bin/php-config && \
    make && make install && \
    echo 'extension=redis.so' > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini && \
    rm -rf /usr/local/src/inotify-tools-3.14.tar.gz && \
    rm -rf /usr/local/src/redis-5.3.1.tgz
COPY ./supervisor/ /etc/supervisor/service.d/
COPY ./ /var/www/
WORKDIR /var/www
RUN composer install
ENTRYPOINT [ "/entrypoint.sh" ]
