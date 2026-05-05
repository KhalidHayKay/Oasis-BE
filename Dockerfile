FROM php:8.4-fpm-alpine

ARG APP_USER
ARG USER_ID
ARG GROUP_ID

# Update & install system dependencies
RUN apk update && apk add --no-cache \
    git \
    curl \
    wget \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-client \
    postgresql-dev \
    ca-certificates

# Install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    mbstring \
    xml \
    gd \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create application directory
WORKDIR /var/www

# Copy composer install + layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .
RUN composer run-script post-autoload-dump

# Permissions
RUN addgroup -g $GROUP_ID $APP_USER \
    && adduser -D -u $USER_ID -G $APP_USER $APP_USER \
    && chown -R $APP_USER:$APP_USER /var/www \
    && chmod -R 775 storage bootstrap/cache

USER $APP_USER

CMD ["php-fpm"]
