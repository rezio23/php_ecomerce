# PHP eCommerce

A minimal PHP eCommerce project built following the tutorial series (videos 51–60).

## Features

- Product listing page with featured products
- Single product detail page
- Shopping cart with session-based storage
- Contact Us page
- User account, login, and registration pages
- Database-driven product loading (with static fallback)
- Coats/category product section from DB

## Setup

### Requirements

- PHP 7.4+
- MySQL / MariaDB
- Apache or PHP built-in server

### 1. Create the Database

1. Open **phpMyAdmin** at `localhost/dashboard`
2. Click **New** and create a database named: `php_project`
3. Select `php_project`, click **SQL**, paste the contents of `tables.sql`, and click **Go**

This creates the tables: `products`, `orders`, `order_items`, `users`, `payments`

### 2. Seed Data (10 rows per table)

A ready-to-use seed file is included. Select `php_project` in phpMyAdmin → click **SQL** → paste contents of `seed.sql` → click **Go**.

This inserts:
- **10 products** (shoes, coats, dresses, shirts, bags) with real Unsplash image URLs
- **10 users** (password for all: `password123`)
- **10 orders** with statuses (delivered / shipped / on_hold / cancelled)
- **10 order_items** linked to orders and products
- **10 payments** with transaction IDs

### 3. Add Products Manually

Insert products via phpMyAdmin **SQL** tab:

```sql
INSERT INTO `products`
  (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_special_offer, product_color)
VALUES
  ('White Shoes', 'Shoes', 'Awesome white shoes', 'clothes1.jpg', 'clothes1.jpg', 'clothes1.jpg', 'clothes1.jpg', 155.00, 0, 'white');
```

To show coats in the coats section:

```sql
INSERT INTO `products`
  (product_name, product_category, product_description, product_image, product_image2, product_image3, product_image4, product_price, product_special_offer, product_color)
VALUES
  ('Black Coat', 'coats', 'Black coat for men', 'clothes1.jpg', 'clothes1.jpg', 'clothes1.jpg', 'clothes1.jpg', 150.00, 0, 'black');
```

### 3. Database Connection

The connection is configured in `server/connection.php`:

```php
$con = mysqli_connect("localhost", "root", "", "php_project", 3308);
```

- **Host:** localhost
- **Username:** root
- **Password:** (empty)
- **Database:** php_project
- **Port:** 3308

### 4. Run the Project

```bash
php -S localhost:8000
```

Then open: [http://localhost:8000](http://localhost:8000)

## File Structure

```
php_ecomerce/
├── index.php              # Homepage (featured products + coats from DB)
├── contact.php            # Contact Us page
├── singleproduct.php      # Single product detail page
├── cart.php               # Shopping cart
├── checkout.php           # Checkout page
├── account.php            # User account page
├── login.php              # Login page
├── register.php           # Register page
├── data.php               # Static site config & fallback product data
├── tables.sql             # SQL schema (products, orders, users, etc.)
├── server/
│   ├── connection.php          # MySQL database connection
│   ├── getFeaturedProducts.php # SELECT * FROM products LIMIT 4
│   └── get_quotes.php          # SELECT * FROM products WHERE product_category = 'coats' LIMIT 4
├── includes/
│   ├── header.php         # Site header / navbar
│   ├── footer.php         # Site footer
│   └── functions.php      # Helper functions (cart, rendering, etc.)
└── assets/
    ├── css/style.css
    └── images/            # Place product images here
```

## Tutorial Videos Covered

| # | Topic | What was implemented |
|---|-------|---------------------|
| 51 | Contact page | `contact.php` created |
| 52 | Activating navbar | Nav links updated (contact.php, cart.php, account.php) |
| 53 | Database | Database `php_project` setup instructions |
| 54 | Database connection | `server/connection.php` |
| 55 | Tables | `tables.sql` included in repo |
| 56.1 | tables.sql | SQL schema file |
| 57 | Run project | `php -S localhost:8000` |
| 58 | Products table | INSERT examples in README |
| 59 | Get products | `server/getFeaturedProducts.php` + DB loop in `index.php` |
| 60 | Get coats | `server/get_quotes.php` + coats section in `index.php` |
