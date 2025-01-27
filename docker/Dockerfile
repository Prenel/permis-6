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
RUN apk update && apk add --no-cache bash curl icu-dev git unzip vim mysql-client postgresql-dev \
    && docker-php-ext-install intl pdo pdo_pgsql pdo_mysql

# Installer Node et Yarn
RUN apk add --no-cache nodejs npm

# Installer Yarn 
RUN npm install -g yarn && apk del npm

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN chmod +x /usr/local/bin/symfony

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers nécessaires pour gérer les dépendances front-end
COPY ../package.json ../yarn.lock /var/www/html/

# Ajouter toutes les dépendances front-end avec yarn add (dont sass-loader@^14.0.0)
RUN yarn add vue@3 vuex@4 vue-loader@next @vue/compiler-sfc axios \
    vue-router@4 @vue/devtools sass sass-loader@^14.0.0 vuex-persist vuetify@next @mdi/font

# Installer les dépendances front-end (pour synchroniser yarn.lock)
RUN yarn install

# Copier le répertoire vendor généré localement après composer install
COPY ../vendor /var/www/html/vendor

# Donner les permissions à www-data pour Symfony
RUN chown -R devops:devops /var/www/html

# Exposer le port par défaut de PHP-FPM
EXPOSE 4000

# Utiliser devops
USER devops

CMD ["tail", "-f", "/dev/null"]
