# -------------- Dockerfile --------------

FROM php:8.2-apache

# Installe les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip \
    libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Active mod_rewrite et définit DocumentRoot sur public/
RUN a2enmod rewrite \
    && sed -ri \
    -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
    -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' \
    /etc/apache2/sites-available/*.conf

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie le projet et installe les dépendances
COPY . /var/www/html
WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

# Prépare l’environnement
RUN cp .env.example .env \
    && php artisan key:generate

EXPOSE 80
CMD ["apache2-foreground"]
