📦 PayTabs PHP 8.4 Order & Payment System
This is a simple PHP-based order and payment management system integrated with PayTabs iFrame mode. It uses Docker for local development and MySQL for storage.

⚙️ Features
- Product selection and order creation
- Checkout page with customer and shipping options
- PayTabs iFrame payment integration (Hosted Payment Page)
- View order details and status
- PHP 8.4 + Apache + MySQL 5.7 environment via Docker

🚀 Getting Started
1. Clone the Repo
    ```
    git clone https://github.com/your-username/paytabs-php-project.git
    cd paytabs-php-project
    ```
2. Create .env File
    ```
    DATABASE_HOST=paytabs_mysql
    DATABASE_NAME=paytabs
    DATABASE_USER=paytabs_user
    DATABASE_PASS=your_password
    DATABASE_ROOT_PASS=root_password

    PAYTABS_PROFILE_ID=your_profile_id
    PAYTABS_INTEGRATION_KEY=your_integration_key
    ```
3. Start Docker Containers
    `docker-compose up -d`

4. Import the initial schema from `docker/mysql/paytabs_db.sql` using either:
    - PhpMyAdmin: http://localhost:8060
    - MySQL CLI (inside the container):

5. Access the Application
    - Frontend App: http://localhost:8080
    - PhpMyAdmin: http://localhost:8060

🛠️ Project Structure
```
/
├── config/             # PHP DB & model classes
├── pages/              # All app pages (checkout, pay, result, etc.)
├── inc/                # Shared headers, navbar, footers
├── paytabs             # Token generation logic (for PayTabs)
├── docker/             # PHP Apache setup
├── docker-compose.yml
└── README.md
```