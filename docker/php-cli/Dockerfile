FROM php:8.1-cli

ENV PHP_IDE_CONFIG='serverName=php-cli'

RUN apt-get update && apt-get install -y git
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions xdebug-^3.2 @composer

# 1000 - идентификатор дефолтного пользователя на linux системах.
# Внутри контейнера создается пользователь с тем же идентификатором что и у внешнего пользователя,
# что бы не возникло проблем с правами при пробрасывании файлов через том
RUN useradd -u 1000 user
USER user

WORKDIR /app
