
# Laravel REST API

## Introduction
This project is a practice REST API built using Laravel. It's designed to serve as the backend for a mobile application, demonstrating key RESTful principles and Laravel's capabilities in API development.

## Requirements
- Docker
- PHP 8.3
- Nginx 1.22
- MySQL 8.0
- Redis 7.1

## Local Development Setup
This project uses Docker to simplify the setup and configuration process. Ensure Docker is installed on your system before proceeding.

### Getting Started
1. **Start the Docker Containers**: To initialize all necessary containers, run:

   ```bash
   docker-compose up
   ```

2. **SSH into the PHP-FPM Container**: Access the `todo-fpm` container to manage dependencies and perform other tasks:

   ```bash
   docker exec -ti todo-fpm bash
   ```

3. **Install Dependencies**: Inside the container, execute Composer to install the required PHP dependencies:

   ```bash
   composer install
   ```

4. **Accessing the Application**: Once setup is complete, the Laravel application will be available at [http://localhost:8099](http://localhost:8099).

## Testing
The project includes both unit and feature tests. To execute these tests, use the following command inside the `php-fpm` container:

```bash
php artisan test
```

## Contributing
Provide guidelines for how others can contribute to your project, including:

- Procedures for submitting issues and pull requests
- Coding standards and practices
- Contact information for the project maintainers

## License
Mention the license under which your project is released, if applicable.
