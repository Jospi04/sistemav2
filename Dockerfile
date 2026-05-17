FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache para soporte de .htaccess
RUN a2enmod rewrite

# Configurar Apache para permitir sobreescritura con .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80
