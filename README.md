# Spot2 API

## Introduction
This project sets up an API required by Spot2.

## Features
- **Laravel Framework**: PHP web application framework with expressive, elegant syntax.
- **Dockerized Environment**: Simplified setup using Docker Compose for consistent development environments.
- **MySQL Database**: MySQL is used as the relational database for the application.
- **Documentation**: Swagger UI is used for the documentation of the API.

## Prerequisites
- Docker installed
- Docker Compose installed
- Composer installed locally for managing PHP dependencies

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/ilich96/spot2-api.git
   ```

2. Navigate to the project directory:
   ```bash
   cd spot2-api
   ```

3. Build and start the containers using Docker Compose:
   ```bash
   docker-compose up -d --build
   ```

4. Run migrations to set up the database:
   ```bash
   docker-compose exec api php artisan migrate
   ```

5. Run seeder to save data in the database:
   ```bash
   docker-compose exec api php artisan db:seed --class=LandUseSeeder
   ```

## Usage
- Access to the api in your browser via SwaggerUI at http://localhost:8080/.
- To stop the containers:
   ```bash
   docker-compose down
   ```

## Docker Compose Configuration
The compose.yml file defines the following services:
- **api**: The container running the Laravel application.
- **database**: The MySQL database container.
- **swagger-ui**: The Swagger UI database container.
