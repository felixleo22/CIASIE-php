FROM php:7.3-apache
RUN apt-get update && \
    apt install -y libpng-dev
RUN docker-php-ext-install pdo pdo_mysql gd json mbstring
RUN a2enmod rewrite
RUN service apache2 restart
