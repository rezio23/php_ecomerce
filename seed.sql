-- ============================================================
-- SEED DATA for php_project
-- 10 rows per table: products, users, orders, order_items, payments
-- Images sourced from Unsplash (free, no attribution required)
-- ============================================================

-- -------------------------------------------------------
-- PRODUCTS (10 rows)
-- Categories: shoes, coats, dresses, shirts, bags
-- -------------------------------------------------------
INSERT INTO `products`
  (`product_name`, `product_category`, `product_description`, `product_image`, `product_image2`, `product_image3`, `product_image4`, `product_price`, `product_special_offer`, `product_color`)
VALUES
  ('Classic White Sneakers',  'shoes',   'Timeless white sneakers with a soft inner lining and lightweight sole, perfect for everyday wear.',
   'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
   'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&q=80',
   'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600&q=80',
   'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=600&q=80',
   89.99, 0, 'white'),

  ('Black Running Shoes',     'shoes',   'Lightweight black running shoes with superior grip and breathable mesh upper for daily training.',
   'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=600&q=80',
   'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600&q=80',
   'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&q=80',
   'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
   119.99, 10, 'black'),

  ('Wool Overcoat',           'coats',   'Premium wool overcoat with a tailored silhouette, ideal for cold weather and formal occasions.',
   'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80',
   'https://images.unsplash.com/photo-1548126032-079a0fb0099d?w=600&q=80',
   'https://images.unsplash.com/photo-1520975916090-b7911b937f12?w=600&q=80',
   'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80',
   199.99, 0, 'grey'),

  ('Puffer Winter Jacket',    'coats',   'Warm puffer jacket filled with synthetic insulation. Water-resistant shell, great for outdoor use.',
   'https://images.unsplash.com/photo-1547949003-9792a18a2601?w=600&q=80',
   'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80',
   'https://images.unsplash.com/photo-1548126032-079a0fb0099d?w=600&q=80',
   'https://images.unsplash.com/photo-1547949003-9792a18a2601?w=600&q=80',
   149.99, 15, 'black'),

  ('Floral Summer Dress',     'dresses', 'Lightweight floral dress made from breathable fabric. Perfect for warm days and casual outings.',
   'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&q=80',
   'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=600&q=80',
   'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=600&q=80',
   'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&q=80',
   69.99, 0, 'multicolor'),

  ('Elegant Evening Dress',   'dresses', 'Sophisticated evening dress with a flowing silhouette. Ideal for formal events and occasions.',
   'https://images.unsplash.com/photo-1566479179817-cee33c78d94a?w=600&q=80',
   'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80',
   'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&q=80',
   'https://images.unsplash.com/photo-1566479179817-cee33c78d94a?w=600&q=80',
   129.99, 5, 'black'),

  ('White Cotton T-Shirt',    'shirts',  'Clean essential white t-shirt in 100% organic cotton. Relaxed fit, works with any outfit.',
   'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=600&q=80',
   'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&q=80',
   'https://images.unsplash.com/photo-1532543949936-4a8a1e17ab2c?w=600&q=80',
   'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=600&q=80',
   34.99, 0, 'white'),

  ('Navy Polo Shirt',         'shirts',  'Classic navy polo shirt crafted from pique cotton. Smart casual style suitable for all occasions.',
   'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80',
   'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&q=80',
   'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&q=80',
   'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80',
   49.99, 0, 'navy'),

  ('Leather Shoulder Bag',    'bags',    'Genuine leather shoulder bag with multiple compartments, gold-tone hardware and adjustable strap.',
   'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80',
   'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
   'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80',
   'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80',
   159.99, 0, 'brown'),

  ('Mini Crossbody Bag',      'bags',    'Compact crossbody bag in vegan leather with a zip closure. Fits your essentials with style.',
   'https://images.unsplash.com/photo-1575844264771-892081089af5?w=600&q=80',
   'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?w=600&q=80',
   'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80',
   'https://images.unsplash.com/photo-1575844264771-892081089af5?w=600&q=80',
   79.99, 20, 'black');


