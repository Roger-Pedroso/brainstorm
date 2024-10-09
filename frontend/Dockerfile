# Use a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Ativar o módulo de reescrita do Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar o código-fonte do frontend para o contêiner
COPY . .

# Expor a porta 80
EXPOSE 80
