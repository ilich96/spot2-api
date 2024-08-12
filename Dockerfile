FROM dunglas/frankenphp

ENV SERVER_NAME=:80

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git \
RUN docker-php-ext-install pdo_pgsql

# Copy the app
COPY . /app

WORKDIR /app

# Copy .env file
RUN cp .env.example .env

# Remove tests folder
RUN rm -Rf /app/tests/

# Install the dependencies
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN /usr/bin/composer install --ignore-platform-reqs --no-dev -a

RUN php artisan optimize
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan config:clear
