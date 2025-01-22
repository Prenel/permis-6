# Utiliser une image PHP 8.2 FPM basée sur Alpine
FROM php:8.2-fpm-alpine

# Installer les outils nécessaires pour gérer les utilisateurs et groupes
RUN apk add --no-cache shadow

# Créer devops
RUN groupadd -g 1001 devops && \
    useradd -u 1001 -g devops -m -s /bin/bash devops

# Ajouter www-data au groupe devops
RUN usermod -aG devops www-data

# Installer les dépendances nécessaires
RUN apk update && apk add --no-cache bash icu-dev git unzip vim mysql-client postgresql-dev \
    && docker-php-ext-install intl pdo pdo_pgsql pdo_mysql


# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

COPY ../vendor /var/www/html/vendor/

# Donner les permissions à www-data pour Symfony
RUN chown -R devops:devops /var/www/html

# Exposer le port par défaut de PHP-FPM
EXPOSE 4000

# Utiliser devops
USER devops

CMD ["tail", "-f", "/dev/null"]
