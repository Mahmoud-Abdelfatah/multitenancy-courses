FROM php:8.2-fpm


WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    git \
    unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql intl


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . .


RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8000


CMD ["php", "artisan", "serve", "--host=0.0.0.0"]

