# NetflixRipOFF Docker Setup

This repository contains the setup for running MariaDB and phpMyAdmin in Docker containers for the NetflixRipOFF project.

## Prerequisites

- Docker
- Docker Compose

## Getting Started

1. Clone this repository:

   git clone https://github.com/DaanRijfers/NetflixRipOFF.git
   cd NetflixRipOFF

2. Ensure that Docker and Docker Compose are installed. You can install them following the official guides:

   - Install Docker: https://docs.docker.com/get-docker/
   - Install Docker Compose: https://docs.docker.com/compose/install/

3. Copy and rename the .envExample file to .env:

   cp .envExample .env

   Important: Ensure that the values inside the .env file are unique, especially the usernames. Users should not have the same name.

4. Once you have Docker and Docker Compose set up, run the following command to start the containers:

   docker-compose up -d

   This will start the MariaDB and phpMyAdmin containers in the background.

## Stopping the Containers

If you want to stop the containers, run:

docker-compose down

## Backing Up a Docker Container

To back up a Docker container, you can follow these steps:

1. Commit the Container to an Image:

   First, commit the container to a new Docker image. Replace <container_name> with the name or ID of your container and <backup_image_name> with a name for your backup image.

   docker commit <container_name> <backup_image_name>

2. Save the Image to a Tar File:

   Next, save the Docker image to a tar file for easy storage and transfer. Replace <backup_image_name> with the name of your backup image and <backup_file_name> with the desired name for your backup file.

   docker save -o <backup_file_name>.tar <backup_image_name>

3. Restore the Backup:

   To restore the backup, load the image from the tar file:

   docker load -i <backup_file_name>.tar

   Then, you can create a new container from the restored image.

## Additional Notes

- Ensure that your .env file is correctly configured before starting the containers.
- Regularly back up your data to prevent loss in case of container failure or accidental deletion.

For more information on Docker and Docker Compose, refer to the official documentation:

- Docker Documentation: https://docs.docker.com/
- Docker Compose Documentation: https://docs.docker.com/compose/