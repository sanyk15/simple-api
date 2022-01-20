FROM yiisoftware/yii2-php:7.4-fpm

RUN apt-get update && apt-get install --no-install-recommends -y locales build-essential libboost-dev

RUN apt-get update && apt-get install -y \
        bash-completion \
    --no-install-recommends && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli && docker-php-ext-enable mysqli