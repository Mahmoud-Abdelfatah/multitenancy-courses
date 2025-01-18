# Multitenancy Courses

This repository demonstrates a Laravel-based multitenancy implementation for managing courses. Below are the setup instructions to get the application running.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Docker and Docker Compose (if running via Docker)
- SQLite (or your preferred database system)

## Setup Instructions

### Common Steps
1. **Create the `.env` file:**
   - Copy the contents of `.env-example` to a new file named `.env`.

2. **Create the SQLite database file:**
   - In the root directory, create a new file named `database.sqlite`. This will be used as the application's database.

### Running the Application with Docker

1. Build and start the Docker containers:

   ```bash
   docker-compose up --build -d
   ```

2. Access the application by navigating to [http://localhost:8000](http://localhost:8000) in your web browser.

### Running the Application Without Docker

1. Install PHP dependencies using Composer:

   ```bash
   composer install
   ```

2. Start the Laravel development server:

   ```bash
   php artisan serve
   ```

3. Access the application by navigating to [http://localhost:8000](http://localhost:8000) in your web browser.





