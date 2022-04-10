FROM 68publishers/docker-images:php-nginx-unit-7.4 AS app

RUN set -ex && apk add --no-cache --update php7-xmlreader=~7.4 php7-pecl-amqp=~1.11

########################################################################################################################
FROM postgis/postgis:13-3.0 AS db

########################################################################################################################
FROM rabbitmq:3.9.8-alpine AS rabbitmq

RUN rabbitmq-plugins enable rabbitmq_management
