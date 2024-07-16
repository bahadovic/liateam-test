# Laravel Project with JWT Authentication, MongoDB, and Redis

This project is a Laravel-based RESTful API that implements JWT-based authentication, uses MongoDB as the database, and Redis as the cache driver. The API supports registration, login, and CRUD operations for products and orders. Additionally, it includes business logic to update product inventory when orders are placed.

## Requirements

- PHP >= 8.3
- Composer
- MongoDB
- Redis

## INSTALL MONGODB EXTENSION FOR PHP

- sudo apt install php-dev php-pear
- sudo apt -y install php-mongodb

## Install dependencies

- composer install

## Configure Environment Variables

- Copy the .env.example file to .env:  
      cp .env.example .env

- Update the .env file with your database and cache configurations

## Authentication Endpoints

- Register: POST /api/register
Request Body:
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}

- logout: POST /api/logout

- refreshToken: GEt /api/refresh-token
Request Body:
{
  "refresh_token": "refresh-token-example",
}

## Product Endpoints

- List Products: GET /api/products

- Create Product: POST /api/products
Request Body:
{
  "name": "Product Name",
  "price": 99.99,
  "inventory": 100
}

- Get Product: GET /api/products/{id}

- Update Product: PUT /api/products/{id}
Request Body (optional fields):
{
  "name": "Updated Name",
  "price": 89.99,
  "inventory": 80
}

- Delete Product: DELETE /api/products/{id}


## Order Endpoints

- List Orders: GET /api/orders

- Create Order: POST /api/orders
Request Body:
{
  "products": [
    {
      "id": 1,
      "quantity": 2
    },
    {
      "id": 2,
      "quantity": 1
    }
  ]
}

- Update Order: PUT /api/Orders/{id}
Request Body (optional fields):
{
  "products": [
    {
      "id": 1,
      "quantity": 2
    },
    {
      "id": 2,
      "quantity": 1
    }
  ]
}

- Get Order: GET /api/orders/{id}

- Delete Order: DELETE /api/orders/{id}

## Running Tests

- php artisan test


