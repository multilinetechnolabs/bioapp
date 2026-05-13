FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    curl \
    exif \
    gd \
    intl \
    mbstring \
    mysqli \
    pcntl \
    pdo_mysql \
    xml \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php74/entrypoint.sh /usr/local/bin/project-entrypoint

RUN chmod +x /usr/local/bin/project-entrypoint

WORKDIR /var/www/html
