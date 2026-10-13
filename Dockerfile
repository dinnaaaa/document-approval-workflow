FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Keep only the prefork MPM enabled

# Diagnose and configure Apache MPM
RUN set -eux; \
    a2dismod mpm_event || true; \
    a2dismod mpm_worker || true; \
    a2enmod mpm_prefork rewrite; \
    find /etc/apache2/mods-enabled -maxdepth 1 -type l -name 'mpm_*.load' -print; \
    grep -RniE 'LoadModule[[:space:]]+mpm_' /etc/apache2 || true

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]