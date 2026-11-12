<div align="center">

# 🍔 FoodNest

### Hungry? Order in a few clicks.

A full-stack online food ordering website built with **PHP**, **MySQL**, **HTML/CSS** and **JavaScript**, with a separate, protected **admin panel** for managing the menu and monitoring orders.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)
![XAMPP](https://img.shields.io/badge/Runs%20on-XAMPP-FB7A24?logo=xampp&logoColor=white)

</div>

---

## 📖 About the Project

**FoodNest** is an e-commerce web application for ordering food online. Customers can browse the menu by category, add items to a cart, and place a delivery order. Every order instantly appears in the admin panel, where the restaurant owner can update its status from *Pending* all the way to *Delivered*.



## ✨ Features

### 👤 Customer Side
| Feature | Description |
|---|---|
| 🔐 **Registration & Login** | Secure sign-up and sign-in with hashed passwords |
| 🍽️ **Browse Menu** | Food grouped into categories (Burgers, Pizza, Fried Rice, Drinks, Desserts, Snacks) |
| 🔎 **Item Details** | Dedicated page for each dish with photo, description and price |
| 🛒 **Shopping Cart** | Add items, change quantities, remove items or clear the cart |
| 📦 **Checkout** | Delivery address, phone number and optional order note |
| ✅ **Order Confirmation** | A summary page after the order is placed |
| 📜 **Order History** | "My Orders" page to track the status of every past order |

### 🛡️ Admin Panel
| Feature | Description |
|---|---|
| 🔑 **Separate Admin Login** | Independent login at `/admin/login.php` |
| 📊 **Dashboard** | Quick overview of the store |
| 🍕 **Menu Management** | Add, edit and delete food items |
| 🖼️ **Image Upload** | Upload a photo for every menu item |
| 🗂️ **Category Management** | Create and manage food categories |
| 📋 **Order Monitoring** | View all orders, filter by status and update progress |
| 👥 **Customer List** | See all registered customers |

---

## 🔄 Order Flow

```
 Register / Login  →  Browse Menu  →  Add to Cart  →  Checkout  →  Order Placed
                                                                       │
                                                                       ▼
      Admin sees order  →  Pending → Confirmed → Preparing → Delivered
```

Order statuses: `pending` · `confirmed` · `preparing` · `delivered` · `cancelled`

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Frontend** | HTML5, CSS3 (custom theme), Vanilla JavaScript |
| **Backend** | PHP (procedural + MySQLi) |
| **Database** | MySQL / MariaDB |
| **Server** | Apache (XAMPP) |
| **Font** | Nunito (Google Fonts) |

---

## 📁 Project Structure

```
foodnest/
├── index.php              # Home page
├── menu.php               # Menu with category filter
├── item.php               # Single item details
├── cart.php               # Shopping cart
├── checkout.php           # Checkout form
├── order_placed.php       # Order confirmation
├── my_orders.php          # Customer order history
├── register.php           # User registration
├── login.php              # User login
├── logout.php             # User logout
├── database.sql           # ⭐ Database schema + sample data
│
├── admin/
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   ├── menu_items.php     # List / delete menu items
│   ├── add_item.php       # Add item (with image upload)
│   ├── edit_item.php      # Edit item
│   ├── categories.php     # Manage categories
│   ├── orders.php         # Monitor & update orders
│   ├── customers.php      # Registered customers
│   ├── header.php / footer.php
│   └── logout.php
│
├── includes/
│   ├── db.php             # Database connection
│   ├── functions.php      # Session & auth helpers
│   └── navbar.php         # Global navigation bar
│
└── assets/
    ├── css/style.css      # Warm red + yellow theme
    ├── js/main.js         # Client-side validation & UI helpers
    └── uploads/           # Uploaded food images
```

---

## 🗄️ Database Design

**Database name:** `foodnest_db`

| Table | Purpose |
|---|---|
| `admins` | Admin accounts (username + hashed password) |
| `users` | Customer accounts |
| `categories` | Food categories with an emoji icon |
| `menu_items` | Food items — name, description, price, image, availability |
| `orders` | One row per order — customer, total, address, phone, status, note |
| `order_items` | Items belonging to each order (with price at time of purchase) |

```
users ──< orders ──< order_items >── menu_items >── categories
```

The `database.sql` file creates the database, all tables and seeds **6 categories** and **14 sample menu items**.

---

## 🚀 Installation & Setup

### Requirements
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 7.4 or newer)

### Steps

**1️⃣ Copy the project**
Extract the zip and place the `foodnest` folder inside:
```
C:\xampp\htdocs\foodnest
```

**2️⃣ Start the servers**
Open the XAMPP Control Panel and start **Apache** and **MySQL**.

**3️⃣ Import the database**
1. Open `http://localhost/phpmyadmin`
2. Click the **Import** tab
3. Choose `database.sql` → click **Go**

**4️⃣ Check the DB settings** *(only if you changed XAMPP defaults)*
Edit `includes/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodnest_db');
```

**5️⃣ Run the website**

| Page | URL |
|---|---|
| 🏠 Website | http://localhost/foodnest/ |
| 🛡️ Admin Panel | http://localhost/foodnest/admin/login.php |

> ⚠️ The project must live in a folder named **`foodnest`**, because internal links use the `/foodnest/` path.

---

## 🔑 Demo Credentials

| Role | Username / Email | Password |
|---|---|---|
| **Admin** | `admin` | `admin123` |
| **Customer** | Register a new account from the Sign Up page | — |

---

## 🔒 Security & Validation

- ✅ **Client-side validation** (JavaScript) on registration, login and forms
- ✅ **Server-side validation** (PHP) on registration, login, checkout and admin forms
- ✅ Passwords stored using **`password_hash()`** / verified with **`password_verify()`**
- ✅ **Prepared statements** (MySQLi) to prevent SQL injection
- ✅ Output escaped with **`htmlspecialchars()`** to prevent XSS
- ✅ **PHP sessions** keep users and admins logged in
- ✅ **Admin pages are guarded** — guests and regular customers are redirected to the admin login
- ✅ **Safe image upload** — only JPG / PNG / GIF / WEBP allowed, max 2 MB, files renamed with a unique ID

### Validation Rules
| Field | Rule |
|---|---|
| Full name | At least 3 characters |
| Email | Valid email format, must be unique |
| Password | At least 6 characters, must match confirmation |
| Phone | Exactly 11 digits |
| Delivery address | At least 8 characters |
| Item price | Greater than 0 |

---

## 🎨 Design

A warm, appetite-friendly theme built with custom CSS variables:

| Color | Hex | Use |
|---|---|---|
| 🔴 Red | `#e63946` | Primary buttons, prices, accents |
| 🟡 Yellow | `#ffd166` | Highlights |
| 🌑 Dark Navy | `#1a1a2e` | Navbar, admin panel |
| 🍑 Cream | `#fff5f0` | Page background |

Currency is shown in Bangladeshi Taka (**৳**).

---

## 🔮 Future Improvements

- Online payment gateway integration (bKash / Nagad / card)
- Live order tracking and email notifications
- Ratings and reviews for dishes
- Coupon codes and discounts
- Search bar and price filters on the menu
- Admin analytics charts (daily sales, best sellers)

---

## 👨‍💻 Author

**Md Shahadat Hossen**
CSE, Varendra University —

---
