<?php
session_start();
require 'config.php';

$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';

/* -------------------------------------------------------
   Featured (hardcoded) recipes — included in every filter
   ------------------------------------------------------- */
$all_featured = [
    1 => [
        'title'            => 'Chicken, Avocado & Mango Salad',
        'image'            => 'https://recipes.net/wp-content/uploads/portal_files/recipes_net_posts/2021-03/chicken-salad-with-mango-avocado-salsa-recipe-1024x683.jpg',
        'cooking_time'     => 25,
        'difficulty_level' => 'Easy',
        'category'         => 'Chicken',
        'categories'       => ['Chicken', 'Lunch'],
        'ingredients'      => 'chicken breast avocado mango mixed greens lime olive oil',
        'link'             => 'recipe-detail.php?featured=1',
    ],
    2 => [
        'title'            => 'Roast Beef Horseradish Roll-Ups',
        'image'            => 'https://thestarvingchefblog.com/wp-content/uploads/2022/05/roast-beef-roll-ups-recipe.jpg',
        'cooking_time'     => 15,
        'difficulty_level' => 'Easy',
        'category'         => 'Beef',
        'categories'       => ['Beef', 'Dinner'],
        'ingredients'      => 'roast beef horseradish cream cheese green onions tortillas spinach',
        'link'             => 'recipe-detail.php?featured=2',
    ],
    3 => [
        'title'            => 'Mushroom Risotto',
        'image'            => 'https://www.seriouseats.com/thmb/_Bm7MuoZztRNzPjQv2W7ACWC7OQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/20220217-pressure-cooker-mushroom-risotto-mariel-delacruz-082-cd5cc0b0b13f4239aeab86d88df6a1b0.jpg',
        'cooking_time'     => 45,
        'difficulty_level' => 'Medium',
        'category'         => 'Italian',
        'categories'       => ['Italian', 'Dinner', 'Vegetarian'],
        'ingredients'      => 'arborio rice mushrooms parmesan butter onion garlic white wine chicken broth',
        'link'             => 'recipe-detail.php?featured=3',
    ],
];

/* Filter featured recipes to match current category/search */
$featured_results = [];
foreach ($all_featured as $fid => $f) {
    if ($category !== '') {
        if (in_array($category, $f['categories'])) {
            $featured_results[] = $f;
        }
    } elseif ($search !== '') {
        if (stripos($f['title'], $search) !== false || stripos($f['ingredients'], $search) !== false) {
            $featured_results[] = $f;
        }
    } else {
        $featured_results[] = $f; // no filter — show all featured
    }
}

/* -------------------------------------------------------
   DB recipes
   ------------------------------------------------------- */
$where  = ['is_public = 1'];
$params = [];
$types  = '';

if ($category !== '') {
    $where[]  = 'FIND_IN_SET(?, category)';
    $params[] = $category;
    $types   .= 's';
}

if ($search !== '') {
    $where[]  = '(title LIKE ? OR ingredients LIKE ?)';
    $like     = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}

$sql  = 'SELECT * FROM recipes WHERE ' . implode(' AND ', $where) . ' ORDER BY id DESC';
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$db_recipes = $stmt->get_result();
$stmt->close();
$conn->close();

$has_results = count($featured_results) > 0 || $db_recipes->num_rows > 0;

/* Page title */
if ($category !== '') {
    $page_title = $category . ' Recipes';
} elseif ($search !== '') {
    $page_title = 'Results for "' . htmlspecialchars($search) . '"';
} else {
    $page_title = 'All Recipes';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> — Cooking Recipe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <?php require 'nav.php'; ?>

    <main>
        <div class="hero-section" style="padding: 40px 24px 32px;">
            <h1><?= $page_title ?></h1>
            <?php if ($category !== '' || $search !== ''): ?>
                <p><a href="recipes.php" style="color:var(--primary);font-weight:600;">&#8592; Browse all recipes</a></p>
            <?php else: ?>
                <p>Discover recipes shared by the community</p>
            <?php endif; ?>
        </div>

        <div class="content">
            <!-- Category filter pills -->
            <?php $cats = ['Breakfast','Lunch','Dinner','Snack','Chicken','Beef','Vegetarian','Italian','Chinese','Mexican']; ?>
            <div class="category-pills">
                <a href="recipes.php" class="pill <?= $category === '' && $search === '' ? 'pill-active' : '' ?>">All</a>
                <?php foreach ($cats as $cat): ?>
                    <a href="recipes.php?category=<?= urlencode($cat) ?>"
                       class="pill <?= $category === $cat ? 'pill-active' : '' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($has_results): ?>
                <div class="recipe-grid" style="margin-top: 28px;">

                    <!-- Featured hardcoded recipes -->
                    <?php foreach ($featured_results as $f): ?>
                        <a href="<?= $f['link'] ?>" class="recipe-card-link">
                            <div class="recipe-card">
                                <img src="<?= htmlspecialchars($f['image']) ?>" alt="<?= htmlspecialchars($f['title']) ?>">
                                <div class="recipe-card-body">
                                    <h2><?= htmlspecialchars($f['title']) ?></h2>
                                    <div class="recipe-card-meta">
                                        <span><?= $f['cooking_time'] ?> min</span>
                                        <span><?= $f['difficulty_level'] ?></span>
                                        <span><?= $f['category'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <!-- DB recipes -->
                    <?php while ($recipe = $db_recipes->fetch_assoc()): ?>
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
                <div class="empty-state" style="margin-top: 28px;">
                    <p>No recipes found<?= $category ? ' in "' . htmlspecialchars($category) . '"' : '' ?><?= $search ? ' for "' . htmlspecialchars($search) . '"' : '' ?>.</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="add_recipe.php" class="btn btn-primary">Add a Recipe</a>
                    <?php endif; ?>
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
