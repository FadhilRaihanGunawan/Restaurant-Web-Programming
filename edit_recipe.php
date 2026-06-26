<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

$user_id   = $_SESSION['user_id'];
$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error     = '';

/* Load the recipe — must belong to logged-in user */
$stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$recipe = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$recipe) {
    $conn->close();
    header("Location: dashboard.php");
    exit();
}

/* Handle POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title            = trim($_POST['title']);
    $ingredients      = trim($_POST['ingredients']);
    $instructions     = trim($_POST['instructions']);
    $cooking_time     = (int) $_POST['cooking_time'];
    $difficulty_level = $_POST['difficulty_level'];
    $category         = implode(',', array_filter(array_map('trim', $_POST['category'] ?? [])));
    if (empty($category)) { $error = 'Please select at least one category.'; }
    $is_public        = isset($_POST['is_public']) ? 1 : 0;
    $image_path       = $recipe['image_path']; // keep existing by default

    /* Handle new image upload */
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $ext     = strtolower(pathinfo($_FILES['recipe_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            $error = 'Only JPG, PNG, GIF, or WebP images are allowed.';
        } elseif ($_FILES['recipe_image']['size'] > 5 * 1024 * 1024) {
            $error = 'Image must be smaller than 5 MB.';
        } else {
            /* Delete old image file */
            if (!empty($recipe['image_path']) && file_exists($recipe['image_path'])) {
                unlink($recipe['image_path']);
            }
            $filename   = uniqid('recipe_', true) . '.' . $ext;
            move_uploaded_file($_FILES['recipe_image']['tmp_name'], $upload_dir . $filename);
            $image_path = $upload_dir . $filename;
        }
    }

    if (!$error) {
        $upd = $conn->prepare(
            "UPDATE recipes SET title=?, ingredients=?, instructions=?, cooking_time=?,
             difficulty_level=?, category=?, image_path=?, is_public=? WHERE id=? AND user_id=?"
        );
        $upd->bind_param("sssisssiii",
            $title, $ingredients, $instructions, $cooking_time,
            $difficulty_level, $category, $image_path, $is_public,
            $recipe_id, $user_id
        );

        if ($upd->execute()) {
            $upd->close();
            $conn->close();
            header("Location: recipe-detail.php?id=" . $recipe_id);
            exit();
        } else {
            $error = "Error updating recipe: " . $upd->error;
        }
        $upd->close();
    }

    /* Re-populate $recipe with submitted values so form retains input */
    $recipe = array_merge($recipe, [
        'title'            => $title,
        'ingredients'      => $ingredients,
        'instructions'     => $instructions,
        'cooking_time'     => $cooking_time,
        'difficulty_level' => $difficulty_level,
        'category'         => $category,
        'is_public'        => $is_public,
    ]);
}

