FROM php:8.3-fpm

ENV WORK_DIR /var/www/server
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		zip \
		unzip \
	&& rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install pdo_mysql

# Installing Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ../../server ${WORK_DIR}
COPY ../../server/.env.example ${WORK_DIR}/.env

WORKDIR ${WORK_DIR}

RUN composer install --no-dev --no-scripts --no-progress --no-interaction -o
RUN chmod -R 777 ${WORK_DIR}/storage
RUN chmod -R 777 ${WORK_DIR}/bootstrap/cache
RUN php artisan key:generate

EXPOSE 9000
# Migratrion
RUN curl -LOk https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
RUN chmod +x ./wait-for-it.sh

CMD [ "bash", "-c", "./wait-for-it.sh --host=mysql --port=3307 -- php artisan migrate --seed && php-fpm" ]
