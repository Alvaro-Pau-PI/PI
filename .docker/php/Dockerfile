FROM php:8.2-fpm

# 1. Instalar dependencias del sistema necesarias para las extensiones de PHP
# Usamos 'apt-get' ya que php:8.2-fpm es una imagen basada en Debian.
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libpng-dev \
    git \
    unzip \
    # Limpieza: para mantener la imagen pequeña
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Instalar las extensiones de PHP
# Estas son las requeridas para Laravel/DB y PhpSpreadsheet
RUN docker-php-ext-install pdo pdo_mysql zip gd sodium

# 2. Instalar las extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql zip gd sodium

# 3. Habilitar GD (para asegurar que se carga en el runtime)
RUN docker-php-ext-enable gd

WORKDIR /var/www/html

# 3. Instalar Composer Globalmente
# Esta parte ya usaba apt-get y es correcta (la he ajustado ligeramente)
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php