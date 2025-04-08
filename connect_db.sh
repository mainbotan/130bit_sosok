#!/bin/bash
set -a  # Автоматически экспортировать все переменные
source .env
set +a

docker-compose exec mysql mysql -h mysql \
  -u "$MYSQL_USER" \
  -p"$MYSQL_PASSWORD" \
  "$MYSQL_DATABASE"
