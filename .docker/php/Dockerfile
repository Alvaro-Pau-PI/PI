FROM php:8.2-fpm

# Instalar extensiones necesarias para Laravel/DB
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Opcional: instalar Composer (si vas a usar Laravel en siguientes iteraciones)
RUN apt-get update && apt-get install -y git unzip \
 && curl -sS https://getcomposer.org/installer -o composer-setup.php \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && rm composer-setup.php \
 && apt-get clean && rm -rf /var/lib/apt/lists/*