$conn->close();
$all_cats = ['Breakfast','Lunch','Dinner','Snack','Chicken','Beef','Vegetarian','Italian','Chinese','Mexican'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe — <?= htmlspecialchars($recipe['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cooking-recipe-CSS.css">
    <style>
        .add-recipe-wrapper { max-width: 680px; margin: 48px auto; padding: 0 24px 64px; }
        .add-recipe-wrapper h1 { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--primary-dark); text-align: center; margin-bottom: 8px; }
        .add-recipe-wrapper .subtitle { text-align: center; color: var(--muted); margin-bottom: 32px; font-size: 0.95rem; }
        .add-recipe-wrapper form { margin: 0; box-shadow: var(--shadow-md); border-radius: var(--radius); padding: 36px 40px; max-width: 100%; }
        .form-section-title { font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); margin: 24px 0 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
        .form-section-title:first-of-type { margin-top: 0; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row label { margin-bottom: 5px; }
        .form-row > div { display: flex; flex-direction: column; }
        .current-image { width: 100%; max-height: 220px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; display: block; }
        .image-upload-zone { border: 2px dashed var(--border); border-radius: var(--radius); padding: 20px 16px; text-align: center; cursor: pointer; transition: border-color var(--transition), background var(--transition); position: relative; background: var(--bg); margin-bottom: 16px; }
        .image-upload-zone:hover, .image-upload-zone.dragover { border-color: var(--primary); background: #faf5ff; }
        .image-upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; margin: 0; padding: 0; border: none; }
        .image-upload-zone p { margin: 0; color: var(--muted); font-size: 0.875rem; }
        .image-upload-zone strong { color: var(--primary); }
        #image-preview { width: 100%; max-height: 220px; object-fit: cover; border-radius: 8px; display: none; margin-bottom: 16px; }
        .error-msg { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.9rem; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } .add-recipe-wrapper form { padding: 24px 20px; } }
    </style>
</head>
<body>
    <?php require 'nav.php'; ?>

    <main>
        <div class="add-recipe-wrapper">
            <h1>Edit Recipe</h1>
            <p class="subtitle"><?= htmlspecialchars($recipe['title']) ?></p>

            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit_recipe.php?id=<?= $recipe_id ?>" method="POST" enctype="multipart/form-data">

                <p class="form-section-title">Recipe Photo</p>

                <?php if (!empty($recipe['image_path']) && file_exists($recipe['image_path'])): ?>
                    <img src="<?= htmlspecialchars($recipe['image_path']) ?>" alt="Current photo" class="current-image" id="image-preview" style="display:block;">
                    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:8px;">Upload a new photo to replace the current one</p>
                <?php else: ?>
                    <img id="image-preview" src="" alt="Preview" style="display:none;" class="current-image">
                <?php endif; ?>

                <div class="image-upload-zone" id="upload-zone">
                    <input type="file" name="recipe_image" id="recipe_image" accept="image/*">
                    <p><strong>Click to upload</strong> or drag &amp; drop</p>
                    <p>JPG, PNG, WebP &mdash; max 5 MB</p>
                </div>

                <p class="form-section-title">Basic Info</p>

                <label for="title">Recipe Title</label>
                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($recipe['title']) ?>">

                <div class="form-row">
                    <div>
                        <label for="cooking_time">Cooking Time (minutes)</label>
                        <input type="number" id="cooking_time" name="cooking_time" min="1" required value="<?= (int)$recipe['cooking_time'] ?>">
                    </div>
                    <div>
                        <label for="difficulty_level">Difficulty</label>
                        <select id="difficulty_level" name="difficulty_level" required>
                            <?php foreach (['Easy','Medium','Hard'] as $d): ?>
                                <option value="<?= $d ?>" <?= $recipe['difficulty_level'] === $d ? 'selected' : '' ?>><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <label>Category <span style="color:var(--muted);font-weight:400;">(pick all that apply)</span></label>
                <?php $saved_cats = array_map('trim', explode(',', $recipe['category'])); ?>
                <div class="checkbox-group">
                    <?php foreach ($all_cats as $cat): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="category[]" value="<?= $cat ?>"
                                   <?= in_array($cat, $saved_cats) ? 'checked' : '' ?>>
                            <span><?= $cat ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <p class="form-section-title">Visibility</p>
                <label class="toggle-label">
                    <input type="checkbox" name="is_public" id="is_public" <?= $recipe['is_public'] ? 'checked' : '' ?>>
                    <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    <span class="toggle-text" id="toggle-text">
                        <?= $recipe['is_public'] ? 'Public — anyone can see this recipe' : 'Private — only you can see this recipe' ?>
                    </span>
                </label>

                <p class="form-section-title">Ingredients &amp; Steps</p>

                <label for="ingredients">Ingredients <span style="color:var(--muted);font-weight:400;">(one per line)</span></label>
                <textarea id="ingredients" name="ingredients" rows="6" required><?= htmlspecialchars($recipe['ingredients']) ?></textarea>

                <label for="instructions">Instructions <span style="color:var(--muted);font-weight:400;">(step by step)</span></label>
                <textarea id="instructions" name="instructions" rows="8" required><?= htmlspecialchars($recipe['instructions']) ?></textarea>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Cooking Recipe Information System. All rights reserved.</p>
    </footer>

    <script>
        const input   = document.getElementById('recipe_image');
        const preview = document.getElementById('image-preview');
        const zone    = document.getElementById('upload-zone');

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });

        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('dragover');
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        });

        /* Checkbox group highlight */
        document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(cb => {
            const label = cb.closest('.checkbox-item');
            if (cb.checked) label.classList.add('is-checked');
            cb.addEventListener('change', () => label.classList.toggle('is-checked', cb.checked));
        });

        const toggleCb   = document.getElementById('is_public');
        const toggleText = document.getElementById('toggle-text');
        function updateToggleText() {
            toggleText.textContent = toggleCb.checked
                ? 'Public — anyone can see this recipe'
                : 'Private — only you can see this recipe';
        }
        toggleCb.addEventListener('change', updateToggleText);
    </script>
    <script src="recipe-system-JSS.js"></script>
</body>
</html>
