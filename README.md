# 🌾 Agriculture Product Management System

A comprehensive web-based system for farmers to manage agricultural products, track sales, manage buyers, record deliveries, and generate insightful reports.

## 📋 System Overview

The Agriculture Product Management System digitizes agricultural product management and sales tracking, addressing common challenges such as:
- Unrecorded sales
- Difficulty tracking buyers
- Loss of transaction records
- No profit calculation system
- Manual record keeping errors

## ✨ Features

### 👥 User Roles
- **Administrator**: Full system access - manage products, buyers, sales, deliveries, users, and reports
- **Sales Officer**: Record sales, manage buyers, and view reports

### 📦 Product Management
- CRUD operations (Create, Read, Update, Delete)
- Search and filter products
- Stock quantity tracking
- Buying/selling price management
- Low stock alerts

### 👤 Buyer Management
- Complete buyer profiles with contact details
- Phone number validation
- Purchase history tracking
- Search functionality

### 🛒 Sales Management
- Create sales with multiple products
- Auto-calculate subtotals and totals
- Stock quantity auto-update
- Payment status tracking (Pending/Paid/Partial)
- Prevent selling more than available stock

### 🚚 Delivery Management
- Record deliveries for sales
- Track delivery status (Pending/In Transit/Delivered)
- Delivery history

### 📊 Reports
- Daily Sales Report
- Monthly Sales Report
- Product Stock Report
- Buyer Purchase History
- Profit Report with Revenue, Cost, and Net Profit

### 📈 Dashboard
- Key metrics cards (Total Products, Buyers, Sales, Revenue, Profit, Pending Deliveries)
- Sales trend chart
- Low stock alerts

## 🛠️ Technology Stack

| Component | Technology |
|-----------|-----------|
| **Frontend** | HTML5, CSS3, Bootstrap 5, JavaScript, jQuery |
| **Backend** | PHP Laravel 12 |
| **Database** | SQLite |
| **Icons** | Font Awesome 6 |
| **Charts** | Chart.js |
| **Tables** | DataTables |
| **Styling** | Tailwind CSS, Custom CSS |

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- Git

## 🚀 Installation Guide

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd greengrid
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Create SQLite Database
```bash
php -r "touch('database/database.sqlite');"
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Seed Database (Optional - includes demo data)
```bash
php artisan db:seed
```

### Step 7: Install Frontend Dependencies
```bash
npm install
npm run build
```

### Step 8: Start the Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔑 Default Accounts

After running the seeder, the following accounts are available:

| Role | Username | Password |
|------|----------|----------|
| **Administrator** | `admin` | `password` |
| **Sales Officer** | `sales` | `password` |

## 📁 Project Structure

```
greengrid/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── BuyerController.php
│   │   │   ├── SaleController.php
│   │   │   ├── DeliveryController.php
│   │   │   ├── ReportController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Buyer.php
│       ├── Sale.php
│       ├── SaleDetail.php
│       └── Delivery.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── database.sqlite
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── layouts/
│       │   └── app.blade.php
│       ├── products/
│       ├── buyers/
│       ├── sales/
│       ├── deliveries/
│       ├── reports/
│       ├── users/
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   └── web.php
└── bootstrap/
    └── app.php
```

## 🗄️ Database Schema

### Users Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| username | VARCHAR(255) | Unique username |
| name | VARCHAR(255) | Full name |
| email | VARCHAR(255) | Email (optional) |
| password | VARCHAR(255) | Hashed password |
| role | VARCHAR(50) | admin / sales_officer |

### Products Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| name | VARCHAR(255) | Product name (unique) |
| quantity | INT | Current stock |
| buying_price | DECIMAL(10,2) | Cost price |
| selling_price | DECIMAL(10,2) | Selling price |

### Buyers Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| name | VARCHAR(255) | Buyer name |
| phone | VARCHAR(20) | Phone number |
| email | VARCHAR(255) | Email (optional) |
| address | TEXT | Physical address |

### Sales Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| buyer_id | BIGINT FK | References buyers |
| total_amount | DECIMAL(12,2) | Total sale amount |
| sale_date | DATE | Date of sale |
| payment_status | ENUM | pending/paid/partial |

### Sale Details Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| sale_id | BIGINT FK | References sales |
| product_id | BIGINT FK | References products |
| quantity | INT | Quantity sold |
| unit_price | DECIMAL(10,2) | Price per unit |
| subtotal | DECIMAL(12,2) | Line item total |

### Deliveries Table
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT PK | Primary key |
| sale_id | BIGINT FK | References sales (unique) |
| delivery_date | DATE | Scheduled delivery date |
| destination | TEXT | Delivery address |
| status | ENUM | pending/in_transit/delivered |

## 🔒 Security Features

- **Password Hashing**: All passwords are hashed using Laravel's bcrypt
- **Session Management**: Secure session-based authentication
- **CSRF Protection**: All forms include CSRF tokens
- **Role-Based Access Control**: Middleware prevents unauthorized access
- **Input Validation**: All inputs validated on server side
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade templating auto-escapes output

## 📖 API Documentation

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/login` | Show login form |
| POST | `/login` | Authenticate user |
| POST | `/logout` | Logout user |

### Products

| Method | Endpoint | Description | Permissions |
|--------|----------|-------------|-------------|
| GET | `/products` | List all products | All |
| GET | `/products/create` | Show create form | Admin |
| POST | `/products` | Store new product | Admin |
| GET | `/products/{id}/edit` | Show edit form | Admin |
| PUT | `/products/{id}` | Update product | Admin |
| DELETE | `/products/{id}` | Delete product | Admin |

### Buyers

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/buyers` | List all buyers |
| GET | `/buyers/create` | Show create form |
| POST | `/buyers` | Store new buyer |
| GET | `/buyers/{id}/edit` | Show edit form |
| PUT | `/buyers/{id}` | Update buyer |
| DELETE | `/buyers/{id}` | Delete buyer |

### Sales

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/sales` | List all sales |
| GET | `/sales/create` | Show create form |
| POST | `/sales` | Store new sale |
| GET | `/sales/{id}` | View sale details |
| GET | `/sales/{id}/edit` | Show edit form (Admin) |
| PUT | `/sales/{id}` | Update sale (Admin) |
| DELETE | `/sales/{id}` | Delete sale (Admin) |

### Deliveries

| Method | Endpoint | Description | Permissions |
|--------|----------|-------------|-------------|
| GET | `/deliveries` | List all deliveries | All |
| GET | `/deliveries/create` | Show create form | Admin |
| POST | `/deliveries` | Store new delivery | Admin |
| GET | `/deliveries/{id}/edit` | Show edit form | Admin |
| PUT | `/deliveries/{id}` | Update delivery | Admin |
| DELETE | `/deliveries/{id}` | Delete delivery | Admin |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/reports` | Reports index |
| GET | `/reports/daily-sales` | Daily sales report |
| GET | `/reports/monthly-sales` | Monthly sales report |
| GET | `/reports/products` | Product stock report |
| GET | `/reports/buyers` | Buyer report |
| GET | `/reports/buyers/{id}/history` | Buyer purchase history |
| GET | `/reports/profit` | Profit report |

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProductTest
```

## 📦 Deployment Guide

### Production Server Requirements
- Web server (Apache/Nginx)
- PHP 8.2+
- Composer
- Node.js

### Deployment Steps
1. Run `composer install --optimize-autoloader --no-dev`
2. Run `php artisan migrate --force`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Set up a cron job for `php artisan schedule:run`
7. Configure web server to point to `/public` directory

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

This project is open-source software.

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap 5
- Font Awesome
- Chart.js
- DataTables
- All contributors and testers
