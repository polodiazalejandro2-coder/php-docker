FROM php:8.5-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libmariadb-dev libonig-dev \
    && docker-php-ext-install mbstring pdo_pgsql pdo_mysql \
    && a2dissite 000-default \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache/jukebox.conf /etc/apache2/sites-available/jukebox.conf

RUN a2ensite jukebox

WORKDIR /var/www/html

EXPOSE 80
