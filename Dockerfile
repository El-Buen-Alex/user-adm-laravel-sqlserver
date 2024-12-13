# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git \
    unixodbc-dev curl gnupg2 lsb-release apt-transport-https ca-certificates \
    gcc g++ make && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

ENV ACCEPT_EULA=Y

# Microsoft SQL Server Prerequisites
RUN apt-get update \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get install -y --no-install-recommends \
        locales \
        apt-transport-https \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen \
    && apt-get update \
    && apt-get -y --no-install-recommends install \
        unixodbc-dev \
        msodbcsql17
     
RUN apt-get update && apt-get install -y \
    msodbcsql18 \
    mssql-tools \
    unixodbc-dev && \
    pecl install pdo_sqlsrv sqlsrv && \
    docker-php-ext-enable pdo_sqlsrv sqlsrv

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
#CMD ["apache2-foreground"]
    
RUN curl -o wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
RUN chmod +x wait-for-it.sh
RUN mv wait-for-it.sh /tmp/wait-for-it.sh
# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY . .

# Instalar las dependencias de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las dependencias de Laravel
RUN composer install

#ENTRYPOINT ["/bin/bash", "-c", "/tmp/wait-for-it.sh sqlsrv:1433 --timeout=0 -- /bin/bash /docker-entrypoint-initdb.d/init-db.sh"]
# ejecutar wait-for-it.sh sqlsrv:1433 --timeout=0 -- /bin/bash /docker-entrypoint-initdb.d/init-db.sh
# copy /init-db.sh into /docker-entrypoint-initdb.d/init-db.sh
COPY init-db.sh /docker-entrypoint-initdb.d/init-db.sh
#change CRLF to LF
RUN apt-get update && apt-get install -y dos2unix && \
    dos2unix /docker-entrypoint-initdb.d/init-db.sh
#verify the file is copied
RUN ls -la /docker-entrypoint-initdb.d/init-db.sh
RUN chmod +x /tmp/wait-for-it.sh /docker-entrypoint-initdb.d/init-db.sh

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache    
EXPOSE 80

CMD ["/bin/bash", "-c", "/tmp/wait-for-it.sh sqlsrv:1433 --timeout=0 -- /bin/bash /docker-entrypoint-initdb.d/init-db.sh"]

# ENTRYPOINT ["/bin/bash", "-c", "/tmp/wait-for-it.sh sqlsrv:1433 --timeout=0 -- /bin/bash /docker-entrypoint-initdb.d/init-db.sh"]

# Exponer el puerto




