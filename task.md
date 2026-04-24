# Task - COMPLETE

## All Steps Done ✅
- [x] MySQL running, php_project DB seeded
- [x] server/connection.php - fixed to phpuser:phppass123:3306
- [x] contact_messages table created
- [x] server/products.php - getProductFromDB, getProductsFromDB, dbRowToProduct
- [x] server/auth.php - authLogin, authRegister, authChangePassword  
- [x] server/orders.php - createOrder, getUserOrders
- [x] includes/functions.php - auth helpers, DB cart, statusBadge
- [x] logout.php
- [x] includes/header.php - auth-aware (shows username + different icons)
- [x] login.php - DB auth, 302 redirect on success ✓
- [x] register.php - DB insert, auto-login ✓
- [x] account.php - real user data, real orders, change password, sidebar nav
- [x] singleproduct.php - loads from DB
- [x] checkout.php - saves order + order_items to DB, success screen ✓
- [x] contact.php - saves to contact_messages table ✓
- [x] index.php - category filter tabs (all/shoes/coats/dresses/shirts/bags) ✓
- [x] cart.php - DB-driven cart items
- [x] assets/css/style.css - full moderate refresh

## Verified
- Login 302 ✓
- Wrong password shows error ✓  
- Register creates user + 302 ✓
- Account shows real name (Alice Johnson) ✓
- Orders table shows real orders ✓
- Unauthenticated account → 302 to login ✓
- Checkout saves order to DB (order #11, #12) ✓
- Checkout success screen renders ✓
- Contact saves message to DB ✓
- DB user passwords updated (all: password123) ✓
