FROM php:5.6-apache

# Ajuste dos repositorios para o Debian sem suporte
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list

RUN apt-get update -o Acquire::Check-Valid-Until=false \
    && apt-get install -y --allow-unauthenticated --no-install-recommends \
        socat \
        wget \
        libpq-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql gd zip mbstring \
    && a2enmod rewrite remoteip headers \
    && wget -O /var/www/html/adminer.php https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php \
    && rm -rf /var/lib/apt/lists/*

# Alteracao do Apache para escutar na porta 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Hardening do PHP 5.6 (Bloqueio de funcoes perigosas)
RUN { \
        echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE'; \
        echo 'display_errors = Off'; \
        echo 'log_errors = On'; \
        echo 'expose_php = Off'; \
        echo 'disable_functions = exec,passthru,shell_exec,system,proc_open,popen'; \
        echo 'session.save_path = "/tmp"'; \
    } > /usr/local/etc/php/conf.d/hardening.ini

# Configuracao para confiar no proxy reverso da DMZ e repassar o HTTPS correto para a aplicacao
RUN { \
        echo 'SetEnvIf X-Forwarded-Proto "https" HTTPS=on'; \
        echo 'RemoteIPHeader X-Real-IP'; \
        echo 'RemoteIPInternalProxy 10.0.0.0/8'; \
    } > /etc/apache2/conf-enabled/reverse-proxy.conf \
    && sed -i '/^LogFormat/s/%h/%a/' /etc/apache2/apache2.conf

COPY src/ /var/www/html/

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html /var/run/apache2 /var/log/apache2

USER www-data
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
