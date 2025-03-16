FROM php:8.3-fpm

# Instalar dependências e o driver do MySQL/MariaDB
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmariadb-dev-compat \
    libmariadb-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure mysqli --with-mysqli \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copiar o Composer de outra imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões (se necessário)
RUN chown -R www-data:www-data storage bootstrap/cache

# Expor a porta 8000
EXPOSE 8000

# RUN php artisan config:clear && php artisan cache:clear

# Comando para iniciar o servidor embutido do Laravel
# CMD php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT:-8000}"]
