<?php
session_start();
require 'config.php';

/* Latest public recipes from DB */
$latest = $conn->query("SELECT * FROM recipes WHERE is_public = 1 ORDER BY id DESC LIMIT 6");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooking Recipe Information System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <?php require 'nav.php'; ?>

    <main>
        <div class="hero-section">
            <h1>Welcome to the Cooking Recipe Information System</h1>
            <p>Find and share everyday cooking inspirations</p>
        </div>

        <div class="content">
            <!-- Featured (hardcoded showcase) -->
            <section class="recommended-recipes">
                <h2>Featured Recipes</h2>
                <div class="recipe-grid">
                    <a href="recipe-detail.php?featured=1" class="recipe-card-link">
                        <div class="recipe-card">
                            <img src="https://recipes.net/wp-content/uploads/portal_files/recipes_net_posts/2021-03/chicken-salad-with-mango-avocado-salsa-recipe-1024x683.jpg" alt="Chicken Avocado Mango Salad">
                            <div class="recipe-card-body">
                                <h2>Chicken, Avocado &amp; Mango Salad</h2>
                                <p>Fresh and delicious salad with chicken, avocado, and mango.</p>
                            </div>
                        </div>
                    </a>
                    <a href="recipe-detail.php?featured=2" class="recipe-card-link">
                        <div class="recipe-card">
                            <img src="https://thestarvingchefblog.com/wp-content/uploads/2022/05/roast-beef-roll-ups-recipe.jpg" alt="Roast Beef Roll-Ups">
                            <div class="recipe-card-body">
                                <h2>Roast Beef Horseradish Roll-Ups</h2>
                                <p>Flavorful roast beef roll-ups with horseradish sauce.</p>
                            </div>
                        </div>
                    </a>
                    <a href="recipe-detail.php?featured=3" class="recipe-card-link">
                        <div class="recipe-card">
                            <img src="https://www.seriouseats.com/thmb/_Bm7MuoZztRNzPjQv2W7ACWC7OQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/20220217-pressure-cooker-mushroom-risotto-mariel-delacruz-082-cd5cc0b0b13f4239aeab86d88df6a1b0.jpg" alt="Mushroom Risotto">
                            <div class="recipe-card-body">
                                <h2>Mushroom Risotto</h2>
                                <p>Tasty authentic mushroom risotto from the heart of Italy.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Latest public recipes from DB -->
            <section class="latest-recipes" style="margin-top: 48px;">
                <h2>Latest Recipes</h2>
                <?php if ($latest && $latest->num_rows > 0): ?>
                    <div class="recipe-grid">
                        <?php while ($recipe = $latest->fetch_assoc()): ?>
                            <a href="recipe-detail.php?id=<?= $recipe['id'] ?>" class="recipe-card-link">
                                <div class="recipe-card">
                                    <?php if (!empty($recipe['image_path']) && file_exists($recipe['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>">
                                    <?php else: ?>
                                        <div class="recipe-card-placeholder-img">
                                            <span><?= htmlspecialchars(mb_substr($recipe['title'], 0, 1)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="recipe-card-body">
                                        <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                                        <div class="recipe-card-meta">
                                            <span><?= (int)$recipe['cooking_time'] ?> min</span>
                                            <span><?= htmlspecialchars($recipe['difficulty_level']) ?></span>
                                            <span><?= htmlspecialchars($recipe['category']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>No community recipes yet. <a href="register.php">Register</a> to be the first!</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cooking Recipe Information System. All rights reserved.</p>
    </footer>
    <script src="recipe-system-JSS.js"></script>
</body>
</html>
