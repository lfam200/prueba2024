# Usar la imagen de PHP con Apache
FROM php:8.1-apache

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cambiar el documento raíz de Apache al directorio public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Actualizar la configuración de Apache para usar el nuevo documento raíz
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente
COPY . /var/www/html

# Exponer el puerto de Apache
EXPOSE 80
