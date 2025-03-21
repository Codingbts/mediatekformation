# Utilisez une image PHP avec Apache
FROM php:8.2-apache

# Installez les dépendances nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath

# Activez le module Apache rewrite
RUN a2enmod rewrite

# Copiez les fichiers de votre application dans le conteneur
COPY . /var/www/html

# Définissez le répertoire de travail
WORKDIR /var/www/html

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez les dépendances de Symfony
RUN composer install --no-dev --optimize-autoloader

# Définissez les permissions pour le dossier var/
RUN chmod -R 777 var/

# Exposez le port 80 (Apache écoute sur ce port par défaut)
EXPOSE 80

# Démarrez Apache
CMD ["apache2-foreground"]