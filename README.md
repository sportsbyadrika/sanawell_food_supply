# Dew Route Product Delivery

Dew Route Product Delivery is a SaaS-based product delivery management system built with **core PHP**, **MySQL**, and **Tailwind CSS** using an MVC architecture.

## ✅ Features
- MVC structure with clean routing.
- Role-based dashboards:
  - Super Admin
  - Supply Agency Admin
  - Office Staff
  - Driver
- Secure authentication using PHP sessions + `password_hash()`.
- CSRF protection.
- Rate card management by product and user type.
- Responsive Tailwind UI.

---

## 📂 Project Structure
```
/ (root)
│── index.php
│── config/
│── controllers/
│── models/
│── views/
│── helpers/
│── public/
│── database/
│── README.md
```

---

## ⚙️ Local Setup

1. **Clone the project**
```
git clone <repo_url>
cd sanawell_food_supply
```

2. **Create a MySQL database**
```
CREATE DATABASE sanawell_delivery;
```

3. **Run schema**
```
mysql -u root -p sanawell_delivery < database/schema.sql
```

4. **Update DB credentials**
Edit: `config/database.php`

5. **Run locally**
Use PHP built-in server:
```
php -S localhost:8000
```

Visit: `http://localhost:8000/index.php?route=login`

---

## 🔐 Default Roles
These roles are seeded in the database:
- Super Admin
- Supply Agency Admin
- Office Staff
- Driver

---

## ✅ Notes
- Tailwind CSS is loaded from CDN in `views/layouts/app.php`.
- Use prepared statements everywhere (PDO).
- Session timeout is configurable in `config/config.php`.

---

## 📌 Sample Test Login
Create a user manually in the database with the `super_admin` role to access the dashboard.

Example password hash:
```
<?php echo password_hash('password123', PASSWORD_DEFAULT); ?>
```

---

## 🧾 Database Schema
See: `database/schema.sql`

---

## ✅ Ready to extend
This project is a clean base to extend with:
- Delivery orders
- Dispatch assignments
- Driver tracking
- Reporting & analytics
