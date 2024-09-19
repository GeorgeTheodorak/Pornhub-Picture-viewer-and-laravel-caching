# Pornhub picture viewer Project

## Overview
Pornhub picture viewer is a web application for viewing the pornstar information using laravel and vue.
This README provides instructions for setting up and running the project.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Installation](#installation)
    - [Docker Setup](#docker-setup)
    - [Manual Setup](#manual-setup)
- [Database Configuration](#database-configuration)
- [Initial Setup](#initial-setup)
- [Running the Application](#running-the-application)
- [Data Retrieval](#data-retrieval)
- [Login Credentials](#login-credentials)

## Prerequisites
- Docker and Docker Compose (for Docker setup)
- PHP and Composer (for manual setup)
- Node.js and npm (for manual setup)

## Installation

### Docker Setup
1. Clone the repository:
   ```
   git clone [repository-url]
   cd project_path
   ```

2. Start the Docker containers:
   ```
   docker-compose up -d --build
   ```

### Manual Setup
1. Clone the repository:
   ```
   git clone [repository-url]
   cd viewer
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Install JavaScript dependencies:
   ```
   npm install
   ```

4. Build the frontend assets:
   ```
   npm run build
   ```

## Database Configuration
By default, the project uses SQLite. Ensure a file named `database.sqlite` is created in the project root.

For MySQL:
1. Uncomment the MySQL section in `docker-compose.yml`
2. Update the `.env` file with MySQL credentials

## Initial Setup
Run database migrations and seed the database:
```
php artisan migrate
php artisan db:seed
```

## Running the Application
- Docker: The application should be running after `docker-compose up`
- Manual: Run `php artisan serve`

## Data Retrieval
To populate the database with initial data and fetch associated pictures:
```
php artisan app:retrieve-pornstar-data
```
Note: This process may take some time to complete.

## Login Credentials
After seeding the database, use these credentials to log in:
- Email: admin@admin.com
- Password: password
