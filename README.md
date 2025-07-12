# About The Project

This is a Laravel-based backend API for an e-commerce website. It includes features like user authentication, product management, cart, and orders.

# Built With

* [Laravel](https://laravel.com/)
* [PHP](https://www.php.net/)
* [SQLite](https://www.sqlite.org/)

# Getting Started

To get a local copy up and running follow these simple example steps.

## Prerequisites

* PHP >= 8.2
* Composer
* MySQL

## Installation

1. Clone the repo
   ```sh
   git clone https://github.com/your_username/your_project_name.git
   ```
2. Install PHP dependencies
   ```sh
   composer install
   ```
3. Create a copy of your .env file
   ```sh
   cp .env.example .env
   ```
4. Generate an app encryption key
   ```sh
   php artisan key:generate
   ```
5. Run the database migrations
   ```sh
   php artisan migrate
   ```

# API Endpoints

## Auth
| Method | URI | Name |
| --- | --- | --- |
| POST | `api/v1/user/register` | `user.register` |
| POST | `api/v1/user/login` | `user.login` |
| GET | `api/v1/user` | `user.index` |
| PATCH | `api/v1/user` | `user.update` |
| DELETE | `api/v1/user` | `user.delete` |
| POST | `api/v1/password/send` | `password.send` |
| POST | `api/v1/password/check` | `password.check` |
| POST | `api/v1/password/reset` | `password.reset` |

## Products
| Method | URI | Name |
| --- | --- | --- |
| GET | `api/v1/products` | `products.index` |
| GET | `api/v1/products/{id}` | `products.show` |
| POST | `api/v1/products` | `products.store` |
| PATCH | `api/v1/products/{product}/update` | `products.update` |
| DELETE | `api/v1/products/{product}` | `products.delete` |

## Cart
| Method | URI | Name |
| --- | --- | --- |
| GET | `api/v1/cart` | `cart.index` |
| POST | `api/v1/cart/add` | `cart.create` |
| POST | `api/v1/cart/remove` | `cart.remove` |
| DELETE | `api/v1/cart` | `cart.delete` |
| POST | `api/v1/cart/checkout` | `cart.checkout` |

## Orders
| Method | URI | Name |
| --- | --- | --- |
| GET | `api/v1/orders` | `orders.index` |
| GET | `api/v1/orders/{order}` | `orders.show` |
| PATCH | `api/v1/orders/{order}` | `orders.update` |

# Testing

To run the tests, run the following command:
```sh
php artisan test
```
