FROM php:8.2-apache

# 1) Installe les extensions système
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip \
    libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# 2) Active mod_rewrite
RUN a2enmod rewrite

# 3) Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4) Copie le projet et donne les droits
COPY . /var/www/html
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# 5) Installe les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 6) Prépare l’environnement
RUN cp .env.example .env \
    && php artisan key:generate

# On n’exécute PLUS php artisan migrate ici

EXPOSE 80
CMD ["apache2-foreground"]
