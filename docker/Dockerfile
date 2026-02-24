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

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- 2. Dev Stage ---
FROM base AS dev

RUN install-php-extensions xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


USER root

RUN chown 1000:1000 /usr/local/bin /usr/local/bin/frankenphp

# Создаем папки и даем права одним махом
RUN mkdir -p /data/caddy /config/caddy && \
    chown -R 1000:1000 /app /data /config /usr/local/bin/frankenphp

USER 1000

ENV FRANKENPHP_BINARY=/usr/local/bin/frankenphp
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

# --- КРИТИЧЕСКИЙ БЛОК ПРАВ ---
USER root

RUN chown 1000:1000 /usr/local/bin /usr/local/bin/frankenphp

# 1. Создаем симлинк в корне /app. 
RUN ln -sf /usr/local/bin/frankenphp /app/frankenphp

# 2. Создаем папки Caddy (они нужны для работы сервера)
RUN mkdir -p /data/caddy /config/caddy

# 3. Отдаем ПРАВА пользователю 1000 на всё необходимое
RUN chown -R 1000:1000 /app /data /config /usr/local/bin/frankenphp


USER 1000
ENV FRANKENPHP_BINARY=/usr/local/bin/frankenphp

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]