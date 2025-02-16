FROM php:8.2-apache
USER root 
RUN docker-php-ext-install pdo_mysql
WORKDIR /var/www/
RUN mkdir Wikout
COPY . Wikout
COPY vhosts.conf /etc/apache2/sites-enabled
RUN chmod -R 777 /var/www/
RUN /etc/init.d/apache2 restart
WORKDIR /var/www/Wikout
EXPOSE 80