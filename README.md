# 🍽️ Tegar's Fancy Recipe

A full-stack recipe management website built with **PHP**, **MySQL**, **CSS**, and **JavaScript** as a Web Programming coursework project at Universiti Teknologi Malaysia (UTM).

---

## 📌 Overview

Tegar's Fancy Recipe is a dynamic web application that allows users to register, log in, and manage their own cooking recipes. It features a clean front-facing recipe gallery alongside a personal dashboard for adding and viewing saved recipes.

---

## ✨ Features

- 🔐 **User Authentication** — Register and login system with session management
- 📋 **Recipe Dashboard** — Personal dashboard to manage your saved recipes
- ➕ **Add Recipe** — Submit new recipes with details via a structured form
- 🌐 **Public Recipe Gallery** — Browse recipes on the front-facing page without login
- 🚪 **Logout** — Secure session termination

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Server | Apache (XAMPP) |

---

## 🗂️ Project Structure

```
Restaurant-Web-Programming/
├── config.php                      # Database connection
├── cooking-recipe-frontpage.php    # Public recipe listing page
├── cooking-recipe-CSS.css          # Front page styles
├── dashboard.php                   # User dashboard
├── dashboard.css                   # Dashboard styles
├── add_recipe.php                  # Add new recipe form
├── add_recipe.css                  # Add recipe styles
├── login.php                       # Login page
├── login.css                       # Login styles
├── register.php                    # Registration page
├── register.css                    # Registration styles
├── logout.php                      # Session logout handler
└── recipe-system-JSS.js            # Client-side scripts
```

---

## ⚙️ Getting Started

### Requirements

- PHP 7.4+
- MySQL
- Apache server (XAMPP recommended)

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/FadhilRaihanGunawan/Restaurant-Web-Programming.git
   ```

2. **Move to your server directory**
   ```bash
   # For XAMPP on Windows
   move Restaurant-Web-Programming C:/xampp/htdocs/
   ```

3. **Create the database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Create a new database (e.g., `recipe_db`)
   - Import the SQL schema if provided, or configure `config.php` with your credentials

4. **Configure database connection**
   ```php
   // config.php
   $host = "localhost";
   $user = "root";
   $password = "";
   $database = "recipe_db";
   ```

5. **Run the app**
   - Start Apache and MySQL in XAMPP
   - Navigate to `http://localhost/Restaurant-Web-Programming/cooking-recipe-frontpage.php`

---

## 👨‍💻 Author

**Fadhil Raihan Gunawan**  
B.Sc. (Hons.) Computer Science (Software Engineering) · UTM  
[fadhilraihangunawan@gmail.com](mailto:fadhilraihangunawan@gmail.com)
