USE paytabs;

-- Drop existing tables (optional for reset)
DROP TABLE IF EXISTS refunds;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;

-- Products Table (static list)
CREATE TABLE products
(
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100)   NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Orders Table
CREATE TABLE orders
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    status          ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    shipping_method ENUM('shipping', 'pickup') NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table (many-to-many relationship between orders and products)
CREATE TABLE order_items
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    product_id INT NOT NULL,
    quantity   INT NOT NULL CHECK (quantity > 0),
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE payments
(
    id               INT AUTO_INCREMENT PRIMARY KEY,
    order_id         INT NOT NULL,
    status           ENUM('initiated', 'success', 'failed', 'refunded') DEFAULT 'initiated',
    payment_request  TEXT,
    payment_response TEXT,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
);

-- Refunds Table
CREATE TABLE refunds
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    payment_id      INT NOT NULL,
    refund_request  TEXT,
    refund_response TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments (id) ON DELETE CASCADE
);

-- Sample Products
INSERT INTO products (name, price)
VALUES ('USB Keyboard', 19.99),
       ('Wireless Mouse', 24.50),
       ('HDMI Cable', 8.99),
       ('Portable SSD 1TB', 109.99),
       ('Laptop Stand', 34.00);
