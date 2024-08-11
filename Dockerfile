FROM dunglas/frankenphp

ENV SERVER_NAME=:80

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

# Copy the app
COPY . /app

WORKDIR /app

# Remove tests folder
RUN rm -Rf /app/tests/

# Install the dependencies
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN /usr/bin/composer install --ignore-platform-reqs --no-dev -a

RUN php artisan optimize
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan config:clear
