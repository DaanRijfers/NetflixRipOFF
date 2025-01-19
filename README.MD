# NetflixRipOFF Docker Setup

Welcome to the NetflixRipOFF Docker setup guide. This repository contains everything you need to run MariaDB and phpMyAdmin in Docker containers for your project.

## Prerequisites

To get started, ensure you have the following installed on your system:

- **Docker**: Install Docker by following the official guide [here](https://docs.docker.com/get-docker/).
- **Docker Compose**: Install Docker Compose from [here](https://docs.docker.com/compose/install/).

## Getting Started

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/DaanRijfers/NetflixRipOFF.git
   cd NetflixRipOFF
   ```

2. **Set Up Environment Variables**:

   Duplicate and rename `.envExample` to `.env`. Ensure that all values in `.env` are unique, especially usernames.(The replica and default user cannot an should not be the same name)

   ```bash
   cp .envExample .env
   ```

3. **Start Docker Containers**:

   Start the containers using Docker Compose:

   ```bash
   docker-compose up
   ```

   Headless:   
   ```bash
   docker-compose up -d
   ```

   This command runs MariaDB, Vue, and Laravel containers in the background.

## Stopping the Containers

To stop the containers, run:

```bash
docker-compose down
```

To delete all volumes and data along with stopping the containers:

```bash
docker-compose down -v
```

## Backing Up a Docker Container

To back up a Docker container, follow these steps:

1. **Run Backup Script (Optional)**:

   Navigate to the `backup` folder and run:

   ```bash
   cd backup
   ./backup.sh
   ```

   This script backs up all containers with timestamps for easy management.

2. **Manual Backup**:

   - **Commit the Container to an Image**:

     ```bash
     docker commit <container_name> <backup_image_name>
     ```

   - **Save the Image to a Tar File**:

     ```bash
     docker save -o <backup_file_name>.tar <backup_image_name>
     ```

3. **Restore Backup**:

   Load the image from the tar file:

   ```bash
   docker load -i <backup_file_name>.tar
   ```

## Testing

### API Testing (Curl)

1. **Navigate to the Tests Folder**:

   ```bash
   cd Tests
   ```

2. **Run the API Tests**:

   Make sure `test_api.sh` is executable:

   ```bash
   ./test_api.sh
   ```
   If not executable:

   ```bash
   chmod +x test_api.sh
   ```

   This script automates sending requests to the API and saves responses in `api_responses.txt`.

### API Testing (Postman)

1. **Open Postman**:

   Download and open Postman from [here](https://www.postman.com/downloads/).

2. **Create a Workspace**:

   Before importing the test collection, create a workspace in Postman:
   - Open Postman.
   - Click on **Workspaces** in the top-left corner.
   - Choose **Create Workspace**, name it, and save.

3. **Import the Collection**:

   - Click **Import** and select `DataProsessingTest.json` from the Tests folder.

4. **Run the Tests**:

   - Open the imported collection.
   - Click **Run** to execute all requests and review responses.

---

### **What the Tests Cover**

The `test_api.sh` script and Postman collection (`DataProsessingTest.json`) cover the following scenarios:

#### **API Component Testing**:
- **User Registration**: Tests the `/auth/register` endpoint to ensure it successfully registers a user.
- **User Login**: Tests the `/auth/login` endpoint and verifies that an authentication token is returned.
- **Protected Routes**: Ensures endpoints like `/profile` and `/content` require valid authentication tokens.
- **Profile Management**: Tests creating, fetching, and updating user profiles.
- **Content and Subscriptions**: Verifies fetching content and subscription data.

#### **Integration Testing**:
- **Authentication Flow**: Tests the interaction between registration, login, and logout endpoints.
- **Profile Creation and Fetching**: Verifies the process of creating a profile and retrieving it afterward.
- **Token Validation**: Ensures protected routes are inaccessible without valid tokens.

#### **Postman-Specific Tests**:
The Postman collection includes additional validations for:
- **Request Body Structure**: Ensures the request body matches expected formats and values.
- **Response Validation**: Validates fields like `email`, `message`, and `access_token` in the response.
- **Error Handling**: Tests invalid requests, such as expired tokens or missing fields, and verifies proper error responses.
- **Token Expiry**: Checks whether the token expiry is handled correctly.
- **Email Validation**: Ensures that the returned email matches the expected format.

---

## Additional Docker Commands

- **Build without Cache**:

  ```bash
  docker-compose build --no-cache
  ```

- **Restart Docker**:

  ```bash
  docker-compose restart
  ```

- **Show Running Containers**:

  ```bash
  docker ps
  ```

## Additional Notes

- Double-check `.env` configuration before starting containers.
- Regularly back up data to prevent loss.
- Ensure API is running before executing tests.
- Use `api_responses.txt` and Postman results for debugging and improvements.

For more detailed information, refer to Docker and Docker Compose documentation:

- Docker Documentation: https://docs.docker.com/
- Docker Compose Documentation: https://docs.docker.com/compose/
