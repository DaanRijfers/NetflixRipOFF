#!/bin/sh
set -e  # Exit on any error

# Ensure the output directory exists
mkdir -p /db-init/sql-file

# Debugging: List files in /db-init
echo "Listing files in /db-init:"
ls -al /db-init

# Skip loading .env as variables are already set (from Docker environment)
echo "Using environment variables directly..."

# Generate and verify the SQL content
echo "Generating create-users.sql from template..."
substituted_content=$(envsubst < /db-init/create-users.template)

# Print the substituted content to verify
echo "$substituted_content"

rm -rf /db-init/sql-file/create-users.sql

# Write to the file
echo "$substituted_content" > /db-init/sql-file/create-users.sql

# Verify the generated file
echo "Generated SQL file:"
cat /db-init/sql-file/create-users.sql
