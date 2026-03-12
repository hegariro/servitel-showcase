# --- ETAPA 1: Dependencias de PHP (Vendor) ---
    FROM php:8.4-fpm-alpine AS vendor

    WORKDIR /var/www
    
    # Instalar dependencias necesarias para Composer y extensiones
    RUN apk add --no-cache \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        oniguruma-dev \
        icu-dev \
        zlib-dev
    
    # Instalar extensiones de PHP básicas para el proceso de instalación
    RUN docker-php-ext-install zip pdo_mysql gd mbstring intl
    
    # Instalar Composer
    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    
    # Copiar archivos de dependencias
    COPY ./composer.json ./composer.lock ./
    
    # Instalar dependencias de PHP (sin scripts para evitar errores de entorno)
    RUN ls -la && composer install \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        --prefer-dist \
        --optimize-autoloader \
        --ignore-platform-reqs
    
    # --- ETAPA 2: Imagen Final de Producción ---
    FROM php:8.4-fpm-alpine
    
    WORKDIR /var/www
    
    # Instalar solo las librerías de tiempo de ejecución necesarias (runtime)
    RUN apk add --no-cache \
        libzip \
        libpng \
        oniguruma \
        libjpeg-turbo \
        freetype \
        icu-libs \
        && apk add --no-cache --virtual .build-deps \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip intl \
        && apk del .build-deps
    
    COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    
    # Copiar el código de la aplicación desde el host
    COPY . /var/www
    
    # Copiar los vendors instalados en la Etapa 1
    COPY --from=vendor /var/www/vendor /var/www/vendor
    
    # Ajustar permisos para el usuario de PHP-FPM (www-data)
    RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
    
    # Exponer el puerto
    EXPOSE 9000
    
    CMD ["php-fpm"]
