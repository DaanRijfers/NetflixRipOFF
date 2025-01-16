#!/bin/bash

set -ex  # Exit on any error

FILE_PATH="/docker-entrypoint-initdb.d/create-users.sql"

apt update -y 
apt install cron -y

mkdir -p /backups

echo "Waiting for the file to be generated: $FILE_PATH"

while [[ ! -f "$FILE_PATH" ]]; do
    echo "File not found..."
    sleep 1
done

sleep 1

echo "File found. Starting MariaDB container..."
echo "Creating backup script..."
echo -e "#!/bin/bash 

DATE=\$(date +%y-%m-%d--%T) 
echo \"Starting backup of \$DATE...\"
mariadb-dump --skip-comments --quick --single-transaction --compact --all-databases -u $MYSQL_USER -p$MYSQL_PASSWORD | gzip -9 > /backups/backup_\$DATE.sql.gz
echo \"Finished backup\"
" > backups/backup.sh

chmod +x /backups/backup.sh

# Creating a cronjob that fires the backup script every day at midnight
echo  "0 0 * * * /bin/bash /backups/backup.sh >> /backups/backup.log 2>&1" | crontab -
service cron start

exec docker-entrypoint.sh mysqld
