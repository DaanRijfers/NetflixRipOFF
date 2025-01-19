#!/bin/bash

# Define the backup directory
BACKUP_DIR="./backup-images"
mkdir -p "$BACKUP_DIR"

# Get the current date and time for the backup file name
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

# List of containers to back up
CONTAINERS=("sql-generator" "laravel-api" "mariadb" "mariadb-replica" "vue-frontend")

# Loop through each container and back it up
for CONTAINER in "${CONTAINERS[@]}"; do
    # Create a backup image name with the container name and timestamp
    BACKUP_IMAGE_NAME="${CONTAINER}_backup_${TIMESTAMP}"
    
    # Commit the container to a new image
    echo "Backing up container: $CONTAINER"
    docker commit "$CONTAINER" "$BACKUP_IMAGE_NAME"
    
    # Save the image to a tar file
    BACKUP_FILE_NAME="${BACKUP_DIR}/${BACKUP_IMAGE_NAME}.tar"
    docker save -o "$BACKUP_FILE_NAME" "$BACKUP_IMAGE_NAME"
    
    # Clean up the backup image to save space
    docker rmi "$BACKUP_IMAGE_NAME"
    
    echo "Backup saved to: $BACKUP_FILE_NAME"
done

echo "All containers backed up successfully!"