-- -------------------------------------------------------
-- USERS (10 rows)
-- Passwords are bcrypt hashes of "password123"
-- -------------------------------------------------------
INSERT INTO `users` (`user_name`, `user_email`, `user_password`)
VALUES
  ('Alice Johnson',  'alice@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Bob Smith',      'bob@example.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Carol White',    'carol@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('David Brown',    'david@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Eva Green',      'eva@example.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Frank Lee',      'frank@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Grace Kim',      'grace@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Henry Park',     'henry@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('Iris Chan',      'iris@example.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
  ('James Tan',      'james@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


-- -------------------------------------------------------
-- ORDERS (10 rows)
-- References user_id 1-10 from users table above
-- -------------------------------------------------------
INSERT INTO `orders` (`order_cost`, `order_status`, `user_id`, `user_phone`, `user_city`, `user_address`, `order_date`)
VALUES
  (89.99,  'delivered',  1, 12345678, 'Phnom Penh',   '123 Norodom Blvd',        '2025-01-05 10:30:00'),
  (269.98, 'delivered',  2, 23456789, 'Siem Reap',    '45 Pub Street',           '2025-01-12 14:00:00'),
  (199.99, 'shipped',    3, 34567890, 'Battambang',   '78 Riverside Road',       '2025-02-03 09:15:00'),
  (69.99,  'shipped',    4, 45678901, 'Kampot',       '12 Garden Lane',          '2025-02-18 11:45:00'),
  (129.99, 'on_hold',    5, 56789012, 'Kep',          '9 Beach Avenue',          '2025-03-01 16:20:00'),
  (34.99,  'delivered',  6, 67890123, 'Phnom Penh',   '56 Monivong Blvd',        '2025-03-14 08:00:00'),
  (319.98, 'on_hold',    7, 78901234, 'Sihanoukville','3 Ochheuteal Rd',         '2025-04-02 13:30:00'),
  (49.99,  'cancelled',  8, 89012345, 'Kampong Cham', '22 Central Market Rd',    '2025-04-10 17:00:00'),
  (159.99, 'shipped',    9, 90123456, 'Phnom Penh',   '101 Russian Blvd',        '2025-04-15 10:00:00'),
  (79.99,  'delivered', 10, 11223344, 'Siem Reap',    '66 Angkor Way',           '2025-04-20 12:00:00');


-- -------------------------------------------------------
-- ORDER_ITEMS (10 rows)
-- Maps orders to products
-- -------------------------------------------------------
INSERT INTO `order_items` (`order_id`, `product_id`, `product_name`, `product_image`, `user_id`, `order_date`)
VALUES
  (1,  '1',  'Classic White Sneakers',  'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',  1,  '2025-01-05 10:30:00'),
  (2,  '2',  'Black Running Shoes',     'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=600&q=80', 2,  '2025-01-12 14:00:00'),
  (2,  '3',  'Wool Overcoat',           'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80', 2,  '2025-01-12 14:00:00'),
  (3,  '3',  'Wool Overcoat',           'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600&q=80', 3,  '2025-02-03 09:15:00'),
  (4,  '5',  'Floral Summer Dress',     'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&q=80', 4,  '2025-02-18 11:45:00'),
  (5,  '6',  'Elegant Evening Dress',   'https://images.unsplash.com/photo-1566479179817-cee33c78d94a?w=600&q=80', 5,  '2025-03-01 16:20:00'),
  (6,  '7',  'White Cotton T-Shirt',    'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=600&q=80', 6,  '2025-03-14 08:00:00'),
  (7,  '9',  'Leather Shoulder Bag',    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80',   7,  '2025-04-02 13:30:00'),
  (9,  '9',  'Leather Shoulder Bag',    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80',   9,  '2025-04-15 10:00:00'),
  (10, '10', 'Mini Crossbody Bag',      'https://images.unsplash.com/photo-1575844264771-892081089af5?w=600&q=80', 10, '2025-04-20 12:00:00');


-- -------------------------------------------------------
-- PAYMENTS (10 rows)
-- Each maps to an order + user with a fake transaction ID
-- -------------------------------------------------------
INSERT INTO `payments` (`order_id`, `user_id`, `transaction_id`)
VALUES
  (1,  1,  'TXN-20250105-A1B2C3'),
  (2,  2,  'TXN-20250112-D4E5F6'),
  (3,  3,  'TXN-20250203-G7H8I9'),
  (4,  4,  'TXN-20250218-J1K2L3'),
  (5,  5,  'TXN-20250301-M4N5O6'),
  (6,  6,  'TXN-20250314-P7Q8R9'),
  (7,  7,  'TXN-20250402-S1T2U3'),
  (8,  8,  'TXN-20250410-V4W5X6'),
  (9,  9,  'TXN-20250415-Y7Z8A1'),
  (10, 10, 'TXN-20250420-B2C3D4');
