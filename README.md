# Tegar's Fancy Recipe

A full-stack recipe management website built with **PHP**, **MySQL**, **CSS**, and **JavaScript** as a Web Programming coursework project at Universiti Teknologi Malaysia (UTM).

---

## Overview

Tegar's Fancy Recipe is a dynamic web application that lets users register, log in, and manage their own cooking recipes. Visitors can browse and search public recipes without an account; registered users get a personal dashboard to add, edit, delete, and toggle the visibility of their recipes.

---

## Features

- **User Authentication** — Register and login with hashed passwords and session management
- **Recipe Dashboard** — Personal dashboard showing your own recipes (public & private) and the latest community recipes
- **Add Recipe** — Upload a photo, pick multiple categories, set difficulty and cooking time, write ingredients and instructions
- **Edit Recipe** — Update any field or replace the photo; changes reflect immediately on the detail page
- **Delete Recipe** — Permanently removes the recipe and its uploaded image file
- **Public / Private toggle** — Mark a recipe Private so only you can see it; Public recipes appear to everyone
- **Recipe Detail Page** — Full ingredients list, step-by-step instructions, and meta badges (time, difficulty, categories)
- **Browse & Filter** — Category pills and a search bar filter all public recipes in real time
- **Functional Nav Categories** — Dinners, Meals, Cuisines, etc. link to filtered recipe lists
- **Image Uploads** — Drag-and-drop or click-to-upload with live preview (JPG, PNG, WebP, GIF — max 5 MB)
- **Responsive Design** — Works on desktop, tablet, and mobile

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3 (custom design system), JavaScript (vanilla) |
| Backend | PHP 8+ |
| Database | MySQL / MariaDB |
| Server | Apache via XAMPP |
| Fonts | Google Fonts (Playfair Display, Inter) |

---

## Project Structure

```
Restaurant-Web-Programming/
│
├── config.php                      # Database connection + auto-migration
├── nav.php                         # Shared navigation partial (included by all pages)
│
├── cooking-recipe-frontpage.php    # Public homepage — featured + latest public recipes
├── recipes.php                     # Browse / search / filter all public recipes
├── recipe-detail.php               # Single recipe view (featured or DB)
│
├── dashboard.php                   # Logged-in user dashboard
├── add_recipe.php                  # Add a new recipe (form + handler)
├── edit_recipe.php                 # Edit an existing recipe (form + handler)
├── delete_recipe.php               # Delete handler (POST only)
│
├── login.php                       # Login page
├── register.php                    # Registration page
├── logout.php                      # Session destroy + redirect
│
├── cooking-recipe-CSS.css          # Main stylesheet (shared across all pages)
├── add_recipe.css                  # Legacy add-recipe styles (kept for compatibility)
├── dashboard.css                   # Legacy dashboard styles
├── login.css                       # Login page styles
├── register.css                    # Registration page styles
│
├── recipe-system-JSS.js            # Client-side scripts (feedback form guard)
│
├── uploads/                        # User-uploaded recipe images (auto-created)
└── Tegar_s_Fancy_Recipe-removebg-preview.png   # Site logo
```

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (includes Apache + MySQL + PHP)
- PHP 7.4 or higher (PHP 8+ recommended)
- A modern web browser

---

## Setup Guide

### 1. Install XAMPP

Download and install XAMPP from https://www.apachefriends.org/. During installation, make sure **Apache** and **MySQL** are selected.

### 2. Place the project files

Copy the project files into XAMPP's web root and give the folder a short name of your choice.
**The folder name becomes your URL — pick anything you like**, for example `fancy-recipe`:

```
C:\xampp\htdocs\fancy-recipe\
```

After placing the files, the folder should look like this:

```
C:\xampp\htdocs\fancy-recipe\
    ├── config.php
    ├── cooking-recipe-frontpage.php
    ├── nav.php
    ├── dashboard.php
    └── ... (all other .php, .css, .js files)
```

> **Downloaded as a ZIP from GitHub?** GitHub ZIPs extract into a nested folder like
> `Restaurant-Web-Programming-main\Restaurant-Web-Programming-main\`.
> Open that inner folder, copy all the files inside it, and paste them directly into
> `C:\xampp\htdocs\fancy-recipe\` — do not copy the outer folder itself.

### 3. Start XAMPP services

Open the **XAMPP Control Panel** and click **Start** next to both:
- Apache
- MySQL

Both status indicators should turn green before proceeding.

### 4. Create the database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **New** in the left sidebar
3. Enter the database name: `recipe_db`
4. Set collation to `utf8mb4_general_ci`
5. Click **Create**

### 5. Create the database tables

In phpMyAdmin, select the `recipe_db` database, click the **SQL** tab, paste the following, and click **Go**:

```sql
-- Users table
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Recipes table
CREATE TABLE IF NOT EXISTS recipes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(255)  NOT NULL,
    ingredients     TEXT          NOT NULL,
    instructions    TEXT          NOT NULL,
    cooking_time    INT           NOT NULL,
    difficulty_level VARCHAR(20)  NOT NULL,
    category        VARCHAR(255)  NOT NULL,
    user_id         INT           NOT NULL,
    image_path      VARCHAR(255)  DEFAULT NULL,
    is_public       TINYINT(1)    NOT NULL DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

