<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

$user_id = $_SESSION['user_id'];

/* Recipes added by the logged-in user (all — public and private) */
$my_stmt = $conn->prepare("SELECT * FROM recipes WHERE user_id = ? ORDER BY id DESC");
$my_stmt->bind_param("i", $user_id);
$my_stmt->execute();
$my_recipes = $my_stmt->get_result();
$my_stmt->close();

/* Latest public recipes from everyone */
$all_stmt = $conn->prepare("SELECT * FROM recipes WHERE is_public = 1 ORDER BY id DESC LIMIT 6");
$all_stmt->execute();
$all_recipes = $all_stmt->get_result();
$all_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cooking Recipe Information System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
</head>
<body>
    <?php require 'nav.php'; ?>

    <main>
        <div class="hero-section">
            <h1>Welcome to Your Dashboard</h1>
            <p>Manage your recipes and discover new inspirations</p>
        </div>

        <div class="content">

            <!-- MY RECIPES -->
            <section class="recommended-recipes">
                <h2>My Recipes</h2>

                <?php if ($my_recipes->num_rows > 0): ?>
                    <div class="recipe-grid">
                        <?php while ($recipe = $my_recipes->fetch_assoc()): ?>
                            <div class="recipe-card-wrapper">
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
                                            <span class="visibility-badge <?= $recipe['is_public'] ? 'badge-public' : 'badge-private' ?>">
                                                <?= $recipe['is_public'] ? '&#127760; Public' : '&#128274; Private' ?>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-actions">
                                    <a href="edit_recipe.php?id=<?= $recipe['id'] ?>" class="edit-btn">&#9998; Edit</a>
                                    <form action="delete_recipe.php" method="POST" style="display:inline;"
                                          onsubmit="return confirm('Delete \'<?= addslashes(htmlspecialchars($recipe['title'])) ?>\'? This cannot be undone.');">
                                        <input type="hidden" name="id" value="<?= $recipe['id'] ?>">
                                        <button type="submit" class="delete-btn">&#128465; Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>You haven't added any recipes yet.</p>
                        <a href="add_recipe.php" class="btn btn-primary">Add Your First Recipe</a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- LATEST PUBLIC RECIPES -->
            <section class="latest-recipes" style="margin-top: 48px;">
                <h2>Latest Recipes</h2>

                <?php if ($all_recipes->num_rows > 0): ?>
                    <div class="recipe-grid">
                        <?php while ($recipe = $all_recipes->fetch_assoc()): ?>
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
                        <p>No public recipes yet. Be the first!</p>
                        <a href="add_recipe.php" class="btn btn-primary">Add a Recipe</a>
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
