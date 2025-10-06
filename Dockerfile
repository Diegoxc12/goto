# Imagen base oficial de PHP con Apache
FROM php:8.4.11-apache

# 1️⃣ Instalar dependencias necesarias del sistema
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    pkg-config \
    libcurl4-openssl-dev \
    libicu-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        xml \
        gd \
    && rm -rf /var/lib/apt/lists/*

# 2️⃣ (Opcional) Habilitar extensión imagick si está disponible
RUN echo "extension=imagick.so" > /usr/local/etc/php/conf.d/docker-php-ext-imagick.ini || true

# 3️⃣ Configurar PHP para subir archivos grandes y alto rendimiento
RUN printf "file_uploads=On\nmax_file_uploads=200\npost_max_size=1024M\nupload_max_filesize=512M\nmax_execution_time=600\nmemory_limit=1024M\n" > /usr/local/etc/php/conf.d/zz-overrides.ini

# 4️⃣ Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# 5️⃣ Instalar Composer globalmente (última versión estable)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6️⃣ Establecer directorio de trabajo
WORKDIR /var/www/html

# 7️⃣ Copiar primero composer.json y composer.lock para aprovechar caché
COPY ./Public_html/composer.json ./Public_html/composer.lock* ./

# 8️⃣ Instalar dependencias de PHP con Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader || true

# 9️⃣ Copiar el resto del proyecto
COPY ./Public_html /var/www/html

# 🔟 Ajustar permisos para Apache
RUN chown -R www-data:www-data /var/www/html

# 1️⃣1️⃣ Exponer puerto interno de Apache
EXPOSE 80

# 1️⃣2️⃣ Iniciar Apache en primer plano
CMD ["apache2-foreground"]
