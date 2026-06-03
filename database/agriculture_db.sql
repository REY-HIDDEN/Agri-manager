-- ============================================================
-- Agriculture Product Management System
-- Database: agriculture_db
-- MySQL Script
-- ============================================================

CREATE DATABASE IF NOT EXISTS agriculture_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE agriculture_db;

-- ------------------------------------------------------------
-- Users Table
-- Stores system users with role-based access
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'sales_officer' COMMENT 'admin or sales_officer',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Password Reset Tokens
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Sessions Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Cache Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Cache Locks Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Jobs Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Products Table
-- Stores agricultural product inventory
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    quantity INT NOT NULL DEFAULT 0 COMMENT 'Current stock quantity',
    buying_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Cost price per unit',
    selling_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Sale price per unit',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT products_quantity_check CHECK (quantity >= 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Buyers Table
-- Stores buyer/customer information
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS buyers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    address TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Sales Table
-- Records product sales transactions
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    sale_date DATE NOT NULL,
    payment_status ENUM('pending', 'paid', 'partial') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT sales_buyer_id_foreign FOREIGN KEY (buyer_id) REFERENCES buyers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Sale Details Table
-- Line items for each sale transaction
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sale_details (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT sale_details_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    CONSTRAINT sale_details_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Deliveries Table
-- Tracks delivery status for sales
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS deliveries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id BIGINT UNSIGNED NOT NULL UNIQUE,
    delivery_date DATE NOT NULL,
    destination TEXT NOT NULL,
    status ENUM('pending', 'in_transit', 'delivered') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT deliveries_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Insert Default Users
-- ------------------------------------------------------------
INSERT INTO users (username, name, email, password, role) VALUES
('admin', 'System Administrator', 'admin@agriculture.com', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPm1Qlq5GGEI5qCJhCJhCJhCJhCJO', 'admin'),
('sales', 'Sales Officer', 'sales@agriculture.com', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPm1Qlq5GGEI5qCJhCJhCJhCJhCJO', 'sales_officer');

-- ------------------------------------------------------------
-- Sample Products (Agriculture Products)
-- ------------------------------------------------------------
INSERT INTO products (name, quantity, buying_price, selling_price) VALUES
('Organic Rice (1kg)', 200, 1.50, 2.50),
('Maize Flour (2kg)', 150, 2.00, 3.50),
('Fresh Tomatoes (kg)', 100, 0.80, 1.50),
('Irish Potatoes (kg)', 300, 0.50, 1.00),
('Sweet Potatoes (kg)', 250, 0.40, 0.80),
('Bananas (bunch)', 80, 2.00, 3.00),
('Cabbage (head)', 60, 0.60, 1.20),
('Carrots (kg)', 120, 0.70, 1.30),
('Onions (kg)', 90, 0.90, 1.60),
('Beans (kg)', 180, 1.20, 2.00),
('Fresh Milk (liter)', 50, 0.70, 1.10),
('Honey (500ml)', 40, 4.00, 6.00);

-- ------------------------------------------------------------
-- Sample Buyers
-- ------------------------------------------------------------
INSERT INTO buyers (name, phone, email, address) VALUES
('Jean Pierre Habimana', '+250 788 100 200', 'jp@example.com', 'Kigali, Rwanda'),
('Alice Muhawenimana', '+250 788 300 400', 'alice@example.com', 'Musanze, Rwanda'),
('David Kagame', '+250 722 500 600', 'david@example.com', 'Huye, Rwanda'),
('Grace Uwimana', '+250 733 700 800', 'grace@example.com', 'Nyagatare, Rwanda'),
('Patrick Niyonzima', '+250 788 900 000', 'patrick@example.com', 'Rubavu, Rwanda');
