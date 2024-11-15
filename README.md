# NetflixRipOFF Docker Setup

This repository contains the setup for running MariaDB and phpMyAdmin in Docker containers for the NetflixRipOFF project.

## Prerequisites

- Docker
- Docker Compose

## Getting Started

1. Clone this repository:

   ```bash
   git clone https://github.com/DaanRijfers/NetflixRipOFF.git
   cd NetflixRipOFF
   ```

2. Ensure that Docker and Docker Compose are installed. You can install them following the official guides:

   - [Install Docker](https://docs.docker.com/get-docker/)
   - [Install Docker Compose](https://docs.docker.com/compose/install/)

3. Once you have Docker and Docker Compose set up, run the following command to start the containers:

   ```bash
   docker-compose up -d
   ```

   This will start the MariaDB and phpMyAdmin containers in the background.

4. Access phpMyAdmin via your browser at `http://localhost:8080`.

## Stopping the Containers

If you want to stop the containers, run:

```bash
docker-compose down
