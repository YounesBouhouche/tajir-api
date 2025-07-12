# Tajir API - E-commerce Backend

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A robust Laravel-based REST API for e-commerce applications, featuring comprehensive user authentication, product management, shopping cart functionality, and order processing.

## 🚀 Features

### Authentication & User Management
- **User Registration & Login** with email verification
- **JWT Token-based Authentication** using Laravel Sanctum
- **Password Reset** with email verification codes
- **User Profile Management** (view, update, delete)
- **Secure Password Hashing** with bcrypt

### Product Management
- **Product CRUD Operations** (Create, Read, Update, Delete)
- **Product Images** with multiple image support and ordering
- **Product Variants** (size, color, etc.) with individual pricing
- **Product Search & Filtering** capabilities
- **User-owned Products** (sellers can manage their products)

### Shopping Cart
- **Add/Remove Products** to/from cart
- **Cart Persistence** across sessions
- **Quantity Management** for cart items
- **Cart Checkout** process
- **Clear Cart** functionality

### Order Management
- **Order Placement** from cart checkout
- **Order History** for users
- **Order Status Tracking**
- **Order Details** with product information
- **Email Notifications** for order placement

### Additional Features
- **RESTful API Design** following Laravel conventions
- **Database Migrations** for easy deployment
- **Factory & Seeder** support for testing data
- **Comprehensive Testing** setup
- **Email Integration** with MailerSend
- **CORS Support** for frontend integration

## 🛠 Built With

