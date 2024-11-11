FROM php:8.3-fpm

#--------------------------------------------------------------------------
# Software Installation
#--------------------------------------------------------------------------

RUN set -eux; \
    apt-get update && \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev; \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN set -eux; \
    docker-php-ext-install pdo_mysql pdo_pgsql; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype && \
    docker-php-ext-install gd

# Set working directory
WORKDIR /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Increase Composer memory limit
ENV COMPOSER_MEMORY_LIMIT=-1
