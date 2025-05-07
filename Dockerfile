# -------------- Dockerfile --------------

    FROM php:8.2-apache

    RUN apt-get update && apt-get install -y \
        libpng-dev libonig-dev libxml2-dev zip unzip \
        libzip-dev libjpeg-dev libfreetype6-dev \
        && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd
    
    # Configure Apache
    RUN a2enmod rewrite \
        && sed -ri \
        -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
        -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' \
        /etc/apache2/sites-available/*.conf
    
    # Installe Composer
    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    
    # Copie les fichiers du projet
    COPY . /var/www/html
    WORKDIR /var/www/html
    
    RUN chown -R www-data:www-data /var/www/html \
        && chmod -R 755 storage bootstrap/cache
    
    # Installe les dépendances
    RUN composer install --no-dev --optimize-autoloader
    
    # Configuration Laravel
    RUN cp .env.example .env \
        && php artisan key:generate \
        && php artisan storage:link
    
    EXPOSE 80
    CMD ["apache2-foreground"]
    