# Utilisez une image PHP avec Apache
FROM php:8.2-apache

# Mettez à jour les paquets et installez les dépendances nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl bcmath

# Activez le module Apache rewrite (nécessaire pour Symfony)
RUN a2enmod rewrite

# Copiez les fichiers de votre application dans le conteneur
COPY . /var/www/html

# Définissez le répertoire de travail
WORKDIR /var/www/html

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez les dépendances de Symfony (en mode production)
RUN composer install --no-dev --optimize-autoloader

# Configurez Apache pour utiliser le dossier public/ comme racine
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Définissez les permissions pour les dossiers var/ et public/
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Exposez le port 80 (Apache écoute sur ce port par défaut)
EXPOSE 80

# Démarrez Apache
CMD ["apache2-foreground"]