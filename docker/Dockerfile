# --- 1. Base Stage ---
FROM dunglas/frankenphp:latest-php8.3-alpine AS base

RUN apk add --no-cache \
    bash git nodejs npm linux-headers \
    gcompat libc6-compat libcap \
    --repository=https://dl-cdn.alpinelinux.org/alpine/v3.19/main \
    --repository=https://dl-cdn.alpinelinux.org/alpine/v3.19/community

RUN install-php-extensions pdo_pgsql pcntl bcmath gd intl zip opcache redis sockets

# Настройка бинарника
RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp && \
    chmod +x /usr/local/bin/frankenphp

WORKDIR /app

# --- 2. Dev Stage ---
FROM base AS dev

RUN install-php-extensions xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


USER root

RUN chown 1000:1000 /usr/local/bin /usr/local/bin/frankenphp

# Создаем папки и даем права одним махом
RUN mkdir -p /data/caddy /config/caddy && \
    chown -R 1000:1000 /app /data /config /usr/local/bin/frankenphp

USER 1000

ENV FRANKENPHP_BINARY=/usr/local/bin/frankenphp
# Важно: Octane иногда ищет бинарник в папке проекта, сделаем его видимым
ENV PATH="/app:${PATH}" 

ENTRYPOINT []
# Порт 8000 для стабильности под не-root
CMD php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000 --admin-port=2019 --watch

# --- 3. Prod Stage ---
FROM base AS prod

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Ставим зависимости
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist

COPY . .
RUN npm ci && npm run build

# Вместо 777 даем права пользователю 1000
RUN mkdir -p /data/caddy /config/caddy && \
    chown -R 1000:1000 /app /data /config /usr/local/bin/frankenphp

USER 1000
ENV FRANKENPHP_BINARY=/usr/local/bin/frankenphp

# Тоже порт 8000, балансировщик снаружи пробросит его на 80 или 443
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]