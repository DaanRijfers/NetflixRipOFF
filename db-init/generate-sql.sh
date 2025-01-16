#!/bin/sh
set -ex  # Exit on any error

rm -rfv /db-init/sql-files

# Ensure the output directory exists
mkdir -p /db-init/sql-files/primary
mkdir -p /db-init/sql-files/replica

# Skip loading .env as variables are already set (from Docker environment)
echo "Using environment variables directly..."

# Generate and verify the SQL content
echo "Generating create-users.sql from templates..."
cat /db-init/create-users-primary.template | envsubst  > /db-init/sql-files/primary/create-users.sql

echo "Waiting for database to be ready at $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
    echo "Database is unavailable - retrying..."
    sleep 1
done
echo "Primary is ready!"

LOG_STATUS=$(mariadb --ssl=0 -h $DB_HOST -u $REPLICATION_USER -p$REPLICATION_PASSWORD -e "SHOW MASTER STATUS;" -B -N)

# Extract the log file and position
export LOG_FILE=$(echo "$LOG_STATUS" | awk '{print $1}')
export POSITION=$(echo "$LOG_STATUS" | awk '{print $2}')

echo "Log file: $LOG_FILE, Position: $POSITION"

cat /db-init/create-users-replica.template | envsubst > /db-init/sql-files/replica/create-users.sql

# Setting correct file permissions
chmod -R 755 /db-init/sql-files

# Verify the generated file
echo "Generated SQL file:"
tree /db-init/sql-files
cat /db-init/sql-files/primary/create-users.sql
cat /db-init/sql-files/replica/create-users.sql
