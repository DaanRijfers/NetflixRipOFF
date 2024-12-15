#!/bin/bash

# Step 1: Generate the SQL file
if [ -f /docker-entrypoint-initdb.d/create-users.template ]; then
  echo "Generating create-users.sql from template using environment variables..."
  envsubst < /docker-entrypoint-initdb.d/create-users.template > /docker-entrypoint-initdb.d/create-users.sql
else
  echo "Template file create-users.template not found! Skipping SQL generation."
fi

# Debug: Output the generated SQL file
echo "Generated create-users.sql:"
cat /docker-entrypoint-initdb.d/create-users.sql

# Step 2: Delegate to MariaDB's default entrypoint
exec /usr/local/bin/docker-entrypoint.sh "$@"
