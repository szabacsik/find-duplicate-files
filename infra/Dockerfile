FROM php:7.4.11-apache-buster
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN sed -i -e 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www