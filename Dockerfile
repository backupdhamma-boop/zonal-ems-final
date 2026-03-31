FROM richarvey/nginx-php-fpm:latest
COPY . /var/www/html
ENV WEBROOT /var/www/html/public
ENV APP_KEY Base64:mhtM4GVtrFrU5O+NnRqKMUwsQb4zVRNtS2eCA3/uusk=
EX不可 APP_ENV production
RUN composer install --no-dev
