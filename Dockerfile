FROM php:7.3-fpm

RUN apt-get update
RUN apt-get install autoconf g++ make && pecl install -f xdebug-beta && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install sockets

ENV HOME /root
ENV COMPOSER_HOME $HOME/.composer
ENV PATH $COMPOSER_HOME/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sSL -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -sSL -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot && rm -rf /tmp/composer-setup.php

ENV PATH=$PATH:$COMPOSER_HOME/vendor/bin
COPY ./dephpugger.phar /usr/local/bin/dephpugger
RUN chmod +x /usr/local/bin/dephpugger

EXPOSE 8888

CMD ["dephpugger", "server"]
