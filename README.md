# Insider One - Bulk Message System

This project is a robust, asynchronous bulk message sending system designed for the Insider One Case Study. It is fully Dockerized for easy setup and consistency. It utilizes Laravel's Queue system, Redis for caching, and follows strict architectural patterns (Service-Repository) to ensure scalability.
    

## Installation & Setup

Follow these steps to get the project running in minutes.

### 1. Clone the Repository

```
git clone <your-repo-url>
cd insider-case

```

### 2. Configure Environment

Copy the example environment file:

```
cp .env.example .env

```

Open `.env` and **ensure** the database and redis configurations match the Docker service names (already pre-configured for Docker):

```
APP_URL=http://localhost:8000

# Database (Service name: mysql)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=insider_case
DB_USERNAME=insider
DB_PASSWORD=password

# Queue & Cache (Service name: redis)
QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=phpredis

# Session
SESSION_DRIVER=file

```

### Start Application 

#### Before the start containers
Install dependencies and generate cache files.

```
composer install
```

Build and start the containers.

```
docker-compose up -d --build

```

### 4. Install Dependencies & Setup (Critical Step)

Since we are using Docker volumes for development, the local (empty) vendor folder might overwrite the container's vendor folder. Run these commands **inside** the container to fix it and setup the DB:

```
# 1. Install Composer Dependencies (Fixes missing vendor/autoload.php)
docker-compose exec app composer install

# 2. Generate App Key
docker-compose exec app php artisan key:generate

# 3. Run Migrations & Seed Dummy Data
docker-compose exec app php artisan migrate --seed

```

## Usage

Since the project uses Docker, you don't need to manually start servers or workers.

-   **API URL:** [http://localhost:8000](https://www.google.com/search?q=http://localhost:8000 "null")
    
-   **phpMyAdmin:** [http://localhost:8080](https://www.google.com/search?q=http://localhost:8080 "null") (User: `insider_user`, Pass: `insider_password`)
    

### Trigger Bulk Sending

To start the message sending process (dispatching jobs to the queue), run the custom command:

```
docker-compose exec app php artisan app:send-bulk-messages

```

_Watch the logs to see the queue worker processing messages in the background:_

```
docker-compose logs -f queue

```

## API Documentation with Scramble

-   **Docs URL:** [http://localhost:8000/docs/api](https://www.google.com/search?q=http://localhost:8000/docs/api "null")
    

### Endpoints

-   `GET /api/sent-messages`: List all sent messages from DB.
    
-   `GET /api/message/{id}/cache`: Get details of a sent message from Redis.
    

## Running Tests

To execute the Unit and Feature tests inside the container:

```
docker-compose exec app php artisan test

```

## Architecture Overview

### 1. Service-Repository Pattern

-   **`MessageRepository`:** Abstraction layer for Database (Eloquent) and Redis operations.
    
-   **`MessageService`:** Handles business logic (validations) and acts as a bridge between Jobs/Controllers and the Repository.
    

### 2. Job & Queue Structure

-   **`SendBulkMessages` (Command):** Calculates delays for rate limiting (2 messages/5s) and dispatches jobs.
    
-   **`SendMessageJob` (Job):** Processed by the queue worker. It sends the request to the webhook and updates the status via the Service layer.
    

### 3. Docker Structure

-   **`app`:** The Laravel application (PHP 8.2 FPM).
    
-   **`queue`:** A separate container running `php artisan queue:work`.
    
-   **`mysql`:** Database container.
    
-   **`redis`:** Cache and Queue driver container