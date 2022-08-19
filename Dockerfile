FROM php:8.1-fpm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Symfony CLI
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update
RUN apt install -y symfony-cli

# Install zip extension
RUN apt install -y libzip-dev zip
RUN docker-php-ext-install zip

# Install postgres driver
RUN apt install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app
COPY . .

RUN composer i
RUN php bin/console cache:clear