* **[Laravel 12.x](https://laravel.com/)** - PHP Web Framework
* **[PHP 8.2+](https://www.php.net/)** - Server-side Programming Language
* **[SQLite](https://www.sqlite.org/)** - Database (configurable to MySQL/PostgreSQL)
* **[Laravel Sanctum](https://laravel.com/docs/sanctum)** - API Authentication
* **[MailerSend](https://www.mailersend.com/)** - Email Service Provider
* **[Laravel Breeze](https://laravel.com/docs/breeze)** - Authentication Scaffolding

## 📋 Prerequisites

Before you begin, ensure you have met the following requirements:

* **PHP >= 8.2** with the following extensions:
  - BCMath PHP Extension
  - Ctype PHP Extension
  - Fileinfo PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
* **[Composer](https://getcomposer.org/)** - Dependency Manager for PHP
* **Database** - MySQL, PostgreSQL, or SQLite
* **Web Server** - Apache, Nginx, or Laravel's built-in server

## ⚡ Quick Start

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your_username/tajir-api.git
   cd tajir-api
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure your database**
   Edit the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   
   # For MySQL:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=tajir_api
   # DB_USERNAME=your_username
   # DB_PASSWORD=your_password
   ```

6. **Configure Email Service (Optional)**
   ```env
   MAIL_MAILER=mailersend
   MAILERSEND_API_KEY=your_mailersend_api_key
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="Tajir API"
   ```

7. **Run database migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed the database (Optional)**
   ```bash
   php artisan db:seed
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

Your API will be available at `http://localhost:8000`

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication
Most endpoints require authentication using Bearer tokens. Include the token in your requests:
```
Authorization: Bearer your_token_here
```

### Response Format
All API responses follow this structure:
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        
    }
}
```

### Authentication Endpoints

#### Register User
```http
POST /api/v1/user/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login User
```http
POST /api/v1/user/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

#### Get User Profile
```http
GET /api/v1/user
Authorization: Bearer {token}
```

#### Update User Profile
```http
PATCH /api/v1/user
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "newemail@example.com"
}
```

#### Delete User Account
```http
DELETE /api/v1/user
Authorization: Bearer {token}
```

### Password Reset Endpoints

#### Send Reset Code
```http
POST /api/v1/password/send
Content-Type: application/json

{
    "email": "john@example.com"
}
```

#### Verify Reset Code
```http
POST /api/v1/password/check
Content-Type: application/json

{
    "email": "john@example.com",
    "code": "123456"
}
```

#### Reset Password
```http
POST /api/v1/password/reset
Content-Type: application/json

{
    "email": "john@example.com",
    "code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### Product Endpoints

#### Get All Products
```http
GET /api/v1/products
```

#### Get Single Product
```http
GET /api/v1/products/{id}
```

#### Create Product (Auth Required)
```http
POST /api/v1/products
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Product Name",
    "description": "Product description",
    "price": 29.99,
    "stock": 100,
    "category": "Electronics"
}
```

#### Update Product
```http
PATCH /api/v1/products/{id}/update
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Product Name",
    "price": 39.99
}
```

#### Delete Product (Auth Required)
```http
DELETE /api/v1/products/{id}
Authorization: Bearer {token}
```

### Cart Endpoints (All Require Authentication)

#### Get Cart Items
```http
GET /api/v1/cart
Authorization: Bearer {token}
```

#### Add Item to Cart
```http
POST /api/v1/cart/add
Authorization: Bearer {token}
Content-Type: application/json

{
    "product_id": 1,
    "quantity": 2,
    "variant_id": 3
}
```

#### Remove Item from Cart
```http
POST /api/v1/cart/remove
Authorization: Bearer {token}
Content-Type: application/json

{
    "product_id": 1,
    "variant_id": 3
}
```

#### Clear Cart
```http
DELETE /api/v1/cart
Authorization: Bearer {token}
```

#### Checkout Cart
```http
POST /api/v1/cart/checkout
Authorization: Bearer {token}
Content-Type: application/json

{
    "shipping_address": "123 Main St, City, Country",
    "payment_method": "credit_card"
}
```

### Order Endpoints (All Require Authentication)

#### Get User Orders
```http
GET /api/v1/orders
Authorization: Bearer {token}
```

#### Get Single Order
```http
GET /api/v1/orders/{id}
Authorization: Bearer {token}
```

#### Update Order Status
```http
PATCH /api/v1/orders/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "shipped"
}
```

## 🗄️ Database Schema

### Core Tables
- **users** - User accounts and authentication
- **products** - Product catalog
- **product_images** - Product image management
- **variants** - Product variants (size, color, etc.)
- **carts** - Shopping cart items
- **orders** - Order information
- **addresses** - User shipping addresses
- **reset_passwords** - Password reset tokens

### Relationships
- Users can have multiple products (seller relationship)
- Products can have multiple images and variants
- Users have shopping carts with multiple products
- Orders belong to users and contain multiple products

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with verbose output
php artisan test --verbose
```

### Test Categories
- **Feature Tests** - API endpoint testing
- **Unit Tests** - Individual component testing
- **Database Tests** - Model and relationship testing

## 🚀 Deployment

### Production Environment Setup

1. **Server Requirements**
   - PHP 8.2+ with required extensions
   - Web server (Apache/Nginx)
   - Database server (MySQL/PostgreSQL)
   - SSL certificate for HTTPS

2. **Environment Configuration**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   # Configure production database
   DB_CONNECTION=mysql
   DB_HOST=your-db-host
   DB_DATABASE=your-db-name
   DB_USERNAME=your-db-user
   DB_PASSWORD=your-secure-password
   
   # Configure email service
   MAIL_MAILER=mailersend
   MAILERSEND_API_KEY=your-production-api-key
   ```

3. **Deployment Steps**
   ```bash
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   
   # Cache configuration
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Run migrations
   php artisan migrate --force
   
   # Set proper permissions
   chmod -R 755 storage bootstrap/cache
   ```

### Docker Deployment (Optional)

Create a `Dockerfile`:
```dockerfile
FROM php:8.2-fpm

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
```

## 🔧 Configuration

### Key Configuration Files
- `.env` - Environment variables
- `config/database.php` - Database configuration
- `config/mail.php` - Email configuration
- `config/cors.php` - CORS settings
- `config/sanctum.php` - API authentication

### Performance Optimization
- Enable OPcache in production
- Use Redis for caching and sessions
- Configure queue workers for email processing
- Implement database indexing for large datasets

## 🤝 Contributing

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests for new features
- Update documentation for API changes
- Use meaningful commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Email: support@yourdomain.com
- Documentation: [API Docs](https://your-api-docs-url.com)

## 🙏 Acknowledgments

- Laravel team for the amazing framework
- Contributors and the open-source community
- MailerSend for email services
