<?php
session_start();

/* -------------------------------------------------------
   Featured (hardcoded) recipe data — used when ?featured=X
   ------------------------------------------------------- */
$featured = [
    1 => [
        'title'           => 'Chicken, Avocado & Mango Salad',
        'image'           => 'https://recipes.net/wp-content/uploads/portal_files/recipes_net_posts/2021-03/chicken-salad-with-mango-avocado-salsa-recipe-1024x683.jpg',
        'cooking_time'    => 25,
        'difficulty_level'=> 'Easy',
        'category'        => 'Lunch',
        'ingredients'     => "2 chicken breasts\n1 ripe avocado, diced\n1 ripe mango, diced\n2 cups mixed greens\n1 lime, juiced\n2 tbsp olive oil\nSalt and pepper to taste\nFresh cilantro",
        'instructions'    => "1. Season chicken breasts with salt and pepper. Cook in a skillet over medium heat for 6–7 minutes per side until cooked through. Let rest 5 minutes, then slice.\n\n2. In a large bowl, combine mixed greens, diced avocado, and diced mango.\n\n3. Whisk together lime juice and olive oil to make the dressing. Season with salt and pepper.\n\n4. Top the salad with sliced chicken and drizzle with dressing.\n\n5. Garnish with fresh cilantro and serve immediately.",
    ],
    2 => [
        'title'           => 'Roast Beef Horseradish Roll-Ups',
        'image'           => 'https://thestarvingchefblog.com/wp-content/uploads/2022/05/roast-beef-roll-ups-recipe.jpg',
        'cooking_time'    => 15,
        'difficulty_level'=> 'Easy',
        'category'        => 'Dinner',
        'ingredients'     => "200g thinly sliced roast beef\n4 tbsp cream cheese, softened\n2 tbsp prepared horseradish\n3 green onions, thinly sliced\n4 large flour tortillas\nHandful of baby spinach\nSalt and pepper",
        'instructions'    => "1. Mix cream cheese and horseradish together in a bowl until smooth. Season with salt and pepper.\n\n2. Lay a tortilla flat and spread a thin layer of the cream cheese mixture over it.\n\n3. Layer roast beef slices evenly over the cream cheese.\n\n4. Scatter green onions and baby spinach on top.\n\n5. Roll up the tortilla tightly, then slice into 1-inch rounds.\n\n6. Serve immediately or refrigerate for up to 2 hours.",
    ],
    3 => [
        'title'           => 'Mushroom Risotto',
        'image'           => 'https://www.seriouseats.com/thmb/_Bm7MuoZztRNzPjQv2W7ACWC7OQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/20220217-pressure-cooker-mushroom-risotto-mariel-delacruz-082-cd5cc0b0b13f4239aeab86d88df6a1b0.jpg',
        'cooking_time'    => 45,
        'difficulty_level'=> 'Medium',
        'category'        => 'Dinner',
        'ingredients'     => "1½ cups arborio rice\n300g mixed mushrooms, sliced\n1 onion, finely chopped\n3 garlic cloves, minced\n½ cup dry white wine\n4 cups warm chicken or vegetable broth\n3 tbsp butter\n½ cup grated parmesan\n2 tbsp olive oil\nSalt, pepper, fresh parsley",
        'instructions'    => "1. Heat broth in a saucepan over low heat and keep warm.\n\n2. In a large pan, heat olive oil over medium heat. Sauté onion for 3 minutes until softened. Add garlic and cook 1 more minute.\n\n3. Add mushrooms and cook for 5 minutes until golden. Set aside half for topping.\n\n4. Add arborio rice to the pan and toast for 2 minutes, stirring constantly.\n\n5. Pour in white wine and stir until absorbed.\n\n6. Add warm broth one ladle at a time, stirring continuously and waiting until each ladle is absorbed before adding the next. This takes about 20–25 minutes.\n\n7. Remove from heat. Stir in butter and parmesan. Season well.\n\n8. Top with reserved mushrooms and fresh parsley. Serve immediately.",
    ],
];

$recipe = null;
$source  = '';

/* --- featured recipe --- */
if (isset($_GET['featured'])) {
    $fid = (int) $_GET['featured'];
    if (isset($featured[$fid])) {
        $recipe = $featured[$fid];
        $source = 'featured';
    }
}

/* --- DB recipe --- */
if ($recipe === null && isset($_GET['id'])) {
    require 'config.php';
    $id   = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        /* Block private recipes from non-owners */
        if ($row['is_public'] == 1 || (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id'])) {
            $recipe = $row;
            $source = 'db';
        }
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recipe ? htmlspecialchars($recipe['title']) . ' — ' : '' ?>Cooking Recipe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <?php require 'nav.php'; ?>

    <main>
        <div class="recipe-detail-wrapper">
            <?php $back = isset($_SESSION['user_id']) ? 'dashboard.php' : 'cooking-recipe-frontpage.php'; ?>
            <a href="<?= $back ?>" class="recipe-back-link">&#8592; Back to Recipes</a>

            <?php if ($recipe): ?>

                <?php
                $hero_img = null;
                if ($source === 'featured' && !empty($recipe['image'])) {
                    $hero_img = $recipe['image'];
                } elseif ($source === 'db' && !empty($recipe['image_path']) && file_exists($recipe['image_path'])) {
                    $hero_img = $recipe['image_path'];
                }
                ?>
                <?php if ($hero_img): ?>
                    <img
                        src="<?= htmlspecialchars($hero_img) ?>"
                        alt="<?= htmlspecialchars($recipe['title']) ?>"
                        class="recipe-detail-hero"
                    >
                <?php endif; ?>

                <?php if ($source === 'db' && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['user_id']): ?>
                    <div style="text-align:right;margin-bottom:16px;">
                        <a href="edit_recipe.php?id=<?= $recipe['id'] ?>" class="btn btn-primary" style="display:inline-block;">&#9998; Edit Recipe</a>
                    </div>
                <?php endif; ?>

                <div class="recipe-detail-header">
                    <h1><?= htmlspecialchars($recipe['title']) ?></h1>
                    <div class="recipe-meta">
                        <?php if (!empty($recipe['cooking_time'])): ?>
                            <span class="recipe-meta-badge">&#9201; <?= (int)$recipe['cooking_time'] ?> min</span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['difficulty_level'])): ?>
                            <span class="recipe-meta-badge">&#9733; <?= htmlspecialchars($recipe['difficulty_level']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['category'])): ?>
                            <?php foreach (explode(',', $recipe['category']) as $cat): ?>
                                <span class="recipe-meta-badge">&#127859; <?= htmlspecialchars(trim($cat)) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="recipe-detail-section">
                    <h2>Ingredients</h2>
                    <?php
                    $lines = array_filter(array_map('trim', explode("\n", $recipe['ingredients'])));
                    ?>
                    <ul class="recipe-ingredients-list">
                        <?php foreach ($lines as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="recipe-detail-section">
                    <h2>Instructions</h2>
                    <p class="recipe-instructions-text"><?= htmlspecialchars($recipe['instructions']) ?></p>
                </div>

            <?php else: ?>
                <div class="recipe-not-found">
                    <h2>Recipe Not Found</h2>
                    <p>Sorry, we couldn't find that recipe. <a href="cooking-recipe-frontpage.php">Browse all recipes</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cooking Recipe Information System. All rights reserved.</p>
    </footer>
    <script src="recipe-system-JSS.js"></script>
</body>
</html>
