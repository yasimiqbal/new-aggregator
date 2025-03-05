<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## News Aggregator

The News Aggregator is a Laravel 12-based application designed to provide a robust solution for managing user authentication, articles, user preferences, user news feed and data aggregation. This application follows a clean architecture with distinct service and repository layers to ensure maintainable business logic and database interactions.

## Features

### User Authentication

- Create a new user account.
- Handle login and logout processes.
- Manage forgotten password recovery and password reset flows.

### Article Management

- Retrieve paginated lists of articles.
- Search for articles using various criteria:
  - q: Search within article titles, descriptions, and authors.
  - date: Filter articles by publication date.
  - category: Filter articles by category.
  - source: Filter articles by source.
- Display detailed article information.

### User Preferences

- Store and access user preferences.

### News Feed

- Create a customized news feed tailored to user preferences.
- Search for news feed using various criteria:
  - authors: Filter articles by authors.
  - categories: Filter articles by category.
  - sources: Filter articles by source.

### Data Aggregation

- Daily automated news article fetching using CRON jobs from various sources.
- Sources for data aggregation include:
  - NewsAPI
  - The Guardian
  - The New York Times

### API Documentation

- Comprehensive API documentation powered by tools like Swagger/OpenAPI.

## Libraries and Tools Used

- **[Laravel Sanctum](https://github.com/laravel/sanctum):** For API authentication.
- **[Swagger OpenAPI](https://github.com/DarkaOnLine/L5-Swagger):** For generating API documentation.

---

## Project Environment Versions

The application is developed and tested with the following versions:

- **PHP:** 8.2
- **Composer:** 2.6.6
- **Laravel:** 12.0

---

## Architecture

The application is built using an n-layer architecture:

- **Service Layer:** Manages business logic.
- **Repository Layer:** Handles database interactions.

This separation promotes a clean, modular, and maintainable codebase.

---

## Installation

### Pre-requisites

- Install **[Docker](https://docs.docker.com/get-started/get-docker/)**
- Install **[Docker Compose](https://docs.docker.com/compose/install)**

### Setup Instructions

1. Clone the repository:
   ```bash
   git clone git@github.com:yasimiqbal/new-aggregator.git
   ```
2. Navigate to the project directory:
   ```bash
   cd news-aggregator
   ```
3. Copy the environment configuration file:
   ```bash
   cp .env.example .env
   ```
4. Update the .env file with the required values, including API keys for news sources.
5. Build and run the application using Docker:

For Docker Compose (v1):

```bash
 docker-compose up --build -d
```

For Docker Compose (v2+):

```bash
 docker compose up --build -d
```

Once the build is complete, the application will be accessible locally.

---

## Accessing the Application

### API Documentation

- Open your browser and navigate to:
  - http://localhost:7001/api/documentation

### Phpmyadmin

- Open your browser and navigate to:
  - http://localhost:8082
- Login using the database credentials specified in the .env file.

---

## Running Feature Tests

To execute the test cases, use the following commands with the news-aggregator container.

### Accessing the news-aggregator Container

Open a terminal session in the news-aggregator container by running:

```bash
    docker exec -it news-aggregator bash
```

With in the news-aggregator container:

```bash
     php artisan test
```

### Outside of the news-aggregator container:

```bash
     docker exec -it news-aggregator php artisan test
```

## For creating swagger file

To create the swagger file.

### Accessing the news-aggregator Container

Open a terminal session in the news-aggregator container by running:

```bash
    docker exec -it news-aggregator bash
```

With in the news-aggregator container:

```bash
     php artisan config:cache
```

```bash
     php artisan l5-swagger:generate
```

### Outside of the news-aggregator container:

```bash
     docker exec -it news-aggregator php artisan config:cache
```

```bash
     docker exec -it news-aggregator php artisan l5-swagger:generate
```

## Docker Compose Services

- app: The Laravel application container (news-aggregator).
- webserver: Nginx server running on port **7001**.
- db: MySQL 8.0 database running on port **3308**.
- phpmyadmin: PhpMyAdmin interface running on port **8082**.
