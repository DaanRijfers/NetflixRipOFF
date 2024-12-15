FROM mariadb:latest

# Install gettext for envsubst
RUN apt-get update && apt-get install -y gettext-base && rm -rf /var/lib/apt/lists/*

# Copy SQL template
COPY create-users.template /docker-entrypoint-initdb.d/
