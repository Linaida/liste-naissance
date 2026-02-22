FROM php:8.5-cli

# Dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        unzip \
        wget \
        zip \
        curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# PHP Extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    bcmath \
    gd xdebug \
    intl \
    pdo_pgsql \
    zip


RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini


# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Working directory
WORKDIR /app

CMD ["symfony", "server:start", "--no-tls", "--allow-http","--allow-all-ip", "--port=8000"]
