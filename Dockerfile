FROM php:7-zts-alpine

RUN apk --update --no-cache add autoconf g++ make && \
pecl install -f xdebug-beta && \
docker-php-ext-enable xdebug && \
apk del --purge autoconf g++ make

ENV HOME /root
ENV COMPOSER_HOME $HOME/.composer
ENV PATH $COMPOSER_HOME/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sSL -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -sSL -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot && rm -rf /tmp/composer-setup.php

RUN composer global require tacnoman/dephpugger
ENV PATH=$PATH:$COMPOSER_HOME/vendor/bin

RUN composer global dump-autoload

RUN curl -sSL -o $HOME/.composer/vendor/tacnoman/dephpugger/bin/dephpugger https://raw.githubusercontent.com/tacnoman/dephpugger/94246836f253a5a0798dac58b009d883abf3d72c/bin/dephpugger

EXPOSE 8888

CMD ["dephpugger", "server"]
