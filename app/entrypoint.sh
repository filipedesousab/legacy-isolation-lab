#!/bin/sh
set -e
socat TCP-LISTEN:5432,bind=127.0.0.1,fork,reuseaddr TCP:${DB_HOST}:${DB_PORT} &
exec apache2-foreground
