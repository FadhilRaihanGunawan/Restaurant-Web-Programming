<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cooking Recipe Information System</title>
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo-search">
                <div class="logo">
                    <img src="Tegar_s_Fancy_Recipe-removebg-preview.png" alt="Cooking Recipes Logo">
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Find a recipe or ingredient">
                    <button>Search</button>
                </div>
                <div class="user-options">
                    <a href="add_recipe.php">Add Recipe</a>
                    <a href="logout.php">Log Out</a>
                    <a href="dashboard.php">Dashboard</a>
                </div>
            </div>
            <div class="main-menu">
                <ul class="main-nav">
                    <li>
                        <a href="#">Dinners</a>
                        <ul class="dropdown">
                            <li><a href="#">Chicken</a></li>
                            <li><a href="#">Beef</a></li>
                            <li><a href="#">Vegetarian</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Meals</a>
                        <ul class="dropdown">
                            <li><a href="#">Breakfast</a></li>
                            <li><a href="#">Lunch</a></li>
                            <li><a href="#">Dinner</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Ingredients</a>
                        <ul class="dropdown">
                            <li><a href="#">Vegetables</a></li>
                            <li><a href="#">Fruits</a></li>
                            <li><a href="#">Meats</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Occasions</a>
                        <ul class="dropdown">
                            <li><a href="#">Christmas</a></li>
                            <li><a href="#">Thanksgiving</a></li>
                            <li><a href="#">Easter</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Cuisines</a>
                        <ul class="dropdown">
                            <li><a href="#">Italian</a></li>
                            <li><a href="#">Chinese</a></li>
                            <li><a href="#">Mexican</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="hero-section">
            <h1>Welcome to the Cooking Recipe Information System</h1>
            <p>Find and share everyday cooking inspirations</p>
        </div>
        <div class="content">
            <section class="recommended-recipes">
                <h2>Featured Recipes</h2>
                <div class="recipe-grid">
                <div class="recipe-card">
                    <img src="https://recipes.net/wp-content/uploads/portal_files/recipes_net_posts/2021-03/chicken-salad-with-mango-avocado-salsa-recipe-1024x683.jpg" alt="Recipe 1">
                    <h2>Chicken, Avocado and Mango Salad</h2>
                    <p>Fresh and delicious salad with chicken, avocado, and mango.</p>
                </div>
                <div class="recipe-card">
                    <img src="https://thestarvingchefblog.com/wp-content/uploads/2022/05/roast-beef-roll-ups-recipe.jpg" alt="Recipe 2">
                    <h2>Roast Beef Horseradish Roll-Ups</h2>
                    <p>Flavorful roast beef roll-ups with horseradish sauce.</p>
                </div>
                <div class="recipe-card">
                    <img src="https://www.seriouseats.com/thmb/_Bm7MuoZztRNzPjQv2W7ACWC7OQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/20220217-pressure-cooker-mushroom-risotto-mariel-delacruz-082-cd5cc0b0b13f4239aeab86d88df6a1b0.jpg" alt="Recipe 3">
                    <h2>Mushroom Risotto</h2>
                    <p>Tasty authentic mushroom risotto from the central of Italy.</p>
                </div>
            </div>
            </section>
            <section class="latest-recipes">
                <h2>Latest Recipes</h2>
                <!-- Latest recipes content goes here -->
            </section>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Cooking Recipe Information System</p>
    </footer>
    <script src="recipe-system-JSS.js"></script>
</body>
</html>