FROM docker.ops.colourlife.com:5000/nginx-php71
COPY . /var/www/html
COPY ./devops/config/default.conf /etc/nginx/nginx.conf
COPY ./devops/config/vhost.conf /etc/nginx/conf.d/vhost.conf
COPY ./devops/config/www.conf /etc/php/7.1/fpm/pool.d/
COPY ./devops/config/mime.types /etc/nginx/mime.types
RUN chown -R nginx.nginx /var/www/html/
RUN rm -rf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/html
RUN sed -i 's@include=/etc/php/7.1/fpm/pool.d/*.conf@include=/etc/php/7.1/fpm/pool.d/www.conf@g' /etc/php/7.1/fpm/php-fpm.conf
RUN ./devops/scripts/start.sh

