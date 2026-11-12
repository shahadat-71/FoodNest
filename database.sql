

CREATE DATABASE IF NOT EXISTS foodnest_db;
USE foodnest_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(10) DEFAULT '🍽️'
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    delivery_address TEXT NOT NULL,
    phone VARCHAR(20),
    status ENUM('pending','confirmed','preparing','delivered','cancelled') DEFAULT 'pending',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (item_id) REFERENCES menu_items(id)
);

-- Default admin (password: admin123)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


INSERT INTO categories (name, icon) VALUES
('Burgers', '🍔'),
('Pizza', '🍕'),
('Fried Rice', '🍚'),
('Drinks', '🥤'),
('Desserts', '🍰'),
('Snacks', '🍟');


INSERT INTO menu_items (category_id, name, description, price, is_available) VALUES
(1, 'Classic Beef Burger', 'Juicy beef patty with lettuce, tomato, cheese and special sauce.', 180.00, 1),
(1, 'Chicken Burger', 'Crispy fried chicken with mayo and coleslaw in a toasted bun.', 150.00, 1),
(1, 'Double Smash Burger', 'Double beef patty, double cheese, caramelized onions.', 250.00, 1),
(2, 'Margherita Pizza', 'Classic tomato sauce, mozzarella and fresh basil. 8 inch.', 320.00, 1),
(2, 'Chicken BBQ Pizza', 'BBQ sauce, grilled chicken, onion, capsicum. 8 inch.', 380.00, 1),
(3, 'Chicken Fried Rice', 'Wok-tossed rice with chicken, egg, vegetables and soy sauce.', 160.00, 1),
(3, 'Egg Fried Rice', 'Classic fried rice with scrambled egg and spring onion.', 120.00, 1),
(4, 'Fresh Lemonade', 'Freshly squeezed lemon with mint and sugar. Chilled.', 60.00, 1),
(4, 'Mango Lassi', 'Thick and creamy mango yogurt drink.', 80.00, 1),
(4, 'Soft Drink (Can)', 'Coke, 7UP or Sprite. 250ml can.', 50.00, 1),
(5, 'Chocolate Lava Cake', 'Warm chocolate cake with molten center. Served with ice cream.', 140.00, 1),
(5, 'Ice Cream (2 Scoop)', 'Choice of vanilla, chocolate or strawberry.', 90.00, 1),
(6, 'French Fries', 'Crispy golden fries with ketchup. Regular size.', 80.00, 1),
(6, 'Onion Rings', 'Beer-battered crispy onion rings with dipping sauce.', 100.00, 1);
