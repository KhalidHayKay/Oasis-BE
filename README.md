# Oasis — E-commerce Backend

A feature-rich Laravel backend powering an online storefront with product catalogs, shopping cart flows, checkout, payments, content feeds, and marketing capture.

This project demonstrates modern backend engineering patterns including API-first architecture, service-layer separation, token authentication, and containerized deployment.

---

## 🌍 Try it Live

[Live Preview](https://oasis.haykay.xyz) · [Frontend Repository](https://github.com/KhalidHayKay/Oasis)

---

## What It Does

- Provides user authentication with Sanctum token-based auth
- Supports social login via Laravel Socialite and email verification flows
- Exposes product catalog, categories, blogs, tags, and inspiration feeds
- Manages user carts and cart item synchronization
- Handles checkout validation, shipping address capture, and payment intent creation
- Tracks orders and order history for authenticated users
- Provides a waitlist endpoint for marketing capture

---

## Architecture

The application is organized with clear separation of concerns:

- **Authentication Layer** — Sanctum and Socialite for API auth and social login
- **Controller Layer** — HTTP request handling, route definitions, and JSON responses
- **Service Layer** — Business logic and domain operations in `app/Services`
- **Resource Layer** — API response formatting via Laravel resources
- **Storage Layer** — local file storage and environment-driven upload configuration

---

## 🚀 Features

- 🔐 Sanctum token-based authentication
- 🌐 Social login providers and email verification
- 🛍️ Product catalog with categories, tags, and inspiration content
- 🛒 Shopping cart management with sync, increment, decrement, and clear
- ✉️ Checkout flow
- 💳 Contract based payment handling
- 📦 Order history retrieval for verified customers
- 🐳 Docker Compose deployment-ready

---

## 🏗 Project Structure

```
Oasis-BE/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # API controllers and route handlers
│   │   ├── Requests/          # Form request validation
│   │   └── Resources/         # API response transformers
│   ├── Models/                # Eloquent models and relationships
│   └── Services/              # Business logic layer
├── config/                    # App configuration and environment mapping
├── database/
│   ├── migrations/            # Database schema definitions
│   └── seeders/               # Seed data generators
├── routes/
│   ├── api/                   # Modular API route definitions
│   └── web.php                # Web routes
├── docker/                    # Docker setup (if present)
├── .env.example               # Environment template
└── composer.json              # PHP dependencies
```

---

## ⚙️ How It Works

1. Users register or login and receive a Sanctum token
2. Authenticated users browse products, categories, blogs, tags, and inspirations
3. Cart endpoints let users add items, sync state, update quantities, and clear the cart
4. Checkout validates the cart and saves shipping address details
5. Payment endpoints create and confirm Stripe intents
6. Orders are created and retrievable for authenticated, verified users
7. Waitlist signups capture leads for marketing or early access

---

## 🧪 Getting Started

### Prerequisites

- Docker and Docker Compose
- PHP 8.2+ (for local composer commands)

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/KhalidHayKay/Oasis-BE.git
cd Oasis-BE
```

### 2️⃣ Setup Environment Variables

```bash
cp .env.example .env
```

Update `.env` with your database credentials, frontend URL, Stripe API key, and mail driver settings.

### 3️⃣ Start Services

```bash
docker-compose up -d
```

### 4️⃣ Initialize the Application

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

The API will be available at: `http://localhost:8010`

---

## 🔑 API Endpoints

### Authentication

```
GET    /api/auth/me
POST   /api/auth/login
POST   /api/auth/register
POST   /api/auth/refresh
POST   /api/auth/logout
POST   /api/auth/email/verify
POST   /api/auth/email/send-code
POST   /api/auth/password/forgot
POST   /api/auth/password/reset
```

### Product & Content

```
GET /api/categories
GET /api/categories/{slug}
GET /api/products
GET /api/products/top
GET /api/products/{product}
GET /api/inspirations
GET /api/tags
GET /api/blogs
GET /api/blogs/{slug}
```

### Cart

```
GET    /api/cart
POST   /api/cart/items
POST   /api/cart/sync
PATCH  /api/cart/items/{item}/quantity/increment
PATCH  /api/cart/items/{item}/quantity/decrement
DELETE /api/cart/items/{item}
DELETE /api/cart
```

### Checkout & Payment

```
GET    /api/checkout
POST   /api/checkout
POST   /api/checkout/address
GET    /api/payment/show
POST   /api/payment/intent
POST   /api/payment/confirm
```

### Orders

```
GET /api/orders
GET /api/orders/{order}
```

---

## 🔐 Security

- Sanctum token authentication on protected endpoints
- Verified email enforcement for cart, checkout, payment, and orders
- Input validation using Laravel Form Requests
- Bcrypt password hashing handled by Laravel
- Eloquent ORM protects against SQL injection
- CORS control via environment-configured origin allowlist

---

## 👨‍💻 Author

Built by Khalid

**Tech Stack:** Laravel 12 · Sanctum · Socialite · Stripe · PostgreSQL · Docker · Nginx
