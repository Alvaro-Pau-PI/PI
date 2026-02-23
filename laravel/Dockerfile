# ═══════════════════════════════════════════════════════════════════════════════
# Dockerfile - Backend Laravel (PHP-FPM)
# Imatge per al servei PHP-FPM que executa l'aplicació Laravel.
# Inclou Composer, extensions PHP necessàries i configuració d'opcache.
# ═══════════════════════════════════════════════════════════════════════════════

FROM php:8.4-fpm

# Dependències del sistema
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

# Extensions PHP requerides per Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    bcmath \
    opcache \
    pcntl

# Instal·lar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar opcache per a desenvolupament
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/laravel

# Usuari no-root per a desenvolupament
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} laravel || true && \
    useradd -u ${UID} -g ${GID} -m laravel || true && \
    chown -R ${UID}:${GID} /var/www/laravel

EXPOSE 9000
CMD ["php-fpm"]
