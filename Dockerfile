FROM php:8.4-apache

WORKDIR /var/www/html

# ------------------------------------------------------------------
# Install system packages and PHP extensions
# ------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    default-mysql-client \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ------------------------------------------------------------------
# Composer
# ------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------------------------------------------
# Node.js
# ------------------------------------------------------------------
COPY --from=node:22 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:22 /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# ------------------------------------------------------------------
# Copy application
# ------------------------------------------------------------------
COPY . .

# ------------------------------------------------------------------
# Clean old frontend build
# ------------------------------------------------------------------
RUN rm -f public/hot \
    && rm -rf public/build

# ------------------------------------------------------------------
# Install PHP dependencies
# ------------------------------------------------------------------
RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# ------------------------------------------------------------------
# Build frontend assets
# ------------------------------------------------------------------
RUN npm ci
RUN npm run build

# ------------------------------------------------------------------
# Laravel directories
# ------------------------------------------------------------------
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# ------------------------------------------------------------------
# Laravel optimization
# ------------------------------------------------------------------
RUN php artisan storage:link || true
RUN php artisan optimize:clear || true

# Only run if Spatie Permission is installed
RUN if php artisan list | grep -q "permission:cache-reset"; then \
        php artisan permission:cache-reset || true; \
    fi

# ------------------------------------------------------------------
# Permissions
# ------------------------------------------------------------------
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    public/storage \
    public/build

RUN find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && find bootstrap/cache -type d -exec chmod 775 {} \; \
 && find bootstrap/cache -type f -exec chmod 664 {} \;

# ------------------------------------------------------------------
# Apache
# ------------------------------------------------------------------
RUN a2enmod rewrite headers

RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf

RUN echo '<VirtualHost *:8080>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    ServerName localhost' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        RewriteEngine On' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        RewriteCond %{REQUEST_FILENAME} !-f' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        RewriteCond %{REQUEST_FILENAME} !-d' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        RewriteRule ^ index.php [L]' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

CMD ["apache2-foreground"]
