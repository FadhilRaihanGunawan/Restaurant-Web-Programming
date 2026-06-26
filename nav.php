<?php /* Shared nav — requires session_start() in the parent file */ ?>
<header>
    <nav>
        <div class="logo-search">
            <div class="logo">
                <a href="cooking-recipe-frontpage.php">
                    <img src="Tegar_s_Fancy_Recipe-removebg-preview.png" alt="Cooking Recipes Logo">
                </a>
            </div>
            <div class="search-bar">
                <form action="recipes.php" method="GET">
                    <input type="text" name="search" placeholder="Find a recipe or ingredient"
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="user-options">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="add_recipe.php">Add Recipe</a>
                    <a href="logout.php">Log Out</a>
                    <a href="dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a href="login.php">Log In</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="main-menu">
            <ul class="main-nav">
                <li>
                    <a href="recipes.php">Dinners</a>
                    <ul class="dropdown">
                        <li><a href="recipes.php?category=Chicken">Chicken</a></li>
                        <li><a href="recipes.php?category=Beef">Beef</a></li>
                        <li><a href="recipes.php?category=Vegetarian">Vegetarian</a></li>
                    </ul>
                </li>
                <li>
                    <a href="recipes.php">Meals</a>
                    <ul class="dropdown">
                        <li><a href="recipes.php?category=Breakfast">Breakfast</a></li>
                        <li><a href="recipes.php?category=Lunch">Lunch</a></li>
                        <li><a href="recipes.php?category=Dinner">Dinner</a></li>
                        <li><a href="recipes.php?category=Snack">Snack</a></li>
                    </ul>
                </li>
                <li>
                    <a href="recipes.php">Ingredients</a>
                    <ul class="dropdown">
                        <li><a href="recipes.php?search=vegetables">Vegetables</a></li>
                        <li><a href="recipes.php?search=fruits">Fruits</a></li>
                        <li><a href="recipes.php?search=meat">Meats</a></li>
                    </ul>
                </li>
                <li>
                    <a href="recipes.php">Occasions</a>
                    <ul class="dropdown">
                        <li><a href="recipes.php?search=christmas">Christmas</a></li>
                        <li><a href="recipes.php?search=thanksgiving">Thanksgiving</a></li>
                        <li><a href="recipes.php?search=easter">Easter</a></li>
                    </ul>
                </li>
                <li>
                    <a href="recipes.php">Cuisines</a>
                    <ul class="dropdown">
                        <li><a href="recipes.php?category=Italian">Italian</a></li>
                        <li><a href="recipes.php?category=Chinese">Chinese</a></li>
                        <li><a href="recipes.php?category=Mexican">Mexican</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
