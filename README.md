# FoodFrenzy Project Documentation

## Overview
FoodFrenzy is a PHP-based web application for online food ordering. It allows users to browse meals, add them to a cart, place orders, view order history, and manage their profiles. Admins can manage meals, orders, users, and view statistics.

## Main Features
- User registration, login, and profile management
- Browse meals and add to cart
- Place orders and view order history
- Admin dashboard for managing meals, orders, users, and viewing stats
- CSRF protection and session-based authentication

## File-by-File Breakdown

### config/db.php
- Database connection setup using PDO.

### includes/
- `auth_check.php`: Ensures user is authenticated (and optionally admin).
- `header.php` / `footer.php`: Common HTML header/footer for all pages.
- `admin_header.php`: Header for admin pages.

### public/
- `index.php`: Homepage. Shows featured meals and navigation.
- `meals.php`: Lists all available meals. Allows adding to cart.
- `cart.php`: Handles cart logic (add/remove items, CSRF protection).
- `order_history.php`: Shows user's past orders. Handles order placement from cart.
- `order_detail.php`: Shows details for a specific order (items, status, etc.).
- `profile.php`: User profile page. Shows and allows editing user info.
- `update_profile.php`: Handles profile update logic (username, email, password).
- `update_profile_image.php`: Handles profile image upload.
- `reserve.php`: Allows users to reserve a meal (if implemented).
- `about.php`, `contact.php`, `terms.php`: Informational pages.
- `login.php`, `register.php`, `logout.php`: Authentication pages.

#### public/admin/
- `dashboard.php`: Admin dashboard overview.
- `manage_meals.php`: Add, edit, delete meals. Handles image uploads.
- `edit_meal.php`: Edit a specific meal.
- `manage_orders.php`: View, filter, and manage all orders.
- `manage_users.php`: View and manage users.
- `stats.php`: Shows statistics (users, meals, orders, sales, top meals, etc.).
- `about.php`: Admin about page.

### uploads/
- Stores uploaded meal images and profile images.
- `profiles/index.php`: Prevents directory listing.

### css/, js/
- Static assets (stylesheets, scripts).

## Main Flows

### User Flow
- Register/login
- Browse meals (`meals.php`)
- Add to cart (`cart.php`)
- Place order (`order_history.php`)
- View order details (`order_detail.php`)
- Manage profile (`profile.php`, `update_profile.php`)

### Admin Flow
- Login as admin
- Access dashboard (`admin/dashboard.php`)
- Manage meals (`admin/manage_meals.php`, `admin/edit_meal.php`)
- Manage orders (`admin/manage_orders.php`)
- View stats (`admin/stats.php`)
- Manage users (`admin/manage_users.php`)

### Security
- Session-based authentication
- CSRF protection on forms
- File upload validation

## Database
- `users`: Stores user info (id, username, email, password, is_admin, profile_image)
- `meals`: Stores meal info (id, name, description, price, image)
- `orders`: Stores orders (id, user_id, total_price, order_date, status)
- `order_items`: Stores items in each order (order_id, meal_id, quantity, price)

## Notes
- All admin pages require `is_admin` session check.
- All user actions are protected by CSRF tokens.
- Images are stored in `/uploads` and referenced by filename in the database.

---

For more details, see the code comments in each file.
