FROM php:8.2-fpm

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar extensiones de PHP (¡Asegúrate de que 'zip' está aquí!)
RUN docker-php-ext-install pdo pdo_mysql zip gd

WORKDIR /var/www/html

# 3. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer