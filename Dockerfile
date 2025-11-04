# 1. Imagem Base
# Usamos a imagem oficial do PHP 8.2 com FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# 2. Diretório de Trabalho
# Define o diretório padrão dentro do contêiner
WORKDIR /var/www/html

# 3. Instalar Extensões PHP
# Seu código precisa da extensão pdo_mysql para conectar ao banco
RUN docker-php-ext-install pdo_mysql

# 4. Instalar o Composer (gerenciador de pacotes do PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar os arquivos do Composer primeiro
COPY composer.json composer.lock ./

# 6. Instalar as dependências do Composer
# --no-dev: Não instala pacotes de desenvolvimento
# --optimize-autoloader: Otimiza o autoload para produção
RUN composer install --no-dev --optimize-autoloader

# 7. Copiar o restante do código da aplicação
COPY . .

# 8. Permissões
# O usuário 'www-data' é o usuário que o Nginx/PHP-FPM usam
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html
