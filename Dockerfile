# syntax=docker/dockerfile:1.7

# --- Stage 1: install PHP dependencies with Composer ---
FROM php:8.3-alpine AS vendor

RUN apk add --no-cache git libzip-dev libxml2-dev icu-dev oniguruma-dev \
    && docker-php-ext-install bcmath intl zip dom mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --no-autoloader \
        --prefer-dist


# --- Stage 2: build front-end assets with Node 24 ---
FROM node:24-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources ./resources
COPY public ./public
COPY vite.config.* tailwind.config.* postcss.config.* ./
# Filament publishes a CSS theme that Tailwind needs to scan during the build
COPY --from=vendor /app/vendor/filament ./vendor/filament

RUN npm run build


# --- Stage 3: assemble app + autoloader ---
FROM vendor AS app

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-scripts


# --- Stage 4: runtime image ---
FROM php:8.3-fpm-alpine AS runtime

RUN apk add --no-cache bash git icu-dev libzip-dev libxml2-dev oniguruma-dev curl-dev mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip dom xml curl bcmath opcache intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --from=app /app /var/www/html

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache \
    && printf '[client]\nssl=FALSE\n' > /etc/my.cnf

EXPOSE 9000

CMD ["php-fpm"]
