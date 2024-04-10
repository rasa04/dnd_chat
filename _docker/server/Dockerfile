FROM php:8.3-fpm

ENV WORK_DIR /var/www/server
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		zip \
		unzip \
		nano \
	&& rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install pdo_mysql

# Setting up volumes
COPY ../../server ${WORK_DIR}
COPY ../../server/.env.example ${WORK_DIR}/.env
RUN rm -dfr ${WORK_DIR}/vendor \
    && find ${WORK_DIR}/bootstrap/cache -type f -not -name ".gitignore" -exec rm -f {} + \
    && chmod -R 777 ${WORK_DIR}/storage \
    && chmod -R 777 ${WORK_DIR}/bootstrap/cache

# Installing Composer
WORKDIR ${WORK_DIR}
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --no-progress --no-interaction -o \
    && php artisan key:generate

EXPOSE 9000

# Running migratrions and seeders
RUN curl -LOk https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh \
    && chmod +x ./wait-for-it.sh

CMD [ "bash", "-c", "./wait-for-it.sh --host=mysql --port=3307 -- php artisan migrate --seed && php-fpm" ]