> **Note:** The `image_path` and `is_public` columns are also added automatically by `config.php` on first run if they are missing — so if you are migrating an existing `recipe_db` you do not need to recreate the table.

### 6. Verify the database connection

Open `config.php` and confirm the credentials match your XAMPP setup (the defaults work for a standard XAMPP install):

```php
$servername = "localhost";
$username   = "root";
$password   = "";          // blank by default in XAMPP
$dbname     = "recipe_db";
```

If you have set a MySQL root password in XAMPP, enter it in the `$password` field.

### 7. Create the uploads folder (optional)

The `uploads/` directory is created automatically when the first image is uploaded. If you want to create it manually, add an empty `uploads` folder inside your project folder:

```
C:\xampp\htdocs\fancy-recipe\uploads\
```

Make sure the folder is writable by the web server.

---

## Running the Website

With XAMPP running, open your browser and navigate to:

```
http://localhost/<your-folder-name>/cooking-recipe-frontpage.php
```

Replace `<your-folder-name>` with whatever you named the folder in `htdocs`.
For example, if you named it `fancy-recipe`:

```
http://localhost/fancy-recipe/cooking-recipe-frontpage.php
```

### Quick page reference

All pages are relative to your folder root. Replace `fancy-recipe` with your actual folder name.

| Page | Full URL example | Login required |
|---|---|---|
| Homepage | `http://localhost/fancy-recipe/cooking-recipe-frontpage.php` | No |
| Browse recipes | `http://localhost/fancy-recipe/recipes.php` | No |
| Filter by category | `http://localhost/fancy-recipe/recipes.php?category=Chicken` | No |
| Search | `http://localhost/fancy-recipe/recipes.php?search=pasta` | No |
| Recipe detail (DB) | `http://localhost/fancy-recipe/recipe-detail.php?id=1` | No (public) |
| Recipe detail (featured) | `http://localhost/fancy-recipe/recipe-detail.php?featured=1` | No |
| Register | `http://localhost/fancy-recipe/register.php` | No |
| Login | `http://localhost/fancy-recipe/login.php` | No |
| Dashboard | `http://localhost/fancy-recipe/dashboard.php` | Yes |
| Add recipe | `http://localhost/fancy-recipe/add_recipe.php` | Yes |
| Edit recipe | `http://localhost/fancy-recipe/edit_recipe.php?id=1` | Yes (owner only) |
| Logout | `http://localhost/fancy-recipe/logout.php` | Yes |

> **Tip:** Bookmark the homepage URL after your first visit so you don't have to type it every time.

### Quick page reference (short form — relative to your root)

| URL | Description | Login required |
|---|---|---|
| `cooking-recipe-frontpage.php` | Homepage with featured & latest public recipes | No |
| `recipes.php` | Browse and search all public recipes | No |
| `recipes.php?category=Chicken` | Filter recipes by category | No |
| `recipes.php?search=pasta` | Search recipes by keyword | No |
| `recipe-detail.php?id=X` | View a recipe from the database | No (public recipes) |
| `recipe-detail.php?featured=1` | View a featured/hardcoded recipe | No |
| `register.php` | Create a new account | No |
| `login.php` | Log in to your account | No |
| `dashboard.php` | Your personal recipe dashboard | Yes |
| `add_recipe.php` | Add a new recipe | Yes |
| `edit_recipe.php?id=X` | Edit one of your recipes | Yes (owner only) |
| `logout.php` | Log out and end session | Yes |

---

## How to Use

### As a visitor (no account)
1. Go to the homepage to see featured and latest recipes
2. Click any recipe card to view its full details
3. Use the nav bar categories or search bar to find recipes by type or keyword

### As a registered user
1. Click **Register** and fill in your username, email, and password
2. Click **Log In** with your credentials
3. You will be taken to your **Dashboard**
4. Click **Add Recipe** to create a new recipe:
   - Upload a photo (drag & drop or click)
   - Fill in the title, cooking time, and difficulty
   - Tick one or more categories (Breakfast, Lunch, Chicken, Italian, etc.)
   - Toggle **Public / Private** — Private recipes are only visible to you
   - Write your ingredients (one per line) and step-by-step instructions
5. Click a recipe card in **My Recipes** to view it, or use the **Edit** / **Delete** buttons below each card
6. Click **Log Out** when done

---
