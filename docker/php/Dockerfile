FROM php:8.1-fpm

ENV PATH="${PATH}:/app/vendor/bin"

RUN apt-get update \
  && apt-get -y install \
    dumb-init \
    gettext \
    libssl-dev \
    unzip \
    wget \
    zip \
  && rm -rf /var/lib/apt/lists/*

# Xdebug
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

 # Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- \
  --filename=composer --install-dir=/usr/local/bin

# Create a directory for project sources and user's home directory
RUN mkdir /app && \
  chown -R www-data:www-data /app && \
  chown -R www-data:www-data /var/www

# Copy XDebug config file
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy entrypoint script
COPY docker/php/entrypoint /usr/local/bin

ENTRYPOINT ["entrypoint"]

CMD ["php-fpm"]

WORKDIR /app

USER www-data
