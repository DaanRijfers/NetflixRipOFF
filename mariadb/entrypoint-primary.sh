#!/bin/bash

set -ex  # Exit on any error

chmod 644 /etc/mysql/conf.d/primary.cnf

FILE_PATH="/docker-entrypoint-initdb.d/create-users.sql"

echo "Waiting for the file to be generated: $FILE_PATH"

while [[ ! -f "$FILE_PATH" ]]; do
    echo "File not found..."
    sleep 1
done

sleep 1

echo "File found. Starting MariaDB container..."
exec docker-entrypoint.sh mysqld
