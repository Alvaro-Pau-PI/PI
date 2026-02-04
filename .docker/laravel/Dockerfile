FROM php:8.4-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Extensiones PHP requeridas por Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    bcmath \
    opcache \
    pcntl

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar opcache para desarrollo
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/laravel

# Usuario no-root para desarrollo (matching host user)
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} laravel || true && \
    useradd -u ${UID} -g ${GID} -m laravel || true && \
    chown -R ${UID}:${GID} /var/www/laravel

EXPOSE 9000
CMD ["php-fpm"]
